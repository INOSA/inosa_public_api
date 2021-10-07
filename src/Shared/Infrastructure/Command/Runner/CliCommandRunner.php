<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Command\Runner;

use Exception;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\HttpKernel\KernelInterface;

final class CliCommandRunner
{
    private const SUCCESSFUL_COMMAND_RETURN_CODE = 0;

    public function __construct(private KernelInterface $kernel)
    {
    }

    /**
     * @throws CommandRunnerException
     */
    public function run(ArrayInput $input): void
    {
        $application = new Application($this->kernel);
        $application->setAutoExit(false);

        try {
            if (self::SUCCESSFUL_COMMAND_RETURN_CODE !== $application->run($input, new NullOutput())) {
                throw new CommandRunnerException('Command run has failed.');
            }
        } catch (Exception $e) {
            throw new CommandRunnerException($e->getMessage());
        }
    }
}
