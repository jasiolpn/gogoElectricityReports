<?php

namespace App\Command;

use App\Service\Manager\LogEntryResource;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateTablePartitioningCommand extends Command
{
    protected static $defaultName = 'app:update-table-partitioning';

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine, LogEntryResource $logsResource)
    {
        $this->doctrine = $doctrine;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $currentYear = new \DateTime('now');
        $currentYear = $currentYear->format('Y') + 1;
        $prevYear = $currentYear - 1;

        $q = $this->doctrine->getManager()->getConnection()->prepare('
            ALTER TABLE log_entries
            REORGANIZE PARTITION future INTO (
                PARTITION y' . $prevYear . ' VALUES LESS THAN (' . $currentYear . '),
                PARTITION future VALUES LESS THAN MAXVALUE
                );
        ');
        $q->execute();

        return Command::SUCCESS;
    }
}