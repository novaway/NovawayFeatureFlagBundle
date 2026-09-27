<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Functional;

use PHPUnit\Framework\Attributes\After;
use PHPUnit\Framework\Attributes\Before;

/**
 * Booting a Symfony kernel registers `Symfony\Component\ErrorHandler\ErrorHandler` as the
 * exception handler, and shutting the kernel down does not unregister it. PHPUnit flags such
 * a leftover handler as a risky test, so the handler stack is restored after each test.
 */
trait RestoreExceptionHandlerTrait
{
    /** @var callable|null */
    private $exceptionHandlerBeforeTest;

    #[Before]
    protected function rememberExceptionHandler(): void
    {
        $this->exceptionHandlerBeforeTest = set_exception_handler(null);
        restore_exception_handler();
    }

    #[After]
    protected function restoreExceptionHandler(): void
    {
        while (null !== ($handler = set_exception_handler(null)) && $handler !== $this->exceptionHandlerBeforeTest) {
            restore_exception_handler();
            restore_exception_handler();
        }

        restore_exception_handler();
    }
}
