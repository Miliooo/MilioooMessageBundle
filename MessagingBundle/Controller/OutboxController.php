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
use Miliooo\Messaging\ThreadProvider\Folder\OutboxProviderPagerFantaInterface;
use Miliooo\Messaging\User\ParticipantProviderInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * The outbox controller is responsible for showing outbox threads for the logged in user.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class OutboxController
{
    protected $templating;
    protected $outboxProvider;
    protected $participantProvider;

    /**
     * Constructor.
     *
     * @param EngineInterface                   $templating
     * @param OutboxProviderPagerFantaInterface $outboxProvider
     * @param ParticipantProviderInterface      $participantProvider
     */
    public function __construct(
        EngineInterface $templating,
        OutboxProviderPagerFantaInterface $outboxProvider,
        ParticipantProviderInterface $participantProvider
    ) {
        $this->templating = $templating;
        $this->outboxProvider = $outboxProvider;
        $this->participantProvider = $participantProvider;
    }

    /**
     * Shows the outbox threads for the logged in user
     *
     * @param integer $page The page we are on
     *
     * @return Response
     */
    public function showAction($page)
    {
        $loggedInUser = $this->participantProvider->getAuthenticatedParticipant();
        $pagerfanta = $this->outboxProvider->getOutboxThreadsPagerfanta($loggedInUser, $page);
        $view = 'MilioooMessagingBundle:Folders:outbox.html.twig';

        return $this->templating->renderResponse($view, ['pagerfanta' => $pagerfanta]);
    }
}
