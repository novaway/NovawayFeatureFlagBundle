<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Novaway\Bundle\FeatureFlagBundle\Checker\ExpressionLanguageChecker;
use Novaway\Bundle\FeatureFlagBundle\EventListener\ControllerListener;
use Novaway\Bundle\FeatureFlagBundle\EventListener\FeatureListener;
use Novaway\Bundle\FeatureFlagBundle\Factory\ArrayStorageFactory;
use Novaway\Bundle\FeatureFlagBundle\Factory\ExceptionFactory;
use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(ExceptionFactory::class)->autoconfigure();

    $services->set('novaway_feature_flag.factory.array', ArrayStorageFactory::class);

    $services->set('novaway_feature_flag.checker.expression_language', ExpressionLanguageChecker::class)
        ->args([
            service('security.expression_language')->nullOnInvalid(),
            service('security.authentication.trust_resolver')->nullOnInvalid(),
            service('security.role_hierarchy')->nullOnInvalid(),
            service('security.token_storage')->nullOnInvalid(),
            service('security.authorization_checker')->nullOnInvalid(),
            service('logger')->nullOnInvalid(),
        ])
    ;

    $services->set(ChainedFeatureManager::class)
        ->args([tagged_iterator('novaway_feature_flag.manager')]);

    $services->alias(FeatureManager::class, ChainedFeatureManager::class);
    $services->alias('novaway_feature_flag.manager', ChainedFeatureManager::class);

    $services->set(ControllerListener::class)
        ->tag('kernel.event_subscriber');

    $services->set(FeatureListener::class)
        ->autowire()
        ->tag('kernel.event_subscriber');
};
