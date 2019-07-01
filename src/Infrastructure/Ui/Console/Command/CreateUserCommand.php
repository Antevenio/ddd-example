<?php


namespace Antevenio\DddExample\Infrastructure\Ui\Console\Command;

use Antevenio\DddExample\Application\ActionBus;
use Antevenio\DddExample\Application\Actions\CreateUserActionRequest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class CreateUserCommand extends Command
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
        $this->setName('create-user')
            ->setDescription('Create a User')
            ->addArgument('email', InputArgument::REQUIRED, 'User email');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $email = $input->getArgument('email');
        $createUserActionRequest = new CreateUserActionRequest($email);
        $user = $this->actionBus->run($createUserActionRequest);
        $output->writeln(json_encode($user));
    }
}
