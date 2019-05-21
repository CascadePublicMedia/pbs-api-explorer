<?php

namespace CascadePublicMedia\PbsApiExplorer\Utils;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

/**
 * Class FieldMapper
 *
 * TODO: BLOW THIS UP. Refactor on field-specific classes/methods/etc.
 *
 * @package CascadePublicMedia\PbsApiExplorer\Utils
 */
class FieldMapper
{
    /**
     * API -> Entity field name mappings.
     */
    private const FIELD_MAP = [
        'action' => 'activity',
        'city' => 'addressCity',
        'country_code' => 'addressCountryCode',
        'created_at' => 'created',
        'episodes_count' => 'episodeCount',
        'encored_on' => 'encored',
        'end' => 'endDateTime',
        'event_tracking' => 'trackingCodeEvent',
        'is_excluded_from_dfp' => 'dfpExclude',
        'kids_live_stream_url' => 'kidsStationUrl',
        'minutes' => 'durationMinutes',
        'object_type' => 'type',
        'ordinal_season' => 'ordinalSeasons',
        'page_tracking' => 'trackingCodePage',
        'premiered_on' => 'premiered',
        'sort_episodes_descending' => 'episodeSortDesc',
        'start' => 'startDateTime',
        'state' => 'addressState',
        'tracking_ga_page' => 'gaTrackingPage',
        'tracking_ga_event' => 'gaTrackingEvent',
        'secondary_timezone' => 'timezoneSecondary',
        'station_kids_url' => 'kidsStationUrl',
        'updated_at' => 'updated',
        'zip_code' => 'addressZipCode',
    ];

    /**
     * @var CamelCaseToSnakeCaseNameConverter
     */
    private $nameConvertor;

    /**
     * FieldMapper constructor.
     */
    public function __construct()
    {
        $this->nameConvertor = new CamelCaseToSnakeCaseNameConverter();
    }

    /**
     * @param string $apiFieldName
     * @return string
     */
    public function map($apiFieldName) {
        if (isset(self::FIELD_MAP[$apiFieldName])) {
            $apiFieldName = self::FIELD_MAP[$apiFieldName];
        }
        else {
            $apiFieldName = $this->nameConvertor->denormalize($apiFieldName);
        }
        return $apiFieldName;
    }
}
