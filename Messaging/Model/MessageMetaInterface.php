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
use Miliooo\Messaging\ValueObjects\ReadStatus;

/**
 * Interface for the message meta
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface MessageMetaInterface extends BuilderInterface
{

    /**
     * The participant has never read the message
     */
    const READ_STATUS_NEVER_READ = 0;

    /**
     * The participant has read the message but marked it as unread
     */
    const READ_STATUS_MARKED_UNREAD = 1;

    /**
     * The participant has read the message
     */
    const READ_STATUS_READ = 2;


    /**
     * Sets the participant to the message meta
     *
     * @param ParticipantInterface $participant The participant we add to the meta
     */
    public function setParticipant(ParticipantInterface $participant);

    /**
     * Gets the participant from the meta
     *
     * @return ParticipantInterface The participant from the meta
     */
    public function getParticipant();

    /**
     * Sets the read status of the message for the given participant.
     *
     * @param ReadStatus $readStatus
     */
    public function setReadStatus(ReadStatus $readStatus);

    /**
     * Gets the read status of the message for the participant of this meta
     *
     * @return integer one of the read statuses constants
     */
    public function getReadStatus();

    /**
     * Sets the message this message meta belongs to
     *
     * @param MessageInterface $message The message this meta belongs to
     */
    public function setMessage(MessageInterface $message);

    /**
     * Gets the message
     *
     * @return MessageInterface The message this meta belongs to
     */
    public function getMessage();



    /**
     * Gets the previous read status of a message.
     *
     * Since we update the read status before we display the messages to the user we need to check the previous read
     * status to see if a message is a new read or not
     *
     * @return $boolean
     */
    public function getPreviousReadStatus();
}
