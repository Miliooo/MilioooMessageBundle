<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\TestHelpers;

use Miliooo\Messaging\Builder\Message\NewThreadBuilder;
use Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient;
use Miliooo\Messaging\Builder\Model\ThreadBuilderModel;
use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Description of ThreadModelTestHelper
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadModelTestHelper
{
    protected $sender;
    protected $recipient;

    const MESSAGE_BODY = "the body of the message";
    const SENDER_ID = "sender";
    const RECIPIENT_ID = "recipient";
    const DATE_TIME_VALUE = "2011-10-13 00:00:00";
    const THREAD_SUBJECT = "The subject of the message";

    /**
     * Builds a thread.
     *
     * @return ThreadInterface
     */
    public function getModelThread()
    {
        $this->sender = new ParticipantTestHelper(self::SENDER_ID);
        $this->recipient = new ParticipantTestHelper(self::RECIPIENT_ID);

        $builder = new NewThreadBuilder();
        $builder->setMessageClass('\Miliooo\Messaging\TestHelpers\Model\Message');
        $builder->setThreadClass('\Miliooo\Messaging\TestHelpers\Model\Thread');
        $builder->setMessageMetaClass('\Miliooo\Messaging\TestHelpers\Model\MessageMeta');
        $builder->setThreadMetaClass('\Miliooo\Messaging\TestHelpers\Model\ThreadMeta');

        $newThreadModel = new NewThreadSingleRecipient();
        $newThreadModel->setSender($this->sender);
        $newThreadModel->setRecipient($this->recipient);
        $newThreadModel->setSubject(self::THREAD_SUBJECT);
        $newThreadModel->setBody(self::MESSAGE_BODY);
        $newThreadModel->setCreatedAt(new \DateTime(self::DATE_TIME_VALUE));

        $builderModel = new ThreadBuilderModel($newThreadModel);

        return $builder->build($builderModel);
    }

    /**
     * Returns the sender of the first message in the model thread.
     *
     * @return ParticipantInterface the sender of the first message in the model thread
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Returns the recipient of the first message in the model thread.
     *
     * @return ParticipantInterface the recipient of the first message in the model thread
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
}
