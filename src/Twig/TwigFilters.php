<?php

namespace CascadePublicMedia\PbsApiExplorer\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class TwigFilters
 *
 * @package CascadePublicMedia\PbsApiExplorer\Twig
 */
class TwigFilters extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new TwigFilter('bool_icon', [$this, 'boolToIcon'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * @param bool $bool
     * @return string
     */
    public function boolToIcon($bool)
    {
        if ($bool) {
            $icon = 'fa-check-circle text-green';
        }
        else {
            $icon = 'fa-times-circle text-red';
        }
        return sprintf('<i class="fas %s"></i>', $icon);
    }
}