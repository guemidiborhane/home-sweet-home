<?php namespace Sweethome;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ReloadCommand extends Command
{
    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('reload')
            ->setDescription('Re-start the Sweethome machine')
            ->addOption('provision', null, InputOption::VALUE_NONE, 'Run the provisioners on the box.');
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
        // Halt the Sweethome Machine
        (new HaltCommand())->execute($input, $output);
        // Start the Sweethome Machine
        (new UpCommand())->execute($input, $output);
    }
}
