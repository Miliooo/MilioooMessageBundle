<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormType;

use Miliooo\Messaging\Form\FormType\ReplyThreadFormType;

/**
 * Test file for Miliooo\Messaging\Form\FormType\ReplyThreadFormType
 *
 * This is a basic test we should check
 * http://symfony.com/doc/master/cookbook/form/unit_testing.html
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyThreadFormTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var ReplyThreadFormType
     */
    private $replyThreadFormType;

    public function setup()
    {
        $this->replyThreadFormType = new ReplyThreadFormType();
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Symfony\Component\Form\FormTypeInterface', $this->replyThreadFormType);
    }

    public function testBuildForm()
    {
        $formBuilder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
                ->disableOriginalConstructor()->getMock();
        $formBuilder->expects($this->at(0))->method('add')->with('body', 'textarea')
            ->will($this->returnValue($formBuilder));
        $this->replyThreadFormType->buildForm($formBuilder, array());
    }

    public function testSetDefaultOptions()
    {
        $defaults = ['intention' => 'reply'];
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');
        $resolver->expects($this->once())->method('setDefaults')->with($defaults);
        $this->replyThreadFormType->setDefaultOptions($resolver);
    }

    public function testGetName()
    {
        $this->assertSame('miliooo_message_reply_message', $this->replyThreadFormType->getName());
    }
}
