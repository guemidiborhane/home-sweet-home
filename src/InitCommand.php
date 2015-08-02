<?php namespace Laravel\Sweethome;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('init')
                  ->setDescription('Create a stub Sweethome.yaml file');
    }

    /**
     * Execute the command.
     *
     * @param  \Symfony\Component\Console\Input\InputInterface  $input
     * @param  \Symfony\Component\Console\Output\OutputInterface  $output
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        if (is_dir(homestead_path())) {
            throw new \InvalidArgumentException("Sweethome has already been initialized.");
        }

        mkdir(homestead_path());

        copy(__DIR__.'/stubs/Sweethome.yaml', homestead_path().'/Sweethome.yaml');
        copy(__DIR__.'/stubs/after.sh', homestead_path().'/after.sh');
        copy(__DIR__.'/stubs/aliases', homestead_path().'/aliases');

        $output->writeln('<comment>Creating Sweethome.yaml file...</comment> <info>✔</info>');
        $output->writeln('<comment>Sweethome.yaml file created at:</comment> '.homestead_path().'/Sweethome.yaml');
    }
}
