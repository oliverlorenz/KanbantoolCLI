<?php
/**
 * @author Oliver Lorenz <oliver.lorenz@project-collins.com>
 * @since 2014-10-18
 */

namespace command;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use system\Configuration;
use \Curl\Curl;

class TaskMove extends Command
{
    /**
     * @var Configuration
     */
    protected $config;

    protected $input;
    protected $output;

    protected function configure()
    {
        $this
            ->setName('task:move')
            ->setDescription('move task')
            ->addArgument(
                'taskId',
                InputArgument::REQUIRED,
                'id of task'
            )
            ->addArgument(
                'columnId',
                InputArgument::REQUIRED,
                'workflow_stage_id'
            )
        ;
    }

    protected function getRootDirectory()
    {
        return realpath(dirname(__FILE__) . '/../..');
    }

    protected function getConfigPaths()
    {
        return array(
            $this->getRootDirectory() . '/src/config',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        $fileName = 'main';
        $config = new Configuration(
            $this->getConfigPaths(),
            $fileName
        );

        $columnId       = $input->getArgument('columnId');
        $taskId         = $input->getArgument('taskId');
        $domain         = $config->getValue('domain');
        $boardId        = $config->getValue('boardId');
        $apiToken       = $config->getValue('apiToken');

        $url = $this->getUrl($domain, $boardId, $apiToken, $taskId);

        $curl = new Curl();
        $curl->put(
            $url,
            array(
                'workflow_stage_id' => $columnId,
            )
        );
    }

    protected function getUrl($domain, $boardId, $apiToken, $taskId)
    {
        return 'https://' . $domain . '.kanbantool.com/api/v1/boards/' . $boardId . '/tasks/' . $taskId . '/move.xml?api_token=' . $apiToken;
    }
}