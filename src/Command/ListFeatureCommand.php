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
use Novaway\Bundle\FeatureFlagBundle\Storage\StorageInterface;
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

    /** @var StorageInterface */
    private $storage;

    public function __construct(StorageInterface $storage)
    {
        parent::__construct('novaway:feature-flag:list');

        $this->storage = $storage;
    }

    protected function configure(): void
    {
        $this->setDescription('List all features with their state');
        $this->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'Output format', self::FORMAT_TABLE);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $features = $this->storage->all();

        switch ($input->getOption('format')) {
            case self::FORMAT_CSV:
                $this->renderCsv($output, $features);
                break;

            case self::FORMAT_JSON:
                $this->renderJson($output, $features);
                break;

            case self::FORMAT_TABLE:
                $this->renderTable($output, $features);
                break;

            default:
                /* @phpstan-ignore-next-line */
                $output->writeln("<error>Invalid format: {$input->getOption('format')}</error>");

                return 1;
        }

        return 0;
    }

    private function renderTable(OutputInterface $output, array $features): void
    {
        $table = new Table($output);
        $table->setHeaders(['Name', 'Enabled', 'Description', 'Options']);
        foreach ($features as $feature) {
            $table->addRow([
                $feature->getKey(),
                $feature->isEnabled() ? 'Yes' : 'No',
                $feature->getDescription(),
                json_encode($feature->getOptions(), JSON_PRETTY_PRINT),
            ]);
        }

        $table->render();
    }

    private function renderJson(OutputInterface $output, array $features): void
    {
        $json = json_encode(
            array_map(static function (Feature $feature): array {
                return $feature->toArray();
            }, $features),
            JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
        );

        $output->writeln($json);
    }

    private function renderCsv(OutputInterface $output, array $features): void
    {
        $output->writeln($this->getCsvLine(['Name', 'Enabled', 'Description', 'Options']));

        foreach ($features as $feature) {
            $output->writeln($this->getCsvLine($feature->toArray()));
        }
    }

    private function getCsvLine(array $columns): string
    {
        $fp = fopen('php://temp', 'w+');
        if (false === $fp) {
            throw new \RuntimeException('Unable to open temporary file');
        }

        if (isset($columns['options'])) {
            $columns['options'] = json_encode($columns['options']);
        }
        fputcsv($fp, $columns, ',', '"', '\\');

        rewind($fp);
        $data = fread($fp, 1048576); // 1MB
        if (false === $data) {
            throw new \RuntimeException('Unable to read temporary file');
        }

        fclose($fp);

        return rtrim($data, PHP_EOL);
    }
}
