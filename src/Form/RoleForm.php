<?php

namespace Adminaut\Form;

use Zend\Form\Element\Radio;
use Zend\Form\Element\Text;
use Zend\Form\Form;

/**
 * Class RoleForm
 * @package Adminaut\Form
 */
class RoleForm extends Form
{

    /**
     * @var string
     */
    public $formMode;

    /**
     * RoleForm constructor.
     * @param string $mode
     * @param array|null $modules
     * @param null $resources
     */
    public function __construct($mode = "add", array $modules = null, $resources = null)
    {

        parent::__construct();

        $this->formMode = $mode;

        $this->setName('Role');
        $this->setAttributes([
            'method' => 'post',
        ]);

        $this->add([
            'type' => Text::class,
            'name' => 'name',
            'options' => [
                'label' => _('Name'),
            ],
            'attributes' => [
                'placeholder' => _('Name'),
            ],
        ]);

        if ($mode === "update") {
            $permissions = [];
            foreach ($resources as $index => $resource) {
                $permissions[$resource->getResource()] = $resource->getPermission();
            }

            array_push($modules, ['module_name' => 'Users']);
            array_push($modules, ['module_name' => 'Roles']);

            foreach ($modules as $index => $module) {
                if (isset($permissions[$module['module_name']])) {
                    $value = $permissions[$module['module_name']];
                } else {
                    $value = 'none';
                }

                $this->add([
                    'type' => Radio::class,
                    'name' => $module['module_name'],
                    'options' => [
                        'label' => $module['module_name'],
                        'label_attributes' => [],
                        'value_options' => [
                            '0' => 'None',
                            '1' => 'Read',
                            '2' => 'Write',
                        ],
                    ],
                    'attributes' => [
                        'value' => $value,
                    ],
                ]);
            }
        }
    }
}
