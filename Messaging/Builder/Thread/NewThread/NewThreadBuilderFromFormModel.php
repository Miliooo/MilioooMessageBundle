<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Thread\NewThread;

use Miliooo\Messaging\Form\FormModel\NewThreadInterface;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * New thread builder from form model
 *
 * @todo make this the default builder, make the setters protected or remove them
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadBuilderFromFormModel extends NewThreadBuilder
{
    /**
     * Builds a thread from a newthreadinterface instance
     * 
     * @param NewThreadInterface $newThreadModel
     *
     * @return ThreadInterface
     */
    public function buildThread(NewThreadInterface $newThreadModel)
    {
        $this->setBuilderAttributes($newThreadModel);

        return $this->build();
    }

    /**
     * Sets the builder attributes by calling their setters
     *
     * @param NewThreadInterface $newThreadModel
     */
    protected function setBuilderAttributes(NewThreadInterface $newThreadModel)
    {
        $this->setSender($newThreadModel->getSender());
        $this->setRecipients($newThreadModel->getRecipients());
        $this->setSubject($newThreadModel->getSubject());
        $this->setBody($newThreadModel->getBody());
        $this->setCreatedAt($newThreadModel->getCreatedAt());
    }
}
