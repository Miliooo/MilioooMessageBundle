<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MilioooMessagingExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('builders.xml');
        $loader->load('config.xml');
        $loader->load('controller.xml');
        $loader->load('form.xml');
        $loader->load('managers.xml');
        $loader->load('specifications.xml');
        $loader->load('thread_providers.xml');
        $loader->load('user.xml');

        $container->setParameter('miliooo_messaging.thread_class', $config['thread_class']);
        $container->setParameter('miliooo_messaging.thread_meta_class', $config['thread_meta_class']);
        $container->setParameter('miliooo_messaging.message_class', $config['message_class']);
        $container->setParameter('miliooo_messaging.message_meta_class', $config['message_meta_class']);
        $container->setParameter('miliooo_messaging.new_thread_form.model', $config['new_thread_form']['model']);
        $container->setAlias('miliooo_messaging.participant_provider', $config['participant_provider']);
        $container->setAlias('miliooo_messaging.new_thread_form.factory', $config['new_thread_form']['factory']);
    }
}
