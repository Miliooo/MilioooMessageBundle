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
use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\Notifications\UnreadMessagesProviderInterface;
use Twig\TwigFunction;

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
     * An unread messages provider instance.
     *
     * @var UnreadMessagesProviderInterface
     */
    protected $unreadMessagesProvider;

    /**
     * Constructor.
     *
     * @param ParticipantProviderInterface $participantProvider
     * @param UnreadMessagesProviderInterface $unreadMessagesProvider
     */
    public function __construct(
        ParticipantProviderInterface $participantProvider,
        UnreadMessagesProviderInterface $unreadMessagesProvider
    ) {
        $this->participantProvider = $participantProvider;
        $this->unreadMessagesProvider = $unreadMessagesProvider;
    }

    /**
     * Returns a list of functions to add to the existing list.
     *
     * @return array An array of functions
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('miliooo_messaging_is_new_read', [$this, 'isMessageNewRead']),
            new TwigFunction('miliooo_messaging_thread_unread_count', [$this, 'getThreadUnreadCount']),
            new TwigFunction('miliooo_messaging_unread_messages_count', [$this, 'getUnreadMessagesCount']),
        ];
    }

    /**
     * Checks if the message needs a new read label.
     *
     * @param MessageInterface $message
     *
     * @return boolean true if it's a new read message for the logged in user
     *                 false if no message meta found for the logged in user
     *                 or not a new read message for the logged in user
     */
    public function isMessageNewRead(MessageInterface $message)
    {
        $currentUser = $this->participantProvider->getAuthenticatedParticipant();

        $messageMeta = $message->getMessageMetaForParticipant($currentUser);

        if ($messageMeta) {
            return (
                // if this is not null this means we changed the read status,
                // Since we changed it in read it was unread before
                $messageMeta->getPreviousReadStatus() !== null
                &&
                //the current read status is already read, this is because we marked this message as read
                // just before sending it to the output.
                $messageMeta->getReadStatus() === MessageMetaInterface::READ_STATUS_READ
            );
        }

        //this can happen if you let non participants (eg admin) see a thread
        return false;
    }

    /**
     * Checks how many unread messages a thread has for the logged in participant
     *
     * @param ThreadInterface $thread The thread we check
     *
     * @return integer The number of unread messages for this thread for the participant
     */
    public function getThreadUnreadCount(ThreadInterface $thread)
    {
        $currentUser = $this->participantProvider->getAuthenticatedParticipant();
        $threadMeta = $thread->getThreadMetaForParticipant($currentUser);

        //the current user is not part of this thread conversation let's just return zero
        if (!$threadMeta) {
            return 0;
        }

        return $threadMeta->getUnreadMessageCount();
    }

    /**
     * Gets the total unread messages count for the logged in user.
     *
     * @return integer The unread messages count for the logged in user
     */
    public function getUnreadMessagesCount()
    {
        $currentUser = $this->participantProvider->getAuthenticatedParticipant();

        return $this->unreadMessagesProvider->getUnreadMessageCountForParticipant($currentUser);
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
