<?php

namespace TinyPngBundle\Helper;

/**
 * Class AssetHelper
 * @package TinyPngBundle\Helper
 */
class AssetHelper
{
    /**
     * @return array
     */
    public static function getConfig() : array
    {
        $configFilePath = PIMCORE_CONFIGURATION_DIRECTORY . '/tinypng.php';
        if (!file_exists($configFilePath)) {
            return ['config' => ['apiKey' => '']];
        }

        return include($configFilePath);
    }
}