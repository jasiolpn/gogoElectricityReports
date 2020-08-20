<?php

namespace App\Command;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class InstallCommand extends Command
{
    protected static $defaultName = 'app:install';

    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    public function __construct(ParameterBagInterface $params, ManagerRegistry $doctrine)
    {
        $this->params = $params;
        $this->doctrine = $doctrine;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $createDbCommand = $this->getApplication()->find('doctrine:database:create');

        $code = $createDbCommand->run(new ArrayInput([]), $output);

        if ($code != 0)
        {
            $output->writeln('Install failed.');

            return Command::FAILURE;
        }

        $createSchemaCommand = $this->getApplication()->find('doctrine:schema:update');
        $arrayInput = new ArrayInput([
            '--force' => true
        ]);

        $code = $createSchemaCommand->run($arrayInput, $output);

        if ($code != 0)
        {
            $output->writeln('Install failed.');

            return Command::FAILURE;
        }

        $currentYear = new \DateTime('now');
        $currentYear = $currentYear->format('Y');

        $q = $this->doctrine->getManager()->getConnection()->prepare('
            ALTER TABLE log_entries
            PARTITION BY RANGE (YEAR(measurement_time)) PARTITIONS 2
            (
                PARTITION old VALUES LESS THAN (' . $currentYear . '), 
                PARTITION future VALUES LESS THAN MAXVALUE
            )
        ');
        $q->execute();

        $appDir = $this->params->get('kernel.project_dir');
        $varDir = $appDir . '/var';
        $binDir = $appDir . '/bin';

        $cronjobs = $varDir . '/cronjobs';

        shell_exec(sprintf('crontab -l >> %s', $cronjobs));
        shell_exec(sprintf('echo "* 1 * * * /bin/php %s/console app:report:generate" >> %s', $binDir, $cronjobs));
        shell_exec(sprintf('echo "0 0 1 1 * /bin/php %s/console app:update-table-partitioning" >> %s', $binDir, $cronjobs));
        shell_exec(sprintf('crontab %s', $cronjobs));
        shell_exec(sprintf('rm %s', $cronjobs));

        return Command::SUCCESS;
    }
}