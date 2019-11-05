<?php
namespace TinyPngBundle\Command;

use Pimcore\Console\AbstractCommand;
use Pimcore\Log\ApplicationLogger;
use Pimcore\Model\Asset;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TinyPngBundle\Service\TinyPngService;

class OptimizeImagesCommand extends AbstractCommand
{
    private $mimeTypes = [
        'image/jpeg',
        'image/png'
    ];

    /**
     *
     */
    protected function configure()
    {
        $this
            ->setName('tinypng:optimize')
            ->setDescription('Optimize all Assets with TinyPNG');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $listing = new Asset\Listing();

        foreach ($listing as $item) {

            if($item instanceof Asset\Image) {
                if(file_exists($item->getFileSystemPath())) {

                    if (!in_array($item->getMimetype(), $this->mimeTypes)) {
                        return false;
                    }

                    try {
                        (new TinyPngService())->minimize($item);
                    } catch (\Exception $e) {
                        (ApplicationLogger::getInstance())->error($e->getMessage(), [
                            'relatedObject' => $item,
                            'component' => 'TinyPNG'
                        ]);
                    }

                    $this->dump("Optimized: " . $item->getKey());

                    return true;

                }
            }
        }
    }
}
