<?php
declare(strict_types=1);

/*
 * This file is part of the some package.
 * (c) Jakub Janata <jakubjanata@gmail.com>
 * For the full copyright and license information, please view the LICENSE file.
 */

namespace FreezyBee\NetteImagineGenerator\Tests\Mock;

use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\Template;

/**
 *
 */
class PresenterMock extends Presenter
{
    /**
     *
     */
    public function actionDefault()
    {
        /** @var Template $template */
        $template = $this->getTemplate();
        $template->setFile(__DIR__ . '/template/file.latte');
        $template->setParameters([
            'file' => new RequestedImagineMock,
            'fileWithoutNamespace' => new RequestedImagineWithoutNamespaceMock
        ]);
    }
}
