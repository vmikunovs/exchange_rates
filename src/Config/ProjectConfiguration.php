<?php


namespace App\Config;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ProjectConfiguration implements ConfigurationInterface
{

    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
       /* $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('project_configuration');
        $rootNode
            ->children()
                ->scalarNode('selected')
                    ->isRequired()
                ->end()
                ->arrayNode('exchanges')
                    ->scalarPrototype()
                ->end()
            ->end();


        return $treeBuilder;*/
    }
}