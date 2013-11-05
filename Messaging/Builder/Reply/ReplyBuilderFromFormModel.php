<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Reply;

use Miliooo\Messaging\Form\FormModel\ReplyMessageInterface;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Builds a reply from a reply message interface instance
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyBuilderFromFormModel extends ReplyBuilder
{
    /**
     * Builds a reply from a reply message interface instance
     *
     * @param ReplyMessageInterface $replyModel
     *
     * @return ThreadInterface The updated thread object with the new message
     */
    public function buildReply(ReplyMessageInterface $replyModel)
    {
        $this->setThread($replyModel->getThread());
        $this->setSender($replyModel->getSender());
        $this->setBody($replyModel->getBody());
        $this->setCreatedAt($replyModel->getCreatedAt());

        return $this->build();
    }
}
