<?php
declare(strict_types=1);

namespace Tests\TinyPngBudle\Service;

use function GuzzleHttp\Psr7\try_fopen;
use PHPUnit\Framework\TestCase;
use Pimcore\Model\Asset;
use Symfony\Component\HttpFoundation\Request;
use Tinify\AccountException;
use Tinify\Exception;
use TinyPngBundle\Controller\ConfigController;
use TinyPngBundle\Service\TinyPngService;

class TinyPngServiceTest extends TestCase
{
    public function testMinimize()
    {
        $this->generateConfig();
        $image = $this->buildAsset();

        $this->expectException(AccountException::class);
        (new TinyPngService())->minimize($image);

        $this->assertFileExists(PIMCORE_ASSET_DIRECTORY."/image.jpg");

        unlink(PIMCORE_CONFIGURATION_DIRECTORY . '/tinypng.php');
    }

    private function buildAsset()
    {
        $this->getImage();
        $image = new Asset();
        $image->setFilename('/image.jpg');

        return $image;
    }

    private function getImage()
    {
        $content = file_get_contents("http://www.google.co.in/intl/en_com/images/srpr/logo1w.png");
        $fp = fopen(PIMCORE_ASSET_DIRECTORY."/image.jpg", "w");
        fwrite($fp, $content);
        fclose($fp);
    }

    private function generateConfig()
    {
        $request = new Request();
        $request->request->set('data', '{"config.apiKey":"1234"}');
        $config = (new ConfigController())->setConfig($request);
    }
}