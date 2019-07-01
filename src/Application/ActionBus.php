<?php


namespace Antevenio\DddExample\Application;

interface ActionBus
{

    public function run($request);
}
