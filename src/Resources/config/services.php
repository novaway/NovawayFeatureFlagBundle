<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Novaway\Bundle\FeatureFlagBundle\Command\ListFeatureCommand;
use Novaway\Bundle\FeatureFlagBundle\EventListener\ControllerListener;
use Novaway\Bundle\FeatureFlagBundle\EventListener\FeatureListener;
use Novaway\Bundle\FeatureFlagBundle\Manager\DefaultFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ListFeatureCommand::class)
        ->args([service(Storage::class)])
        ->tag('console.command');

    $services->set(DefaultFeatureManager::class)
        ->args([service(Storage::class)]);
    $services->alias(FeatureManager::class, DefaultFeatureManager::class);

    $services->set(ArrayStorage::class)
        ->factory([null, 'fromArray'])
        ->args(['%novaway_feature_flag.features%']);

    $services->set(ControllerListener::class)
        ->tag('kernel.event_subscriber');

    $services->set(FeatureListener::class)
        ->args([service(FeatureManager::class)])
        ->tag('kernel.event_subscriber');
};
