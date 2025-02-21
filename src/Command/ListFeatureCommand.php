<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Command;

use Novaway\Bundle\FeatureFlagBundle\Manager\ChainedFeatureManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class ListFeatureCommand extends Command
{
    private const FORMAT_CSV = 'csv';
    private const FORMAT_JSON = 'json';
    private const FORMAT_TABLE = 'table';

    public function __construct(
        private readonly ChainedFeatureManager $manager,
    ) {
        parent::__construct('novaway:feature-flag:list');
    }

    protected function configure(): void
    {
        $this->setDescription('List all features with their state');
        $this->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Output format', self::FORMAT_TABLE);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $storagesFeatures = [];
        foreach ($this->manager->getManagers() as $manager) {
            $storagesFeatures[$manager->getName()] = [];
            foreach ($manager->all() as $feature) {
                $storagesFeatures[$manager->getName()][$feature->getKey()] = $feature->toArray();
            }
        }

        try {
            match ($input->getOption('format')) {
                self::FORMAT_CSV => $this->renderCsv($output, $storagesFeatures),
                self::FORMAT_JSON => $this->renderJson($output, $storagesFeatures),
                self::FORMAT_TABLE => $this->renderTable(new SymfonyStyle($input, $output), $storagesFeatures),
                /* @phpstan-ignore-next-line */
                default => throw new \InvalidArgumentException("Invalid format: {$input->getOption('format')}"),
            };
        } catch (\Throwable $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function renderTable(SymfonyStyle $output, array $storagesFeatures): void
    {
        if (empty($storagesFeatures)) {
            $output->writeln('<info>No feature declared.</info>');

            return;
        }

        foreach ($storagesFeatures as $storage => $features) {
            $output->title($storage);

            $table = new Table($output);
            $table->setHeaders(['Name', 'Enabled', 'Description']);
            foreach ($features as $feature) {
                $table->addRow([
                    $feature['key'],
                    $feature['enabled'] ? 'Yes' : 'No',
                    $feature['description'],
                    json_encode($feature['options'], JSON_PRETTY_PRINT),
                ]);
            }

            $table->render();
        }
    }

    private function renderJson(OutputInterface $output, array $storagesFeatures): void
    {
        $json = json_encode($storagesFeatures, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);

        $output->writeln($json);
    }

    private function renderCsv(OutputInterface $output, array $storagesFeatures): void
    {
        $output->writeln($this->getCsvLine(['Manager', 'Name', 'Enabled', 'Description', 'Options']));

        foreach ($storagesFeatures as $storage => $features) {
            foreach ($features as $feature) {
                $output->writeln($this->getCsvLine([$storage, ...$feature]));
            }
        }
    }

    private function getCsvLine(array $columns): string
    {
        if (isset($columns['options'])) {
            $columns['options'] = json_encode($columns['options'], JSON_PRETTY_PRINT);
        }

        $fp = fopen('php://temp', 'w+') ?: throw new \RuntimeException('Unable to open temporary file');
        fputcsv($fp, $columns);

        rewind($fp);
        $data = fread($fp, 1_048_576); // 1MB
        if (false === $data) {
            throw new \RuntimeException('Unable to read temporary file');
        }

        fclose($fp);

        return rtrim($data, PHP_EOL);
    }
}
