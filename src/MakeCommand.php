<?php

namespace Laravel\Sweethome;

use Symfony\Component\Process\Process;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakeCommand extends Command
{
    /**
     * The base path of the Laravel installation.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The name of the project folder.
     *
     * @var string
     */
    protected $projectName;

    /**
     * Sluggified Project Name.
     *
     * @var string
     */
    protected $defaultName;

    /**
     * Configure the command options.
     *
     * @return void
     */
    protected function configure()
    {
        $this->basePath = getcwd();
        $this->projectName = basename(getcwd());
        $this->defaultName = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $this->projectName)));

        $this
            ->setName('make')
            ->setDescription('Install Sweethome into the current project')
            ->addOption('name', null, InputOption::VALUE_OPTIONAL, 'The name the virtual machine.', $this->defaultName)
            ->addOption('hostname', null, InputOption::VALUE_OPTIONAL, 'The hostname the virtual machine.', $this->defaultName)
            ->addOption('after', null, InputOption::VALUE_NONE, 'Determines if the after.sh file is created.')
            ->addOption('aliases', null, InputOption::VALUE_NONE, 'Determines if the aliases file is created.');
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
        copy(__DIR__.'/stubs/LocalizedVagrantfile', $this->basePath.'/Vagrantfile');

        if (!file_exists($this->basePath.'/Sweethome.yaml')) {
            copy( __DIR__ . '/stubs/Sweethome.yaml', $this->basePath . '/Sweethome.yaml' );
        }

        if ($input->getOption('after')) {
            if (!file_exists($this->basePath.'/after.sh')) {
                copy( __DIR__ . '/stubs/after.sh', $this->basePath . '/after.sh' );
            }
        }

        if ($input->getOption('aliases')) {
            if (!file_exists($this->basePath.'/aliases')) {
                copy( __DIR__ . '/stubs/aliases', $this->basePath . '/aliases' );
            }
        }

        if ($input->getOption('name')) {
            $this->updateName($input->getOption('name'));
        }

        if ($input->getOption('hostname')) {
            $this->updateHostName($input->getOption('hostname'));
        }

        $this->configurePaths();

        $output->writeln('Sweethome Installed!');
    }

    /**
     * Update paths in Sweethome.yaml
     */
    protected function configurePaths()
    {
        $yaml = str_replace(
            "- map: ~/Code", "- map: \"".str_replace('\\', '/', $this->basePath)."\"", $this->getSweethomeFile()
        );

        $yaml = str_replace(
            "to: /home/vagrant/Code", "to: \"/home/vagrant/".$this->defaultName."\"", $yaml
        );

        // Fix path to the public folder (sites: to:)
        $yaml = str_replace(
            $this->defaultName."\"/Laravel/public", $this->defaultName."/public\"", $yaml
        );

        file_put_contents($this->basePath.'/Sweethome.yaml', $yaml);
    }

    /**
     * Update the "name" variable of the Sweethome.yaml file.
     *
     * VirtualBox requires a unique name for each virtual machine.
     *
     * @param  string  $name
     * @return void
     */
    protected function updateName($name)
    {
        file_put_contents($this->basePath.'/Sweethome.yaml', str_replace(
            "cpus: 1", "cpus: 1".PHP_EOL."name: ".$name, $this->getSweethomeFile()
        ));
    }

    /**
     * Set the virtual machine's hostname setting in the Sweethome.yaml file.
     *
     * @param  string  $hostname
     * @return void
     */
    protected function updateHostName($hostname)
    {
        file_put_contents($this->basePath.'/Sweethome.yaml', str_replace(
            "cpus: 1", "cpus: 1".PHP_EOL."hostname: ".$hostname, $this->getSweethomeFile()
        ));
    }

    /**
     * Get the contents of the Sweethome.yaml file.
     *
     * @return string
     */
    protected function getSweethomeFile()
    {
        return file_get_contents($this->basePath.'/Sweethome.yaml');
    }
}
