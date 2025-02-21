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
use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Manager\DefaultFeatureManager;
use Novaway\Bundle\FeatureFlagBundle\Storage\ArrayStorage;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class ListFeatureCommandTest extends TestCase
{
    private const TEST_DATA = [
        'empty-features' => [
            'features' => [],
            'output' => [
                'table' => <<<OUTPUT
No feature declared.

OUTPUT,
                'json' => <<<JSON
[]

JSON,
                'csv' => <<<CSV
Manager,Name,Enabled,Description,Options

CSV,
            ],
        ],
        'with-features' => [
            'features' => [
                'manager1' => [
                    'options' => [
                        'features' => [
                            'feature1' => [
                                'name' => 'feature1',
                                'enabled' => true,
                                'description' => 'Feature 1 description',
                            ],
                            'feature2' => [
                                'name' => 'feature2',
                                'enabled' => false,
                                'description' => 'Feature 2 description',
                                'options' => [
                                    'foo' => 'bar',
                                    'parray' => [
                                        'key1' => 'value1',
                                        'key2' => 'value2',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
                'manager2' => [
                    'options' => [
                        'features' => [
                            'feature3' => [
                                'name' => 'feature3',
                                'enabled' => true,
                                'description' => 'Feature 3 description',
                            ],
                        ],
                    ],
                ],
            ],
            'output' => [
                'table' => <<<OUTPUT

manager1
========

+----------+---------+-----------------------+---------------------------+
| Name     | Enabled | Description           |                           |
+----------+---------+-----------------------+---------------------------+
| feature1 | Yes     | Feature 1 description | []                        |
| feature2 | No      | Feature 2 description | {                         |
|          |         |                       |     "foo": "bar",         |
|          |         |                       |     "parray": {           |
|          |         |                       |         "key1": "value1", |
|          |         |                       |         "key2": "value2"  |
|          |         |                       |     }                     |
|          |         |                       | }                         |
+----------+---------+-----------------------+---------------------------+

manager2
========

+----------+---------+-----------------------+----+
| Name     | Enabled | Description           |    |
+----------+---------+-----------------------+----+
| feature3 | Yes     | Feature 3 description | [] |
+----------+---------+-----------------------+----+

OUTPUT,
                'json' => <<<JSON
{
    "manager1": {
        "feature1": {
            "key": "feature1",
            "enabled": true,
            "description": "Feature 1 description",
            "options": []
        },
        "feature2": {
            "key": "feature2",
            "enabled": false,
            "description": "Feature 2 description",
            "options": {
                "foo": "bar",
                "parray": {
                    "key1": "value1",
                    "key2": "value2"
                }
            }
        }
    },
    "manager2": {
        "feature3": {
            "key": "feature3",
            "enabled": true,
            "description": "Feature 3 description",
            "options": []
        }
    }
}

JSON,
                'csv' => <<<CSV
Manager,Name,Enabled,Description,Options
manager1,feature1,1,"Feature 1 description",[]
manager1,feature2,,"Feature 2 description","{
    ""foo"": ""bar"",
    ""parray"": {
        ""key1"": ""value1"",
        ""key2"": ""value2""
    }
}"
manager2,feature3,1,"Feature 3 description",[]

CSV,
            ],
        ],
    ];

    public function testAnErrorOccuredIfInvalidFormatIsProvided(): void
    {
        $commandTester = $this->createCommandTester();
        $commandTester->execute(['--format' => 'invalid']);

        static::assertNotSame(0, $commandTester->getStatusCode());
        static::assertSame(<<<OUTPUT
Invalid format: invalid

OUTPUT, $commandTester->getDisplay());
    }

    #[DataProvider('featuresProvider')]
    public function testConfiguredFeaturesAreDisplayedInAskedFormat(array $features, string $outputFormat, string $expectedOutput): void
    {
        $commandTester = $this->createCommandTester($features);
        $commandTester->execute(['--format' => $outputFormat]);

        static::assertSame(0, $commandTester->getStatusCode());
        static::assertSame($expectedOutput, $commandTester->getDisplay());
    }

    public static function featuresProvider(): iterable
    {
        foreach (self::TEST_DATA as $caseDescription => $testData) {
            foreach ($testData['output'] as $format => $expectedOutput) {
                yield "$caseDescription in $format format" => [$testData['features'], $format, $expectedOutput];
            }
        }
    }

    private function createCommandTester(array $managersDefinition = []): CommandTester
    {
        $managers = [];
        foreach ($managersDefinition as $managerName => $featuresDefinition) {
            $managers[] = new DefaultFeatureManager($managerName, new ArrayStorage($featuresDefinition['options']));
        }

        $command = new ListFeatureCommand(new ChainedFeatureManager($managers));

        return new CommandTester($command);
    }
}
