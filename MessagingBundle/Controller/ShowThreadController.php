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
use Miliooo\Messaging\ThreadProvider\ThreadProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Controller for showing a single thread.
 *
 * This controller is responsible for showing a single thread to the user.
 * It can only show the thread if the user has permissions to view that thread. *
 *
 */
class ShowThreadController
{
    protected $threadProvider;
    protected $templating;

    /**
     * Constructor.
     *
     * @param ThreadProviderInterface $threadProvider A thread provider instance
     * @param EngineInterface         $templating     A templating engine
     */
    public function __construct(ThreadProviderInterface $threadProvider, EngineInterface $templating)
    {
        $this->threadProvider = $threadProvider;
        $this->templating = $templating;
    }

    /**
     * Shows a single thread and allows the user to reply on it
     *
     * @param Request $request
     * @param integer $threadId
     * 
     * @throws NotFoundHttpException
     */
    public function showAction(Request $request, $threadId)
    {
       $thread = $this->threadProvider->findThreadById($threadId);
        if (!$thread) {
            throw new NotFoundHttpException('Thread not found');
        }

        var_dump($thread);
        exit();
    }
}
