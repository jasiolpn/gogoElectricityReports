<?php

namespace App\Command;

use App\Entity\AveragePowerReport;
use App\Service\Manager\LogEntryResource;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateReportCommand extends Command
{
    protected static $defaultName = 'app:report:generate';

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var LogEntryResource
     */
    private $logsResource;

    public function __construct(ManagerRegistry $doctrine, LogEntryResource $logsResource)
    {
        $this->doctrine = $doctrine;
        $this->logsResource = $logsResource;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $dateFrom = new \DateTime('now');
        $dateFrom->setTime(0, 0, 0, 0);
        //$dateFrom->modify('-1 day');

        $dateTo = clone $dateFrom;
        $dateTo->modify('-1 millisecond');
        $dateTo->modify('+1 hour');

        for ($i = 0; $i < 24; ++$i)
        {
            $date = clone $dateFrom;

            for ($j = 1; $j <= 20; ++$j)
            {
                $logs = $this->logsResource->getList([
                    'generatorId'   => $j,
                    'dateFrom'      => $dateFrom->format('Y-m-d H:i:s.v'),
                    'dateTo'        => $dateTo->format('Y-m-d H:i:s.v'),
                ]);

                $avrPower = 0;

                if (count($logs) > 0)
                {
                    foreach ($logs as $log)
                        $avrPower += $log->getPower();

                    $avrPower /= count($logs);
                }

                $report = new AveragePowerReport;
                $report->setGeneratorId($j);
                $report->setDate($date);
                $report->setAveragePower($avrPower);

                $this->doctrine->getManager()->persist($report);
            }

            $dateFrom->modify('+1 hour');
            $dateTo->modify('+1 hour');
        }

        $this->doctrine->getManager()->flush();

        $output->writeln($dateFrom->format('Reports generated'));

        return Command::SUCCESS;
    }
}