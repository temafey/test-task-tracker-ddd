<?php

declare(strict_types=1);

namespace Micro\Tracker\Task\Presentation\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouterInterface;

class EmulateHttpRequestCommand extends Command
{
    private KernelInterface $kernel;
    private RouterInterface $router;

    public function __construct(KernelInterface $kernel, RouterInterface $router)
    {
        parent::__construct();
        $this->kernel = $kernel;
        $this->router = $router;
    }

    protected function configure()
    {
        $this
            ->setName('app:emulate-request')
            ->setDescription('Эмулирует HTTP запрос внутри Symfony')
            ->addArgument('method', InputArgument::REQUIRED, 'HTTP метод (GET, POST, PUT, etc.)')
            ->addArgument('uri', InputArgument::REQUIRED, 'URI запроса')
            ->addArgument('headers', InputArgument::OPTIONAL, 'Заголовки запроса в формате JSON')
            ->addArgument('content', InputArgument::OPTIONAL, 'Тело запроса');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $method = $input->getArgument('method');
        $uri = $input->getArgument('uri');
        $headersJson = $input->getArgument('headers');
        $content = $input->getArgument('content');

        $headers = $headersJson ? json_decode($headersJson, true, 512, JSON_THROW_ON_ERROR) : [];

        // Создаем Request объект напрямую
        $request = Request::create(
            $uri,
            $method,
            [],  // GET params
            [],  // POST params
            [],  // FILES
            [],  // SERVER
            $content
        );

        // Устанавливаем заголовки
        foreach ($headers as $name => $value) {
            $request->headers->set($name, $value);
        }

        // Выполняем запрос через Kernel
        $response = $this->kernel->handle($request);

        // Выводим информацию о результате
        $output->writeln('<info>Статус ответа: ' . $response->getStatusCode() . '</info>');
        $output->writeln('<info>Заголовки ответа:</info>');

        foreach ($response->headers->all() as $name => $values) {
            $output->writeln('  ' . $name . ': ' . implode(', ', $values));
        }

        $output->writeln("\n<info>Тело ответа:</info>");
        $output->writeln($response->getContent());

        return Command::SUCCESS;
    }
}