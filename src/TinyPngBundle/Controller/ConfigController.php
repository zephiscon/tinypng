<?php

namespace TinyPngBundle\Controller;

use Pimcore\Controller\FrontendController;
use Pimcore\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use TinyPngBundle\Helper\AssetHelper;

/**
 * Class ConfigController
 * @package TinyPngBundle\Controller
 */
class ConfigController extends FrontendController
{
    /**
     * @Route("/admin/tinypng/get-config")
     */
    public function getConfig()
    {
        $config = AssetHelper::getConfig();
        return new JsonResponse($config, 200);
    }

    /**
     * @Route("/admin/tinypng/set-config")
     */
    public function setConfig(Request $request)
    {
        $data = json_decode($request->get('data'), true);

        if (is_null($data)) {
            return new JsonResponse(['success' => false], 200);
        }

        File::putPhpFile(PIMCORE_CONFIGURATION_DIRECTORY . '/tinypng.php', to_php_data_file_format([
            'config' => [
                'apiKey' => $data['config.apiKey']
            ]
        ]));
        return new JsonResponse(['success' => true], 200);
    }
}