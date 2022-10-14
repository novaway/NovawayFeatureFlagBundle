<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Novaway\Bundle\FeatureFlagBundle\EventListener\ControllerListener;
use Novaway\Bundle\FeatureFlagBundle\EventListener\FeatureListener;
use Novaway\Bundle\FeatureFlagBundle\Manager\DefaultFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->alias(FeatureManager::class, DefaultFeatureManager::class);

    $services->set(DefaultFeatureManager::class)
        ->args([service(ArrayStorage::class)]);

    $services->alias('novaway_feature_flag.manager', DefaultFeatureManager::class);

    $services->set(ArrayStorage::class)
        ->args(['%novaway_feature_flag.features%']);

    $services->alias('novaway_feature_flag.storage.default', ArrayStorage::class);

    $services->set('novaway_feature_flag.listener.controller', ControllerListener::class)
        ->args([service('annotation_reader')])
        ->tag('kernel.event_subscriber');

    $services->set('novaway_feature_flag.listener.feature', FeatureListener::class)
        ->args([service('novaway_feature_flag.manager')])
        ->tag('kernel.event_subscriber');
};
