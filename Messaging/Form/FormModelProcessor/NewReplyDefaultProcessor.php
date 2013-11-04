<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormModelProcessor;

use Miliooo\Messaging\Form\FormModel\ReplyMessageInterface;
use Miliooo\Messaging\Manager\NewMessageManagerInterface;
use Miliooo\Messaging\Builder\Reply\ReplyBuilder;

/**
 * Description of NewReplyDefaultProcessor
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewReplyDefaultProcessor implements NewReplyFormProcessorInterface
{
    protected $replyBuilder;
    protected $newMessageManager;

    /**
     * Constructor.
     *
     * @param ReplyBuilder               $replyBuilder
     * @param NewMessageManagerInterface $newMessageManager
     */
    public function __construct(ReplyBuilder $replyBuilder, NewMessageManagerInterface $newMessageManager)
    {
        $this->replyBuilder = $replyBuilder;
        $this->newMessageManager = $newMessageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ReplyMessageInterface $formModel)
    {
        $this->callSettersForTheBuilderFromFormModel($formModel);
        $thread = $this->replyBuilder->build();
        $message = $thread->getLastMessage();
        $this->newMessageManager->saveNewReply($message);
    }

    /**
     * Populates the builder setters with the form model
     *
     * @param ReplyMessageInterface $formModel
     */
    protected function callSettersForTheBuilderFromFormModel($formModel)
    {
        $this->replyBuilder->setThread($formModel->getThread());
        $this->replyBuilder->setSender($formModel->getSender());
        $this->replyBuilder->setBody($formModel->getBody());
        $this->replyBuilder->setCreatedAt($formModel->getCreatedAt());
    }
}
