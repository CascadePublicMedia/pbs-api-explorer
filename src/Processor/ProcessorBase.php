<?php

namespace CascadePublicMedia\PbsApiExplorer\Processor;

/**
 * Class ProcessorBase
 *
 * @package CascadePublicMedia\PbsApiExplorer\Processor
 */
class ProcessorBase
{
    /**
     * @var string
     */
    protected $rawValue;

    /**
     * "Process" the raw value in some way.
     */
    public function process() {
        return $this->rawValue;
    }

    public function getRawValue(): ?string
    {
        return $this->rawValue;
    }

    public function setRawValue(string $value): self
    {
        $this->rawValue = $value;
       return $this;
    }

}