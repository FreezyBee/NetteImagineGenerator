<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\Tests\Unit\Latte;

require __DIR__ . '/../../bootstrap.php';

use FreezyBee\NetteImagineGenerator\Latte\Macros;
use FreezyBee\NetteImagineGenerator\Tests\Mock\RequestedImagineMock;
use Tester\Assert;
use Tester\TestCase;

/**
 * @testCase
 */
class MacrosTest extends TestCase
{
    /**
     * @return array
     */
    public function getArgParams(): array
    {
        $base = ['namespace' => 'namespace-2', 'id' => 'id-1', 'extension' => 'gif'];

        return [
            [
                $base + ['width' => 100, 'height' => 200],
                [100, 200]
            ],
            [
                $base + ['param' => 'test'],
                ['param' => 'test']
            ],
            [
                $base + ['arg' => 'ok', 'width' => 100, 'height' => 200],
                [100, 200, 'arg' => 'ok']
            ]
        ];
    }

    /**
     * @dataProvider getArgParams
     * @param array $expected
     * @param array $args
     */
    public function testPrepareArguments(array $expected, array $args): void
    {
        $image = new RequestedImagineMock;
        Assert::equal($expected, Macros::prepareArguments(array_merge([$image], $args)));
    }

    /**
     *
     */
    public function testPrepareArgumentsErrors(): void
    {
        Assert::error(function () {
            Macros::prepareArguments([]);
        }, E_USER_NOTICE, 'Missing arguments in macro src');

        Assert::error(
            function () {
                Macros::prepareArguments([1]);
            },
            E_USER_NOTICE,
            'First parameter must be instance of FreezyBee\\NetteImagineGenerator\\RequestedImagineInterface'
        );
    }
}

(new MacrosTest)->run();
