<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Novaway\Bundle\FeatureFlagBundle\DataCollector\FeatureCollector;
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    //    $services->set(FeatureCollector::class)
    //        ->args([service(FeatureManager::class), service(Storage::class)])
    //        ->tag('data_collector', [
    //            'template' => '@NovawayFeatureFlag/data_collector/template.html.twig',
    //            'id' => 'novaway_feature_flag.feature_collector',
    //        ]);
};
