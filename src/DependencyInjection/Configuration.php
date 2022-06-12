<?php

namespace Swicku\AnonymizationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('anonymization');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode->children()
            ->booleanNode('enabled')->defaultTrue()->end();

        return $treeBuilder;
    }
}
