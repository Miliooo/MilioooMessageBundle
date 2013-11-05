<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormFactory;

use Miliooo\Messaging\Form\FormFactory\ReplyMessageFormFactory;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Description of ReplyMessageFormFactoryTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyMessageFormFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var ReplyMessageFormFactory
     */
    private $replyFormfactory;
    private $formFactory;
    private $formType;
    private $formName;
    private $modelClassName;

    public function setUp()
    {
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->formType = $this->getMockBuilder('Symfony\Component\Form\AbstractType')->disableOriginalConstructor()->getMock();
        $this->formName = 'message';
        $this->modelClassName = '\Miliooo\Messaging\Form\FormModel\ReplyMessage';
        $this->replyFormfactory = new ReplyMessageFormFactory($this->formFactory, $this->formType, $this->formName, $this->modelClassName);
    }

    public function testCreate()
    {
        $sender = new ParticipantTestHelper('sender');
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');

        $replyFormFactoryMock = $this->getMockBuilder('Miliooo\Messaging\Form\FormFactory\ReplyMessageFormFactory')
            ->setConstructorArgs(array($this->formFactory, $this->formType, $this->formName, $this->modelClassName))
            ->setMethods(array('createNewFormModel'))
            ->getMock();

        $formModel = $this->getMock('Miliooo\Messaging\Form\FormModel\ReplyMessageInterface');

        $replyFormFactoryMock->expects($this->once())
            ->method('createNewFormModel')
            ->will($this->returnValue($formModel));

        $formModel->expects($this->once())
            ->method('setSender')
            ->with($sender);
        $formModel->expects($this->once())
            ->method('setThread')->with($thread);

        $formMock = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->formFactory->expects($this->once())
            ->method('createNamed')
            ->with($this->formName, $this->formType, $formModel)
            ->will($this->returnValue($formMock));

        $this->assertInstanceOf('Symfony\Component\Form\Form', $replyFormFactoryMock->create($thread, $sender));
    }

    //a test without mocking that method
    public function testCreateReturnsWhatFormFactoryReturns()
    {
        $thread = $this->getMock('Miliooo\Messaging\Model\ThreadInterface');
        $sender = new ParticipantTestHelper('sender');
        $formMock = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->formFactory->expects($this->once())
            ->method('createNamed')
            ->with($this->anything())
            ->will($this->returnValue($formMock));
         $this->assertInstanceOf('Symfony\Component\Form\Form', $this->replyFormfactory->create($thread, $sender));
    }
}
