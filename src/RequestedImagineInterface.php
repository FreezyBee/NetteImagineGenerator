<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
interface RequestedImagineInterface
{
    /**
     * @return string|null
     */
    public function getImageNamespace(): ?string;

    /**
     * @return string
     */
    public function getImageIdentifier(): string;

    /**
     * @return string
     */
    public function getImageExtension(): string;
}
