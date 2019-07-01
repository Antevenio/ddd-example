<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class HelloWorldCommand extends Command
{

    protected function configure()
    {
        $this->setName('hello-world')
            ->setDescription('Say hello')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name', 'World');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $output->writeln("Hello $name!");
    }
}
