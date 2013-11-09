<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Miliooo\Messaging\ThreadProvider\Folder\InboxProviderInterface;
use Miliooo\Messaging\User\ParticipantProviderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * The inbox controller is responsible for showing inbox threads to the logged in user.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class InboxController
{
    protected $participantProvider;
    protected $inboxProvider;
    protected $templating;

    /**
     * Constructor.
     *
     * @param ParticipantProviderInterface $participantProvider
     * @param InboxProviderInterface       $inboxProvider
     * @param EngineInterface              $templating
     */
    public function __construct(
        ParticipantProviderInterface $participantProvider,
        InboxProviderInterface $inboxProvider,
        EngineInterface $templating
    ) {
        $this->participantProvider = $participantProvider;
        $this->inboxProvider = $inboxProvider;
        $this->templating = $templating;
    }

    /**
     * Shows inbox threads for the logged in user
     *
     * @return Response
     */
    public function showAction()
    {
        $loggedInUser = $this->participantProvider->getAuthenticatedParticipant();
        $threads = $this->inboxProvider->getInboxThreads($loggedInUser);
        $view = 'MilioooMessagingBundle:Folders:inbox.html.twig';

        return $this->templating->renderResponse($view, ['threads' => $threads]);
    }
}
