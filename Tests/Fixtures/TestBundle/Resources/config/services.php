<?php
declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Novaway\Bundle\FeatureFlagBundle\Tests\Fixtures\TestBundle\Controller\DefaultController;

return static function (ContainerConfigurator $container) {

    $container->services()
        ->set(DefaultController::class)
        ->autoconfigure(true)
        ->autowire(true)
        ->arg('$storage', service('novaway_feature_flag.manager.feature'))
        ->tag('controller.service_arguments');
};