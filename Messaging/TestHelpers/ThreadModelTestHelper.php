<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\TestHelpers;

use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;
use Miliooo\Messaging\Builder\Thread\NewThread\NewThreadBuilderFromFormModel;
use Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient;

/**
 * Description of ThreadModelTestHelper
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadModelTestHelper
{
    const MESSAGE_BODY = "the body of the message";
    const SENDER_ID = "sender";
    const RECIPIENT_ID = "recipient";
    const DATE_TIME_VALUE = "2011-10-13 00:00:00";
    const THREAD_SUBJECT = "The subject of the message";

    public function getModelThread()
    {
        $sender = new ParticipantTestHelper(self::SENDER_ID);
        $recipient = new ParticipantTestHelper(self::RECIPIENT_ID);

        $builder = new NewThreadBuilderFromFormModel();
        $builder->setMessageClass('\Miliooo\Messaging\TestHelpers\Model\Message');
        $builder->setThreadClass('\Miliooo\Messaging\TestHelpers\Model\Thread');
        $builder->setMessageMetaClass('\Miliooo\Messaging\TestHelpers\Model\MessageMeta');
        $builder->setThreadMetaClass('\Miliooo\Messaging\TestHelpers\Model\ThreadMeta');

        $newThreadModel = new NewThreadSingleRecipient();
        $newThreadModel->setSender($sender);
        $newThreadModel->setRecipients($recipient);
        $newThreadModel->setSubject(self::THREAD_SUBJECT);
        $newThreadModel->setBody(self::MESSAGE_BODY);
        $newThreadModel->setCreatedAt(new \DateTime(self::DATE_TIME_VALUE));

        return $builder->buildThread($newThreadModel);
    }
}
