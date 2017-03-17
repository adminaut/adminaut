<?php
namespace Adminaut\Form\View\Helper;

use TwbBundle\Form\View\Helper\TwbBundleFormElement;

use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormDateTime as ZendFormDateTime;


class FormDate extends ZendFormDateTime
{
    /**
     * @param ElementInterface|null $element
     * @return string
     */
    public function __invoke(ElementInterface $element = null)
    {
        return $this->render($element);
    }

    /**
     * @param $format
     * @return string
     */
    public function convertPHPToMomentFormat($format)
    {
        $replacements = [
            'd' => 'DD',
            'D' => 'ddd',
            'j' => 'D',
            'l' => 'dddd',
            'N' => 'E',
            'S' => 'o',
            'w' => 'e',
            'z' => 'DDD',
            'W' => 'W',
            'F' => 'MMMM',
            'm' => 'MM',
            'M' => 'MMM',
            'n' => 'M',
            't' => '', // no equivalent
            'L' => '', // no equivalent
            'o' => 'YYYY',
            'Y' => 'YYYY',
            'y' => 'YY',
            'a' => 'a',
            'A' => 'A',
            'B' => '', // no equivalent
            'g' => 'h',
            'G' => 'H',
            'h' => 'hh',
            'H' => 'HH',
            'i' => 'mm',
            's' => 'ss',
            'u' => 'SSS',
            'e' => 'zz', // deprecated since version 1.6.0 of moment.js
            'I' => '', // no equivalent
            'O' => '', // no equivalent
            'P' => '', // no equivalent
            'T' => '', // no equivalent
            'Z' => '', // no equivalent
            'c' => '', // no equivalent
            'r' => '', // no equivalent
            'U' => 'X',
        ];
        $momentFormat = strtr($format, $replacements);
        return $momentFormat;
    }

    /**
     * @param ElementInterface $element
     * @return string
     */
	public function render(ElementInterface $element)
    {
        $render = parent::render($element);
        $render .= '<script type="text/javascript">console.log("'.$this->convertPHPToMomentFormat($element->getOption('format')).'"); $(\'#'.$element->getAttribute('id').'\').datetimepicker({format: \'YYYY-MM-DD\'});</script>';
        return $render;
    }


}