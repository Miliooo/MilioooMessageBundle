<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormFactory;

use Miliooo\Messaging\Form\FormFactory\NewThreadMessageFormFactory;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for Miliooo\Messaging\Form\FormFactory\NewThreadMessageFormFactory
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadMessageFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var NewThreadMessageFormFactory
     */
    private $newThreadFormfactory;
    private $formFactory;
    private $formType;
    private $formName;
    private $messageClass;

    public function setUp()
    {
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->formType = $this->getMockBuilder('Symfony\Component\Form\AbstractType')->disableOriginalConstructor()->getMock();
        $this->formName = 'message';
        $this->messageClass = '\Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient';
        $this->newThreadFormfactory = new NewThreadMessageFormFactory($this->formFactory, $this->formType, $this->formName, $this->messageClass);
    }

    public function testCreateCallsFormFactoryWithRightArguments()
    {
        $sender = new ParticipantTestHelper('sender');

        $newThreadFormFactoryMock = $this->getMockBuilder('Miliooo\Messaging\Form\FormFactory\NewThreadMessageFormFactory')
            ->setConstructorArgs(array($this->formFactory, $this->formType, $this->formName, $this->messageClass))
            ->setMethods(array('createNewMessage'))
            ->getMock();

        $messageClass = $this->getMock('Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient');

        $newThreadFormFactoryMock->expects($this->once())
            ->method('createNewMessage')
            ->will($this->returnValue($messageClass));

        $messageClass->expects($this->once())
            ->method('setSender')
            ->with($sender);

        $formMock = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->formFactory->expects($this->once())
            ->method('createNamed')
            ->with($this->formName, $this->formType, $messageClass)
            ->will($this->returnValue($formMock));

        $this->assertInstanceOf('Symfony\Component\Form\Form', $newThreadFormFactoryMock->create($sender));
    }

    public function testCreateReturnsForm()
    {
        $sender = new ParticipantTestHelper('sender');
        $formMock = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->formFactory->expects($this->once())
            ->method('createNamed')
            ->with($this->anything())
            ->will($this->returnValue($formMock));
        $this->assertInstanceOf('Symfony\Component\Form\Form', $this->newThreadFormfactory->create($sender));
    }
}
