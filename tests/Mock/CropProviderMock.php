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
use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;

/**
 *
 */
class CropProviderMock implements ImageProviderInterface
{
    /**
     * For testing
     * @var bool
     */
    public $used = false;

    /**
     * @var string
     */
    private $appDir;

    /**
     * CropProviderMock constructor.
     * @param string $appDir
     */
    public function __construct(string $appDir)
    {
        $this->appDir = $appDir;
    }

    /**
     * @param ImagineRequest $request
     * @return ImageInterface|null
     */
    public function getImage(ImagineRequest $request): ?ImageInterface
    {
        $crop = $request->getParameters()['crop'] ?? null;
        if ($crop !== 'crop') {
            return null;
        }

        $this->used = true;

        $image = new Imagine;
        return $image
            ->open('http://fakeimg.pl/350x200/?text=Hello')
            ->crop(new Point(0, 0), new Box(10, 10));
    }

    /**
     * @param ImagineRequest $request
     * @return array
     */
    public function getImagineSaveOptions(ImagineRequest $request): array
    {
        return ['jpeg_quality' => 50];
    }
}
