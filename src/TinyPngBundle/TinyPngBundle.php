<?php

namespace TinyPngBundle;

use Pimcore\Extension\Bundle\AbstractPimcoreBundle;
use Pimcore\Extension\Bundle\Traits\PackageVersionTrait;

/**
 * Class TinyPngBundle
 * @package TinyPngBundle
 * @codeCoverageIgnore
 */
class TinyPngBundle extends AbstractPimcoreBundle
{
    use PackageVersionTrait;

    /**
     * @return string
     */
    protected function getComposerPackageName(): string
    {
        return 'zephis/tiny-png-bundle';
    }

    /**
     * @return array|\Pimcore\Routing\RouteReferenceInterface[]|string[]
     */
    public function getJsPaths()
    {
        return [
            '/bundles/tinypng/js/pimcore/startup.js',
            '/bundles/tinypng/js/pimcore/config.js'
        ];
    }
}
