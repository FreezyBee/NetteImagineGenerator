<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\Tests\Mock;

use Nette\Application\IRouter;
use Nette\Application\Routers\RouteList;

/**
 *
 */
class RouteFactoryMock
{
    /**
     * @return IRouter
     */
    public function createRouter(): IRouter
    {
        return new RouteList();
    }
}
