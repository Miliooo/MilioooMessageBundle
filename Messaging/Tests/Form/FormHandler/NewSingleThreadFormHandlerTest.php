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
    private $form;

    public function setUp()
    {
        $this->request = $this->getMock('Symfony\Component\HttpFoundation\Request');
        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
        $this->formHandler = new NewSingleThreadFormHandler($this->request);
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
}
