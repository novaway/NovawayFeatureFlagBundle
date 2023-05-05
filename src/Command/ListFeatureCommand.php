<?php

declare(strict_types=1);

/*
 * This file is part of the NovawayFeatureFlagBundle package.
 * (c) Novaway <https://github.com/novaway/NovawayFeatureFlagBundle>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Novaway\Bundle\FeatureFlagBundle\Command;

use Novaway\Bundle\FeatureFlagBundle\Model\Feature;
use Novaway\Bundle\FeatureFlagBundle\Storage\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ListFeatureCommand extends Command
{
    private const FORMAT_CSV = 'csv';
    private const FORMAT_JSON = 'json';
    private const FORMAT_TABLE = 'table';

    public function __construct(
        private readonly Storage $storage,
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
        $features = $this->storage->all();

        try {
            match ($input->getOption('format')) {
                self::FORMAT_CSV => $this->renderCsv($output, $features),
                self::FORMAT_JSON => $this->renderJson($output, $features),
                self::FORMAT_TABLE => $this->renderTable($output, $features),
                /* @phpstan-ignore-next-line */
                default => throw new \InvalidArgumentException("Invalid format: {$input->getOption('format')}"),
            };
        } catch (\Throwable $e) {
            $output->writeln("<error>{$e->getMessage()}</error>");

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function renderTable(OutputInterface $output, array $features): void
    {
        $table = new Table($output);
        $table->setHeaders(['Name', 'Enabled', 'Description']);
        foreach ($features as $feature) {
            $table->addRow([
                $feature->getKey(),
                $feature->isEnabled() ? 'Yes' : 'No',
                $feature->getDescription(),
            ]);
        }

        $table->render();
    }

    private function renderJson(OutputInterface $output, array $features): void
    {
        $json = json_encode(
            array_map(static fn (Feature $feature): array => $feature->toArray(), $features),
            JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR,
        );

        $output->writeln($json);
    }

    private function renderCsv(OutputInterface $output, array $features): void
    {
        $output->writeln($this->getCsvLine(['Name', 'Enabled', 'Description']));

        foreach ($features as $feature) {
            $output->writeln($this->getCsvLine($feature->toArray()));
        }
    }

    private function getCsvLine(array $columns): string
    {
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
