<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\TestBundle\Exception;

use Novaway\Bundle\FeatureFlagBundle\Factory\ExceptionFactory;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Exception\LengthRequiredHttpException;

class Http411ExceptionFactory implements ExceptionFactory
{
    public function create(string $feature, ControllerEvent $event): \Throwable
    {
        return new LengthRequiredHttpException();
    }
}
