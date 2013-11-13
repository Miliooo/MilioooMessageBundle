<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Model;

use Miliooo\Messaging\User\ParticipantInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * The message Interface
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface MessageInterface extends BuilderInterface
{
    /**
     * Gets the unique id of the message
     *
     * @return integer The unique id of the message
     */
    public function getId();

    /**
     * Gets the creation time of the message
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Sets the creation time of the message
     *
     * @param \DateTime $createdAt The time the message was created
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Gets the body of the message
     *
     * @return string The body
     */
    public function getBody();

    /**
     * Sets the body of the message
     *
     * @param string $body The body
     */
    public function setBody($body);

    /**
     * Sets the sender of the message
     *
     * @param ParticipantInterface $sender The sender of the message
     */
    public function setSender(ParticipantInterface $sender);

    /**
     * Gets the sender of the message
     *
     * @return ParticipantInterface
     */
    public function getSender();

    /**
     * Adds message meta to the messageMeta collection
     *
     * @param MessageMetaInterface $messageMeta
     */
    public function addMessageMeta(MessageMetaInterface $messageMeta);

    /**
     * Returns an array collection with message meta
     *
     * @return ArrayCollection An ArrayCollection of messageMeta
     */
    public function getMessageMeta();

    /**
     * Gets message meta for the given participant
     *
     * @param ParticipantInterface $participant The participant
     *
     * @return MessageMetaInterface|null The messagemeta or null when not found
     */
    public function getMessageMetaForParticipant(ParticipantInterface $participant);

    /**
     * Sets the thread this message belongs to
     *
     * @param ThreadInterface $thread The thread this message belongs to
     */
    public function setThread(ThreadInterface $thread);

    /**
     * Gets the thread this message belongs to
     *
     * @return ThreadInterface the thread this message belongs to
     */
    public function getThread();
}
