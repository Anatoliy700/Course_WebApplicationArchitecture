<?php

namespace Console\User;


use Model\Repository\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GetUserCommand extends Command
{
    protected static $defaultName = 'user:get-user';

    protected function configure()
    {
        $this
            ->setName('user:get-user')
            ->setDescription('Получить пользователя по логину или id')
            ->setDefinition(
                new InputDefinition([
                    new InputOption(
                        'login',
                        'l',
                        InputOption::VALUE_OPTIONAL,
                        'Возвращает пользователя по логину'
                    ),
                    new InputOption(
                        'id',
                        'i',
                        InputOption::VALUE_OPTIONAL,
                        'Возвращает пользователя по id'
                    )
                ])
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            if ($input->getOption('login')) {
                $user = (new User())->getByLogin($input->getOption('login'));
            } elseif ($input->getOption('id')) {
                $user = (new User())->getById($input->getOption('id'));

            } else {
                $output->writeln("Передайте необходимые аргументы!");
                return;
            }
            if (!$user) {
                throw new \InvalidArgumentException('Пользователь не найден');
            }
            $output->writeln($user->getName());
        } catch (\InvalidArgumentException $e) {
            $output->writeln($e->getMessage());
        }
    }
}