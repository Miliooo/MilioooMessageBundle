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
use Miliooo\Messaging\User\ParticipantProviderInterface;
use Symfony\Component\HttpFoundation\Response;
use Miliooo\Messaging\ThreadProvider\Folder\InboxProviderPagerFantaInterface;

/**
 * The inbox controller is responsible for showing inbox threads to the logged in user.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class InboxController
{
    /**
     * A participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * An inbox provider pagerfanta instance.
     *
     * @var InboxProviderPagerFantaInterface
     */
    protected $inboxProvider;

    /**
     * A templating engine instance.
     *
     * @var EngineInterface
     */
    protected $templating;

    /**
     * Constructor.
     *
     * @param ParticipantProviderInterface     $participantProvider
     * @param InboxProviderPagerFantaInterface $inboxProvider
     * @param EngineInterface                  $templating
     */
    public function __construct(
        ParticipantProviderInterface $participantProvider,
        InboxProviderPagerFantaInterface $inboxProvider,
        EngineInterface $templating
    ) {
        $this->participantProvider = $participantProvider;
        $this->inboxProvider = $inboxProvider;
        $this->templating = $templating;
    }

    /**
     * Shows inbox threads for the logged in user
     *
     * @param integer $page The page we are on
     *
     * @return Response
     */
    public function showAction($page)
    {
        $loggedInUser = $this->participantProvider->getAuthenticatedParticipant();
        $pagerfanta = $this->inboxProvider->getInboxThreadsPagerfanta($loggedInUser, $page);
        $view = 'MilioooMessagingBundle:Folders:inbox.html.twig';

        return $this->templating->renderResponse($view, ['pagerfanta' => $pagerfanta]);
    }
}
