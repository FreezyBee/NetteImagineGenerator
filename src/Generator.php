<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator;

use FreezyBee\NetteImagineGenerator\Http\ImagineRequest;
use FreezyBee\NetteImagineGenerator\Providers\ImageProviderInterface;
use Imagine\Exception\RuntimeException;
use Imagine\Image\ImageInterface;
use Nette\Application\BadRequestException;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\SmartObject;
use Tracy\Debugger;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
class Generator
{
    use SmartObject;

    /** @var string[] */
    private static $formats = ['jpeg', 'jpg', 'gif', 'png', 'wbmp', 'xbm'];

    /** @var string */
    private $wwwDir;

    /** @var IRequest */
    private $request;

    /** @var IResponse */
    private $response;

    /** @var ImageProviderInterface[] */
    private $providers;

    /**
     * Generator constructor.
     * @param string $wwwDir
     * @param IRequest $request
     * @param IResponse $response
     */
    public function __construct(string $wwwDir, IRequest $request, IResponse $response)
    {
        $this->wwwDir = $wwwDir;
        $this->request = $request;
        $this->response = $response;
        $this->providers = [];
    }

    /**
     * @param ImageProviderInterface $provider
     */
    public function addProvider(ImageProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }

    /**
     * @param ImagineRequest $imageRequest
     * @throws BadRequestException
     */
    public function generateImage(ImagineRequest $imageRequest): void
    {
        /** @var ImageProviderInterface $provider */
        $provider = null;

        /** @var ImageInterface $image */
        $image = null;

        foreach ($this->providers as $provider) {
            $image = $provider->getImage($imageRequest);
            if ($image) {
                break;
            }
        }

        if ($image === null) {
            throw new BadRequestException;
        }

        $destination = $this->wwwDir . $this->request->getUrl()->getPath();

        try {
            $image->save($destination, $provider->getImagineSaveOptions($imageRequest));

            $format = strtolower($imageRequest->getExtension());
            $format = in_array($format, self::$formats, true) ? $format : 'jpg';
            $image->show($format);
        } catch (RuntimeException $e) {
            $this->response->setCode(IResponse::S404_NOT_FOUND);
            Debugger::log($e, Debugger::EXCEPTION);
        }
    }
}
