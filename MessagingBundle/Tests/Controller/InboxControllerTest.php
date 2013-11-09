<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Controller;

use Miliooo\MessagingBundle\Controller\InboxController;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for the InboxController
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class InboxControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var InboxController
     */
    private $controller;
    private $participantProvider;
    private $templating;
    private $inboxProvider;
    private $loggedInUser;
    private $threads;

    public function setUp()
    {
        $this->loggedInUser = new ParticipantTestHelper(1);
        $this->threads = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->inboxProvider = $this->getMock('Miliooo\Messaging\ThreadProvider\Folder\InboxProviderInterface');
        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->controller = new InboxController(
            $this->participantProvider, $this->inboxProvider, $this->templating
        );
    }

    public function testShowAction()
    {
        $this->participantProvider
            ->expects($this->once())
            ->method('getAuthenticatedParticipant')
            ->will($this->returnvalue($this->loggedInUser));

        $this->inboxProvider->expects($this->once())
            ->method('getInboxThreads')
            ->with($this->loggedInUser)
            ->will($this->returnValue([$this->threads]));

        $this->templating->expects($this->once())
            ->method('renderResponse')
            ->with('MilioooMessagingBundle:Folders:inbox.html.twig', ['threads' => [$this->threads]]);

        $this->controller->showAction();
    }
}
