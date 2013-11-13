<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Builder\Model;

use Miliooo\Messaging\Builder\Model\ThreadBuilderModel;
use Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Description of ExtendedBuilderModel
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class BuilderModelTest extends \PHPUnit_Framework_TestCase
{
    private $builderModel;

    public function setUp()
    {

    }

    public function testIt()
    {
        $model = new NewThreadSingleRecipient();
        $sender = new ParticipantTestHelper(1);
        $model->setSender($sender);
        $model->setBody('this is my message lalala');
        $model->setCreatedAt(new \DateTime('now'));
        $model->setRecipient(new ParticipantTestHelper(2));
        $model->setSubject('hoi hier is mijn bericht');
        $this->builderModel = new ThreadBuilderModel($model);
    }
}
