<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\TestBundle\Exception;

use Novaway\Bundle\FeatureFlagBundle\Factory\ExceptionFactory;
use Symfony\Component\HttpKernel\Exception\HttpException;

final class Http423ExceptionFactory implements ExceptionFactory
{
    public function create(): \Throwable
    {
        return new HttpException(423);
    }
}
