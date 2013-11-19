<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Twig\Extension;

use Miliooo\Messaging\User\ParticipantProviderInterface;
use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\Model\MessageMetaInterface;

/**
 * Twig extension class
 *
 * There is a problem with using the securityToken directly here since it does not get populated in time.
 * http://stackoverflow.com/questions/18770467/
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MessagingExtension extends \Twig_Extension
{
    /**
     * A participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * Constructor.
     *
     * @param ParticipantProviderInterface $participantProvider A participant Provider Instance
     */
    public function __construct(ParticipantProviderInterface $participantProvider)
    {
        $this->participantProvider = $participantProvider;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            'miliooo_messaging_is_new_read' => new \Twig_Function_Method($this, 'isMessageNewRead')
        ];
    }

    /**
     * @param MessageInterface $message
     *
     * @return boolean true if it's a new read message for the logged in user
     *                 false if no messagemeta found for the logged in user
     *                 or not a new read message for the logged in user
     */
    public function isMessageNewRead(MessageInterface $message)
    {
        $currentUser = $this->participantProvider->getAuthenticatedParticipant();

        $messageMeta = $message->getMessageMetaForParticipant($currentUser);

        if ($messageMeta) {
            return (
                //since it's not null the read status has changed
                $messageMeta->getPreviousReadStatus() !== null
                &&
                //the recent read status is read
                $messageMeta->getReadStatus() === MessageMetaInterface::READ_STATUS_READ
            );
        }

        //this can happen if you let non participants (eg admin) see a thread
        return false;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'miliooo_messaging';
    }
}
