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
    private $loggedInUser;
    private $thread;
    private $formFactory;
    private $formHandler;
    private $form;
    private $formView;

    public function setUp()
    {
        $this->setConstructorMocks();
        $this->controller = new ShowThreadController($this->formFactory, $this->formHandler, $this->threadProvider, $this->templating, $this->participantProvider);

        $this->loggedInUser = new ParticipantTestHelper(1);
        $this->thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')
                ->disableOriginalConstructor()->getMock();
        $this->formView = $this->getMockBuilder('Symfony\Component\Form\FormView')->disableOriginalConstructor()->getMock();
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

        $this->controller->showAction($this->request, 1);
    }

    public function testShowActionReturnsResponse()
    {
        $this->expectsUser();
        $this->expectsThread();
        $this->expectsFormFactoryCreatesForm();
        $this->expectsFormHandlerProcessesForm();
        $this->expectsFormCreatesAView();
        $this->expectsTemplatingRendersResponse();
        $this->controller->showAction($this->request, 1);
    }

    protected function expectsFormFactoryCreatesForm()
    {
        $this->formFactory->expects($this->once())
            ->method('create')
            ->with($this->thread, $this->loggedInUser)
            ->will($this->returnValue($this->form));
    }

    protected function expectsFormHandlerProcessesForm()
    {
        $this->formHandler->expects($this->once())
            ->method('process')->with($this->form);
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

        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
                ->disableOriginalConstructor()->getMock();

        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->threadProvider = $this->getMock('Miliooo\Messaging\ThreadProvider\SecureThreadProviderInterface');
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
}
