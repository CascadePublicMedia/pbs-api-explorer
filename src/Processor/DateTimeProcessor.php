<?php

namespace CascadePublicMedia\PbsApiExplorer\Processor;

use DateTime;

/**
 * Class DateTimeProcessor
 *
 * @package CascadePublicMedia\PbsApiExplorer\Processor
 */
class DateTimeProcessor extends ProcessorBase
{
    /**
     * Media Manager uses two different date formats seemingly interchangeably.
     */
    private const MEDIA_MANAGER_API_DATE_FORMAT = 'Y-m-d\TH:i:s.u\Z';
    private const MEDIA_MANAGER_API_DATE_FORMAT_ALT = 'Y-m-d\TH:i:s\Z';

    /**
     * @var string
     */
    private $format;

    public function process(): DateTime
    {
        if (!empty($this->format)) {
            $value = DateTime::createFromFormat($this->format, $this->rawValue);
        }
        else {
            $value = self::processDateTimeString($this->rawValue);
        }
        return $value;
    }

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $value): self
    {
        $this->format = $value;
        return $this;
    }

    /**
     * Attempt to a convert a string datetime to a DateTime instance.
     *
     * @param $string
     *
     * @return DateTime
     */
    public static function processDateTimeString($string) {
        $datetime = DateTime::createFromFormat(
            self::MEDIA_MANAGER_API_DATE_FORMAT,
            $string
        );
        if ($datetime === FALSE) {
            $datetime = DateTime::createFromFormat(
                self::MEDIA_MANAGER_API_DATE_FORMAT_ALT,
                $string
            );
        }
        return $datetime;
    }

}
