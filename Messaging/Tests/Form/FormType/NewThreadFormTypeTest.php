<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Form\FormType;

use Miliooo\Messaging\Form\FormType\NewThreadFormType;

/**
 * Test file for Miliooo\Messaging\Form\FormType\NewThreadFormType
 *
 * This is a basic test we should check
 * http://symfony.com/doc/master/cookbook/form/unit_testing.html
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadFormTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     * @var NewThreadFormType
     */
    private $newThreadFormType;

    public function setUp()
    {
        $this->newThreadFormType = new NewThreadFormType;
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Symfony\Component\Form\FormTypeInterface', $this->newThreadFormType);
    }

    public function testBuildForm()
    {
        $formBuilder = $this->getMockBuilder('Symfony\Component\Form\FormBuilder')
                ->disableOriginalConstructor()->getMock();
        $formBuilder->expects($this->at(0))->method('add')->with('recipient', 'username_selector')
            ->will($this->returnValue($formBuilder));
        $formBuilder->expects($this->at(1))->method('add')->with('subject', 'text')
            ->will($this->returnValue($formBuilder));
        $formBuilder->expects($this->at(2))->method('add')->with('body', 'textarea')
            ->will($this->returnValue($formBuilder));

        $this->newThreadFormType->buildForm($formBuilder, []);
    }

    public function testSetDefaultOptions()
    {
        $defaults = ['intention' => 'add_new_thread'];
        $resolver = $this->getMock('Symfony\Component\OptionsResolver\OptionsResolverInterface');
        $resolver->expects($this->once())->method('setDefaults')->with($defaults);
        $this->newThreadFormType->setDefaultOptions($resolver);
    }

    public function testGetName()
    {
        $this->assertSame('miliooo_message_new_thread', $this->newThreadFormType->getName());
    }
}
