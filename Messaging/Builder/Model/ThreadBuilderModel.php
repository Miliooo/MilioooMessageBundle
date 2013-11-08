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

/**
 * Description of ExtendedBuilderModel
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadBuilderModel extends AbstractMessageBuilderModel
{
    protected $newThreadModel;

    public function __construct(NewThreadInterface $newThreadModel)
    {
        $this->newThreadModel = $newThreadModel;

        parent::__construct($newThreadModel);
    }

    protected function processExtra()
    {
        $this->addThreadData('subject', $this->newThreadModel->getSubject());
        $this->addThreadData('createdAt', $this->newThreadModel->getCreatedAt());
        $this->addThreadData('createdBy', $this->newThreadModel->getSender());
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
