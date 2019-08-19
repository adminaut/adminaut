<?php


namespace Adminaut\Exception;


use Throwable;

class DuplicateValueForUniqueException extends \Exception
{
    protected $columnName;

    protected $formFieldName;

    protected $invalidValue;

    public function __construct($invalidValue = null, $columnName = null, $formFieldName = null, $code = 0, Throwable $previous = null)
    {
        $this->columnName = $columnName;
        $this->formFieldName = $formFieldName;
        $this->invalidValue = $invalidValue;

        $message = sprintf("Cannot save record, there is already a record with value '%s' - the value must be unique.", $invalidValue);

        if (!empty($columnName) || !empty($formFieldName)) {
            $message = sprintf("Cannot save record, there is already a record with value '%s' in the field '%s' - the value must be unique.", $invalidValue, !empty($formFieldName) ? $formFieldName : $columnName);
        }

        parent::__construct($message, $code, $previous);
    }

    /**
     * @return null
     */
    public function getColumnName()
    {
        return $this->columnName;
    }

    /**
     * @return null
     */
    public function getFormFieldName()
    {
        return $this->formFieldName;
    }

    /**
     * @return null
     */
    public function getInvalidValue()
    {
        return $this->invalidValue;
    }
}