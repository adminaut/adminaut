<?php

namespace Adminaut\Datatype\DatatypeManager;

use Adminaut\Datatype\Checkbox;
use Adminaut\Datatype\Text;
use Adminaut\Form\Factory;
use Interop\Container\ContainerInterface;
use Zend\Form\Element;
use Zend\Form\ElementFactory;
use Zend\Form\ElementInterface;
use Zend\Form\Exception;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\Form\FormElementManager\FormElementManagerV3Polyfill;
use Zend\Form\FormFactoryAwareInterface;

/**
 * zend-servicemanager v3-compatible plugin manager implementation for form elements.
 *
 * Enforces that elements retrieved are instances of ElementInterface.
 */
class DatatypeManagerV3Polyfill extends FormElementManagerV3Polyfill
{

    /**
     * Aliases for default set of helpers
     *
     * @var array
     */
    protected $aliases = [
        'text' => Text::class,
        'checkbox' => Checkbox::class,
    ];

    /**
     * Factories for default set of helpers
     *
     * @var array
     */
    protected $factories = [
        Element\Button::class => ElementFactory::class,
        Element\Captcha::class => ElementFactory::class,
        Element\Checkbox::class => ElementFactory::class,
        Element\Collection::class => ElementFactory::class,
        Element\Color::class => ElementFactory::class,
        Element\Csrf::class => ElementFactory::class,
        Element\Date::class => ElementFactory::class,
        Element\DateSelect::class => ElementFactory::class,
        Element\DateTime::class => ElementFactory::class,
        Element\DateTimeLocal::class => ElementFactory::class,
        Element\DateTimeSelect::class => ElementFactory::class,
        Element::class => ElementFactory::class,
        Element\Email::class => ElementFactory::class,
        Fieldset::class => ElementFactory::class,
        Element\File::class => ElementFactory::class,
        Form::class => ElementFactory::class,
        Element\Hidden::class => ElementFactory::class,
        Element\Image::class => ElementFactory::class,
        Element\Month::class => ElementFactory::class,
        Element\MonthSelect::class => ElementFactory::class,
        Element\MultiCheckbox::class => ElementFactory::class,
        Element\Number::class => ElementFactory::class,
        Element\Password::class => ElementFactory::class,
        Element\Radio::class => ElementFactory::class,
        Element\Range::class => ElementFactory::class,
        Element\Select::class => ElementFactory::class,
        Element\Submit::class => ElementFactory::class,
        Element\Text::class => ElementFactory::class,
        Element\Textarea::class => ElementFactory::class,
        Element\Time::class => ElementFactory::class,
        Element\Url::class => ElementFactory::class,
        Element\Week::class => ElementFactory::class,

        // v2 normalized variants

        'zendformelementbutton' => ElementFactory::class,
        'zendformelementcaptcha' => ElementFactory::class,
        'zendformelementcheckbox' => ElementFactory::class,
        'zendformelementcollection' => ElementFactory::class,
        'zendformelementcolor' => ElementFactory::class,
        'zendformelementcsrf' => ElementFactory::class,
        'zendformelementdate' => ElementFactory::class,
        'zendformelementdateselect' => ElementFactory::class,
        'zendformelementdatetime' => ElementFactory::class,
        'zendformelementdatetimelocal' => ElementFactory::class,
        'zendformelementdatetimeselect' => ElementFactory::class,
        'zendformelement' => ElementFactory::class,
        'zendformelementemail' => ElementFactory::class,
        'zendformfieldset' => ElementFactory::class,
        'zendformelementfile' => ElementFactory::class,
        'zendformform' => ElementFactory::class,
        'zendformelementhidden' => ElementFactory::class,
        'zendformelementimage' => ElementFactory::class,
        'zendformelementmonth' => ElementFactory::class,
        'zendformelementmonthselect' => ElementFactory::class,
        'zendformelementmulticheckbox' => ElementFactory::class,
        'zendformelementnumber' => ElementFactory::class,
        'zendformelementpassword' => ElementFactory::class,
        'zendformelementradio' => ElementFactory::class,
        'zendformelementrange' => ElementFactory::class,
        'zendformelementselect' => ElementFactory::class,
        'zendformelementsubmit' => ElementFactory::class,
        'zendformelementtext' => ElementFactory::class,
        'zendformelementtextarea' => ElementFactory::class,
        'zendformelementtime' => ElementFactory::class,
        'zendformelementurl' => ElementFactory::class,
        'zendformelementweek' => ElementFactory::class,
    ];

    /**
     * Inject the factory to any element that implements FormFactoryAwareInterface
     *
     * @param ContainerInterface $container
     * @param mixed $instance Instance to inspect and optionally inject.
     */
    public function injectFactory(ContainerInterface $container, $instance)
    {
        if (!$instance instanceof FormFactoryAwareInterface) {
            return;
        }

        $factory = $instance->getFormFactory();

        $factory = new Factory();
//        $factory->setFormElementManager($this);
        $factory->setDatatypeManager($this);

        if ($container && $container->has('InputFilterManager')) {
            $inputFilters = $container->get('InputFilterManager');
            $factory->getInputFilterFactory()->setInputFilterManager($inputFilters);
        }
    }
}
