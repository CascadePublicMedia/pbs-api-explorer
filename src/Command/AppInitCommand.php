<?php

namespace CascadePublicMedia\PbsApiExplorer\Command;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class AppInitCommand
 *
 * @package CascadePublicMedia\PbsApiExplorer\Command
 */
class AppInitCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected static $defaultName = 'app:init';

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setDescription('Initializes the app.')
            ->setHelp('This command creates the database, loads fixtures, 
                and prompts the user for creation of a user account.')
        ;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputNoInteractive = new ArrayInput([]);
        $inputNoInteractive->setInteractive(false);

        $command = $this->getApplication()->find('doctrine:migrations:migrate');
        $command->run($inputNoInteractive, $output);

        $command = $this->getApplication()->find('doctrine:fixtures:load');
        $command->run($inputNoInteractive, $output);

        $command = $this->getApplication()->find('app:user:create');
        $command->run(new ArrayInput([]), $output);

        $io = new ConsoleStyle($input, $output);
        $io->success('App initialized!');
    }

}
