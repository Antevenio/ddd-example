<?php


namespace Antevenio\DddExample\Application\Actions;

class GetUserActionRequest
{
    /**
     * @var string
     */
    private $id;

    /**
     * GetUserActionRequest constructor.
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
