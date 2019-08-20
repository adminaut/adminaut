<?php


namespace Adminaut\Exception;


use Throwable;

class UniqueNotNullableException extends \Exception
{
    protected $property;


    public function __construct($property, $code = 0, Throwable $previous = null)
    {
        $this->property = $property;

        parent::__construct(sprintf("Unique property '%s' must be nullable for soft delete.", $property), $code, $previous);
    }

    /**
     * @return null
     */
    public function getRecord()
    {
        return $this->record;
    }
}