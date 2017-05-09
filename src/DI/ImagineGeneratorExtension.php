<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\DI;

use FreezyBee\NetteImagineGenerator\Generator;
use FreezyBee\NetteImagineGenerator\Http\ImagineRoute;
use FreezyBee\NetteImagineGenerator\Latte\Macros;
use Nette\DI\CompilerExtension;
use Nette\DI\Helpers;
use Nette\InvalidArgumentException;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
class ImagineGeneratorExtension extends CompilerExtension
{
    /** @var array */
    private static $defaults = [
        'routes' => [],
        'providers' => [],
        'wwwDir' => '%wwwDir%',
    ];

    public function loadConfiguration()
    {
        $container = $this->getContainerBuilder();
        $config = $this->validateConfig(Helpers::expand(self::$defaults, $container->parameters));

        /** @var array $routes */
        $routes = $config['routes'];

        /** @var array $providers */
        $providers = $config['providers'];

        if (!$providers || !$routes) {
            throw new InvalidArgumentException(__CLASS__ . ': You have to register some providers and routes');
        }

        $generator = $container->addDefinition($this->prefix('generator'))
            ->setClass(Generator::class, [$config['wwwDir']]);


        $router = $container->getDefinition('router');

        // register routes
        foreach ($routes as $route => $mask) {
            $def = $container->addDefinition($this->prefix('route.' . $route))
                ->setClass(ImagineRoute::class, [$mask, $this->prefix('@generator')])
                ->setAutowired(false);

            $router->addSetup('$service[] = ?', [$def]);
        }

        // register providers
        foreach ($providers as $name => $providerClassName) {
            $provider = $container->addDefinition($this->prefix('provider.' . $name))
                ->setClass($providerClassName)
                ->setAutowired(false);

            $generator->addSetup('addProvider', [$provider]);
        }

        // register macros
        $latte = $container->getDefinition('nette.latteFactory');
        $latte->addSetup(Macros::class . '::install(?->getCompiler())', ['@self']);
    }
}
