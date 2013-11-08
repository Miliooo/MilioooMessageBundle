<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Tests\Controller;

use Miliooo\MessagingBundle\Controller\NewThreadController;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

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
    private $formFactory;
    private $formHandler;
    private $participantProvider;
    private $templating;

    public function setUp()
    {
        $this->formFactory = $this->getMockBuilder('Miliooo\Messaging\Form\FormFactory\NewThreadMessageFormFactory')->disableOriginalConstructor()->getMock();
        $this->formHandler = $this->getMockBuilder('Miliooo\Messaging\Form\FormHandler\NewSingleThreadFormHandler')->disableOriginalConstructor()->getMock();
        $this->participantProvider = $this->getMock('Miliooo\Messaging\User\ParticipantProviderInterface');
        $this->templating = $this->getMock('Symfony\Bundle\FrameworkBundle\Templating\EngineInterface');
        $this->controller = new NewThreadController($this->formFactory, $this->formHandler, $this->participantProvider, $this->templating);
    }

    public function testCreateAction()
    {
        $form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $sender = new ParticipantTestHelper('sender');

        $this->participantProvider->expects($this->once())
            ->method('getAuthenticatedParticipant')->will($this->returnValue($sender));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->with($sender)
            ->will($this->returnValue($form));
        $this->formHandler->expects($this->once())->method('process')->with($form);

        $formView = $this->getMockBuilder('Symfony\Component\Form\FormView')->disableOriginalConstructor()->getMock();
        $form->expects($this->once())->method('createView')->will($this->returnValue($formView));

        $this->templating
            ->expects($this->once())
            ->method('renderResponse')
            ->with('MilioooMessagingBundle:NewThread:new_thread.html.twig', array('form' => $formView));
        $this->controller->createAction();
    }
}
