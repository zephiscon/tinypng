<?php
declare(strict_types=1);

namespace Tests\TinyPngBudle\EventListener;

use PHPUnit\Framework\TestCase;
use Pimcore\Event\Model\AssetEvent;
use Pimcore\Model\Asset;
use Symfony\Component\HttpFoundation\Request;
use Tinify\AccountException;
use TinyPngBundle\Controller\ConfigController;
use TinyPngBundle\EventListener\TinyPngAssetListener;

class TinyPngAssetListenerTest extends TestCase
{
    public function testGetSubscribedEvents()
    {
        $this->assertArrayHasKey('pimcore.asset.postAdd', TinyPngAssetListener::getSubscribedEvents());
    }

    public function testOnImageSaveWrongType()
    {
        $event = new AssetEvent($this->buildAsset());
        $this->assertFalse((new TinyPngAssetListener())->onImageSave($event));
    }

    public function testOnImageSave()
    {
        $event = new AssetEvent($this->buildAsset(true));
        $this->expectException('Error');
        (new TinyPngAssetListener())->onImageSave($event);
    }

    private function buildAsset($mime = false)
    {
        $this->getImage();
        $image = new Asset();
        $image->setFilename('/image.jpg');

        if($mime){
            $image->setMimetype('image/jpeg');
        }

        return $image;
    }

    private function getImage()
    {
        $content = file_get_contents("http://www.google.co.in/intl/en_com/images/srpr/logo1w.png");
        $fp = fopen(PIMCORE_ASSET_DIRECTORY."/image.jpg", "w");
        fwrite($fp, $content);
        fclose($fp);
    }
}