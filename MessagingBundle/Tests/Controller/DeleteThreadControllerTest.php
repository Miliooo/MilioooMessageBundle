<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Controller;

use Miliooo\MessagingBundle\Controller\DeleteThreadController;

/**
 * Test file for the delete thread controller.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class DeleteThreadControllerTest extends \PHPUnit_Framework_TestCase
{
    const THREAD_ID = 1;

    /**
     * @var DeleteThreadController
     */
    private $controller;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $templating;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $deleteThreadManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $thread;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $flashMessageProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    public function setUp()
    {
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->deleteThreadManager = $this->getMock('Miliooo\Messaging\Manager\DeleteThreadManagerInterface');
        $this->threadProvider = $this->getMock('Miliooo\Messaging\ThreadProvider\ThreadProviderInterface');
        $this->flashMessageProvider = $this->getMock('Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProviderInterface');
        $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface');

        $this->controller = new DeleteThreadController(
            $this->templating,
            $this->deleteThreadManager,
            $this->threadProvider,
            $this->flashMessageProvider,
            $this->router
        );

        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
    }

    public function testDeleteWithExistingThread()
    {
        $this->expectsThreadExists();

        $this->deleteThreadManager->expects($this->once())
            ->method('deleteThread')
            ->with($this->thread);

        $this->flashMessageProvider->expects($this->once())
            ->method('addFlash')
            ->with('success', 'flash.thread_deleted_success');

        $this->expectsRouterInbox();

        $this->controller->deleteAction(self::THREAD_ID);
    }

    public function testDeleteWithNonExistingThread()
    {
        $this->threadProvider->expects($this->once())
            ->method('findThreadById')
            ->with(self::THREAD_ID)
            ->will($this->returnValue(null));

        $this->flashMessageProvider->expects($this->once())
            ->method('addFlash')
            ->with('error', 'flash.thread_not_found');

        $this->deleteThreadManager->expects($this->never())
            ->method('deleteThread');

        $this->expectsRouterInbox();

        $this->controller->deleteAction(self::THREAD_ID);
    }

    protected function expectsThreadExists()
    {
        $this->threadProvider->expects($this->once())
            ->method('findThreadById')
            ->with(self::THREAD_ID)
            ->will($this->returnValue($this->thread));
    }

    protected function expectsRouterInbox()
    {
        //we need to return an url or redirect response will error out...
        $this->router->expects($this->once())
            ->method('generate')
            ->with('miliooo_message_inbox')
            ->will($this->returnValue('http://test.com'));
    }
}
