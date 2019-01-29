<?php

namespace Adminaut\View\Helper;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Entity\AdminautEntityInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Actions
 */
class Actions extends AbstractHelper
{
    /**
     * @param string $moduleId
     * @param AdminautEntityInterface $entity
     * @return string
     */
    public function __invoke(string $moduleId, AdminautEntityInterface $entity)
    {
        $html = '<div class="btn-group btn-group-sm" role="group" aria-label="Actions" style="min-width: 135px;">';
        $html .= '    <a href="' . $this->getView()->url('adminaut/module/action', ['module_id' => $moduleId, 'mode' => 'view', 'entity_id' => $entity->getId()]) . '" class="btn btn-success view"
               data-toggle="tooltip" data-placement="top" title="' . $this->getView()->translate('View', 'adminaut') . '"
               data-original-title="' . $this->getView()->translate('View', 'adminaut') . '"><i class="fa fa-eye"></i></a>';
        if ($this->getView()->isAllowed($moduleId, \Adminaut\Service\AccessControlService::WRITE)) {
                $html .= '<a href="' . $this->getView()->url('adminaut/module/action', ['module_id' => $moduleId, 'mode' => 'edit', 'entity_id' => $entity->getId()]) . '" class="btn btn-primary edit"
                   data-toggle="tooltip" data-placement="top" title="' . $this->getView()->translate('Edit', 'adminaut') . '"
                   data-original-title="' . $this->getView()->translate('Edit', 'adminaut') . '"><i class="fa fa-pencil"></i></a>
                <a href="' . $this->getView()->url('adminaut/module/action', ['module_id' => $moduleId, 'entity_id' => $entity->getId(), 'mode' => 'add']) . '"
                   class="btn btn-sm btn-default clone" data-toggle="tooltip" data-placement="top" title="' . $this->getView()->translate('Copy', 'adminaut') . '"
                   data-original-title="' . $this->getView()->translate('Copy', 'adminaut') . '"><i class="fa fa-clone"></i></a>';
        }
        if ($this->getView()->isAllowed($moduleId, \Adminaut\Service\AccessControlService::FULL)) {
                $html .= '<a href="#" data-href="' . $this->getView()->url('adminaut/module/delete', ['module_id' => $moduleId, 'entity_id' => $entity->getId()]) . '"
                   class="btn btn-danger btn-modal-delete delete"
                   data-toggle="tooltip" data-placement="top" title="' . $this->getView()->translate('Delete', 'adminaut') . '"
                   data-original-title="' . $this->getView()->translate('Delete', 'adminaut') . '"><i class="fa fa-trash"></i></a>';
        }
        $html .= '</div>';

        return $html;
    }
}
