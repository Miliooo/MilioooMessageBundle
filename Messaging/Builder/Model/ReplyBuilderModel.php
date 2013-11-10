<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Model;

use Miliooo\Messaging\Form\FormModel\ReplyMessageInterface;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * The reply builder model contains all the data needed to build a new reply.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReplyBuilderModel extends AbstractMessageBuilderModel
{
    /**
     * @var ReplyMessageInterface
     */
    private $replyModel;

    /**
     * @param ReplyMessageInterface $replyModel
     */
    public function __construct(ReplyMessageInterface $replyModel)
    {
        $this->replyModel = $replyModel;
        parent::__construct($replyModel);
    }

    /**
     * Gets the thread where we reply on
     *
     * @return ThreadInterface
     */
    public function getThread()
    {
        return $this->replyModel->getThread();
    }

    /**
     * {@inheritdoc}
     */
    protected function processExtra()
    {
        //nothing more to process here
    }
}
