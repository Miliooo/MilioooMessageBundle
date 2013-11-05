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
        return array($this->recipients);
    }

    /**
     * Sets the recipient of the message
     *
     * @param ParticipantInterface $recipient
     */
    public function setRecipients(ParticipantInterface $recipient)
    {
        $this->recipients = $recipient;
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
