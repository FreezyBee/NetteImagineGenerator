<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\Providers;

use FreezyBee\NetteImagineGenerator\Http\ImagineRequest;
use Imagine\Image\ImageInterface;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
interface ImageProviderInterface
{
    /**
     * @param ImagineRequest $request
     * @return ImageInterface|null
     */
    public function getImage(ImagineRequest $request): ?ImageInterface;

    /**
     * @param \FreezyBee\NetteImagineGenerator\Http\ImagineRequest $request
     * @return array
     */
    public function getImagineSaveOptions(ImagineRequest $request): array;
}
