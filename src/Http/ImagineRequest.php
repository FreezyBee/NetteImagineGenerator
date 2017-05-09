<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\Http;

use Nette\SmartObject;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
class ImagineRequest
{
    use SmartObject;

    /** @var string|null */
    private $namespace;

    /** @var string */
    private $id;

    /** @var string */
    private $extension;

    /** @var int|null */
    private $width;

    /** @var int|null */
    private $height;

    /** @var array */
    private $parameters;

    /**
     * @param string|null $namespace
     * @param string $id
     * @param string $extension
     * @param int|null $width
     * @param int|null $height
     * @param array $parameters
     */
    public function __construct(
        ?string $namespace,
        string $id,
        string $extension,
        ?int $width,
        ?int $height,
        array $parameters
    ) {
        $this->namespace = $namespace;
        $this->id = $id;
        $this->extension = $extension;
        $this->width = $width;
        $this->height = $height;
        unset($parameters['callback']);
        $this->parameters = $parameters;
    }

    /**
     * @return string|null
     */
    public function getNamespace(): ?string
    {
        return $this->namespace;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getExtension(): string
    {
        return $this->extension;
    }

    /**
     * @return int|null
     */
    public function getWidth(): ?int
    {
        return $this->width;
    }

    /**
     * @return int|null
     */
    public function getHeight(): ?int
    {
        return $this->height;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }
}
