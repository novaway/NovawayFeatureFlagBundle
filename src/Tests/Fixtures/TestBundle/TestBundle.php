<?php

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\TestBundle;

use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\TestBundle\DependencyInjection\TestBundleExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class TestBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new TestBundleExtension();
    }
}
