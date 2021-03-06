<?php

declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\Tests\Mock;

use FreezyBee\NetteImagineGenerator\Providers\ImageProviderInterface;
use FreezyBee\NetteImagineGenerator\Http\ImagineRequest;
use Imagine\Gd\Imagine;
use Imagine\Image\ImageInterface;

/**
 *
 */
class CopyProviderMock implements ImageProviderInterface
{
    /**
     * @param ImagineRequest $request
     * @return ImageInterface|null
     */
    public function getImage(ImagineRequest $request): ?ImageInterface
    {
        $request->getId();
        $request->getNamespace();
        $request->getWidth();
        $request->getHeight();

        if ($request->getExtension() !== 'jpg') {
            return null;
        }

        $image = new Imagine();
        return $image->open('http://fakeimg.pl/350x200/?text=Hello');
    }

    public function getImagineSaveOptions(ImagineRequest $request): array
    {
        return ['jpeg_quality' => 50];
    }
}
