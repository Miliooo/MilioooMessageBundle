<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormHandler;

use Miliooo\Messaging\Form\FormHandler\AbstractFormHandler;

/**
 * Test file for AbstractFormHandler
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class AbstractFormHandlerTest extends \PHPUnit_Framework_TestCase
{
    private $abstractFormHandler;
    private $request;
    private $form;

    public function setUp()
    {
        $this->request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')->disableOriginalConstructor()->getMock();
        $this->abstractFormHandler = $this->getMockForAbstractClass('Miliooo\Messaging\Form\FormHandler\AbstractFormHandler', array($this->request));
        $this->form = $this->getMockBuilder('Symfony\Component\Form\Form')->disableOriginalConstructor()->getMock();
    }

    public function testProcessingNotSubmittedFormReturnsFalse()
    {
        $this->request->expects($this->once())->method('getMethod')
            ->will($this->returnValue('GET'));

        $this->expectsNotValidatingForm();
        $this->expectsNotCallingDoProcess();

        $this->assertFalse($this->abstractFormHandler->process($this->form));
    }

    public function testProcessingSubmittedFormReturnsFalseOnInvalidForm()
    {
        $this->expectsRequestMethodPost();
        $this->expectsValidatingFormWillReturn(false);
        $this->expectsNotCallingDoProcess();
        $this->assertFalse($this->abstractFormHandler->process($this->form));
    }

    public function testProcessingValidFormCallsDoProcessAndReturnsTrue()
    {
        $this->expectsRequestMethodPost();
        $this->expectsValidatingFormWillReturn(true);
        $this->expectsCallingDoProcess();
        $this->assertTrue($this->abstractFormHandler->process($this->form));
    }

    protected function expectsRequestMethodPost()
    {
        $this->request->expects($this->once())->method('getMethod')
            ->will($this->returnValue('POST'));
    }

    protected function expectsNotCallingDoProcess()
    {
        $this->abstractFormHandler->expects($this->never())->method('doProcess');
    }

    protected function expectsCallingDoProcess()
    {
        $this->abstractFormHandler->expects($this->once())->method('doProcess');
    }

    protected function expectsNotValidatingForm()
    {
        $this->form->expects($this->never())->method('handleRequest');
        $this->form->expects($this->never())->method('isValid');
    }

    /**
     * We expect that the form gets validated
     *
     * @param boolean $returnValue The returnValue for the isValid method on the form
     */
    protected function expectsValidatingFormWillReturn($returnValue)
    {
        $this->form->expects($this->once())->method('handleRequest');
        $this->form->expects($this->once())->method('isValid')->will($this->returnValue($returnValue));
    }
}
