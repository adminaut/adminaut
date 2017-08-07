<?php

namespace Adminaut\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Class VariableViewHelper
 * @package Adminaut\View\Helper
 */
class VariableViewHelper extends AbstractHelper
{
    /**
     * @var array
     */
    private $variables;

    /**
     * VariableViewHelper constructor.
     * @param array $variables
     */
    public function __construct(array $variables = [])
    {
        $this->variables = $variables;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function __invoke($key)
    {
        if (isset($this->variables[$key])) {
            return $this->variables[$key];
        }

        return null;
    }
}
