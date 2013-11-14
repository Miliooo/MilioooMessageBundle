<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Manager;

use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Miliooo\Messaging\Event\MilioooMessagingEvents;
use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\Event\ReadStatusMessageEvent;
use Miliooo\Messaging\ValueObjects\ReadStatus;

/**
 * Read status manager who dispatches read status updates events.
 *
 * This class uses the decorator pattern.
 * The read status manager returns an array with updated messages.
 * This class uses this array to dispatch events for these updated messages.
 * Since it implements the same interface it also has to return this array.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReadStatusManagerEventAware implements ReadStatusManagerInterface
{
    /**
     * A read status manager instance.
     *
     * @var ReadStatusManagerInterface
     */
    private $readStatusManager;

    /**
     * An event dispatcher instance.
     *
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * Constructor.
     *
     * @param ReadStatusManagerInterface $readStatusManager
     * @param EventDispatcherInterface   $eventDispatcher
     */
    public function __construct(
        ReadStatusManagerInterface $readStatusManager,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->readStatusManager = $readStatusManager;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function updateReadStatusForMessageCollection(
        ReadStatus $updatedReadStatus,
        ParticipantInterface $participant,
        $messages = []
    ) {

        $updatedMessages = $this->readStatusManager->updateReadStatusForMessageCollection(
            $updatedReadStatus,
            $participant,
            $messages
        );

        $this->maybeDispatchMessages($updatedMessages, $participant);

        return $updatedMessages;
    }

    /**
     * Looping over the updated messages (if any) and dispatching them.
     *
     * @param MessageInterface[]|[] $updatedMessages An array with message interfaces or an empty array if none updated
     * @param ParticipantInterface  $participant     The participant for whom we updated the messages
     */
    protected function maybeDispatchMessages($updatedMessages, $participant)
    {
        foreach ($updatedMessages as $message) {
            $this->dispatchMessage($message, $participant);
        }
    }

    /**
     * Dispatch a read status changed event.
     *
     * @param MessageInterface     $message     The message whom read status is changed for the participant
     * @param ParticipantInterface $participant The participant for whom the read status is changed.
     */
    protected function dispatchMessage(MessageInterface $message, ParticipantInterface $participant)
    {
        $metaForParticipant = $message->getMessageMetaForParticipant($participant);
        $readStatus = $metaForParticipant->getReadStatus();
        $oldReadStatus = $metaForParticipant->getPreviousReadStatus();

        $event = new ReadStatusMessageEvent($message, $participant, $oldReadStatus, $readStatus);
        $this->eventDispatcher->dispatch(MilioooMessagingEvents::READ_STATUS_CHANGED, $event);
    }
}
