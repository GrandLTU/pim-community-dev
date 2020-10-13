<?php

declare(strict_types=1);

namespace AkeneoTest\Platform\Integration\ImportExport\Repository\InternalApi;

use Akeneo\Platform\Bundle\ImportExportBundle\Model\JobExecutionTracking;
use Akeneo\Platform\Bundle\ImportExportBundle\Model\StepExecutionTracking;
use Akeneo\Platform\Bundle\ImportExportBundle\Query\GetJobExecutionTracking;
use Akeneo\Platform\Bundle\ImportExportBundle\Repository\InternalApi\JobInstanceRepository;
use Akeneo\Test\Integration\Configuration;
use Akeneo\Test\Integration\TestCase;
use Akeneo\Tool\Component\Batch\Job\JobRepositoryInterface;
use Doctrine\DBAL\Connection;

class GetJobExecutionTrackingIntegration extends TestCase
{
    /** @var Connection */
    private $sqlConnection;

    /** @var GetJobExecutionTracking */
    private $getJobExecutionTracking;

    public function setUp(): void
    {
        parent::setUp();

        $this->sqlConnection = $this->get('database_connection');
        $this->getJobExecutionTracking = $this->get('pim_import_export.query.get_job_execution_tracking');
    }
    public function testItFetchesTheJobExecutionTrackingForAJobExecutionInProgress(): void
    {
        $jobExecutionId = $this->thereIsAJobInProgress();

        $jobExecutionTracking = $this->getJobExecutionTracking->execute($jobExecutionId);

        $expectedJobExecutionTracking = $this->expectedJobExecutionTrackingInProgress();

        self::assertEquals($expectedJobExecutionTracking, $jobExecutionTracking);
    }

    // Terminé avec warning et erreurs
    // Check Status
    //


    /**
     * {@inheritdoc}
     */
    protected function getConfiguration(): Configuration
    {
        return $this->catalog->useTechnicalCatalog();
    }

    private function thereIsAJobInProgress(): int
    {
        $JobInstanceId = $this->sqlConnection->executeQuery('SELECT id FROM akeneo_batch_job_instance WHERE code = "csv_product_import";')->fetchColumn();
        $insertJobExecution = <<<SQL
INSERT INTO `akeneo_batch_job_execution` (`job_instance_id`, `pid`, `user`, `status`, `start_time`, `end_time`, `create_time`, `updated_time`, `health_check_time`, `exit_code`, `exit_description`, `failure_exceptions`, `log_file`, `raw_parameters`)
VALUES
	(:job_instance_id, 86472, 'admin', 3, '2020-10-13 13:05:49', '2020-10-13 13:05:49', '2020-10-13 13:05:45', '2020-10-13 13:05:48', '2020-10-13 13:05:48', 'COMPLETED', '', 'a:0:{}', '/Users/samir/Workspace/akeneo/e40/vendor/akeneo/pim-community-dev/var/logs/batch/21/batch_020ba21ecf89d800381052b4c0334c5afc04daa6.log', '{}');
SQL;
        $this->sqlConnection->executeUpdate($insertJobExecution, ['job_instance_id' => $JobInstanceId]);
        $jobExecutionId = $this->sqlConnection->lastInsertId();

        $insertStepExecutions = <<<SQL
INSERT INTO `akeneo_batch_step_execution` (`job_execution_id`, `step_name`, `status`, `read_count`, `write_count`, `filter_count`, `start_time`, `end_time`, `exit_code`, `exit_description`, `terminate_only`, `failure_exceptions`, `errors`, `summary`)
VALUES
	(:job_execution_id, 'validation', 1, 0, 0, 0, '2020-10-13 13:05:50', '2020-10-13 13:05:55', 'COMPLETED', '', 0, 'a:0:{}', 'a:0:{}', 'a:1:{s:23:\"charset_validator.title\";s:8:\"UTF-8 OK\";}'),
	(:job_execution_id, 'import', 3, 0, 0, 0, '2020-10-13 13:05:55', null, 'STARTED', '', 0, 'a:0:{}', 'a:0:{}', 'a:3:{s:13:\"item_position\";i:38;s:23:\"product_skipped_no_diff\";i:37;s:4:\"skip\";i:1;}')
	;
SQL;
        $this->sqlConnection->executeUpdate($insertStepExecutions, ['job_execution_id' => $jobExecutionId]);
        $stepExecutionId = $this->sqlConnection->lastInsertId();

        $insertWarnings = <<<SQL
INSERT INTO `akeneo_batch_warning` (`step_execution_id`, `reason`, `reason_parameters`, `item`)
VALUES
	(:step_execution_id, 'Property \"variation_image\" expects a valid pathname as data, \"/var/folders/jm/d58y_3x52v9dz79knt487byh0000gp/T/akeneo_batch_5f85a62d0a7c5//files/Tshirt-unique-size-blue/variation_image/unique-size.jpg\" given.', 'a:0:{}', 'a:7:{s:10:\"categories\";a:1:{i:0;s:7:\"tshirts\";}s:7:\"enabled\";b:1;s:6:\"family\";s:8:\"clothing\";s:6:\"parent\";s:24:\"model-tshirt-unique-size\";s:6:\"groups\";a:0:{}s:6:\"values\";a:6:{s:3:\"sku\";a:1:{i:0;a:3:{s:6:\"locale\";N;s:5:\"scope\";N;s:4:\"data\";s:23:\"Tshirt-unique-size-blue\";}}s:5:\"color\";a:1:{i:0;a:3:{s:6:\"locale\";N;s:5:\"scope\";N;s:4:\"data\";s:4:\"blue\";}}s:11:\"composition\";a:1:{i:0;a:3:{s:6:\"locale\";N;s:5:\"scope\";N;s:4:\"data\";N;}}s:3:\"ean\";a:1:{i:0;a:3:{s:6:\"locale\";N;s:5:\"scope\";N;s:4:\"data\";s:13:\"1234567890350\";}}s:15:\"variation_image\";a:1:{i:0;a:3:{s:6:\"locale\";N;s:5:\"scope\";N;s:4:\"data\";s:138:\"/var/folders/jm/d58y_3x52v9dz79knt487byh0000gp/T/akeneo_batch_5f85a62d0a7c5//files/Tshirt-unique-size-blue/variation_image/unique-size.jpg\";}}s:14:\"variation_name\";a:3:{i:0;a:3:{s:6:\"locale\";s:5:\"de_DE\";s:5:\"scope\";N;s:4:\"data\";N;}i:1;a:3:{s:6:\"locale\";s:5:\"en_US\";s:5:\"scope\";N;s:4:\"data\";s:24:\"T-shirt unique size blue\";}i:2;a:3:{s:6:\"locale\";s:5:\"fr_FR\";s:5:\"scope\";N;s:4:\"data\";N;}}}s:10:\"identifier\";s:23:\"Tshirt-unique-size-blue\";}')
	;
SQL;
        $this->sqlConnection->executeUpdate($insertWarnings, ['step_execution_id' => $stepExecutionId]);

        return $jobExecutionId;
    }

    private function expectedJobExecutionTrackingInProgress(): JobExecutionTracking
    {
        $expectedJobExecutionTracking = new JobExecutionTracking();
        $expectedJobExecutionTracking->status = 'IN PROGRESS';
        $expectedJobExecutionTracking->currentStep = 2;
        $expectedJobExecutionTracking->totalSteps = 3;

        $expectedStepExecutionTracking1 = new StepExecutionTracking();
        $expectedStepExecutionTracking1->isTrackable = false;
        $expectedStepExecutionTracking1->name = 'validation';
        $expectedStepExecutionTracking1->status = 'COMPLETED';
        $expectedStepExecutionTracking1->duration = 5;
        $expectedStepExecutionTracking1->hasError = false;
        $expectedStepExecutionTracking1->hasWarning = true;
        $expectedStepExecutionTracking1->processedItems = 0;
        $expectedStepExecutionTracking1->totalItems = 0;

        $expectedStepExecutionTracking2 = new StepExecutionTracking();
        $expectedStepExecutionTracking2->isTrackable = true;
        $expectedStepExecutionTracking2->name = 'import';
        $expectedStepExecutionTracking2->status = 'IN PROGRESS';
//        $expectedStepExecutionTracking2->duration = 0;
        $expectedStepExecutionTracking2->hasError = false;
        $expectedStepExecutionTracking2->hasWarning = false;
        $expectedStepExecutionTracking2->processedItems = 10;
        $expectedStepExecutionTracking2->totalItems = 100;

        $expectedStepExecutionTracking3 = new StepExecutionTracking();
        $expectedStepExecutionTracking3->isTrackable = true;
        $expectedStepExecutionTracking3->name = 'import_associations';
        $expectedStepExecutionTracking3->status = 'NOT STARTED';
        $expectedStepExecutionTracking2->duration = 0;
        $expectedStepExecutionTracking3->hasError = false;
        $expectedStepExecutionTracking3->hasWarning = false;
        $expectedStepExecutionTracking3->processedItems = 0;
        $expectedStepExecutionTracking3->totalItems = 0;

        $expectedJobExecutionTracking->steps = [
            $expectedStepExecutionTracking1,
            $expectedStepExecutionTracking2
        ];

        return $expectedJobExecutionTracking;
    }
}
