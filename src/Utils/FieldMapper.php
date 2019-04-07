<?php

namespace CascadePublicMedia\PbsApiExplorer\Utils;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

class FieldMapper
{
    /**
     * API -> Entity field name mappings.
     */
    private const FIELD_MAP = [
        'city' => 'addressCity',
        'country_code' => 'addressCountryCode',
        'created_at' => 'created',
        'episodes_count' => 'episodeCount',
        'event_tracking' => 'trackingCodeEvent',
        'is_excluded_from_dfp' => 'dfpExclude',
        'kids_live_stream_url' => 'kidsStationUrl',
        'ordinal_season' => 'ordinalSeasons',
        'page_tracking' => 'trackingCodePage',
        'premiered_on' => 'premiered',
        'sort_episodes_descending' => 'episodeSortDesc',
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
