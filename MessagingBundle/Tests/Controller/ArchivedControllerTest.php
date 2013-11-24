<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Controller;

use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\MessagingBundle\Controller\ArchivedController;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for the ArchivedController
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ArchivedControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var ArchivedController
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
    private $archivedProvider;

    /**
     * @var ParticipantInterface
     */
    private $loggedInUser;
    private $pagerfanta;

    public function setUp()
    {
        $this->loggedInUser = new ParticipantTestHelper(1);
        $this->pagerfanta = $this->getMockBuilder('Pagerfanta\Pagerfanta')->disableOriginalConstructor()->getMock();
        $this->archivedProvider =
            $this->getMock('Miliooo\Messaging\ThreadProvider\Folder\ArchivedProviderPagerFantaInterface');

        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->controller = new ArchivedController(
            $this->participantProvider,
            $this->archivedProvider,
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

        $this->archivedProvider->expects($this->once())
            ->method('getArchivedThreadsPagerfanta')
            ->with($this->loggedInUser, $page)
            ->will($this->returnValue($this->pagerfanta));

        $this->templating->expects($this->once())
            ->method('renderResponse')
            ->with('MilioooMessagingBundle:Folders:archived.html.twig', ['pagerfanta' => $this->pagerfanta]);

        $this->controller->showAction($page);
    }
}
