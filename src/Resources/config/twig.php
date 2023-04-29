<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Twig\Extension\FeatureFlagExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(FeatureFlagExtension::class)
        ->args([service(FeatureManager::class)])
        ->tag('twig.extension');
};
