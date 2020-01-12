<?php


namespace App\Command;


use App\Services\ExchangeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExchangeCommand extends Command {
    protected static $defaultName = 'app:exchange-rates-update';

    /**
     * @var ExchangeService
     */
    private $exchangeService;

    public function __construct(ExchangeService $exchangeService) {
        $this->exchangeService = $exchangeService;

        parent::__construct();
    }


    protected function configure() {
        $this
            // the short description shown while running "php bin/console list"
            ->setDescription('Creates a new user.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to create a user...');
    }

    protected function execute(InputInterface $input, OutputInterface $output) {
        // outputs multiple lines to the console (adding "\n" at the end of each line)

        $start = time();

        $this->exchangeService->updateRates();

        $updateTime = time() - $start;
        $output->writeln(['data of exchangers was updated in '.$updateTime. 'ms']);
    }
}