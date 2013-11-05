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
    private $modelClassName;

    public function setUp()
    {
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->formType = $this->getMockBuilder('Symfony\Component\Form\AbstractType')->disableOriginalConstructor()->getMock();
        $this->formName = 'message';
        $this->modelClassName = '\Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient';
        $this->newThreadFormfactory = new NewThreadMessageFormFactory($this->formFactory, $this->formType, $this->formName, $this->modelClassName);
    }

    public function testCreateCallsFormFactoryWithRightArguments()
    {
        $sender = new ParticipantTestHelper('sender');

        $newThreadFormFactoryMock = $this->getMockBuilder('Miliooo\Messaging\Form\FormFactory\NewThreadMessageFormFactory')
            ->setConstructorArgs(array($this->formFactory, $this->formType, $this->formName, $this->modelClassName))
            ->setMethods(array('createNewFormModel'))
            ->getMock();

        $formModel = $this->getMock('Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient');

        $newThreadFormFactoryMock->expects($this->once())
            ->method('createNewFormModel')
            ->will($this->returnValue($formModel));

        $formModel->expects($this->once())
            ->method('setSender')
            ->with($sender);

        $formMock = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->formFactory->expects($this->once())
            ->method('createNamed')
            ->with($this->formName, $this->formType, $formModel)
            ->will($this->returnValue($formMock));

        $this->assertInstanceOf('Symfony\Component\Form\Form', $newThreadFormFactoryMock->create($sender));
    }

    public function testCreateReturnsWhatFormFactoryReturns()
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
