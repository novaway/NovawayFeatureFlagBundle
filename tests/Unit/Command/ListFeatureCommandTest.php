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
    private const TEST_DATA = [
        'empty-features' => [
            'features' => [],
            'output' => [
                'table' => <<<OUTPUT
+------+---------+-------------+
| Name | Enabled | Description |
+------+---------+-------------+

OUTPUT,
                'json' => <<<JSON
[]

JSON,
                'csv' => <<<CSV
Name,Enabled,Description

CSV,
            ],
        ],
        'with-features' => [
            'features' => [
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
            'output' => [
                'table' => <<<OUTPUT
+----------+---------+-----------------------+
| Name     | Enabled | Description           |
+----------+---------+-----------------------+
| feature1 | Yes     | Feature 1 description |
| feature2 | No      | Feature 2 description |
| feature3 | Yes     | Feature 3 description |
+----------+---------+-----------------------+

OUTPUT,
                'json' => <<<JSON
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

JSON,
                'csv' => <<<CSV
Name,Enabled,Description
feature1,1,"Feature 1 description"
feature2,,"Feature 2 description"
feature3,1,"Feature 3 description"

CSV,
            ],
        ],
    ];

    public function testAnErrorOccuredIfInvalidFormatIsProvided(): void
    {
        $commandTester = $this->createCommandTester();

        $this->expectException(\InvalidArgumentException::class);

        $commandTester->execute(['--format' => 'invalid']);
    }

    /**
     * @dataProvider featuresProvider
     */
    public function testConfiguredFeaturesAreDisplayedInAskedFormat(array $features, string $outputFormat, string $expectedOutput): void
    {
        $commandTester = $this->createCommandTester($features);
        $commandTester->execute(['--format' => $outputFormat]);

        $commandTester->assertCommandIsSuccessful();

        static::assertSame($expectedOutput, $commandTester->getDisplay());
    }

    private function featuresProvider(): iterable
    {
        foreach (self::TEST_DATA as $caseDescription => $testData) {
            foreach ($testData['output'] as $format => $expectedOutput) {
                yield "$caseDescription in $format format" => [$testData['features'], $format, $expectedOutput];
            }
        }
    }

    private function createCommandTester(array $features = []): CommandTester
    {
        $storage = new ArrayStorage($features);
        $command = new ListFeatureCommand($storage);

        return new CommandTester($command);
    }
}
