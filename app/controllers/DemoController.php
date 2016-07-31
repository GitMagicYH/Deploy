<?php

class DemoController
{
    public function indexAction()
    {
        $demo = new Demo();

        $welcome = $demo->welcome();

        return View::make("home.index");
    }
}
