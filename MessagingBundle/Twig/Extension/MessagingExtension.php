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

/**
 * Twig extension class
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
        /*
         * Errors out https://github.com/Elao/WebProfilerExtraBundle/issues/35
        return [
            [new \Twig_SimpleFunction('miliooo_messaging_is_new_read', [$this, 'isMessageNewRead'])]
        ]; */
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

        if($messageMeta) {
            return $messageMeta->getNewRead();
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
