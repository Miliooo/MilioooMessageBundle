<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Parser;
use Miliooo\MessagingBundle\DependencyInjection\MilioooMessagingExtension;

/**
 * Test file for MilioooMessagingExtensionTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MilioooMessagingExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var ContainerBuilder */
    protected $containerBuilder;

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadThrowsExceptionUnlessMessageClassSet()
    {
        $loader = new MilioooMessagingExtension();
        $config = $this->getEmptyConfig();
        unset($config['message_class']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadThrowsExceptionUnlessMessageMetaClassSet()
    {
        $loader = new MilioooMessagingExtension();
        $config = $this->getEmptyConfig();
        unset($config['message_meta_class']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadThrowsExceptionUnlessThreadClassSet()
    {
        $loader = new MilioooMessagingExtension();
        $config = $this->getEmptyConfig();
        unset($config['thread_class']);
        $loader->load(array($config), new ContainerBuilder());
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testLoadThrowsExceptionUnlessThreadMetaSet()
    {
        $loader = new MilioooMessagingExtension();
        $config = $this->getEmptyConfig();
        unset($config['thread_meta_class']);
        $loader->load(array($config), new ContainerBuilder());
    }

    public function testMessagingLoadModelClassesWithDefaults()
    {
        $this->createEmptyConfiguration();

        $this->assertParameter('\Acme\MyBundle\Entity\Message', 'miliooo_messaging.message_class');
        $this->assertParameter('\Acme\MyBundle\Entity\MessageMeta', 'miliooo_messaging.message_meta_class');
        $this->assertParameter('\Acme\MyBundle\Entity\Thread', 'miliooo_messaging.thread_class');
        $this->assertParameter('\Acme\MyBundle\Entity\ThreadMeta', 'miliooo_messaging.thread_meta_class');
    }

    protected function createEmptyConfiguration()
    {
        $this->containerBuilder = new ContainerBuilder();
        $loader = new MilioooMessagingExtension();
        $config = $this->getEmptyConfig();
        $loader->load(array($config), $this->containerBuilder);
        $this->assertTrue($this->containerBuilder instanceof ContainerBuilder);
    }

    /**
     * gets an empty config
     *
     * @return array
     */
    protected function getEmptyConfig()
    {
        $yaml = <<<EOF
thread_class: \Acme\MyBundle\Entity\Thread
thread_meta_class: \Acme\MyBundle\Entity\ThreadMeta
message_class: \Acme\MyBundle\Entity\Message
message_meta_class: \Acme\MyBundle\Entity\MessageMeta
EOF;
        $parser = new Parser();

        return $parser->parse($yaml);
    }

    /**
     * Asserts that a parameter key has a certain value
     *
     * @param mixed  $value
     * @param string $key
     */
    private function assertParameter($value, $key)
    {
        $this->assertEquals($value, $this->containerBuilder->getParameter($key), sprintf('%s parameter is correct', $key));
    }

    /**
     * Asserts that a definition exists
     *
     * @param string $id
     */
    private function assertHasDefinition($id)
    {
        $this->assertTrue(($this->containerBuilder->hasDefinition($id) || $this->containerBuilder->hasAlias($id)));
    }

    /**
     * Asserts that a definition does not exist
     *
     * @param string $id
     */
    private function assertNotHasDefinition($id)
    {
        $this->assertFalse(($this->containerBuilder->hasDefinition($id) || $this->containerBuilder->hasAlias($id)));
    }
}
