<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\Http;

use FreezyBee\NetteImagineGenerator\Generator;
use Nette\Application\Routers\Route;
use NetteModule\MicroPresenter;

/**
 * Class ImagineRoute
 * @package FreezyBee\NetteImagineGenerator\Http
 */
class ImagineRoute extends Route
{
    /**
     * @param string $mask
     * @param Generator $generator
     */
    public function __construct(string $mask, Generator $generator)
    {
        parent::__construct($mask, function (MicroPresenter $presenter) use ($generator) {
            $parameters = $presenter->getRequest() !== null ? $presenter->getRequest()->getParameters() : [];
            $generator->generateImage(new ImagineRequest(
                $parameters['namespace'] ?? '',
                $parameters['id'] ?? '',
                $parameters['extension'] ?? '',
                isset($parameters['width']) ? (int) $parameters['width'] : null,
                isset($parameters['height']) ? (int) $parameters['height'] : null,
                $parameters
            ));
        });
    }
}
