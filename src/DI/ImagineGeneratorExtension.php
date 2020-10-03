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
use FreezyBee\PrependRoute\DI\IPrependRouteProvider;
use FreezyBee\PrependRoute\DI\PrependRouteExtension;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Definitions\Statement;
use Nette\DI\MissingServiceException;
use Nette\InvalidArgumentException;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
class ImagineGeneratorExtension extends CompilerExtension implements IPrependRouteProvider
{
    /** @var string */
    public $wwwDir;

    /** @var string[] */
    private $routeDefs = [];

    public function __construct(string $wwwDir)
    {
        $this->wwwDir = $wwwDir;
    }

    /**
     *
     */
    public function loadConfiguration(): void
    {
        $container = $this->getContainerBuilder();

        /** @var mixed $config */
        $config = $this->config;

        /** @var array<int|string, string> $routes */
        $routes = $config['routes'];

        /** @var array<int|string, string|Statement> $providers */
        $providers = $config['providers'];

        if (!$providers || !$routes) {
            throw new InvalidArgumentException(__CLASS__ . ': You have to register some providers and routes');
        }

        if (!$this->compiler->getExtensions(PrependRouteExtension::class)) {
            throw new MissingServiceException('You must register PrependRouteExtension');
        }

        $generator = $container->addDefinition($this->prefix('generator'))
            ->setFactory(Generator::class, [$this->wwwDir]);

        // register routes
        foreach ($routes as $route => $mask) {
            $serviceName = $this->prefix('route.' . $route);
            $container->addDefinition($serviceName)
                ->setFactory(ImagineRoute::class, [$mask, $this->prefix('@generator')])
                ->setAutowired(false);

            $this->routeDefs[] = $serviceName;
        }

        // register providers
        foreach ($providers as $name => $providerClassName) {
            $provider = $container->addDefinition($this->prefix('provider.' . $name))
                ->setAutowired(false);

            if ($providerClassName instanceof Statement) {
                // @phpstan-ignore-next-line
                $provider->setFactory($providerClassName->getEntity(), $providerClassName->arguments);
            } else {
                $provider->setFactory($providerClassName);
            }

            $generator->addSetup('addProvider', [$provider]);
        }

        // register macros
        /** @var FactoryDefinition $latte */
        $latte = $container->getDefinition('nette.latteFactory');
        $latte->getResultDefinition()->addSetup(Macros::class . '::install(?->getCompiler())', ['@self']);
    }

    /**
     * {@inheritdoc}
     */
    public function getPrependRoutes(): array
    {
        return $this->routeDefs;
    }
}
