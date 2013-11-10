<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormModelProcessor;

use Miliooo\Messaging\Builder\Message\ReplyBuilderInterface;
use Miliooo\Messaging\Form\FormModel\ReplyMessageInterface;
use Miliooo\Messaging\Manager\NewMessageManagerInterface;
use Miliooo\Messaging\Builder\Model\ReplyBuilderModel;

/**
 * Description of NewReplyDefaultProcessor
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewReplyDefaultProcessor implements NewReplyFormProcessorInterface
{
    /**
     * A reply builder instance
     * @var ReplyBuilderInterface
     */
    protected $replyBuilder;

    /**
     * A new message manager instance
     *
     * @var NewMessageManagerInterface
     */
    protected $newMessageManager;

    /**
     * Constructor.
     *
     * @param ReplyBuilderInterface $replyBuilder
     * @param NewMessageManagerInterface $newMessageManager
     */
    public function __construct(ReplyBuilderInterface $replyBuilder, NewMessageManagerInterface $newMessageManager)
    {
        $this->replyBuilder = $replyBuilder;
        $this->newMessageManager = $newMessageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ReplyMessageInterface $formModel)
    {
        $replyBuilderModel = new ReplyBuilderModel($formModel);
        $thread = $this->replyBuilder->build($replyBuilderModel);
        $message = $thread->getLastMessage();
        $this->newMessageManager->saveNewReply($message);
    }
}
