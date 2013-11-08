<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormModel;

use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Implementation of the ReplyMessageInterface.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyMessage extends AbstractNewMessage implements ReplyMessageInterface
{
    /**
     * The thread we reply to
     *
     * @var ThreadInterface
     */
    protected $thread;

    /**
     * {@inheritdoc}
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * {@inheritdoc}
     */
    public function setThread(ThreadInterface $thread)
    {
        $this->thread = $thread;
    }
}
