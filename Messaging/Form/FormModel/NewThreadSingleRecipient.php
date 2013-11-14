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
 * Implementation of NewThreadInterface for a single recipient
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadSingleRecipient extends AbstractNewMessage implements NewThreadInterface
{
    /**
     * Recipients of the message
     *
     * @var ParticipantInterface
     */
    protected $recipients;

    /**
     * Subject of the message
     *
     * @var string
     */
    protected $subject;

    /**
     * {@inheritdoc}
     */
    public function getRecipients()
    {
        if(!empty($this->recipients))
        {
            return [$this->recipients];
        }
        return [];
    }

    /**
     * Sets the recipient of the message.
     *
     * Setting the recipient is not part of the interface. We only care that we can call a function
     * getRecipients which returns an array of recipients
     *
     * @param ParticipantInterface $recipient
     */
    public function setRecipient(ParticipantInterface $recipient)
    {
        $this->recipients = $recipient;
    }

    /**
     * Gets the recipient of the message.
     *
     * This function is not part of the interface it's only used to populate the form.
     * The function getRecipients is part of the interface and expects an array.
     *
     */
    public function getRecipient()
    {
        return $this->recipients;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }
}
