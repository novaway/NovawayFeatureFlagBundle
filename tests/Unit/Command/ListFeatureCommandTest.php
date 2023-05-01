<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Tests\Unit\Command;

use Novaway\Bundle\FeatureFlagBundle\Command\ListFeatureCommand;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class ListFeatureCommandTest extends TestCase
{
    /**
     * @dataProvider tableListFeatureProvider
     */
    public function testConfiguredFeaturesAreDisplayedInTable(array $features, string $expectedOutput): void
    {
        $commandTester = $this->createCommandTester($features);
        $commandTester->execute([]);

        $commandTester->assertCommandIsSuccessful();

        static::assertSame($expectedOutput, $commandTester->getDisplay());
    }

    /**
     * @dataProvider jsonListFeatureProvider
     */
    public function testConfiguredFeaturesAreDisplayedInJson(array $features, string $expectedOutput): void
    {
        $commandTester = $this->createCommandTester($features);
        $commandTester->execute(['--format' => 'json']);

        $commandTester->assertCommandIsSuccessful();

        static::assertSame($expectedOutput, $commandTester->getDisplay());
    }

    public function tableListFeatureProvider(): iterable
    {
        yield 'no feature' => [
            [],
            <<<OUTPUT
+------+---------+-------------+
| Name | Enabled | Description |
+------+---------+-------------+

OUTPUT
        ];

        yield 'with features' => [
            [
                'feature1' => [
                    'enabled' => true,
                    'description' => 'Feature 1 description',
                ],
                'feature2' => [
                    'enabled' => false,
                    'description' => 'Feature 2 description',
                ],
                'feature3' => [
                    'enabled' => true,
                    'description' => 'Feature 3 description',
                ],
            ],
            <<<OUTPUT
+----------+---------+-----------------------+
| Name     | Enabled | Description           |
+----------+---------+-----------------------+
| feature1 | Yes     | Feature 1 description |
| feature2 | No      | Feature 2 description |
| feature3 | Yes     | Feature 3 description |
+----------+---------+-----------------------+

OUTPUT
        ];
    }

    public function jsonListFeatureProvider(): iterable
    {
        yield 'no feature' => [
            [],
            <<<JSON
[]

JSON
        ];

        yield 'with features' => [
            [
                'feature1' => [
                    'enabled' => true,
                    'description' => 'Feature 1 description',
                ],
                'feature2' => [
                    'enabled' => false,
                    'description' => 'Feature 2 description',
                ],
                'feature3' => [
                    'enabled' => true,
                    'description' => 'Feature 3 description',
                ],
            ],
            <<<JSON
{
    "feature1": {
        "key": "feature1",
        "enabled": true,
        "description": "Feature 1 description"
    },
    "feature2": {
        "key": "feature2",
        "enabled": false,
        "description": "Feature 2 description"
    },
    "feature3": {
        "key": "feature3",
        "enabled": true,
        "description": "Feature 3 description"
    }
}

JSON
        ];
    }

    private function createCommandTester(array $features = []): CommandTester
    {
        $storage = new ArrayStorage($features);
        $command = new ListFeatureCommand($storage);

        return new CommandTester($command);
    }
}
