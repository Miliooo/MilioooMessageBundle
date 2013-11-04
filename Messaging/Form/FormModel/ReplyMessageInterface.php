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
 * The interface reply message form models should implement.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ReplyMessageInterface extends NewMessageInterface
{
    /**
     * Sets the thread where we add the reply
     *
     * @param ThreadInterface $thread the thread where we add the reply
     */
    public function setThread(ThreadInterface $thread);

    /**
     * Gets the thread where we add the reply
     *
     * @return ThreadInterface The thread where we add the reply
     */
    public function getThread();
}
