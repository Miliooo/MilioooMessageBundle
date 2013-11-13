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
 * The interface class used by the thread model
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ThreadInterface extends BuilderInterface
{
    /**
     * Gets the unique id of the thread.
     *
     * @return integer The unique id
     */
    public function getId();

    /**
     * Sets the subject of the thread.
     *
     * @param string $subject The subject of the thread
     */
    public function setSubject($subject);

    /**
     * Gets the subject of the thread.
     *
     * @return string The subject of the thread
     */
    public function getSubject();

    /**
     * Sets the participant who created the thread.
     *
     * @param ParticipantInterface $participant The participant who created the thread
     */
    public function setCreatedBy(ParticipantInterface $participant);

    /**
     * Gets the participant who created the thread.
     *
     * @return ParticipantInterface The participant who created the thread
     */
    public function getCreatedBy();

    /**
     * Sets the datetime when the thread was created
     *
     * @param \DateTime $createdAt The creation datetime of the thread
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Gets the datetime when the thread was created.
     */
    public function getCreatedAt();

    /**
     * Adds a message to the thread
     *
     * @param MessageInterface $message
     */
    public function addMessage(MessageInterface $message);

    /**
     * Gets all the messages contained in the thread.
     *
     * @return ArrayCollection
     */
    public function getMessages();

    /**
     * Gets the first message of the thread.
     *
     * @return MessageInterface The first message of the thread
     */
    public function getFirstMessage();

    /**
     * Gets the last message of the thread.
     *
     * @return MessageInterface The last message of the thread
     */
    public function getLastMessage();

    /**
     * Sets the last message.
     *
     * We set the last message of a thread because we use it in the folders overview.
     * If we don't do this we will loop over the whole array collection.
     *
     * There is always the trade off between denormalizing or not, if you don't want this to be denormalized
     * you can use $this->messages->last();
     *
     * @param MessageInterface $message
     */
    public function setLastMessage(MessageInterface $message);

    /**
     * Adds thread meta to the thread meta collection
     *
     * @param ThreadMetaInterface $threadMeta
     */
    public function addThreadMeta(ThreadMetaInterface $threadMeta);

    /**
     * Returns an array collection with thread meta
     *
     * @return ArrayCollection An ArrayCollection of threadmeta
     */
    public function getThreadMeta();

    /**
     * Gets thread meta for the given participant
     *
     * @param ParticipantInterface $participant The participant
     *
     * @return ThreadMetaInterface
     */
    public function getThreadMetaForParticipant(ParticipantInterface $participant);

    /**
     * Gets all the participants for the current thread
     *
     * This loops over the threadmetas and collects all the participants.
     * There is no method to add an participant because we don't map this in the database
     *
     * If we want to add a participant we need to create the threadmeta
     * and add the participant there.
     *
     * @return ParticipantInterface[] An array with participants
     */
    public function getParticipants();

    /**
     * Checks if the given participant is a participant of the thread
     *
     * @param ParticipantInterface $participant The participant we check
     *
     * @return boolean true if participant, false otherwise
     */
    public function isParticipant(ParticipantInterface $participant);

    /**
     * Gets th participants besides the given participant
     *
     * @param ParticipantInterface $participant The participant to exclude
     *
     * @return ParticipantInterface[] Array of participants
     */
    public function getOtherParticipants(ParticipantInterface $participant);
}
