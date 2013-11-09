<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Controller;

use Miliooo\MessagingBundle\Controller\OutboxController;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for outboxcontroller.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class OutboxControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var OutboxController
     */
    private $controller;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $participantProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $templating;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $outboxProvider;
    private $loggedInUser;
    private $threads;

    public function setup()
    {
        $this->loggedInUser = new ParticipantTestHelper(1);
        $this->threads = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->outboxProvider = $this->getMock('Miliooo\Messaging\ThreadProvider\Folder\OutboxProviderInterface');
        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->controller = new OutboxController(
            $this->templating,
            $this->outboxProvider,
            $this->participantProvider
        );
    }

    public function testShowAction()
    {
        $this->participantProvider
            ->expects($this->once())
            ->method('getAuthenticatedParticipant')
            ->will($this->returnvalue($this->loggedInUser));

        $this->outboxProvider->expects($this->once())
            ->method('getOutboxThreads')
            ->with($this->loggedInUser)
            ->will($this->returnValue([$this->threads]));

        $this->templating->expects($this->once())
            ->method('renderResponse')
            ->with('MilioooMessagingBundle:Folders:outbox.html.twig', ['threads' => [$this->threads]]);

        $this->controller->showAction();
    }
}
