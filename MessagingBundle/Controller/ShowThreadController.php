<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Miliooo\Messaging\ThreadProvider\SecureThreadProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller for showing a single thread.
 *
 * This controller is responsible for showing a single thread to the user.
 * It can only show the thread if the user has permissions to view that thread.
 *
 */
class ShowThreadController
{
    protected $threadProvider;
    protected $templating;
    protected $securityToken;

    /**
     * Constructor.
     *
     * @param SecureThreadProviderInterface $threadProvider A secure thread provider instance
     * @param EngineInterface               $templating     A templating engine
     * @param TokenInterface                $securityToken  A security token instance
     */
    public function __construct(SecureThreadProviderInterface $threadProvider, EngineInterface $templating, TokenInterface $securityToken)
    {
        $this->threadProvider = $threadProvider;
        $this->templating = $templating;
        $this->securityToken = $securityToken;
    }

    /**
     * Shows a single thread and allows the user to reply on it
     *
     * @param Request $request  The current request
     * @param integer $threadId The unique thread id
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function showAction(Request $request, $threadId)
    {
        $loggedInUser = $this->securityToken->getUser();
        $thread = $this->threadProvider->findThreadForParticipant($loggedInUser, $threadId);
        if (!$thread) {
            throw new NotFoundHttpException('Thread not found');
        }

        $twig = 'MilioooMessagingBundle:ShowThread:show_thread.html.twig';

        return $this->templating->renderResponse($twig, array('thread' => $thread));
    }
}
