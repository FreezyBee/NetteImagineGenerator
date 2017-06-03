<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\Tests\Integration;

require __DIR__ . '/../bootstrap.php';

use FreezyBee\NetteImagineGenerator\Tests\Mock\CropProviderMock;
use Nette\Application\BadRequestException;
use Nette\Application\Responses\TextResponse;
use Nette\Application\Request as AppRequest;
use Nette\Configurator;
use Nette\DI\Container;
use Nette\Http\Request;
use Nette\Http\UrlScript;
use Nette\Utils\FileSystem;
use Tester\Assert;
use Tester\TestCase;

/**
 * @testCase
 */
class NetteImagineGeneratorTest extends TestCase
{
    /**
     *
     */
    public function testGetImageCopy()
    {
        $container = $this->initContainer();

        $url = new UrlScript('http://localhost/generated/test/1.jpg');
        $request = new Request($url);

        $container->removeService('http.request');
        $container->addService('http.request', $request);

        ob_start();
        $container->getService('application')->run();
        $html = ob_get_clean();

        // response contains file
        Assert::match('#gd-jpeg v1.0#', $html);

        // new file saved to filesystem storage
        Assert::true(file_exists(__DIR__ . '/../www/generated/test/1.jpg'));

        /** @var CropProviderMock $cropProviderMock */
        $cropProviderMock = $container->getService('imagineGenerator.provider.0');
        Assert::false($cropProviderMock->used);
    }

    /**
     *
     */
    public function testGetImageCrop()
    {
        $container = $this->initContainer();

        $url = new UrlScript('http://localhost/generated/1--crop.jpg');
        $request = new Request($url);

        $container->removeService('http.request');
        $container->addService('http.request', $request);

        ob_start();
        $container->getService('application')->run();
        $html = ob_get_clean();

        // response contains file
        Assert::match('#gd-jpeg v1.0#', $html);

        // new file saved to filesystem storage
        Assert::true(file_exists(__DIR__ . '/../www/generated/1--crop.jpg'));

        /** @var CropProviderMock $cropProviderMock */
        $cropProviderMock = $container->getService('imagineGenerator.provider.0');
        Assert::true($cropProviderMock->used);
    }

    /**
     *
     */
    public function testImageNotFound()
    {
        $container = $this->initContainer();

        $url = new UrlScript('http://localhost/generated/test/1.gif');
        $request = new Request($url);

        $container->removeService('http.request');
        $container->addService('http.request', $request);

        Assert::exception(function () use ($container) {
            $container->getService('application')->run();
        }, BadRequestException::class);
    }

    /**
     *
     */
    public function testMacro()
    {
        $container = $this->initContainer();

        /** @var TextResponse $response */
        $response = $container->getService('presenterMock')->run(new AppRequest(''));

        ob_start();
        $response->getSource()->render();
        $html = ob_get_clean();
        Assert::same(file_get_contents(__DIR__ . '/../Mock/template/expected.html'), $html);
    }

    /**
     * @return Container
     */
    private function initContainer(): Container
    {
        $configurator = new Configurator;
        $dir = __DIR__ . '/../tmp/' . random_int(0, 100);
        FileSystem::createDir($dir);
        $configurator->setTempDirectory($dir);
        $configurator->addConfig(__DIR__ . '/../config.neon');
        $configurator->addParameters(['wwwDir' => __DIR__ . '/../www']);
        return $configurator->createContainer();
    }
}

(new NetteImagineGeneratorTest)->run();
