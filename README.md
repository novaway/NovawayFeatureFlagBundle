# FeatureFlagBundle

[![Build Status](https://img.shields.io/endpoint.svg?url=https%3A%2F%2Factions-badge.atrox.dev%2Fnovaway%2FNovawayFeatureFlagBundle%2Fbadge%3Fref%3Dmaster&style=flat)](https://actions-badge.atrox.dev/Novaway/NovawayFeatureFlagBundle/goto?ref=master)
[![Latest Stable Version](https://poser.pugx.org/novaway/feature-flag-bundle/v/stable.png)](https://packagist.org/packages/novaway/feature-flag-bundle)

The FeatureFlagBundle is a bundle to manage features flags in your Symfony applications.

⚠️ You're currently reading the documentation for the next major version. Refer to the [2.x documentation](https://github.com/novaway/NovawayFeatureFlagBundle/tree/2.x) to read the stable version documentation.

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

###  Use it

#### Define your features

First you have to configure the bundle and define your feature flags in the `config.yml`.

```yaml
# ...
novaway_feature_flag:
    default_manager: default
    managers:
        default:
            storage: 'Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage'
            options:
                features:
                    my_feature_1: false
                    my_feature_2: true
                    my_feature3: '%env(bool:FEATURE_ENVVAR)%'
```

The `Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage` allows you to define your feature flags in an extended way:

```yaml
# ...
novaway_feature_flag:
    default_manager: default
    managers:
        default:
            storage: 'Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage'
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
            storage: 'Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage'
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
            storage: 'Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage'
            options:
                features:
                    my_feature_4:
                        enabled: false
                        description: MyFeature4 description text
```

When several managers are defined, they are registered in the Symfony dependency injection container as services with the following naming convention: `novaway_feature_flag.manager.<manager_name>`.

For example, the `manager_bar` is accessible with the following service name: `novaway_feature_flag.manager.manager_bar`.

Manager storage are also registered in the Symfony dependency injection container as services with the following naming convention: `novaway_feature_flag.storage.<manager_name>`.

#### Use it as a service

The bundle adds a global `novaway_feature_flag.manager` service you can use in your PHP classes.

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
```

#### As a controller attribute

You can also restrict a controller access with attributes :

```php
#[Feature(name: "foo", enabled: true)]
class MyController extends Controller
{
    #[Feature(name: "foo")]
    public function annotationFooEnabledAction(): Response
    {
        return new Response('MyController::annotationFooEnabledAction');
    }

    #[Feature(name: "foo", enabled: true)]
    public function annotationFooEnabledBisAction(): Response
    {
        return new Response('MyController::annotationFooEnabledAction');
    }

    #[Feature(name: "foo", enabled: false)]
    public function annotationFooDisabledAction(): Response
    {
        return new Response('MyController::annotationFooDisabledAction');
    }
}
```

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
