<?php

declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\Latte;

use FreezyBee\NetteImagineGenerator\RequestedImagineInterface;
use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

/**
 * @author Jakub Janata <jakubjanata@gmail.com>
 */
class Macros extends MacroSet
{
    /**
     * @param Compiler $compiler
     */
    public static function install(Compiler $compiler): void
    {
        $me = new self($compiler);
        $me->addMacro('linkSrc', [$me, 'macroSrc']);
        $me->addMacro('src', null, null, function (MacroNode $node, PhpWriter $writer) use ($me) {
            return ' ?> src="<?php ' . $me->macroSrc($node, $writer) . ' ?>"<?php ';
        });
        $me->addMacro('srcset', null, null, function (MacroNode $node, PhpWriter $writer) use ($me) {
            return ' ?> srcset="<?php ' . $me->macroSrc($node, $writer) . ' ?>"<?php ';
        });
    }

    /**
     * {linkSrc image [,] [params]}
     * n:src="image [,] [params]"
     * @param MacroNode $node
     * @param PhpWriter $writer
     * @return string
     */
    public function macroSrc(MacroNode $node, PhpWriter $writer): string
    {
        return $writer->write('echo %escape(%modify($this->global->uiPresenter->link(":Nette:Micro:",' .
            ' FreezyBee\NetteImagineGenerator\Latte\Macros::prepareArguments(%node.array))))');
    }

    /**
     * @param mixed[] $arguments
     * @return mixed[]
     */
    public static function prepareArguments(array $arguments): array
    {
        if (count($arguments) === 0) {
            trigger_error('Missing arguments in macro src');
            return [];
        }

        if (!$arguments[0] instanceof RequestedImagineInterface) {
            trigger_error('First parameter must be instance of ' . RequestedImagineInterface::class);
            return [];
        }

        $image = $arguments[0];
        unset($arguments[0]);

        if (isset($arguments[1])) {
            $arguments['width'] = $arguments[1];
            unset($arguments[1]);
        }

        if (isset($arguments[2])) {
            $arguments['height'] = $arguments[2];
            unset($arguments[2]);
        }

        $namespace = $image->getImageNamespace();
        if ($namespace !== null) {
            $arguments['namespace'] = $namespace;
        }

        $arguments['id'] = $image->getImageIdentifier();
        $arguments['extension'] = $image->getImageExtension();

        return $arguments;
    }
}
