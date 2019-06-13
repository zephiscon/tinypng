<?php
declare(strict_types=1);

namespace Tests\TinyPngBudle\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use TinyPngBundle\Controller\ConfigController;

class ContentControllerTest extends TestCase
{
    public function testGetConfigWithoutConfigFile()
    {
        $this->cleanup();
        $config = (new ConfigController())->getConfig();
        $this->assertEquals('{"config":{"apiKey":""}}', $config->getContent());
    }

    public function testSetConfigWithoutData()
    {
        $request = new Request();
        $config = (new ConfigController())->setConfig($request);
        $this->assertEquals('{"success":false}', $config->getContent());
    }

    public function testSetConfigWithData()
    {
        $request = new Request();
        $request->request->set('data', '{"config.apiKey":"1234"}');
        $config = (new ConfigController())->setConfig($request);
        $this->assertEquals('{"success":true}', $config->getContent());
    }

    public function testGetConfigWithConfigFile()
    {
        $config = (new ConfigController())->getConfig();
        $this->assertEquals('{"config":{"apiKey":"1234"}}', $config->getContent());
        $this->cleanup();
    }

    private function cleanup()
    {
        $file = PIMCORE_CONFIGURATION_DIRECTORY . '/tinypng.php';
        if(self::fileExists($file)){
            unlink($file);
        }
    }
}