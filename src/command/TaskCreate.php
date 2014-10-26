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

class TaskCreate extends Command
{
    protected $config;
    protected $input;
    protected $output;

    protected function configure()
    {
        $this
            ->setName('task:create')
            ->setDescription('create task')
            ->addArgument(
                'name',
                InputArgument::REQUIRED,
                'name of the task'
            )
            ->addArgument(
                'description',
                InputArgument::OPTIONAL,
                'description of task'
            )
        ;
    }

    protected function getConfigPaths()
    {
        return array(
            getcwd() . '/src/config',
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

        $name           = $input->getArgument('name');
        $description    = $input->getArgument('description');
        $domain         = $config->getValue('domain');
        $boardId        = $config->getValue('boardId');
        $apiToken       = $config->getValue('apiToken');

        $url = $this->getUrl($domain, $boardId, $apiToken);

        $curl = new Curl();
        $curl->post(
            $url,
            array(
                'task[name]' => $name,
                'task[description]' => $description
            )
        );
    }

    protected function getUrl($domain, $boardId, $apiToken)
    {
        return 'https://' . $domain . '.kanbantool.com/api/v1/boards/' . $boardId . '/tasks.xml?api_token=' . $apiToken;
    }
}