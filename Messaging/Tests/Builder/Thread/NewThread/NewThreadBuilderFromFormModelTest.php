<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Builder\Thread\NewThread;

use Miliooo\Messaging\Builder\Thread\NewThread\NewThreadBuilderFromFormModel;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Description of NewThreadBuilderFromFormModelTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadBuilderFromFormModelTest extends \PHPUnit_Framework_TestCase
{
    private $builder;
    private $newThreadModel;
    private $sender;

    public function setUp()
    {
        $this->newThreadModel = $this->getMock('Miliooo\Messaging\Form\FormModel\NewThreadInterface');
        $this->builder = new NewThreadBuilderFromFormModel();
        $this->builder->setMessageClass('Miliooo\Messaging\TestHelpers\Model\Message');
        $this->builder->setThreadClass('Miliooo\Messaging\TestHelpers\Model\Thread');
        $this->builder->setMessageMetaClass('Miliooo\Messaging\TestHelpers\Model\MessageMeta');
        $this->builder->setThreadMetaClass('Miliooo\Messaging\TestHelpers\Model\ThreadMeta');

        $this->sender = new ParticipantTestHelper('sender');
        $this->recipients = new ParticipantTesthelper('recipient');
    }

    public function testBuild()
    {
        $this->newThreadModel->expects($this->once())
            ->method('getSender')
            ->will($this->returnValue($this->sender));
        $this->newThreadModel->expects($this->once())
            ->method('getRecipients')
            ->will($this->returnValue([$this->recipients]));
        $this->newThreadModel->expects($this->once())
            ->method('getSubject')
            ->will($this->returnValue('The subject'));
        $this->newThreadModel->expects($this->once())
            ->method('getBody')
            ->will($this->returnValue('the body of the message'));
        $this->newThreadModel->expects($this->once())
            ->method('getCreatedAt')
            ->will($this->returnValue(new \DateTime('now')));

        $this->assertInstanceOf('Miliooo\Messaging\Model\ThreadInterface', $this->builder->build($this->newThreadModel));
    }
}
