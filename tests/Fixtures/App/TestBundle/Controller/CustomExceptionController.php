<?php

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\TestBundle\Controller;

use Novaway\Bundle\FeatureFlagBundle\Attribute\IsFeatureDisabled;
use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\TestBundle\Exception\Http411ExceptionFactory;
use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\TestBundle\Exception\Http423ExceptionFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class CustomExceptionController extends AbstractController
{
    public function routeWithExceptionInConfiguration(): Response
    {
        return new Response('OK');
    }

    #[IsFeatureDisabled('foo', exceptionClass: BadRequestHttpException::class)]
    public function disableWithCustomException(): Response
    {
        return new Response('OK');
    }

    #[IsFeatureDisabled('foo', exceptionClass: ConflictHttpException::class)]
    public function enableWithCustomException(): Response
    {
        return new Response('OK');
    }

    #[IsFeatureDisabled('foo', exceptionFactory: Http423ExceptionFactory::class)]
    public function disableWithCustomExceptionFactory(): Response
    {
        return new Response('OK');
    }

    #[IsFeatureDisabled('foo', exceptionFactory: Http411ExceptionFactory::class)]
    public function enableWithCustomExceptionFactory(): Response
    {
        return new Response('OK');
    }
}
