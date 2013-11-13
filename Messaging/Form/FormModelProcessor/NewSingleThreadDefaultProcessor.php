<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormModelProcessor;

use Miliooo\Messaging\Form\FormModel\NewThreadInterface;
use Miliooo\Messaging\Builder\Message\NewThreadBuilder;
use Miliooo\Messaging\Manager\NewMessageManagerInterface;
use Miliooo\Messaging\Builder\Model\ThreadBuilderModel;

/**
 * Processes a single thread.
 *
 * This processor processes a single thread by storing it to the database.
 * By single thread we mean that only one thread object gets created, it can have
 * multiple recipients.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewSingleThreadDefaultProcessor implements NewThreadFormProcessorInterface
{
    protected $newThreadBuilder;
    protected $newMessageManager;

    /**
     * Constructor.
     *
     * @param NewThreadBuilder           $newThreadBuilder  A new thread builder instance
     * @param NewMessageManagerInterface $newMessageManager A new message manager instance
     */
    public function __construct(NewThreadBuilder $newThreadBuilder, NewMessageManagerInterface $newMessageManager)
    {
        $this->newThreadBuilder = $newThreadBuilder;
        $this->newMessageManager = $newMessageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function process(NewThreadInterface $formModel)
    {
        $threadBuilderModel = new ThreadBuilderModel($formModel);
        $thread = $this->newThreadBuilder->build($threadBuilderModel);
        $message = $thread->getLastMessage();
        $this->newMessageManager->saveNewThread($message);
    }
}
