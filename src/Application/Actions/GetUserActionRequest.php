<?php


namespace Antevenio\DddExample\Application\Actions;

class GetUserActionRequest
{
    private $id;

    /**
     * GetUserActionRequest constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
}
