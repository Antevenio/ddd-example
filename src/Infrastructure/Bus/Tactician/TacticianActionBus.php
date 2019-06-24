<?php


namespace Antevenio\DddExample\Infrastructure\Bus\Tactician;

use Antevenio\DddExample\Actions\ActionBus;
use League\Tactician\CommandBus as Tactician;

class TacticianActionBus implements ActionBus
{

    /**
     * @var Tactician
     */
    private $tactician;

    public function __construct(Tactician $tactician)
    {
        $this->tactician = $tactician;
    }


    public function run($request)
    {
        return $this->tactician->handle($request);
    }
}
