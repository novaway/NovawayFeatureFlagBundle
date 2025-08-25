# FeatureFlagBundle

[![Build Status](https://github.com/novaway/NovawayFeatureFlagBundle/actions/workflows/tests.yml/badge.svg)](https://actions-badge.atrox.dev/novaway/NovawayFeatureFlagBundle/goto?ref=master)
![Coverage](coverage_badge.svg)
[![License](https://poser.pugx.org/novaway/feature-flag-bundle/license)](https://packagist.org/packages/novaway/feature-flag-bundle)
[![Latest Stable Version](https://poser.pugx.org/novaway/feature-flag-bundle/v/stable)](https://packagist.org/packages/novaway/feature-flag-bundle)
[![Latest Unstable Version](https://poser.pugx.org/novaway/feature-flag-bundle/v/unstable)](https://packagist.org/packages/novaway/feature-flag-bundle)
[![Total Downloads](https://poser.pugx.org/novaway/feature-flag-bundle/downloads)](https://packagist.org/packages/novaway/feature-flag-bundle)

The FeatureFlagBundle is a bundle to manage features flags in your Symfony applications.

## Compatibility

This bundle is tested with at least all maintained Symfony version.

## Documentation

###  Install it

Install extension using [composer](https://getcomposer.org):

```bash
composer require novaway/feature-flag-bundle
```

If you don't use Flex, enable the bundle in your `config/bundles.php` file:

```php
<?php

return [
    // ...
    Novaway\Bundle\FeatureFlagBundle\NovawayFeatureFlagBundle::class => ['all' => true],
];
```

###  Configuration

To configure and register a feature manager you need a factory service. You may also need to change some options to the factory.

```yaml
# ...
novaway_feature_flag:
    default_manager: default
    managers:
        default:
            factory: 'novaway_feature_flag.factory.array'
            options:
                features:
                    my_feature_1: false
                    my_feature_2: true
                    my_feature3: '%env(bool:FEATURE_ENVVAR)%'
```

The factories that come with this bundle can be found in the table below.

| Factory service id                 | Options    |
|------------------------------------|------------|
| novaway_feature_flag.factory.array | `features` |

#### Example configuration

```yaml
# ...
novaway_feature_flag:
    default_manager: default
    managers:
        default:
            factory: novaway_feature_flag.factory.array
            options:
                features:
                    my_feature_1:
                        enabled: false
                        description: MyFeature1 description text
                    my_feature_2:
                        enabled: true
                        description: MyFeature2 description text
                    my_feature3:
                        enabled: '%env(bool:FEATURE_ENVVAR)%'
                        description: MyFeature3 description text
```

You can declare multiple managers. Multiple providers is useful if you want to use different storage providers or to isolate your features flags.

```yaml
# ...
novaway_feature_flag:
    default_manager: manager_foo
    managers:
        manager_foo:
            factory: novaway_feature_flag.factory.array
            options:
                features:
                    my_feature_1:
                        enabled: false
                        description: MyFeature1 description text
                    my_feature_2:
                        enabled: true
                        description: MyFeature2 description text
                    my_feature3:
                        enabled: '%env(bool:FEATURE_ENVVAR)%'
                        description: MyFeature3 description text
        manager_bar:
            factory: novaway_feature_flag.factory.array
            options:
                features:
                    my_feature_4:
                        enabled: false
                        description: MyFeature4 description text
                    my_feature_5: []
                    my_feature_6: ~
                    my_feature_7: false
```

When several managers are defined, they are registered in the Symfony dependency injection container as services with the following naming convention: `novaway_feature_flag.manager.<manager_name>`.

For example, the `manager_bar` is accessible with the following service name: `novaway_feature_flag.manager.manager_bar`.

Manager storage are also registered in the Symfony dependency injection container as services with the following naming convention: `novaway_feature_flag.storage.<manager_name>`.

#### Use it as a service

The bundle adds a global `novaway_feature_flag.manager` (also bind to `FeatureManager`) service you can use in your PHP classes.

In the case you have defined several managers, the service use the `ChainedFeatureManager` class to chain all declared managers.

```php
use Novaway\Bundle\FeatureFlagBundle\Manager\FeatureManager;
// ...

class MyController extends Controller
{
    public function myAction(FeatureManager $featureManager): Response
    {
        if ($featureManager->isEnabled('my_feature_1')) {
            // my_feature_1 is enabled
        }

        if ($featureManager->isDisabled('my_feature_2')) {
            // my_feature_2 is not enabled
        }

        // ...
    }
}
```

#### In your Twig templates

You can also check a flag in your templates:

```twig
{% if isFeatureEnabled('my_feature_1') %}
    {% include 'feature1_template.html.twig' %}
{% endif %}

{% if isFeatureDisabled('my_feature_2') %}
    {% include 'feature2_template.html.twig' %}
{% endif %}
```

#### In the routing configuration

The package allows you to restrict a controller access by adding some configuration in your routing definition.

```yaml
# app/config/routing.yml
my_first_route:
    path: /my/first/route
    defaults:
        _controller: AppBundle:Default:index
        _features:
            - { feature: my_feature_key, enabled: false } # The action is accessible if "my_feature_key" is disabled

my_second_route:
    path: /my/second-route
    defaults:
        _controller: AppBundle:Default:second
        _features:
            - { feature: foo } # The action is accessible if "foo" is enabled ...
            - { feature: bar, enabled: true } # ... and "bar" feature is also enabled
            - { feature: feature-42, enabled: true, exceptionClass: Symfony\Component\HttpKernel\Exception\BadRequestHttpException } # will throw a BadRequestHttpException if "feature-42" is disabled
            - { feature: feature-44, enabled: true, exceptionFactory: Symfony\Component\HttpKernel\Exception\BadRequestHttpExceptionFactory } # will use the BadRequestHttpExceptionFactory registered factory class to create the exception to be thrown
```

#### As a controller attribute

You can also restrict a controller access with attributes, two attributes are available:

* `Novaway\Bundle\FeatureFlagBundle\Attribute\FeatureEnabled`
* `Novaway\Bundle\FeatureFlagBundle\Attribute\FeatureDisabled`

```php
#[FeatureEnabled(name: "foo")]
class MyController extends Controller
{
    #[FeatureEnabled(name: "foo", exceptionClass: BadRequestHttpException::class)]
    public function annotationFooEnabledAction(): Response
    {
        return new Response('MyController::annotationFooEnabledAction');
    }

    #[FeatureDisabled(name: "foo", exceptionFactory: MyExceptionFactory::class)]
    public function annotationFooDisabledAction(): Response
    {
        return new Response('MyController::annotationFooDisabledAction');
    }
}
```

### API Controller

The bundle provides an API controller that exposes feature flags via REST endpoints:

- `GET /features` - Get all feature flags

To enable the API controller, you need to register its routes in your routing configuration:

```yaml
# config/routes.yaml (or app/config/routing.yml for older Symfony versions)
feature_api:
    resource: '@NovawayFeatureFlagBundle/Resources/config/routing.yml'
```

You can also register endpoints manually:

```yaml
features_all:
    path: /features
    defaults:
        _controller: Novaway\Bundle\FeatureFlagBundle\Controller\FeatureApiController::all
    methods: [GET]
```

The API returns JSON responses with feature flag data including key, description, and enabled status.

### Implement your own storage provider

1. First your need to create your storage provider class which implement the `Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface` interface
2. Register it in the Symfony dependency injection container
3. Specify the storage you want to use in a manager configuration

```yaml
novaway_feature_flag:
    manager:
        manager_name:
            storage: your.custom.service.name
            options:
                # arguments need to create the storage service
```

When you create a storage, the static method `create` is called to create the storage instance.

## License

This library is published under [MIT license](LICENSE)
