<?php

namespace TinyPngBundle\EventListener;

use Pimcore\Event\AssetEvents;
use Pimcore\Event\Model\AssetEvent;
use Pimcore\Log\ApplicationLogger;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TinyPngBundle\Helper\AssetHelper;
use TinyPngBundle\Service\TinyPngService;

/**
 * Class TinyPngAssetListener
 * @package TinyPngBundle\EventListener
 */
class TinyPngAssetListener implements EventSubscriberInterface
{

    private $mimeTypes = [
        'image/jpeg',
        'image/png'
    ];

    /**
     * @var ApplicationLogger
     */
    private $logger;

    public function __construct()
    {
        $this->logger = ApplicationLogger::getInstance('TinyPNG');
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            AssetEvents::POST_ADD => ['onImageSave'],
            AssetEvents::POST_UPDATE => ['onImageSave']
        ];
    }

    /**
     * @param AssetEvent $event
     */
    public function onImageSave(AssetEvent $event)
    {
        if (!in_array($event->getAsset()->getMimetype(), $this->mimeTypes)) {
            return;
        }

        try {
            (new TinyPngService())->minimize($event->getAsset());
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage(), [
                'relatedObject' => $event->getAsset(),
                'component' => 'TinyPNG'
            ]);
        }
    }
}
