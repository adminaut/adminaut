<?php

namespace Adminaut\View\Helper;

use Adminaut\Authentication\Service\AuthenticationService;
use Adminaut\Entity\AdminautEntityInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * Class Primary
 */
class Primary extends AbstractHelper
{
    /**
     * @param string $value
     * @param string $moduleId
     * @param AdminautEntityInterface $entity
     * @return string
     */
    public function __invoke(string $value, string $moduleId, AdminautEntityInterface $entity)
    {
        return '<a href="' . $this->getView()->url('adminaut/module/action', ['module_id' => $moduleId, 'mode' => 'view', 'entity_id' => $entity->getId()]) . '" class="primary">' . $value . '</a>';
    }
}
