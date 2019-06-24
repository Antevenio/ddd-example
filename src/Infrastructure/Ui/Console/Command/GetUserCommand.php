<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Actions\ActionBus;
use Antevenio\DddExample\Application\Actions\GetUserActionRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class GetUserCommand extends Command
{

    /**
     * @var ActionBus
     */
    private $actionBus;

    /**
     * GetUserCommand constructor.
     * @param ActionBus $actionBus
     */
    public function __construct(ActionBus $actionBus)
    {
        $this->actionBus = $actionBus;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('get-user')
            ->setDescription('Get User by id')
            ->addArgument('id', InputArgument::REQUIRED, 'User Id');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $id = $input->getArgument('id');
        $getUserActionRequest = new GetUserActionRequest($id);
        $user = $this->actionBus->run($getUserActionRequest);
        $output->writeln(json_encode($user));
    }
}
