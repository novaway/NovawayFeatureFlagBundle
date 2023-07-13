<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Storage;

use Novaway\Bundle\FeatureFlagBundle\Exception\FeatureUndefinedException;
use Novaway\Bundle\FeatureFlagBundle\Model\FeatureFlag;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;

final class ArrayStorageTest extends TestCase
{
    public function testAllReturnEmptyArrayIfNoFeatureDefined(): void
    {
        $storage = new ArrayStorage(['features' => []]);

        $this->assertEmpty($storage->all());
    }

    public function testAllReturnDefinedFeatures(): void
    {
        $storage = new ArrayStorage([
            'features' => [
                'foo' => ['name' => 'foo', 'enabled' => false],
                'bar' => ['name' => 'bar', 'enabled' => true, 'description' => 'Feature bar description'],
            ],
        ]);

        $features = $storage->all();

        $this->assertCount(2, $features);
        $this->assertEquals(
            new FeatureFlag('foo', false),
            $features['foo'],
        );
        $this->assertEquals(
            new FeatureFlag('bar', true, 'Feature bar description'),
            $features['bar'],
        );
    }

    public function testAnExceptionThrowsIfAccessUndefinedFeature(): void
    {
        $storage = new ArrayStorage(['features' => []]);

        $this->expectException(FeatureUndefinedException::class);

        $storage->get('unknown-feature');
    }
}
