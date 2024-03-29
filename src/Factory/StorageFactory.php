<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Factory;

use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;

interface StorageFactory
{
    public function createStorage(string $storageName, array $options = []): Storage;
}
