<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Specifications;

use Miliooo\Messaging\User\ParticipantInterface;

/**
 * This specification is responsible for deciding if a given user can send a message to a given recipient.
 *
 * The default implementation is to just return true.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface CanMessageRecipientInterface
{
    CONST ERROR_RECIPIENT_BANNED = 'validate.recipient_banned';
    CONST ERROR_RECIPIENT_BLOCKED_SENDER = 'validate.recipient_blocked_sender';
    CONST ERROR_RECIPIENT_INACTIVE = 'validate_recipient_no_longer_active';

    /**
     * Decides whether the current participant can send a message to the given recipient.
     *
     * @param ParticipantInterface $currentParticipant The current participant
     * @param ParticipantInterface $recipient          The recipient
     *
     * @return boolean true if the participant can send a message to the recipient, false otherwise
     */
    public function isSatisfiedBy(ParticipantInterface $currentParticipant, ParticipantInterface $recipient);

    /**
     * Returns an error message when isSatisfiedBy returns false.
     *
     * This function makes it possible to return a custom error message for the canMessageRecipientValidator.
     *
     * There can be many reasons why you can't message a recipient.
     * Since the validator wants to output a correct message why you can't send a message to someone it needs
     * this information.
     *
     * To make it possible to translate those reasons we return a placeholder that gets translated in
     * validation.{language}.yml. For this we use one of the constants.
     *
     * If there are more generic constants that needs to be add you can do a pull request for this.
     * That way we can update the constants and the translations.
     *
     * @return string|null An error message when isSatisfiedBy returns false, or null otherwise.
     */
    public function getErrorMessage();
}
