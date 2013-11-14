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
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\ValueObjects\ReadStatus;
use Miliooo\Messaging\Model\MessageMetaInterface;

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
    private $threadProvider;

    /**
     * @var ParticipantInterface
     */
    private $loggedInUser;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $thread;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formHandler;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $form;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $formView;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $readStatusManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $message;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $arrayCollection;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $router;


    public function setUp()
    {
        $this->setConstructorMocks();
        $this->controller = new ShowThreadController(
            $this->formFactory,
            $this->formHandler,
            $this->threadProvider,
            $this->templating,
            $this->participantProvider,
            $this->readStatusManager,
            $this->router
        );

        $this->loggedInUser = new ParticipantTestHelper(1);
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')
                ->disableOriginalConstructor()->getMock();
        $this->formView = $this->getMockBuilder('Symfony\Component\Form\FormView')
                ->disableOriginalConstructor()->getMock();

        $this->message = $this->getMock('Miliooo\Messaging\Model\MessageInterface');
        $this->arrayCollection = $this->getMock('Doctrine\Common\Collections\ArrayCollection');
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
            ->with($this->loggedInUser, 1)
            ->will($this->returnValue(null));

        $this->controller->showAction(1);
    }

    public function testShowActionReturnsResponseWhenFormNotProcessed()
    {
        $this->expectsUser();
        $this->expectsThread();
        $this->expectsFormFactoryCreatesForm();
        $this->expectsFormHandlerProcessesFormAndReturnsFalse();
        $this->expectsFormCreatesAView();
        $this->expectsTemplatingRendersResponse();

        $this->expectsReadStatusUpdates();

        $this->controller->showAction(1);
    }

    public function testShowActionRedirectsResponseWhenFormProcessed()
    {
        $this->expectsUser();
        $this->expectsThread();
        $this->expectsFormFactoryCreatesForm();
        $this->expectsFormHandlerProcessesFormAndReturnsTrue();
        $this->router->expects($this->once())->method('generate')
            ->with('miliooo_message_thread_view', ['threadId' => 1])
            ->will($this->returnValue('test.com'));

        $this->controller->showAction(1);

    }

    protected function expectsFormFactoryCreatesForm()
    {
        $this->formFactory->expects($this->once())
            ->method('create')
            ->with($this->thread, $this->loggedInUser)
            ->will($this->returnValue($this->form));
    }

    protected function expectsFormHandlerProcessesFormAndReturnsFalse()
    {
        $this->formHandler->expects($this->once())
            ->method('process')->with($this->form)
            ->will($this->returnValue(false));
    }

    protected function expectsFormHandlerProcessesFormAndReturnsTrue()
    {
        $this->formHandler->expects($this->once())
            ->method('process')->with($this->form)
            ->will($this->returnValue(true));

    }

    protected function expectsFormCreatesAView()
    {
        $this->form->expects($this->once())
            ->method('createView')
            ->will($this->returnValue($this->formView));
    }

    protected function expectsTemplatingRendersResponse()
    {
        $template = 'MilioooMessagingBundle:ShowThread:show_thread.html.twig';
        $this->templating->expects($this->once())->method('renderResponse')
            ->with($template, ['thread' => $this->thread, 'form' => $this->formView]);
    }

    protected function setConstructorMocks()
    {
        $this->formFactory = $this->getMockBuilder('Miliooo\Messaging\Form\FormFactory\ReplyMessageFormFactory')
                ->disableOriginalConstructor()->getMock();

        $this->formHandler = $this->getMockBuilder('Miliooo\Messaging\Form\FormHandler\NewReplyFormHandler')
                ->disableOriginalConstructor()->getMock();

        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->threadProvider = $this->getMock('Miliooo\Messaging\ThreadProvider\SecureThreadProviderInterface');
        $this->readStatusManager = $this->getMock('Miliooo\Messaging\Manager\ReadStatusManagerInterface');
        $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface');
    }

    protected function expectsUser()
    {
        $this->participantProvider->expects($this->once())
            ->method('getAuthenticatedParticipant')->will($this->returnValue($this->loggedInUser));
    }

    protected function expectsThread()
    {
        $this->threadProvider->expects($this->once())
            ->method('findThreadForParticipant')
            ->with($this->loggedInUser, 1)
            ->will($this->returnValue($this->thread));
    }

    protected function expectsReadStatusUpdates()
    {
        $this->thread->expects($this->once())
            ->method('getMessages')
            ->will($this->returnValue($this->arrayCollection));

        $this->arrayCollection->expects($this->once())->method('toArray')
            ->will($this->returnValue([$this->message]));

        $this->readStatusManager
            ->expects($this->once())
            ->method('updateReadStatusForMessageCollection')
            ->with(new ReadStatus(MessageMetaInterface::READ_STATUS_READ), $this->loggedInUser, [$this->message]);
    }
}
