<?php

namespace PHPDish\Bundle\CoreBundle\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class InstallCommand extends AbstractInstallCommand
{
    /**
     * @var array
     */
    protected $commands = [
        [
            'command' => 'check-requirements',
            'message' => 'Checking system requirements.',
        ],
        [
            'command' => 'phpdish:install:database',
            'message' => 'Setting up the database.',
        ],
        [
            'command' => 'phpdish:create:admin',
            'message' => 'Creats the super admin acount.',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('phpdish:install')
            ->setDescription('Install PHPDish in your machine');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $outputStyle = new SymfonyStyle($input, $output);
        $outputStyle->writeln('<info>Installing PHPDish...</info>');
        $outputStyle->writeln(static::PHPDISH_LOGO);

        $this->ensureDirectoryWritableAndExists($this->getContainer()->getParameter('kernel.cache_dir'), $output);

        foreach ($this->commands as $index => $command) {
            $outputStyle->writeln("<info>Step {$index}:</info>");
            $command = $this->getApplication()->get($command['command']);
            $command->run($input, $output);
        }

        $output->writeln('PHPDish has been successfully installed.');
    }
}
