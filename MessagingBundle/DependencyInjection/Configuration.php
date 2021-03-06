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
            ->scalarNode('username_object_transformer')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('participant_provider')->defaultValue('miliooo_messaging.participant_provider.default')->cannotBeEmpty()->end()
            ->arrayNode('new_thread_form')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('factory')->defaultValue('miliooo_messaging.new_thread_form.factory.default')->cannotBeEmpty()->end()
                    ->scalarNode('model')->defaultValue('Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient')->cannotBeEmpty()->end()
                    ->scalarNode('type')->defaultValue('miliooo_messaging.new_thread_form.type.default')->cannotBeEmpty()->end()
                ->end()
            ->end()
            ->arrayNode('specification')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('can_message_recipient')->defaultValue('miliooo_messaging.specification.can_message_recipient.default')->cannotBeEmpty()->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
