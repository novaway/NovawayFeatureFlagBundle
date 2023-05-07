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
use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\DefaultFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->instanceof(FeatureManager::class)
        ->tag('novaway_feature_flag.manager');

    $services->instanceof(Storage::class)
        ->tag('novaway_feature_flag.storage');

    $services->set(ChainedFeatureManager::class)
        ->args([tagged_iterator('novaway_feature_flag.manager')]);

    $services->set(ListFeatureCommand::class)
        ->args([tagged_iterator('novaway_feature_flag.storage')])
        ->tag('console.command');

    //    $services->set(DefaultFeatureManager::class)
    //        ->args([service(Storage::class)]);
    //    $services->alias(FeatureManager::class, DefaultFeatureManager::class);
    //
    //    $services->set(ArrayStorage::class)
    //        ->factory([null, 'fromArray'])
    //        ->args(['%novaway_feature_flag.features%']);

    $services->set(ControllerListener::class)
        ->tag('kernel.event_subscriber');

    $services->set(FeatureListener::class)
        ->args([service(ChainedFeatureManager::class)])
        ->tag('kernel.event_subscriber');
};
