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
    private $inboxProvider;
    private $loggedInUser;
    private $pagerfanta;

    public function setUp()
    {
        $this->loggedInUser = new ParticipantTestHelper(1);
        $this->pagerfanta = $this->getMockBuilder('Pagerfanta\Pagerfanta')->disableOriginalConstructor()->getMock();
        $this->inboxProvider =
            $this->getMock('Miliooo\Messaging\ThreadProvider\Folder\InboxProviderPagerFantaInterface');

        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->controller = new InboxController(
            $this->participantProvider,
            $this->inboxProvider,
            $this->templating
        );
    }

    public function testShowAction()
    {
        $page = 2;

        $this->participantProvider
            ->expects($this->once())
            ->method('getAuthenticatedParticipant')
            ->will($this->returnvalue($this->loggedInUser));

        $this->inboxProvider->expects($this->once())
            ->method('getInboxThreadsPagerfanta')
            ->with($this->loggedInUser, $page)
            ->will($this->returnValue($this->pagerfanta));

        $this->templating->expects($this->once())
            ->method('renderResponse')
            ->with('MilioooMessagingBundle:Folders:inbox.html.twig', ['pagerfanta' => $this->pagerfanta]);

        $this->controller->showAction($page);
    }
}
