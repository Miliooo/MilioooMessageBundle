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
use Miliooo\Messaging\ThreadProvider\Folder\ArchivedProviderPagerFantaInterface;

/**
 * The archived controller is responsible for showing archived threads to the logged in user.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ArchivedController
{
    /**
     * A participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * An archived provider pagerfanta instance.
     *
     * @var ArchivedProviderPagerFantaInterface
     */
    protected $archivedProvider;

    /**
     * A templating engine instance.
     *
     * @var EngineInterface
     */
    protected $templating;

    /**
     * Constructor.
     *
     * @param ParticipantProviderInterface $participantProvider
     * @param ArchivedProviderPagerFantaInterface $archivedProvider
     * @param EngineInterface $templating
     */
    public function __construct(
        ParticipantProviderInterface $participantProvider,
        ArchivedProviderPagerFantaInterface $archivedProvider,
        EngineInterface $templating
    ) {
        $this->participantProvider = $participantProvider;
        $this->archivedProvider = $archivedProvider;
        $this->templating = $templating;
    }

    /**
     * Shows archived threads for the logged in user
     *
     * @param integer $page The page we are on
     *
     * @return Response
     */
    public function showAction($page)
    {
        $loggedInUser = $this->participantProvider->getAuthenticatedParticipant();
        $pagerfanta = $this->archivedProvider->getArchivedThreadsPagerfanta($loggedInUser, $page);
        $view = 'MilioooMessagingBundle:Folders:archived.html.twig';

        return $this->templating->renderResponse($view, ['pagerfanta' => $pagerfanta]);
    }
}
