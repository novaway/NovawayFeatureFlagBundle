<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Factory;

use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ArrayStorageFactory extends AbstractStorageFactory
{
    public function createStorage(string $storageName, array $options = []): Storage
    {
        return new ArrayStorage($this->transform($this->validate($storageName, $options)));
    }

    protected function configureOptionResolver(OptionsResolver $resolver): void
    {
        parent::configureOptionResolver($resolver);
        $resolver
            ->setRequired('features')
            ->setAllowedTypes('features', ['array'])
        ;
    }

    private function transform(array $options): array
    {
        foreach ($options['features'] as $name => $features) {
            $feature = ['name' => $name, 'enabled' => true, 'condition' => '', 'description' => ''];

            if (\is_bool($features)) {
                $feature['enabled'] = $features;
            }

            if (\is_array($features)) {
                $feature = $features + $feature;
            }

            $options['features'][$name] = $feature;
        }

        return $options;
    }
}
