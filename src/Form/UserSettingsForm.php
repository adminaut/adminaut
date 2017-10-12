<?php

namespace Adminaut\Form;

use Adminaut\Datatype\Language;
use Zend\Form\Element\Text;

/**
 * Class UserSettingsForm
 * @package Adminaut\Form
 */
class UserSettingsForm extends Form
{
    public function __construct()
    {
        parent::__construct('UserSettingsForm');

        $this->setAttribute('method', 'post');

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

        $this->add([
            'type' => Language::class,
            'name' => 'language',
            'options' => [
                'label' => _('Language'),
                'availableLanguages' => ['en', 'de', 'cs', 'sk'],
            ],
        ]);
    }
}
