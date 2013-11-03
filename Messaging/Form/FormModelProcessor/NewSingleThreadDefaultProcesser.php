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
use Miliooo\Messaging\Builder\Thread\NewThread\NewThreadBuilder;
use Miliooo\Messaging\Manager\NewMessageManagerInterface;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Description of NewThreadFormProcessor
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewSingleThreadDefaultProcesser implements NewThreadFormProcessorInterface
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
        $thread = $this->buildNewThreadFromFormModel($formModel);
        $message = $thread->getLastMessage();
        $this->newMessageManager->saveNewThread($message);
    }

    /**
     * Builds a new thread from the form model
     *
     * @param NewThreadInterface $formModel The form model
     *
     * @return ThreadInterface The new build thread
     */
    protected function buildNewThreadFromFormModel(NewThreadInterface $formModel)
    {
        $this->newThreadBuilder->setBody($formModel->getBody());
        $this->newThreadBuilder->setCreatedAt($formModel->getCreatedAt());
        $this->newThreadBuilder->setRecipients($formModel->getRecipients());
        $this->newThreadBuilder->setSender($formModel->getSender());
        $this->newThreadBuilder->setSubject($formModel->getSubject());

        return $this->newThreadBuilder->build();
    }
}
