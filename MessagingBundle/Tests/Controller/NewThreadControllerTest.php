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
use Miliooo\MessagingBundle\Controller\NewThreadController;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProviderInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Test file for Miliooo\MessagingBundle\Controller\NewThreadController
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadControllerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var NewThreadController
     */
    private $controller;

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
    private $participantProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $templating;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $flashMessageProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $router;

    /**
     * @var ParticipantInterface
     */
    private $loggedInUser;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $form;

    public function setUp()
    {
        $this->formFactory = $this->getMockBuilder('Miliooo\Messaging\Form\FormFactory\NewThreadMessageFormFactory')
            ->disableOriginalConstructor()->getMock();
        $this->formHandler = $this->getMockBuilder('Miliooo\Messaging\Form\FormHandler\NewSingleThreadFormHandler')
            ->disableOriginalConstructor()->getMock();
        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->flashMessageProvider = $this->getMock(
            'Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProviderInterface'
        );
        $this->router = $this->getMock('Symfony\Component\Routing\RouterInterface');
        $this->loggedInUser = new ParticipantTestHelper('1');
        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->controller = new NewThreadController(
            $this->formFactory,
            $this->formHandler,
            $this->participantProvider,
            $this->templating,
            $this->flashMessageProvider,
            $this->router
        );
    }

    public function testCreateActionWithNonProcessedForm()
    {
        $this->expectsLoggedInUser();
        $this->expectsFormProcessingWillReturn(false);
        $this->expectsTwigRendering();
        $this->flashMessageProvider->expects($this->never())->method('addFlash');
        $this->controller->createAction(new Request());
    }

    public function testCreateActionWithProcessedForm()
    {
        $this->expectsLoggedInUser();
        $this->expectsFormProcessingWillReturn(true);
        $this->flashMessageProvider->expects($this->once())->method('addFlash')
            ->with(FlashMessageProviderInterface::TYPE_SUCCESS, 'flash.thread_created_success');
        $this->router->expects($this->once())
            ->method('generate')
            ->with('miliooo_message_thread_new')
            ->will($this->returnValue('http://www.test.com'));

        $this->controller->createAction(new Request());
    }

    protected function expectsLoggedInUser()
    {
        $this->participantProvider->expects($this->once())
            ->method('getAuthenticatedParticipant')->will($this->returnValue($this->loggedInUser));
    }

    /**
     * Expects Form processing and the process method will return the first parameter value.
     *
     * @param boolean $boolean
     */
    protected function expectsFormProcessingWillReturn($boolean)
    {
        $this->formFactory->expects($this->once())
            ->method('create')
            ->with($this->loggedInUser)
            ->will($this->returnValue($this->form));

        $this->formHandler->expects($this->once())->method('process')->with($this->form)
            ->will($this->returnValue($boolean));
    }

    protected function expectsTwigRendering()
    {
        $formView = $this->getMockBuilder('Symfony\Component\Form\FormView')->disableOriginalConstructor()->getMock();
        $this->form->expects($this->once())->method('createView')->will($this->returnValue($formView));

        $this->templating
            ->expects($this->once())
            ->method('renderResponse')
            ->with('MilioooMessagingBundle:NewThread:new_thread.html.twig', ['form' => $formView]);
    }
}
