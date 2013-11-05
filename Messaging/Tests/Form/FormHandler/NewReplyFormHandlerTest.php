<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormHandler;

use Miliooo\Messaging\Form\FormHandler\NewReplyFormHandler;

/**
 * Test file for Miliooo\Messaging\Form\FormHandler\NewReplyFormHandlerTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewReplyFormHandlerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var NewSingleThreadFormHandler
     */
    private $formHandler;
    private $request;
    private $processor;
    private $form;
    private $formModel;

    public function setUp()
    {
        $this->processor = $this->getMock('Miliooo\Messaging\Form\FormModelProcessor\NewReplyFormProcessorInterface');
        $this->request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->formHandler = new NewReplyFormHandler($this->request, $this->processor);

        $this->formModel = $this->getMock('Miliooo\Messaging\Form\FormModel\ReplyMessageInterface');
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Form data needs to implement ReplyMessageInterface
     */
    public function testDoProcessThrowsExceptionOnInvalidData()
    {
        $this->form->expects($this->once())->method('getData')->will($this->returnValue('foo'));
        $this->formHandler->doProcess($this->form);
    }

    public function testDoProcess()
    {
        $this->expectsValidFormData();
        $this->expectsUpdatingFormModelWithCreatedAt();
        $this->expectsCallingProcessOnProcessor();
        $this->formHandler->doProcess($this->form);
    }

    protected function expectsValidFormData()
    {
        $this->form->expects($this->once())->method('getData')->will($this->returnValue($this->formModel));
    }

    protected function expectsUpdatingFormModelWithCreatedAt()
    {
        $this->formModel->expects($this->once())->method('setCreatedAt');
    }

    protected function expectsCallingProcessOnProcessor()
    {
        $this->processor->expects($this->once())->method('process')->with($this->formModel);
    }
}
