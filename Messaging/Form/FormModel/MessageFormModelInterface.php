<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormModel;

use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Description of MessageFormModelInterface
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface MessageFormModelInterface
{
    /**
     * Gets the sender of the message.
     *
     * If we create a new thread also the creator of the thread
     *
     * @return ParticipantInterface
     */
    public function getSender();

    /**
     * Sets the sender of the message
     *
     * If we create a new thread this is also the creator of the thread
     *
     * @param ParticipantInterface $sender
     */
    public function setSender(ParticipantInterface $sender);

    /**
     * Sets the creation date of the message
     *
     * If we create a new thread this is also the creation time of the thread
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Gets the creation time of the thread and message
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Sets the body of the message
     *
     * @param string $body
     */
    public function setBody($body);

    /**
     * Gets the body of the message
     * 
     * @return string
     */
    public function getBody();
}
