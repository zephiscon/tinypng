<?php

namespace TinyPngBundle\Service;

use Pimcore\Model\Asset;
use function Tinify\fromFile;
use function Tinify\setKey;
use TinyPngBundle\Helper\AssetHelper;

/**
 * Class TinyPngService
 * @package TinyPngBundle\Service
 */
class TinyPngService
{
    /**
     * @param Asset $image
     */
    public function minimize(Asset $image)
    {
        setKey($this->getApiKey());
        $source = fromFile($image->getFileSystemPath());
        $source->toFile($image->getFileSystemPath());
    }

    /**
     * @return string
     */
    private function getApiKey() : string
    {
        $config = AssetHelper::getConfig();
        return $config['config']['apiKey'] ?? '';
    }
}
