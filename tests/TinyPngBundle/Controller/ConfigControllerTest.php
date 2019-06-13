<?php
declare(strict_types=1);

namespace Tests\TinyPngBudle\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Tests\TinyPngBundle\TestHelper;
use TinyPngBundle\Controller\ConfigController;

class ContentControllerTest extends TestCase
{
    public function testGetConfigWithoutConfigFile()
    {
        $this->cleanConfig();
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
        $this->cleanConfig();
    }

    public function cleanConfig()
    {
        $file = PIMCORE_CONFIGURATION_DIRECTORY . '/tinypng.php';
        if(file_exists($file)){
            unlink($file);
        }
    }


}