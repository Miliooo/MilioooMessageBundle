<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Model;

use Miliooo\Messaging\Model\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * The interface for the threadmeta class
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ThreadMetaInterface
{
    /**
     * Gets the unique id of the thread meta
     *
     * @return integer
     */
    public function getId();

    /**
     * Gets the participant of the thread meta
     *
     * @return ParticipantInterface
     */
    public function getParticipant();

    /**
     * Sets the participant of the thread meta
     *
     * @param ParticipantInterface $participant The participant
     */
    public function setParticipant(ParticipantInterface $participant);

    /**
     * Sets the thread for this thread meta
     *
     * @param ThreadInterface $thread The thread this meta belongs to
     */
    public function setThread(ThreadInterface $thread);

    /**
     * Gets the thread for this thread meta
     *
     * @return ThreadInterface the thread this meta belongs to
     */
    public function getThread();

    /**
     * Gets if the thread is deleted by the participant.
     *
     * It returns true if the thread is deleted by the participant,
     * false if the thread is not deleted by the participant
     *
     * return boolean true if thread deleted for the participant, false otherwise
     */
    public function getIsDeleted();

    /**
     * Sets the delete status of the thread for the given participant.
     *
     * True marks this thread as deleted for the participant
     * False marks this thread as not deleted for the participant
     *
     * @param boolean $boolean true if deleted for participant, false otherwise
     */
    public function setIsDeleted($boolean);

    /**
     * Gets the datetime when the participant has written his last message for
     * the given thread
     *
     * @return \DateTime
     */
    public function getLastParticipantMessageDate();

    /**
     * Sets the datetime when the participant has written his last message for the given thread
     *
     * @param \DateTime $dateTime DateTime of participant's last message
     */
    public function setLastParticipantMessageDate(\DateTime $dateTime);

    /**
     * Gets the date time of the last message written by another participant
     *
     * @return \DateTime datetime of the last message written by another participant
     */
    public function getLastMessageDate();

    /**
     * Sets the date of the last message written by another participant
     *
     * @param \DateTime $lastMessageDate datetime of the last message by another participant
     */
    public function setLastMessageDate(\DateTime $lastMessageDate);
}
