<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\TestBundle;

use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\App\TestBundle\DependencyInjection\TestBundleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TestBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new TestBundleExtension();
    }
}
