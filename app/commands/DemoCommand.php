<?php

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DemoCommand extends Command
{

    protected $name = "demo:demo";

    protected $description = "This is a demo command.";

    public function fire()
    {
        $arguments = $this->argument();

        $demoOption = $this->option('demo_option');

        print_r($arguments);

        print_r($demoOption);
    }

    protected function getArguments()
    {
        return array(
            array('demo_argument', InputArgument::OPTIONAL, 'This is an argument.'),
        );
    }

    protected function getOptions()
    {
        return array(
            array('demo_option', 'o', InputOption::VALUE_REQUIRED, 'This is an option.', 'default_value'),
        );
    }
}
