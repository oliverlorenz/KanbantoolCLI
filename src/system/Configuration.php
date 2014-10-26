<?php

namespace system;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration
{
    /* @var string[] $settings */
    private $settings;

    /**
     * @param string[] $configPaths a list of directories where to look for config files
     * @param string   $filename    the config file to load
     */
    public function __construct($configPaths, $filename)
    {
        $this->settings = new Settings($configPaths, $filename, $this->buildConfigTree());
        $this->settings->load();
    }

    /**
     * @param $name
     * @return string
     */
    public function getValue($name)
    {
        return $this->settings[$name];
    }

    /**
     * @return \Symfony\Component\Config\Definition\NodeInterface
     */
    protected function buildConfigTree()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('allSettings');
        $rootNode
            ->children()
                ->scalarNode('domain')->isRequired()->end()
                ->scalarNode('apiToken')->isRequired()->end()
                ->scalarNode('boardId')->isRequired()->end()
            ->end();
        return $treeBuilder->buildTree();
    }
}