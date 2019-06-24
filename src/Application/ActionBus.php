<?php


namespace Antevenio\DddExample\Actions;

interface ActionBus
{

    public function run($request);
}
