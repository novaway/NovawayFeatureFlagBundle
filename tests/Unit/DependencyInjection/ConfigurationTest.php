<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\DependencyInjection;

use Novaway\Bundle\FeatureFlagBundle\DependencyInjection\NovawayFeatureFlagExtension;
use Novaway\Bundle\FeatureFlagBundle\Tests\FixturePath;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class ConfigurationTest extends TestCase
{
    public function testFeatureConfigurationIsLoaded(): void
    {
        $configuration = $this->getConfiguration('config.yml');

        static::assertArrayHasKey('features', $configuration);
        static::assertTrue($configuration['features']['foo']);
        static::assertFalse($configuration['features']['bar']);
    }

    private function getConfiguration(string $file): array
    {
        $extension = new NovawayFeatureFlagExtension();

        $container = new ContainerBuilder();
        $container->registerExtension($extension);

        $loader = new YamlFileLoader($container, new FileLocator());
        $loader->load(FixturePath::CONFIG_FILE);

        $configuration = $container->getExtensionConfig($extension->getAlias());

        return reset($configuration);
    }
}
