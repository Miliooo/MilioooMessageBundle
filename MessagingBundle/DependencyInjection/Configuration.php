<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This class contains the configuration information for the bundle.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('miliooo_messaging');

        $rootNode
            ->children()
                ->scalarNode('thread_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('thread_meta_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('message_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('message_meta_class')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('participant_provider')->defaultValue('Miliooo\Messaging\User\ParticipantProviderSecurityToken')->cannotBeEmpty()->end()
            ->end();

        return $treeBuilder;
    }
}
