<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormHandler;

use Miliooo\Messaging\Form\FormHandler\NewSingleThreadFormHandler;
use Miliooo\Messaging\Form\FormModel\NewThreadInterface;

/**
 * Test file for Miliooo\Messaging\Form\FormHandler\NewSingleThreadFormHandler
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewSingleThreadFormHandlerTest extends \PHPUnit_Framework_TestCase
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

    public function setUp()
    {
        $this->processor = $this->getMock('Miliooo\Messaging\Form\FormModelProcessor\NewThreadFormProcessorInterface');
        $this->request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->formHandler = new NewSingleThreadFormHandler($this->request, $this->processor);
    }

    public function testProcessReturnsFalseWhenRequestMethodNotPost()
    {
        $this->request->expects($this->once())->method('getMethod')
            ->will($this->returnValue('GET'));
        $this->form->expects($this->never())->method('handleRequest');
        $this->formHandler->process($this->form);
    }

    public function testProcessFormHandlesRequestWhenMethodPost()
    {
        $this->request->expects($this->once())->method('getMethod')
            ->will($this->returnValue('POST'));
        $this->form->expects($this->once())->method('handleRequest')->with($this->request);
        $this->formHandler->process($this->form);
    }

    public function testProcessFormValidatesRequestWhenRequestMethodPost()
    {
        $this->request->expects($this->once())->method('getMethod')
            ->will($this->returnValue('POST'));
        $this->form->expects($this->once())->method('handleRequest')->with($this->request);
        $this->form->expects($this->once())->method('isValid');
        $this->formHandler->process($this->form);
    }

    public function testProcessReturnsFalseWhenFormNotValid()
    {
        $this->request->expects($this->once())->method('getMethod')
            ->will($this->returnValue('POST'));
        $this->form->expects($this->once())->method('handleRequest')->with($this->request);
        $this->form->expects($this->once())->method('isValid')
            ->will($this->returnValue(false));
        $this->assertFalse($this->formHandler->process($this->form));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Form data needs to implement NewThreadInterface
     */
    public function testProcessWithValidFormReturnsInvalidDataThrowsException()
    {
        $this->expectsMethodPost();
        $this->expectsValidFormData();
        $this->form->expects($this->once())->method('getData')->will($this->returnValue('foo'));

        $this->formHandler->process($this->form);
    }

    public function testProcessWithValidFormReturnsRightData()
    {
        $formData = $this->getMock('Miliooo\Messaging\Form\FormModel\NewThreadInterface');
        $this->expectsMethodPost();
        $this->expectsValidFormData();
        $this->form->expects($this->once())->method('getData')->will($this->returnValue($formData));

        $formData->expects($this->once())->method('setCreatedAt');
        $this->processor->expects($this->once())->method('process')->with($formData);

        $this->formHandler->process($this->form);
    }

    protected function expectsMethodPost()
    {
        $this->request->expects($this->once())->method('getMethod')
            ->will($this->returnValue('POST'));
    }

    protected function expectsValidFormData()
    {
        $this->form->expects($this->once())->method('handleRequest')->with($this->request);
        $this->form->expects($this->once())->method('isValid')
            ->will($this->returnValue(true));
    }
}
