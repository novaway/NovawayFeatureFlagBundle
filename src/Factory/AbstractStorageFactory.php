<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Factory;

use Novaway\Bundle\FeatureFlagBundle\Exception\ConfigurationException;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractStorageFactory implements StorageFactory
{
    /**
     * @throws ConfigurationException
     */
    public function validate(string $storageName, array $options): array
    {
        $resolver = new OptionsResolver();
        $this->configureOptionResolver($resolver);

        try {
            return $resolver->resolve($options);
        } catch (\Exception $e) {
            $message = sprintf(
                'Error while configure storage %s. Verify your configuration at "novaway_feature_flag.storages.%s.options". %s',
                $storageName,
                $storageName,
                $e->getMessage()
            );

            throw new ConfigurationException($message, $e->getCode(), $e);
        }
    }

    protected function configureOptionResolver(OptionsResolver $resolver): void
    {
    }
}
