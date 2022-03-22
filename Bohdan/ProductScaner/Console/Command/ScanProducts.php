<?php

declare(strict_types=1);

namespace Bohdan\ProductScaner\Console\Command;

use Bohdan\ProductScaner\Api\Terminal;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * class ScanProducts
 */
class ScanProducts extends Command
{

    const PRODUCTS = 'products';

    /**
     * @var Terminal $terminal
     */
    private Terminal $terminal;

    /**
     * @var State $state
     */
    private State $state;

    /**
     * @param State $state
     * @param Terminal $terminal
     * @param string|null $name
     */
    public function __construct(State $state, Terminal $terminal, string $name = null)
    {
        parent::__construct($name);
        $this->terminal = $terminal;
        $this->state = $state;
    }

    protected function configure()
    {
        $options = [
            new InputOption(
                self::PRODUCTS,
                null,
                InputOption::VALUE_REQUIRED,
                'Product List'
            )
        ];

        $this->setName('products:scan')
            ->setDescription('Scanning products prices')
            ->setDefinition($options);

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return $this
     * @throws LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): self
    {
        $this->state->setAreaCode(Area::AREA_FRONTEND);

        if ($products = $input->getOption(self::PRODUCTS)) {
            $productList = array_count_values(str_split($products));

            foreach ($productList as $sku => $count) {
                $this->terminal->scan($sku, $count);
            }

            $output->writeln($this->terminal->getTotal());

        } else {
            $output->writeln("Please enter the list of products to scan");
        }

        return $this;
    }
}
