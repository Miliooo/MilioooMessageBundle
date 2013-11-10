<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Model;

use Miliooo\Messaging\Form\FormModel\NewThreadInterface;
use Miliooo\Messaging\Model\ThreadMetaInterface;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * ThreadBuilderModel.
 *
 * The thread builder model contains all the data needed to build a new thread object.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadBuilderModel extends AbstractMessageBuilderModel
{
    /**
     * @var NewThreadInterface
     */
    protected $newThreadModel;

    /**
     * Constructor.
     *
     * @param NewThreadInterface $newThreadModel
     */
    public function __construct(NewThreadInterface $newThreadModel)
    {
        $this->newThreadModel = $newThreadModel;

        parent::__construct($newThreadModel);
    }

    /**
     * {@inheritdoc}
     */
    protected function processExtra()
    {
        $this->addThreadData('subject', $this->newThreadModel->getSubject());
        $this->addThreadData('createdAt', $this->newThreadModel->getCreatedAt());
        $this->addThreadData('createdBy', $this->newThreadModel->getSender());
        $this->addThreadMeta(self::ALL, 'status', ThreadMetaInterface::STATUS_ACTIVE);
    }

    /**
     * Gets the recipients of the new message
     *
     * @return ParticipantInterface[] Array with participants
     */
    public function getRecipients()
    {
        return $this->newThreadModel->getRecipients();
    }
}
