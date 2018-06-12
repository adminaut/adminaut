<?php

namespace Adminaut\Controller;

use Adminaut\Datatype\Reference;
use Adminaut\Form\Form;
use Doctrine\Common\Annotations\AnnotationReader;
use Adminaut\Entity\AdminautEntityInterface;
use Adminaut\Manager\ModuleManager;
use Adminaut\Manager\FileManager;
use Adminaut\Options\ModuleOptions;
use Adminaut\Service\AccessControlService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManager;
use Zend\Form\Element;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\Response;
use Zend\View\Model\ViewModel;

/**
 * Class ModuleController
 * @package Adminaut\Controller
 */
class ModuleController extends AdminautBaseController
{

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var ModuleManager
     */
    private $moduleManager;

    /**
     * @var FileManager
     */
    private $fileManager;

    /**
     * @var AccessControlService
     */
    private $accessControlService;

    //-------------------------------------------------------------------------

    /**
     * ModuleController constructor.
     * @param EntityManager $entityManager
     * @param ModuleManager $moduleManager
     * @param FileManager $fileManager
     * @param AccessControlService $accessControlService
     */
    public function __construct(EntityManager $entityManager, ModuleManager $moduleManager, FileManager $fileManager, AccessControlService $accessControlService)
    {
        $this->entityManager = $entityManager;
        $this->moduleManager = $moduleManager;
        $this->fileManager = $fileManager;
        $this->accessControlService = $accessControlService;
    }

    //-------------------------------------------------------------------------

    /**
     * @param null $default
     * @return mixed
     */
    private function getMode($default = null)
    {
        return $this->params()->fromRoute('mode', $default);
    }

    /**
     * @param null $default
     * @return mixed
     */
    private function getModuleId($default = null)
    {
        return $this->params()->fromRoute('module_id', $default);
    }

    /**
     * @param null $default
     * @return mixed
     */
    private function getEntityId($default = null)
    {
        return $this->params()->fromRoute('entity_id', $default);
    }

    //-------------------------------------------------------------------------

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $mode = $this->getMode();
        if (null !== $mode) {
            $view = $this->{$mode . "Action"}();
            if ($view instanceof ViewModel) {
                $view->setTemplate('adminaut/module/' . $mode . ".phtml");
                return $view;
            } else {
                return $view;
            }
        }
        return new ViewModel;
    }

    /**
     * @return ViewModel|Response
     */
    public function listAction()
    {
        $moduleId = $this->getModuleId();
        if (null === $moduleId) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }


        if (!$this->isAllowed($moduleId, AccessControlService::READ)) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        $moduleOptions = $this->getModuleManager()->createModuleOptions($moduleId);

        $form = $this->getModuleManager()->createForm($moduleOptions);

        $listedElements = [];

        /* @var $element \Zend\Form\Element */
        foreach ($form->getElements() as $key => $element) {
            if ($element->getOption('listed')
                && $this->isAllowed($moduleId, AccessControlService::READ, $key)
                || (method_exists($element, 'isPrimary')
                    && $element->isPrimary()
                    || $element->getOption('primary'))
            ) {
                $listedElements[$key] = $element;
            }
        }

        if(!empty($criteria = $this->accessControlService->getModuleCriteria($moduleId))) {
            $list = $this->getModuleManager()->findby($moduleOptions->getEntityClass(), $criteria);
        } else {
            $list = $this->getModuleManager()->findAll($moduleOptions->getEntityClass());
        }

        return new ViewModel([
            'list' => $list,
            'listedElements' => $listedElements,
            'hasPrimary' => ($form->getPrimaryField() !== 'id'),
            'moduleOption' => $moduleOptions,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function viewAction()
    {
        $moduleId = $this->getModuleId();
        $entityId = $this->getEntityId();

        if (null === $moduleId) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        if (!$this->isAllowed($moduleId, AccessControlService::READ)) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        if (null === $entityId) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        $moduleOptions = $this->getModuleManager()->createModuleOptions($moduleId);

        $form = $this->getModuleManager()->createForm($moduleOptions);

        if(!empty($criteria = $this->accessControlService->getModuleCriteria($moduleId))) {
            $entity = $this->getModuleManager()->findOneby($moduleOptions->getEntityClass(), array_merge(['id' => $entityId], $criteria));
        } else {
            $entity = $this->getModuleManager()->findOneById($moduleOptions->getEntityClass(), $entityId);
        }

        if (!$entity) {
            $this->addErrorMessage($this->translate('Record was not found.', 'adminaut'));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        $form->bind($entity);

        $elements = [];

        /* @var $element \Zend\Form\Element */
        foreach ($form->getElements() as $key => $element) {
            if ($this->isAllowed($moduleId, AccessControlService::READ, $key)) {
                $elements[$element->getName()] = $element;
            }
        }

        $tabs = $form->getTabs();
        $tabs[$this->params()->fromRoute('tab')]['active'] = true;

        return new ViewModel([
            'url_params' => [
                'module_id' => $moduleId,
                'entity_id' => $entityId,
                'mode' => 'view',
            ],
            'entity' => $entity,
            'primary' => $form->getPrimaryField(),
            'elements' => $elements,
            'tabs' => $tabs,
            'widgets' => $form->getWidgets(),
            'moduleOption' => $moduleOptions,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function addAction()
    {
        $moduleId = $this->getModuleId();
        $entityId = $this->getEntityId();

        if (null === $moduleId) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        if (!$this->isAllowed($moduleId, AccessControlService::WRITE)) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        $moduleOptions = $this->getModuleManager()->createModuleOptions($moduleId);

        $entityClass = $moduleOptions->getEntityClass();
        $fm = $this->getFilemanager();
        $form = $this->getModuleManager()->createForm($moduleOptions);
        if ($entityId) {
            $entity = $this->getModuleManager()->findOneById($moduleOptions->getEntityClass(), $entityId);
            $form->bind($entity);
        } else {
            $form->bind(new $entityClass());
        }

        /* @var Element $element */
        foreach ($form->getElements() as $element) {
            if (!$this->isAllowed($moduleId, AccessControlService::READ, $element->getName())) {
                $form->remove($element->getName());
                continue;
            }

            $element->setAttribute('disabled', true);
            if ($this->isAllowed($moduleId, AccessControlService::WRITE, $element->getName())) {
                $element->removeAttribute('disabled');
            }
        }

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost()->toArray();
            $files = $request->getFiles()->toArray();
            $post = array_merge_recursive($postData, $files);

            $form->setData($post);
            if ($form->isValid()) {
                try {
                    foreach ($files as $key => $file) {
                        if ($file['error'] != 0) {
                            continue;
                        }

                        $fm->upload($form->getElements()[$key], $this->authentication()->getIdentity());
                    }
                    $this->getEventManager()->trigger($moduleId . '.beforeCreateRecord', $this, [
                        'form' => &$form
                    ]);
                    $entity = $this->getModuleManager()->create($moduleOptions->getEntityClass(), $form, null, $this->authentication()->getIdentity());
                    $this->getEventManager()->trigger($moduleId . '.createRecord', $this, [
                        'entity' => $entity,
                    ]);
                    $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $entity->getId();
                    $this->addSuccessMessage(sprintf($this->translate('Record "%s" has been successfully created.', 'adminaut'), $primaryFieldValue));
                    switch ($post['submit']) {
                        case 'create-and-continue' :
                            return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'entity_id' => $entity->getId(), 'mode' => 'edit']);
                        case 'create-and-new' :
                            return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'mode' => 'add']);
                        case 'create' :
                        default :
                            return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'entity_id' => $entity->getId(), 'mode' => 'view']);
                    }
                } catch (\Exception $e) {
                    $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));

                    // todo: delete, do not redirect when error occurred
                    //return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'mode' => 'add']);
                }
            }
        }

        return new ViewModel([
            'form' => $form,
            'moduleOption' => $moduleOptions,
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function editAction()
    {
        $moduleId = $this->getModuleId();
        $entityId = $this->getEntityId();

        if (null === $moduleId) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        if (false === $this->isAllowed($moduleId, AccessControlService::WRITE)) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        if (null === $entityId) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        $moduleOptions = $this->getModuleManager()->createModuleOptions($moduleId);

        /* @var $entity AdminautEntityInterface */
        if(!empty($criteria = $this->accessControlService->getModuleCriteria($moduleId))) {
            $entity = $this->getModuleManager()->findOneby($moduleOptions->getEntityClass(), array_merge(['id' => $entityId], $criteria));
        } else {
            $entity = $this->getModuleManager()->findOneById($moduleOptions->getEntityClass(), $entityId);
        }

        if (!$entity) {
            $this->addErrorMessage($this->translate('Record was not found.', 'adminaut'));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        $fm = $this->getFilemanager();
        /* @var $form \Adminaut\Form\Form */
        $form = $this->getModuleManager()->createForm($moduleOptions);

        $tabs = $form->getTabs();
        $tabs[$this->params()->fromRoute('tab')]['active'] = true;
        $form->bind($entity);

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $postData = $request->getPost()->toArray();
            $files = $request->getFiles()->toArray();

            $post = array_merge_recursive($postData, $files);

            $form->setData($post);
            if ($form->isValid()) {
                try {
                    foreach ($files as $key => $file) {
                        if ($file['error'] != UPLOAD_ERR_OK) {
                            if ($file['error'] == UPLOAD_ERR_NO_FILE) {
                                $form->getElements()[$key]->setFileObject(null);
                            }
                            continue;
                        }

                        $fm->upload($form->getElements()[$key], $this->authentication()->getIdentity());
                    }

                    $this->getEventManager()->trigger($moduleId . '.beforeUpdateRecord', $this, [
                        'entity' => &$entity,
                        'form' => &$form
                    ]);

                    $this->getModuleManager()->update($entity, $form, null, $this->authentication()->getIdentity());

                    $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $entity->getId();
                    $this->addSuccessMessage(sprintf($this->translate('Record "%s" has been successfully updated.', 'adminaut'), $primaryFieldValue));
                    $this->getEventManager()->trigger($moduleId . '.updateRecord', $this, [
                        'entity' => &$entity,
                    ]);

                    if ($post['submit'] == 'save-and-continue') {
                        return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => 'edit']);
                    } else {
                        return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => 'view']);
                    }
                } catch (\Exception $e) {
                    $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));

                    // todo: delete, do not redirect when error occurred
                    //return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => 'edit']);
                }
            }
        }

        return new ViewModel([
            'form' => $form,
            'tabs' => $tabs,
            'entity' => $entity,
            'primary' => $form->getPrimaryField(),
            'moduleOption' => $moduleOptions,
            'url_params' => [
                'module_id' => $moduleId,
                'entity_id' => $entityId,
                'mode' => 'edit',
            ],
            'widgets' => $form->getWidgets(),
        ]);
    }

    /**
     * @return Response|ViewModel
     */
    public function tabAction()
    {
        $moduleId = $this->getModuleId();
        $entityId = $this->getEntityId();

        if (null === $moduleId) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        if (!$this->isAllowed($moduleId, AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        if (null === $entityId) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        $moduleOptions = $this->getModuleManager()->createModuleOptions($moduleId);

        /* @var $entity AdminautEntityInterface */
        $entity = $this->getModuleManager()->findOneById($moduleOptions->getEntityClass(), $entityId);
        if (!$entity) {
            $this->addErrorMessage($this->translate('Record was not found.', 'adminaut'));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        /* @var $form \Adminaut\Form\Form */
        $form = $this->getModuleManager()->createForm($moduleOptions);

        $tabs = $form->getTabs();
        $tabs[$this->params()->fromRoute('tab')]['active'] = true;

        if ($tabs[$this->params()->fromRoute('tab')]['action'] == 'cyclicSheetAction') {
            return $this->cyclicSheetAction();
        } else {
            return new ViewModel([
                'moduleOption' => $moduleOptions,
                'tabs' => $tabs,
                'url_params' => [
                    'module_id' => $moduleId,
                    'entity_id' => $entityId,
                    'mode' => $this->params()->fromRoute('mode'),
                ],
            ]);
        }
    }

    /**
     * @return Response|ViewModel
     */
    public function cyclicSheetAction()
    {
        $moduleId = $this->getModuleId();
        $entityId = $this->getEntityId();
        $currentTab = $this->params()->fromRoute('tab');
        $cyclicEntityId = $this->params()->fromRoute('cyclic_entity_id', false);
        $action = $this->params()->fromRoute('entity_action', false);
        $mode = $this->getMode();


        if (!$moduleId) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        $parentModuleOptions = $this->getModuleManager()->createModuleOptions($moduleId);

        if (!$this->isAllowed($parentModuleOptions->getModuleId(), AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        if (!$entityId) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        /* @var $entity AdminautEntityInterface */
        $entity = $this->getModuleManager()->findOneById($parentModuleOptions->getEntityClass(), $entityId);
        if (!$entity) {
            $this->addErrorMessage($this->translate('Record was not found.', 'adminaut'));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        /* @var $form \Adminaut\Form\Form */
        $entityForm = $this->getModuleManager()->createForm($parentModuleOptions);

        $tabs = $entityForm->getTabs();
        $tabs[$currentTab]['active'] = true;

        $options = [
            'entity_class' => $tabs[$currentTab]['entity'],
            'module_id' => $moduleId,
        ];
        $referencedProperty = $tabs[$currentTab]['referencedProperty'];
        $readonly = $tabs[$currentTab]['readonly'];

        $moduleOptions = new ModuleOptions($options);

        $list = $this->getModuleManager()->findBy($moduleOptions->getEntityClass(), [$referencedProperty => $entity]);
        $fm = $this->getFilemanager();
        $form = $this->getModuleManager()->createForm($moduleOptions);
        $moduleEntityClass = $moduleOptions->getEntityClass();
        $moduleEntity = new $moduleEntityClass();
        $form->bind($moduleEntity);

        /* @var $element \Zend\Form\Element */
        $listedElements = [];
        foreach ($form->getElements() as $key => $element) {
            if ($element->getOption('listed')) {
                $listedElements[] = clone $element;
            }

            if ($element instanceof Reference) {
                // parent Entity
                $pAR = new AnnotationReader();
                $pRO = new \ReflectionObject($entity);//new $options['entity_class']
                $isSubEntityReference = false;
                $subRefenrecedProperty = null;

                foreach ($pRO->getProperties() as $property) {
                    $cyclicSheet = null;
                    foreach ($pAR->getPropertyAnnotations($property) as $annotation) {
                        if ($annotation instanceof \Zend\Form\Annotation\Type) {
                            if ($annotation->getType() === \Adminaut\Form\Element\CyclicSheet::class) {
                                $cyclicSheet = true;
                                break;
                            }
                        }
                    }

                    if ($cyclicSheet) {
                        foreach ($pAR->getPropertyAnnotations($property) as $annotation) {
                            if ($annotation instanceof \Zend\Form\Annotation\Options) {
                                $_options = $annotation->getOptions();
                                if ($_options['target_class'] === $element->getOptions()['target_class']) {
                                    $element->setSubEntityReference(true);
                                    $subRefenrecedProperty = isset($_options['referenced_property']) ? $_options['referenced_property'] : 'parentId';
                                    break;
                                }
                            }
                        }
                    }

                    if ($element->isSubEntityReference()) {
                        break;
                    }
                }

                if ($element->isSubEntityReference()) {
                    $rep = $this->getEntityManager()->getRepository($element->getOptions()['target_class']);
                    $qb = $rep->createQueryBuilder('e');
                    $qb->andWhere('e.deleted = 0')
                        ->andWhere('e.' . $subRefenrecedProperty . ' = :' . $subRefenrecedProperty)
                        ->setParameter($subRefenrecedProperty, $entity);
                    $data = $qb->getQuery()->getResult();
                    $element->getProxy()->setObjects($data);
                    $element->getProxy()->setLoaded(true);
                }
            }
        }

        if (!isset($form->getElements()['reference_property'])) {
            $form->add([
                'name' => 'reference_property',
                'attributes' => [
                    'type' => 'hidden',
                ],
            ]);
        }
        $form->getElements()['reference_property']->setValue($referencedProperty);

        /** @var AdminautEntityInterface $cyclicEntity */
        $cyclicEntity = $this->getModuleManager()->findOneById($moduleOptions->getEntityClass(), $cyclicEntityId);

        if ($action === 'edit') {
            if (!$this->isAllowed($parentModuleOptions->getModuleId(), AccessControlService::WRITE)) {
                return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
            }

            if (!$cyclicEntity) {
                $this->addErrorMessage($this->translate('Record was not found.', 'adminaut'));
                return $this->redirect()->toRoute('adminaut/module/action/tab', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => $mode, 'tab' => $currentTab]);
            }

            $form->bind($cyclicEntity);

        } else if ($action == 'delete') {

            try {
                $this->getModuleManager()->delete($cyclicEntity, $this->authentication()->getIdentity());
                $this->addSuccessMessage(sprintf($this->translate('Record "%s" has been deleted.', 'adminaut'), $cyclicEntity->getPrimaryFieldValue()));
                $this->getEventManager()->trigger($moduleId . '.deleteCyclicRecord', $this, [
                    'entity' => $entity,
                    'cyclicEntity' => $cyclicEntity,
                ]);
                return $this->redirect()->toRoute('adminaut/module/action/tab', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => $mode, 'tab' => $currentTab]);
            } catch (\Exception $e) {
                $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));
                return $this->redirect()->toRoute('adminaut/module/action/tab', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => $mode, 'tab' => $currentTab]);
            }
        }

        /** @var Request $request */
        $request = $this->getRequest();

        if ($request->isPost() && !$readonly) {
            if (!$this->isAllowed($parentModuleOptions->getModuleId(), AccessControlService::WRITE)) {
                return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
            }

            $postData = $request->getPost()->toArray();
            $files = $request->getFiles()->toArray();
            $post = array_merge_recursive($postData, $files);

            $form->setData($post);
            if ($form->isValid()) {
                try {
                    foreach ($files as $key => $file) {
                        if ($file['error'] != UPLOAD_ERR_OK) {
                            if ($file['error'] == UPLOAD_ERR_NO_FILE) {
                                $form->getElements()[$key]->setFileObject(null);
                            }
                            continue;
                        }

                        $fm->upload($form->getElements()[$key], $this->authentication()->getIdentity());
                    }

                    if ($action == 'edit') {
                        $this->getEventManager()->trigger($moduleId . '.beforeUpdateCyclicRecord', $this, [
                            'entity' => $entity,
                            'cyclicEntity' => $cyclicEntity,
                            'form' => &$form
                        ]);
                        $cyclicEntity = $this->getModuleManager()->update($cyclicEntity, $form, $entity, $this->authentication()->getIdentity());
                        $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $cyclicEntity->getId();
                        $this->getEventManager()->trigger($moduleId . '.updateCyclicRecord', $this, [
                            'entity' => $entity,
                            'cyclicEntity' => $cyclicEntity,
                        ]);
                        $this->addSuccessMessage(sprintf($this->translate('Record "%s" has been successfully updated.', 'adminaut'), $primaryFieldValue));
                    } else {
                        $this->getEventManager()->trigger($moduleId . '.beforeCreateCyclicRecord', $this, [
                            'entity' => $entity,
                            'form' => &$form
                        ]);
                        $cyclicEntity = $this->getModuleManager()->create($moduleOptions->getEntityClass(), $form, $entity, $this->authentication()->getIdentity());
                        $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $cyclicEntity->getId();
                        $this->getEventManager()->trigger($moduleId . '.createCyclicRecord', $this, [
                            'entity' => $entity,
                            'cyclicEntity' => $cyclicEntity,
                        ]);
                        $this->addSuccessMessage(sprintf($this->translate('Record "%s" has been successfully created.', 'adminaut'), $primaryFieldValue));
                    }

                    return $this->redirect()->toRoute('adminaut/module/action/tab', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => 'edit', 'tab' => $currentTab]);
                } catch (\Exception $e) {
                    $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));
                    return $this->redirect()->toRoute('adminaut/module/action/tab', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => 'edit', 'tab' => $currentTab]);
                }
            }
        }

        $view = new ViewModel([
            'parentModuleOption' => $parentModuleOptions,
            'moduleOption' => $moduleOptions,
            'tabs' => $tabs,
            'currentTab' => $currentTab,
            'mode' => $this->params()->fromRoute('mode'),
            'title' => $tabs[$currentTab]['label'],
            'listedElements' => $listedElements,
            'hasPrimary' => ($form->getPrimaryField() !== 'id'),
            'list' => $list,
            'form' => $form,
            'action' => $action,
            'readonly' => $readonly,
            'url_params' => [
                'module_id' => $moduleId,
                'entity_id' => $entityId,
                'mode' => $this->params()->fromRoute('mode'),
            ],
        ]);
        $view->setTemplate('adminaut/module/cyclicTab.phtml');
        return $view;
    }

    /**
     * @return Response
     */
    public function deleteAction()
    {
        $moduleId = $this->getModuleId();
        $entityId = $this->getEntityId();

        if (null === $moduleId) {
            return $this->redirect()->toRoute(DashboardController::ROUTE_INDEX);
        }

        if (!$this->isAllowed($moduleId, AccessControlService::FULL)) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        if (null === $entityId) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        $moduleOptions = $this->getModuleManager()->createModuleOptions($moduleId);

        /** @var Form $form */
        $form = $this->getModuleManager()->createForm($moduleOptions);
        $primaryField = $form->getPrimaryField();

        /* @var $entity AdminautEntityInterface */
        $entity = $this->getModuleManager()->findOneById($moduleOptions->getEntityClass(), $entityId);
        if (!$entity) {
            $this->addErrorMessage($this->translate('Record was not found.', 'adminaut'));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        try {
            $this->getModuleManager()->delete($entity, $this->authentication()->getIdentity());
            $primaryFieldValue = $entity->{'get' . ucfirst($primaryField)}();
            $this->addSuccessMessage(sprintf($this->translate('Record "%s" has been deleted.', 'adminaut'), $primaryFieldValue));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId, 'entity_id' => $entityId]);
        } catch (\Exception $e) {
            $this->addErrorMessage(sprintf($this->translate('Error: %s', 'adminaut'), $e->getMessage()));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId, 'entity_id' => $entityId]);
        }
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->entityManager;
    }

    /**
     * @return ModuleManager
     */
    public function getModuleManager()
    {
        return $this->moduleManager;
    }

    /**
     * @return FileManager
     */
    public function getFileManager()
    {
        return $this->fileManager;
    }
}
