<?php
namespace Adminaut\Controller\Api;

use Adminaut\Datatype\Datatype;
use Adminaut\Form\Form;
use Adminaut\Manager\ModuleManager;
use Adminaut\Options\ModuleOptions;
use Adminaut\Service\AccessControlService;
use Zend\Form\Element;
use Zend\Paginator\Paginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
use Zend\View\HelperPluginManager;
use Zend\View\Model\JsonModel;
use Zend\View\Renderer\RendererInterface;

/**
 * Class ModuleApiController
 */
class ModuleApiController extends BaseApiController
{
    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @var AccessControlService
     */
    protected $accessControlService;

    /**
     * @var HelperPluginManager
     */
    protected $viewHelperManager;

    /**
     * ModuleApiController constructor.
     * @param ModuleManager $moduleManager
     * @param AccessControlService $accessControlService
     */
    public function __construct(
        ModuleManager $moduleManager,
        AccessControlService $accessControlService,
        HelperPluginManager $viewHelperManager
    ) {
        $this->moduleManager = $moduleManager;
        $this->accessControlService = $accessControlService;
        $this->viewHelperManager = $viewHelperManager;
    }

    // =================================================================================================================

    /**
     * @param mixed|null $default
     * @return mixed
     */
    protected function getMode($default = null)
    {
        return $this->params()->fromRoute('mode', $default);
    }

    /**
     * @param mixed|null $default
     * @return mixed
     */
    protected function getModuleId($default = null)
    {
        return $this->params()->fromRoute('module_id', $default);
    }

    /**
     * @param string|null $moduleId
     * @return ModuleOptions
     * @throws \Exception
     */
    protected function getModuleOptions($moduleId = null)
    {
        if ($moduleId === null) {
            $moduleId = $this->getModuleId();
        }

        return $this->moduleManager->createModuleOptions($moduleId);
    }

    /**
     * @param null $default
     * @return mixed
     */
    protected function getEntityId($default = null)
    {
        return $this->params()->fromRoute('entity_id', $default);
    }

    // =================================================================================================================

    /**
     * @return array
     */
    protected function moduleNotFound()
    {
        $this->response->setStatusCode(404);

        return new JsonModel([
            'content' => 'Module not found.'
        ]);
    }

    // =================================================================================================================

    public function datatableAction()
    {
        if(($moduleId = $this->getModuleId()) === null || !$this->moduleManager->hasModule($moduleId))
        {
            return $this->moduleNotFound();
        }

        if (!$this->hasIdentity() || !$this->isAllowed($moduleId, AccessControlService::READ) || !$this->getRequest()->isPost())
        {
            return $this->returnForbidden();
        }

        $params = $this->params()->fromPost();
        $searchValue = $params['search']['value'] ?: "";

        /** @var ModuleOptions $moduleOptions */
        $moduleOptions = $this->getModuleOptions();
        /** @var Form $form */
        $form = $this->moduleManager->createForm($moduleOptions);

        $listedElements = $this->moduleManager->getListedColumns($moduleId, $form);
        $searchableColumns = $this->moduleManager->getSearchableColumns($moduleId, $form);
        $filterableColumns = $this->moduleManager->getFilterableColumns($moduleId, $form);
        $datatableColumns = $this->moduleManager->getDatatableColumns($moduleId, $form);
        $orders = $this->moduleManager->getOrderedElements($params['order']?: [], $moduleId, $form);

        $criteria = $this->accessControlService->getModuleCriteria($moduleId);
        $ormPaginator = $this->moduleManager->getDatatable($moduleOptions->getEntityClass(), $criteria, $searchableColumns, $searchValue, $orders);

        if ( !empty( $filterableColumns ) ) {
            $_filters = $this->moduleManager->getDatatableFilters($moduleOptions->getEntityClass(), $filterableColumns, $criteria, $searchableColumns, $searchValue);

            $dtColumns = array_keys($datatableColumns);

            $filters = [];
            foreach (array_keys($_filters) as $columnName) {
                if ($columnKey = array_search($columnName, $dtColumns)) {
                    $filters[$columnKey] = $_filters[$columnName];
                }
            }
        }

        $paginator = new Paginator(new DoctrineAdapter($ormPaginator));
        $paginator->setItemCountPerPage((int)$params['length'] ?: 10);
        $paginator->setCurrentPageNumber((((int)$params['start'] ?: 0) / ((int)$params['length'] ?: 10)) + 1);

        $primaryHelper = $this->viewHelperManager->get('primary');
        $actionsHelper = $this->viewHelperManager->get('actions');

        $return = [];
        $return['draw'] = ((int)$params['draw']) ?: 1;
        $return['recordsFiltered'] = $return['recordsTotal'] = count($ormPaginator);
        $return['filters'] = $filters;
        $return['data'] = [];

        foreach ($paginator as $entity) {
            $data = [];

            $data['id'] = $entity->getId();

            /**
             * @var string $key
             * @var Datatype|Element $listedElement
             */
            foreach ($listedElements as $key => $listedElement) {
                $listedElement->setValue($entity->{$listedElement->getName()});

                if (method_exists($listedElement, 'getListedValue')) {
                    $value = $listedElement->isPrimary() && empty($listedElement->getListedValue()) ? 'undefined' : $listedElement->getListedValue();
                } else {
                    $value = $listedElement->getValue();
                }

                if ((method_exists($listedElement, 'isPrimary') && $listedElement->isPrimary()) || $listedElement->getOption('primary') === true) {
                    $data[$key] = $primaryHelper($value, $moduleId, $entity);
                } else {
                    $data[$key] = $value;
                }
            }
            $data['actions'] = $actionsHelper($moduleId, $entity);

            $return['data'][] = $data;
        }

        return new JsonModel($return);
    }
}