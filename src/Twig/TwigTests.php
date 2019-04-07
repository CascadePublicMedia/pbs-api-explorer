<?php

namespace CascadePublicMedia\PbsApiExplorer\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

/**
 * Class TwigTests
 *
 * @package CascadePublicMedia\PbsApiExplorer\Twig
 */
class TwigTests extends AbstractExtension
{
    /**
     * @return array
     */
    public function getTests()
    {
        return [
            new TwigTest('instanceof', [$this, 'isInstanceof']),
            new TwigTest('boolean', [$this, 'isBool']),
        ];
    }

    /**
     * @see https://stackoverflow.com/questions/10788138/instanceof-operator-in-twig-symfony-2
     *
     * @param $var
     * @param $instance
     * @return bool
     */
    public function isInstanceof($var, $instance)
    {
        return $var instanceof $instance;
    }

    /**
     * @param $var
     * @return bool
     */
    public function isBool($var) {
        return is_bool($var);
    }
}