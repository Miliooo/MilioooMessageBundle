<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Controller;

use Miliooo\MessagingBundle\Controller\ShowThreadController;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for Miliooo\MessagingBundle\Controller\ShowThreadController
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ShowThreadControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     * @var ShowThreadController
     */
    private $controller;
    private $participantProvider;
    private $templating;
    private $request;
    private $threadProvider;
    private $user;
    private $thread;

    public function setUp()
    {
        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->disableOriginalConstructor()->getMock();
        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->threadProvider = $this->getMock('Miliooo\Messaging\ThreadProvider\SecureThreadProviderInterface');
        $this->controller = new ShowThreadController($this->threadProvider, $this->templating, $this->participantProvider);
        $this->user = new ParticipantTestHelper(1);
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
    }

    /**
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @expectedExceptionMessage Thread not found
     */
    public function testShowActionThreadNotFoundThrowsException()
    {
        $this->expectsUser();
        $this->threadProvider->expects($this->once())
            ->method('findThreadForParticipant')
            ->with($this->user, 1)
            ->will($this->returnValue(null));

        $this->controller->showAction($this->request, 1);
    }

    public function testShowActionReturnsResponse()
    {
        $this->expectsUser();
        $this->expectsThread();

        $template = 'MilioooMessagingBundle:ShowThread:show_thread.html.twig';
        $this->templating->expects($this->once())->method('renderResponse')
            ->with($template, array('thread' => $this->thread));
        $this->controller->showAction($this->request, 1);
    }

    protected function expectsUser()
    {
        $this->participantProvider->expects($this->once())
            ->method('getAuthenticatedParticipant')->will($this->returnValue($this->user));
    }

    protected function expectsThread()
    {
        $this->threadProvider->expects($this->once())
            ->method('findThreadForParticipant')
            ->with($this->user, 1)
            ->will($this->returnValue($this->thread));
    }
}
