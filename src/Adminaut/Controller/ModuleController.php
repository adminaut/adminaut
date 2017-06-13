<?php

namespace Adminaut\Controller;

use Adminaut\Controller\Plugin\Acl;
use Adminaut\Datatype\Reference;
use Adminaut\Form\Form;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Andx;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use Adminaut\Entity\BaseEntityInterface;
use Adminaut\Form\Element;
use Adminaut\Manager\ModuleManager;
use Adminaut\Manager\FileManager;
use Adminaut\Mapper\ModuleMapper;
use Adminaut\Options\ModuleOptions;

use Adminaut\Service\AccessControlService;
use ReflectionClass;
use Webmozart\Assert\Assert;
use Zend\EventManager\EventManager;
use Zend\Form\Annotation\AnnotationBuilder;
use Zend\Http\Response;
use Zend\Mvc\Service\ViewPhpRendererFactory;
use Zend\View\Model\ViewModel;
use Zend\View\View;

/**
 * Class ModuleController
 * @package Adminaut\Controller
 * @method Acl acl()
 */
class ModuleController extends AdminautBaseController
{
    /**
     * @var ModuleManager
     */
    protected $moduleManager;

    /**
     * @var ViewPhpRendererFactory
     */
    protected $viewRenderer;

    /**
     * @var FileManager
     */
    protected $filemanager;

    /**
     * @var array
     */
    protected $tabs;

    /**
     * @var ModuleManager
     */
    protected $moduleManagerService;

    /**
     * ModuleController constructor.
     * @param $config
     * @param $acl
     * @param $em
     * @param $translator
     * @param $moduleManager
     * @param $viewRenderer
     * @param $filemanager
     */
    public function __construct($config, $acl, $em, $translator, $moduleManager, $viewRenderer, $filemanager)
    {
        parent::__construct($config, $acl, $em, $translator);
        $this->setModuleManagerService($moduleManager);
        $this->setViewRenderer($viewRenderer);
        $this->setFilemanager($filemanager);
    }

    /**
     * @return ViewModel
     */
    public function indexAction()
    {
        $mode = $this->params()->fromRoute('mode', false);
        if ($mode) {
            $view = $this->{$mode . "Action"}();
            if($view instanceof ViewModel) {
                $view->setTemplate('adminaut/module/' . $mode . ".phtml");
                return $view;
            } else {
                return $view;
            }
        } else {
            return new ViewModel;
        }
    }

    /**
     * @return ViewModel|Response
     */
    public function listAction()
    {
        $module_id = $this->params()->fromRoute('module_id', false);
        if (!$module_id) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $this->getAdminModuleManager($module_id);

        if (!$this->acl()->isAllowed($this->moduleManager->getOptions()->getModuleId(), AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $form = $this->moduleManager->getForm();

        /* @var $element \Zend\Form\Element */
        $listedElements = [];
        foreach ($form->getElements() as $key => $element) {
            if ($element->getOption('listed')
                && $this->getAcl()->isAllowed($module_id, AccessControlService::READ, $key)
                || (method_exists($element, 'isPrimary')
                    && $element->isPrimary()
                    || $element->getOption('primary'))
            ) {
                $listedElements[$key] = $element;
            }
        }

        $list = $this->moduleManager->getList();

        return new ViewModel([
            'list' => $list,
            'listedElements' => $listedElements,
            'hasPrimary' => ($form->getPrimaryField() !== 'id'),
            'moduleOption' => $this->moduleManager->getOptions()
        ]);
    }

    public function viewAction()
    {
        $moduleId = $this->params()->fromRoute('module_id', false);
        $entityId = (int)$this->params()->fromRoute('entity_id', 0);

        if (!$moduleId) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $this->getAdminModuleManager($moduleId);

        if (!$this->acl()->isAllowed($this->moduleManager->getOptions()->getModuleId(), AccessControlService::READ)) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        if (!$entityId) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        $form = $this->moduleManager->getForm();
        $entity = $this->moduleManager->findById($entityId);
        $form->bind($entity);

        $elements = [];
        foreach ($form->getElements() as $key => $element) {
            if ($this->getAcl()->isAllowed($moduleId, AccessControlService::READ, $key)) {
                $elements[$element->getName()] = $element;
            }
        }

        $tabs = $form->getTabs();
        $tabs[$this->params()->fromRoute('tab')]['active'] = true;

        return new ViewModel([
            'url_params' => [
                'module_id' => $moduleId,
                'entity_id' => $entityId,
                'mode' => 'view'
            ],
            'entity' => $entity,
            'primary' => $form->getPrimaryField(),
            'elements' => $elements,
            'tabs' => $tabs,
            'moduleOption' => $this->moduleManager->getOptions()
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function addAction()
    {
        $moduleId = $this->params()->fromRoute('module_id', false);

        if (!$moduleId) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $this->getAdminModuleManager($moduleId);

        if (!$this->acl()->isAllowed($this->moduleManager->getOptions()->getModuleId(), AccessControlService::WRITE)) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        $entityClass = $this->moduleManager->getEntityClass();
        $fm = $this->getFilemanager();
        $form = $this->moduleManager->getForm();
        $form->bind(new $entityClass());

        /* @var Element $element */
        foreach ($form->getElements() as $element) {
            if (!$this->acl()->isAllowed($moduleId, AccessControlService::READ, $element->getName())) {
                $form->remove($element->getName());
                continue;
            }

            $element->setAttribute('disabled', true);
            if ($this->acl()->isAllowed($moduleId, AccessControlService::WRITE, $element->getName())) {
                $element->removeAttribute('disabled');
            }
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost()->toArray();
            $files = $this->getRequest()->getFiles()->toArray();
            $post = array_merge_recursive($postData, $files);

            $form->setData($post);
            if ($form->isValid()) {
                try {
                    foreach ($files as $key => $file) {
                        if ($file['error'] != 0) {
                            continue;
                        }

                        $fm->upload($form->getElements()[$key], $this->userAuthentication()->getIdentity());
                    }

                    $entity = $this->moduleManager->addEntity($form, $this->userAuthentication()->getIdentity());
                    $this->getEventManager()->trigger($moduleId . '.createRecord', $this, [
                        'entity' => $entity
                    ]);
                    $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $entity->getId();
                    $this->flashMessenger()->addSuccessMessage(sprintf($this->getTranslator()->translate('Record "%s" has been successfully created.'), $primaryFieldValue));
                    switch ($post['submit']) {
                        case 'create-and-continue' :
                            return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'entity_id' => $entity->getId(), 'mode' => 'edit']);
                        case 'create-and-new' :
                            return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'mode' => 'add']);
                        case 'create' :
                        default :
                            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->getTranslator()->translate('Error: %s'), $e->getMessage()));
                    return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'mode' => 'add']);
                }
            }
        }

        return new ViewModel([
            'form' => $form,
            'moduleOption' => $this->moduleManager->getOptions()
        ]);
    }

    /**
     * @return \Zend\Http\Response|ViewModel
     */
    public function editAction()
    {
        $moduleId = $this->params()->fromRoute('module_id', false);
        $entityId = (int)$this->params()->fromRoute('entity_id', 0);
        if (!$moduleId) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        if (!$this->acl()->isAllowed($this->getAdminModuleManager($moduleId)->getOptions()->getModuleId(), AccessControlService::WRITE)) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        if (!$entityId) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        /* @var $entity BaseEntityInterface */
        $entity = $this->getAdminModuleManager($moduleId)->findById($entityId);
        if (!$entity) {
            $this->flashMessenger()->addErrorMessage($this->getTranslator()->translate('Record was not found.'));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        $fm = $this->getFilemanager();
        /* @var $form \Adminaut\Form\Form */
        $form = $this->moduleManager->getForm();

        $tabs = $form->getTabs();
        $tabs[$this->params()->fromRoute('tab')]['active'] = true;
        $form->bind($entity);

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost()->toArray();
            $files = $this->getRequest()->getFiles()->toArray();

            $post = array_merge_recursive($postData, $files);

            $form->setData($post);
            if ($form->isValid()) {
                try {
                    foreach ($files as $key => $file) {
                        if ($file['error'] != UPLOAD_ERR_OK) {
                            if($file['error'] == UPLOAD_ERR_NO_FILE) {
                                $form->getElements()[$key]->setFileObject(null);
                            }
                            continue;
                        }

                        $fm->upload($form->getElements()[$key], $this->userAuthentication()->getIdentity());
                    }

                    $this->moduleManager->updateEntity($entity, $form, $this->userAuthentication()->getIdentity());

                    $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $entity->getId();
                    $this->flashMessenger()->addSuccessMessage(sprintf($this->getTranslator()->translate('Record "%s" has been successfully updated.'), $primaryFieldValue));
                    $this->getEventManager()->trigger($moduleId . '.updateRecord', $this, [
                        'entity' => $entity
                    ]);
                    if ($post['submit'] == 'save-and-continue') {
                        return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => 'edit']);
                    } else {
                        return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
                    }
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->getTranslator()->translate('Error: %s'), $e->getMessage()));
                    return $this->redirect()->toRoute('adminaut/module/action', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => 'edit']);
                }
            }
        }

        return new ViewModel([
            'form' => $form,
            'tabs' => $tabs,
            'entity' => $entity,
            'primary' => $form->getPrimaryField(),
            'moduleOption' => $this->moduleManager->getOptions(),
            'url_params' => [
                'module_id' => $moduleId,
                'entity_id' => $entityId,
                'mode' => 'edit'
            ]
        ]);
    }

    public function tabAction()
    {
        $moduleId = $this->params()->fromRoute('module_id', false);
        $entityId = (int)$this->params()->fromRoute('entity_id', 0);
        if (!$moduleId) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        if (!$this->acl()->isAllowed($this->getAdminModuleManager($moduleId)->getOptions()->getModuleId(), AccessControlService::WRITE)) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        if (!$entityId) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        /* @var $entity BaseEntityInterface */
        $entity = $this->getAdminModuleManager($moduleId)->findById($entityId);
        if (!$entity) {
            $this->flashMessenger()->addErrorMessage($this->getTranslator()->translate('Record was not found.'));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        /* @var $form \Adminaut\Form\Form */
        $form = $this->moduleManager->getForm();

        $tabs = $form->getTabs();
        $tabs[$this->params()->fromRoute('tab')]['active'] = true;

        if ($tabs[$this->params()->fromRoute('tab')]['action'] == 'cyclicSheetAction') {
            return $this->cyclicSheetAction();
        } else {
            return new ViewModel([
                'moduleOption' => $this->moduleManager->getOptions(),
                'tabs' => $tabs,
                'url_params' => [
                    'module_id' => $moduleId,
                    'entity_id' => $entityId,
                    'mode' => $this->params()->fromRoute('mode')
                ]
            ]);
        }
    }

    public function cyclicSheetAction()
    {
        $moduleId = $this->params()->fromRoute('module_id', false);
        $entityId = (int)$this->params()->fromRoute('entity_id', 0);
        $currentTab = $this->params()->fromRoute('tab');
        $cyclicEntityId = $this->params()->fromRoute('cyclic_entity_id', false);
        $action = $this->params()->fromRoute('entity_action', false);
        $mode = $this->params()->fromRoute('mode');


        if (!$moduleId) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        $parentModuleOption = $this->getAdminModuleManager($moduleId)->getOptions();
        if (!$this->acl()->isAllowed($parentModuleOption->getModuleId(), AccessControlService::WRITE)) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        if (!$entityId) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        /* @var $entity BaseEntityInterface */
        $entity = $this->getAdminModuleManager($moduleId)->findById($entityId);
        if (!$entity) {
            $this->flashMessenger()->addErrorMessage($this->getTranslator()->translate('Record was not found.'));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        /* @var $form \Adminaut\Form\Form */
        $entityForm = $this->moduleManager->getForm();

        $tabs = $entityForm->getTabs();
        $tabs[$currentTab]['active'] = true;

        $options = [
            'entity_class' => $tabs[$currentTab]['entity']
        ];
        $referencedProperty = $tabs[$currentTab]['referencedProperty'];

        $moduleManager = $this->getModuleManagerService();
        $moduleOptions = new ModuleOptions($options);
        $moduleOptions->setModuleId($moduleId);
        $moduleManager->setOptions($moduleOptions);
        $moduleMapper = new ModuleMapper($this->getEntityManager(), $moduleOptions);
        $moduleManager->setMapper($moduleMapper);

        $list = $moduleManager->getList([$referencedProperty => $entity]);
        $fm = $this->getFilemanager();
        $form = $moduleManager->getForm();
        $form->bind(new $moduleOptions->entityClass);

        /* @var $element \Zend\Form\Element */
        $listedElements = [];
        foreach ($form->getElements() as $key => $element) {
            if ($element->getOption('listed')) {
                $listedElements[] = clone $element;
            }

            if($element instanceof Reference) {
                // parent Entity
                $pAR = new AnnotationReader();
                $pRO = new \ReflectionObject($entity);//new $options['entity_class']
                $isSubEntityReference = false;
                $subRefenrecedProperty = null;

                foreach($pRO->getProperties() as $property) {
                    $cyclicSheet = null;
                    foreach($pAR->getPropertyAnnotations($property) as $annotation) {
                        if($annotation instanceof \Zend\Form\Annotation\Type) {
                            if($annotation->getType() === \Adminaut\Form\Element\CyclicSheet::class) {
                                $cyclicSheet = true;
                                break;
                            }
                        }
                    }

                    if($cyclicSheet) {
                        foreach($pAR->getPropertyAnnotations($property) as $annotation) {
                            if($annotation instanceof \Zend\Form\Annotation\Options) {
                                $_options = $annotation->getOptions();
                                if($_options['target_class'] === $element->getOptions()['target_class']) {
                                    $isSubEntityReference = true;
                                    $subRefenrecedProperty = isset($_options['referenced_property']) ? $_options['referenced_property'] : 'parentId';
                                    break;
                                }
                            }
                        }
                    }

                    if($isSubEntityReference) {break;}
                }

                if($isSubEntityReference) {
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

        if(!isset($form->getElements()['reference_property'])) {
            $form->add([
                'name' => 'reference_property',
                'attributes' => [
                    'type' => 'hidden'
                ]
            ]);
        }
        $form->getElements()['reference_property']->setValue($referencedProperty);

        if ($action === 'edit') {
            $cyclicEntity = $moduleManager->findById($cyclicEntityId);

            if (!$cyclicEntity) {
                $this->flashMessenger()->addErrorMessage($this->getTranslator()->translate('Record was not found.'));
                return $this->redirect()->toRoute('adminaut/module/action/tab', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => $mode, 'tab' => $currentTab]);
            }

            $form->bind($cyclicEntity);
        } elseif ($action == 'delete') {
            try {
                $cyclicEntity = $moduleManager->findById($cyclicEntityId);
                $moduleManager->deleteEntity($cyclicEntity, $this->userAuthentication()->getIdentity());
                $form->bind($cyclicEntity);
                $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $cyclicEntity->getId();
                $this->flashMessenger()->addSuccessMessage(sprintf($this->getTranslator()->translate('Record "%s" has been deleted.'), $primaryFieldValue));
                $this->getEventManager()->trigger($moduleId . '.deleteCyclicRecord', $this, [
                    'entity' => $entity,
                    'cyclicEntity' => $cyclicEntity
                ]);
                return $this->redirect()->toRoute('adminaut/module/action/tab', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => $mode, 'tab' => $currentTab]);
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage(sprintf($this->getTranslator()->translate('Error: %s'), $e->getMessage()));
                return $this->redirect()->toRoute('adminaut/module/action/tab', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => $mode, 'tab' => $currentTab]);
            }
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost()->toArray();
            $files = $this->getRequest()->getFiles()->toArray();
            $post = array_merge_recursive($postData, $files);

            $form->setData($post);
            if ($form->isValid()) {
                try {
                    foreach ($files as $key => $file) {
                        if ($file['error'] != UPLOAD_ERR_OK) {
                            if($file['error'] == UPLOAD_ERR_NO_FILE) {
                                $form->getElements()[$key]->setFileObject(null);
                            }
                            continue;
                        }

                        $fm->upload($form->getElements()[$key], $this->userAuthentication()->getIdentity());
                    }

                    if ($action == 'edit') {
                        $cyclicEntity = $moduleManager->updateEntity($cyclicEntity, $form, $this->userAuthentication()->getIdentity(), $entity);
                        $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $cyclicEntity->getId();
                        $this->getEventManager()->trigger($moduleId . '.updateCyclicRecord', $this, [
                            'entity' => $entity,
                            'cyclicEntity' => $cyclicEntity
                        ]);
                        $this->flashMessenger()->addSuccessMessage(sprintf($this->getTranslator()->translate('Record "%s" has been successfully updated.'), $primaryFieldValue));
                    } else {
                        $cyclicEntity = $moduleManager->addEntity($form, $this->userAuthentication()->getIdentity(), $entity);
			            $primaryFieldValue = isset($form->getElements()[$form->getPrimaryField()]) ? (method_exists($form->getElements()[$form->getPrimaryField()], 'getListedValue') ? $form->getElements()[$form->getPrimaryField()]->getListedValue() : $form->getElements()[$form->getPrimaryField()]->getValue()) : $cyclicEntity->getId();
                        $this->getEventManager()->trigger($moduleId . '.createCyclicRecord', $this, [
                            'entity' => $entity,
                            'cyclicEntity' => $cyclicEntity
                        ]);
                        $this->flashMessenger()->addSuccessMessage(sprintf($this->getTranslator()->translate('Record "%s" has been successfully created.'), $primaryFieldValue));
                    }

                    return $this->redirect()->toRoute('adminaut/module/action/tab', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => 'edit', 'tab' => $currentTab]);
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage(sprintf($this->getTranslator()->translate('Error: %s'), $e->getMessage()));
                    return $this->redirect()->toRoute('adminaut/module/action/tab', ['module_id' => $moduleId, 'entity_id' => $entityId, 'mode' => 'edit', 'tab' => $currentTab]);
                }
            }
        }

        $view = new ViewModel([
            'parentModuleOption' => $parentModuleOption,
            'moduleOption' => $this->moduleManager->getOptions(),
            'tabs' => $tabs,
            'currentTab' => $currentTab,
            'mode' => $this->params()->fromRoute('mode'),
            'title' => $tabs[$currentTab]['label'],
            'listedElements' => $listedElements,
            'hasPrimary' => ($form->getPrimaryField() !== 'id'),
            'list' => $list,
            'form' => $form,
            'action' => $action,
            'url_params' => [
                'module_id' => $moduleId,
                'entity_id' => $entityId,
                'mode' => $this->params()->fromRoute('mode')
            ]
        ]);
        $view->setTemplate('adminaut/module/cyclicTab.phtml');
        return $view;
    }

    public function deleteAction()
    {
        $moduleId = $this->params()->fromRoute('module_id', false);
        $entityId = (int)$this->params()->fromRoute('entity_id', 0);
        if (!$moduleId) {
            return $this->redirect()->toRoute('adminaut/dashboard');
        }

        if (!$this->acl()->isAllowed($this->getAdminModuleManager($moduleId)->getOptions()->getModuleId(), AccessControlService::FULL)) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        if (!$entityId) {
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        /** @var Form $form */
        $form = $this->getAdminModuleManager($moduleId)->getForm();
        $primaryField = $form->getPrimaryField();

        /* @var $entity BaseEntityInterface */
        $entity = $this->getAdminModuleManager($moduleId)->findById($entityId);
        if (!$entity) {
            $this->flashMessenger()->addErrorMessage($this->getTranslator()->translate('Record was not found.'));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId]);
        }

        try {
            $this->getAdminModuleManager($moduleId)->deleteEntity($entity, $this->userAuthentication()->getIdentity());
            $primaryFieldValue = $entity->{'get' . ucfirst($primaryField)}();
            $this->flashMessenger()->addSuccessMessage(sprintf($this->getTranslator()->translate('Record "%s" has been deleted.'), $primaryFieldValue));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId, 'entity_id' => $entityId]);
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage(sprintf($this->getTranslator()->translate('Error: %s'), $e->getMessage()));
            return $this->redirect()->toRoute('adminaut/module/list', ['module_id' => $moduleId, 'entity_id' => $entityId]);
        }
    }

    /**
     * @param $moduleId
     * @return ModuleManager
     */
    protected function getAdminModuleManager($moduleId)
    {
        if (!$this->moduleManager instanceof ModuleManager) {
            $moduleManager = $this->getModuleManagerService();

            $config = $this->getConfig();

            if (isset($config['adminaut']['modules']) and isset($config['adminaut']['modules'][$moduleId])) {
                $adminModuleOption = new ModuleOptions($config['adminaut']['modules'][$moduleId]);
                $adminModuleOption->setModuleId($moduleId);
                $moduleManager->setOptions($adminModuleOption);
                $moduleMapper = new moduleMapper($this->getEntityManager(), $adminModuleOption);
                $moduleManager->setMapper($moduleMapper);
            }

            $this->moduleManager = $moduleManager;
            return $this->moduleManager;
        } else {
            return $this->moduleManager;
        }
    }

    /**
     * @return ViewPhpRendererFactory
     */
    public function getViewRenderer()
    {
        return $this->viewRenderer;
    }

    /**
     * @param ViewPhpRendererFactory $viewRenderer
     */
    public function setViewRenderer($viewRenderer)
    {
        $this->viewRenderer = $viewRenderer;
    }

    /**
     * @return ModuleManager
     */
    public function getModuleManagerService()
    {
        return $this->moduleManagerService;
    }

    /**
     * @param ModuleManager $moduleManagerService
     */
    public function setModuleManagerService($moduleManagerService)
    {
        $this->moduleManagerService = $moduleManagerService;
    }

    /**
     * @return FileManager
     */
    public function getFilemanager()
    {
        return $this->filemanager;
    }

    /**
     * @param FileManager $filemanager
     */
    public function setFilemanager($filemanager)
    {
        $this->filemanager = $filemanager;
    }
}
