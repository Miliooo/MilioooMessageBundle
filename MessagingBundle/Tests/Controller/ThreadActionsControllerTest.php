<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Controller;

use Miliooo\MessagingBundle\Controller\ThreadActionsController;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\HttpFoundation\Request;
use Miliooo\Messaging\ValueObjects\ThreadStatus;
use Miliooo\Messaging\Model\ThreadMetaInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProviderInterface;

/**
 * Test file for ThreadActionsControllerTest
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadActionsControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ThreadActionsController
     */
    private $controller;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadStatusManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $participantProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $threadProvider;

    /**
     * @var ParticipantInterface
     */
    private $loggedInUser;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $request;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $parameterBag;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $flashProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    public function setUp()
    {
        $this->loggedInUser = new ParticipantTestHelper('1');

        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $this->parameterBag = $this->getMockBuilder('Symfony\Component\HttpFoundation\ParameterBag')
        ->disableOriginalConstructor()->getMock();

        $this->threadStatusManager = $this->getMock('Miliooo\Messaging\Manager\ThreadStatusManagerInterface');
        $this->threadProvider = $this->getMock('Miliooo\Messaging\ThreadProvider\SecureThreadProviderInterface');
        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->flashProvider = $this->getMock('Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProviderInterface');
        $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface');

        $this->controller = new ThreadActionsController(
            $this->threadStatusManager,
            $this->threadProvider,
            $this->participantProvider,
            $this->flashProvider,
            $this->router
        );
    }

    public function testNoThreadsSelectedWithReferrerSet()
    {
        //setup a request object with action archive_thread and one thread selected
        $postArray = ['thread_action' => 'archive_thread', 'folder' => 'outbox'];
        $this->request = new Request([], $postArray, [], [], [], ['HTTP_REFERER' => 'http://test.com']);

        $this->flashProvider->expects($this->once())->method('addFlash')
            ->with(FlashMessageProviderInterface::TYPE_ERROR, 'flash.thread_updates.no_threads_selected');

        $this->router->expects($this->never())->method('generate');

        $this->controller->threadAction($this->request);
    }

    public function testNoThreadsSelectedWithNoRefererSet()
    {
        //setup a request object with action archive_thread and one thread selected
        $postArray = ['thread_action' => 'archive_thread', 'folder' => 'outbox'];
        $this->request = new Request([], $postArray, [], [], []);

        $this->flashProvider->expects($this->once())->method('addFlash')
            ->with(FlashMessageProviderInterface::TYPE_ERROR, 'flash.thread_updates.no_threads_selected');

        $this->expectsRouterCallWith('miliooo_message_outbox');

        $this->controller->threadAction($this->request);
    }

    public function testUnknownThreadAction()
    {
        //setup a request object with action archive_thread and one thread selected
        $postArray = ['thread_action' => 'foo', 'selected_threads' => ['1'], 'folder' => 'outbox'];
        $this->request = new Request([], $postArray, [], [], [], ['HTTP_REFERER' => 'http://test.com']);

        $this->flashProvider->expects($this->once())->method('addFlash')
            ->with(FlashMessageProviderInterface::TYPE_ERROR, 'flash.thread_updates.unknown_action');
        $this->expectsNoThreadStatusManagerUpdate();

        $this->controller->threadAction($this->request);
    }

    public function testThreadActionArchiveThreadWhenThreadsFound()
    {
        //setup a request object with action archive_thread and one thread selected
        $postArray = ['thread_action' => 'archive_thread', 'selected_threads' => ['1'], 'folder' => 'outbox'];
        $this->request = new Request([], $postArray);

        $this->expectsLoggedInUser();

        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->threadProvider->expects($this->once())
            ->method('findThreadForParticipant')
            ->with($this->loggedInUser, 1)
            ->will($this->returnValue($thread));

        $threadStatusObject = new ThreadStatus(ThreadMetaInterface::STATUS_ARCHIVED);
        $this->threadStatusManager->expects($this->once())->method('updateThreadStatusForParticipant')
            ->with($threadStatusObject, $thread, $this->loggedInUser);

        $this->flashProvider->expects($this->once())->method('addFlash')
            ->with(FlashMessageProviderInterface::TYPE_SUCCESS, 'flash.thread_updates.archived_success');

        $this->expectsRouterCallWith('miliooo_message_outbox');

        $this->controller->threadAction($this->request);
    }

    public function testThreadActionArchiveThreadWhenNoThreadsFound()
    {
        $this->expectsLoggedInUser();

        //setup a request object with action archive_thread and one thread selected
        $postArray = ['thread_action' => 'archive_thread', 'selected_threads' => ['1']];
        $this->request = new Request([], $postArray);

        $this->threadProvider->expects($this->once())
            ->method('findThreadForParticipant')
            ->with($this->loggedInUser, 1)
            ->will($this->returnValue(null));

        $this->expectsNoThreadStatusManagerUpdate();

        $this->flashProvider->expects($this->once())->method('addFlash')
            ->with(FlashMessageProviderInterface::TYPE_ERROR, 'flash.thread_updates.archived_no_threads');

        $this->expectsRouterCallWith('miliooo_message_inbox');
        $this->controller->threadAction($this->request);
    }

    public function testThreadActionWhenNotAllowedToViewThread()
    {
        $this->expectsLoggedInUser();

        //setup a request object with action archive_thread and one thread selected
        $postArray = ['thread_action' => 'archive_thread', 'selected_threads' => ['1']];
        $this->request = new Request([], $postArray);

        $this->threadProvider->expects($this->once())
            ->method('findThreadForParticipant')
            ->with($this->loggedInUser, 1)
            ->will($this->throwException(new AccessDeniedException()));

        $this->expectsNoThreadStatusManagerUpdate();

        $this->flashProvider->expects($this->once())->method('addFlash')
            ->with(FlashMessageProviderInterface::TYPE_ERROR, 'flash.thread_updates.archived_no_threads');

        $this->expectsRouterCallWith('miliooo_message_inbox');
        $this->controller->threadAction($this->request);
    }

    protected function expectsLoggedInUser()
    {
        $this->participantProvider->expects($this->once())->method('getAuthenticatedParticipant')
            ->will($this->returnValue($this->loggedInUser));
    }

    /**
     * @param string $routingName
     */
    protected function expectsRouterCallWith($routingName)
    {
        $this->router->expects($this->once())->method('generate')->with($routingName)
            ->will($this->returnValue('http://test.com'));
    }

    protected function expectsNoThreadStatusManagerUpdate()
    {
        $this->threadStatusManager->expects($this->never())->method('updateThreadStatusForParticipant');
    }
}
