<?php

declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\Tests\Mock;

use FreezyBee\NetteImagineGenerator\RequestedImagineInterface;

/**
 *
 */
class RequestedImagineWithoutNamespaceMock implements RequestedImagineInterface
{
    /**
     * @return string|null
     */
    public function getImageNamespace(): ?string
    {
        return null;
    }

    /**
     * @return string
     */
    public function getImageIdentifier(): string
    {
        return 'id-2';
    }

    /**
     * @return string
     */
    public function getImageExtension(): string
    {
        return 'jpg';
    }
}
