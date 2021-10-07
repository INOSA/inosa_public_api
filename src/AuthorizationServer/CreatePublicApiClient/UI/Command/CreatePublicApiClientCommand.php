<?php

declare(strict_types=1);

namespace App\AuthorizationServer\CreatePublicApiClient\UI\Command;

use App\AuthorizationServer\CreatePublicApiClient\Application\Command\CreatePublicApiClientCommand as CreatePublicApiClientCommandApplication;
use App\AuthorizationServer\CreatePublicApiClient\Application\Query\FindPublicApiClientQueryInterface;
use App\Shared\Application\MessageBus\MessageBusInterface;
use App\Shared\Domain\Identifier\IdentifierFactoryInterface;
use App\Shared\Domain\Identifier\InosaSiteIdentifier;
use RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreatePublicApiClientCommand extends Command
{
    private const ARGUMENT_SITE_ID = 'siteId';

    public function __construct(
        private IdentifierFactoryInterface $identifierFactory,
        private MessageBusInterface $messageBus,
        private FindPublicApiClientQueryInterface $clientQuery,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('public-api:client:create')
            ->addArgument(self::ARGUMENT_SITE_ID, InputArgument::REQUIRED)
            ->setHelp('This command generates public api client application');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inosaSiteIdentifier = $this->getInosaSiteIdentifier($input);

        $this->messageBus->dispatch(new CreatePublicApiClientCommandApplication($inosaSiteIdentifier));

        $clientView = $this->clientQuery->findByInosaSiteId($inosaSiteIdentifier);

        if (null === $clientView) {
            return Command::FAILURE;
        }

        $table = new Table($output);
        $table->setHeaders(['client_id', 'secret'])
            ->setRows(
                [
                    [
                        $clientView->getClientId(), $clientView->getClientSecret()
                    ]
                ]
            );
        $table->render();

        return Command::SUCCESS;
    }

    /**
     * @throws RuntimeException
     */
    private function getInosaSiteIdentifier(InputInterface $input): InosaSiteIdentifier
    {
        $siteId = $input->getArgument(self::ARGUMENT_SITE_ID);

        if (!is_string($siteId)) {
            throw new RuntimeException('Invalid siteId argument provided, Inosa site id expected.');
        }

        return InosaSiteIdentifier::fromIdentifier($this->identifierFactory->fromString($siteId));
    }
}
