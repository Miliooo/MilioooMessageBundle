<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Controller;

use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\HttpFoundation\Request;
use Miliooo\Messaging\Manager\ThreadStatusManagerInterface;
use Miliooo\Messaging\ThreadProvider\SecureThreadProviderInterface;
use Miliooo\Messaging\User\ParticipantProviderInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\ValueObjects\ThreadStatus;
use Miliooo\Messaging\Model\ThreadMetaInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProviderInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * This class is responsible for handling thread actions.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadActionsController
{
    /**
     * A thread status manager interface.
     *
     * @var ThreadStatusManagerInterface
     */
    private $threadStatusManager;

    /**
     * A secure thread provider.
     *
     * @var SecureThreadProviderInterface
     */
    private $threadProvider;

    /**
     * A participant provider instance.
     *
     * @var ParticipantProviderInterface
     */
    private $participantProvider;

    /**
     * A flash message provider instance.
     *
     * @var FlashMessageProviderInterface
     */
    private $flashMessageProvider;

    /**
     * A router instance.
     *
     * @var RouterInterface
     */
    private $router;
    /**
     * @param ThreadStatusManagerInterface  $threadStatusManager
     * @param SecureThreadProviderInterface $threadProvider
     * @param ParticipantProviderInterface  $participantProvider
     * @param FlashMessageProviderInterface $flashMessageProvider
     * @param RouterInterface               $router
     */
    public function __construct(
        ThreadStatusManagerInterface $threadStatusManager,
        SecureThreadProviderInterface $threadProvider,
        ParticipantProviderInterface $participantProvider,
        FlashMessageProviderInterface $flashMessageProvider,
        RouterInterface $router
    ) {
        $this->threadStatusManager = $threadStatusManager;
        $this->threadProvider = $threadProvider;
        $this->participantProvider = $participantProvider;
        $this->flashMessageProvider = $flashMessageProvider;
        $this->router = $router;
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function threadAction(Request $request)
    {
        $threadIds = $this->getThreadIdsFromRequest($request);
        $threadAction = $this->getThreadActionFromRequest($request);
        $loggedInUser = $this->participantProvider->getAuthenticatedParticipant();

        //no action needed here... probably no threads selected but pressed submit
        if (!is_array($threadIds) || !$threadAction) {
            //the user has not selected any threads
            if (!is_array($threadIds)) {
                //add flash messsage that they have no threads selected
                $this->flashMessageProvider->addFlash(FlashMessageProviderInterface::TYPE_ERROR, 'flash.thread_updates.no_threads_selected');
            } else {
                //there is an error with the threadAction but this should not happen unless we screw up
                $this->flashMessageProvider->addFlash(FlashMessageProviderInterface::TYPE_ERROR, 'flash.thread_updates_error');
            }
            //redirect them to the referer
            return $this->redirectToReferrer($request);
        }

       switch($threadAction)
       {
           case "archive_thread":
               $this->archiveThreads($threadIds, $loggedInUser);
               break;

           default:
               $this->flashMessageProvider->addFlash(FlashMessageProviderInterface::TYPE_ERROR, 'flash.thread_updates.unknown_action');
               return $this->redirectToReferrer($request);
               break;
       }

        return $this->redirectResponse($request);
    }

    /**
     * Gets the thread ids from the request.
     *
     * @param Request $request
     *
     * @return array|false An array with thread ids selected or false when no thread id array given.
     */

    protected function getThreadIdsFromRequest(Request $request)
    {
        $threadIds = $request->request->get('selected_threads');

        if (empty($threadIds) || !is_array($threadIds)) {
            return false;
        }

        return $threadIds;
    }

    /**
     * Archive threads.
     *
     * @param array                $threadIds
     * @param ParticipantInterface $loggedInUser
     */
    protected function archiveThreads($threadIds, ParticipantInterface $loggedInUser)
    {
        //do the thread update if there are any threads to update
        $processed = $this->doArchiveThreads($threadIds, $loggedInUser);
        $this->addFlashMessage($processed);
    }

    /**
     * Gets the thread action from the request.
     *
     * @param Request $request
     *
     * @return string|false
     */
    protected function getThreadActionFromRequest(Request $request)
    {
        $threadAction = $request->request->get('thread_action');
        if (empty($threadAction) || !is_string($threadAction)) {
            return false;
        }

        return $threadAction;
    }

    /**
     * Gets the threads from the thread ids for whom the user has access to view.
     *
     * @param array                $threadIds
     * @param ParticipantInterface $loggedInUser
     *
     * @return ThreadInterface[] An array with threadInterfaces for whom the user has permission to view them.
     */
    protected function getAllowedThreads($threadIds, ParticipantInterface $loggedInUser)
    {
        $threadArray = [];
        foreach ($threadIds as $threadId) {
            //try to get the thread, it returns null when not found or throws an exception...
            try {
                $thread = $this->threadProvider->findThreadForParticipant($loggedInUser, $threadId);
            } catch (AccessDeniedException $e) {
                $thread = null;
            }

            //we only want an array with valid thread objects...
            if (is_object($thread) && $thread instanceof ThreadInterface) {
                $threadArray[] = $thread;
            }
        }

        return $threadArray;
    }

    /**
     * Does the archiving of the threads by calling the thread status manager.
     *
     * @param array                $threadIds
     * @param ParticipantInterface $loggedInUser
     *
     * @return boolean true if there are threads processed, false otherwise
     */
    protected function doArchiveThreads($threadIds, ParticipantInterface $loggedInUser)
    {
        $threads = $this->getAllowedThreads($threadIds, $loggedInUser);
        if ($threads) {
            //make a thread status value object
            $threadStatus = new ThreadStatus(ThreadMetaInterface::STATUS_ARCHIVED);
            foreach ($threads as $thread) {
                $this->threadStatusManager->updateThreadStatusForParticipant($threadStatus, $thread, $loggedInUser);
            }

            return true;
        }

        return false;
    }

    /**
     * @param boolean $processed Whether or not there where thread status updates
     */
    protected function addFlashMessage($processed)
    {
        if ($processed) {
            $this->flashMessageProvider->addFlash(
                FlashMessageProviderInterface::TYPE_SUCCESS,
                'flash.thread_updates.archived_success'
            );
        } else {
        $this->flashMessageProvider->addFlash(
            FlashMessageProviderInterface::TYPE_ERROR,
            'flash.thread_updates.archived_no_threads'
        );
        }
    }

    /**
     * Redirects the user
     * @param Request $request
     *
     * @return RedirectResponse
     */
    private function redirectResponse(Request $request)
    {
        $currentFolder = $request->request->get('folder', 'inbox');
        $url = $this->router->generate('miliooo_message_'.$currentFolder);

        return new RedirectResponse($url);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    protected function redirectToReferrer(Request $request)
    {
        //redirect them to the referrer if it's set
        $referrer = $request->server->get('HTTP_REFERER');
        if ($referrer) {
            return new redirectResponse($referrer);
        } else {
            return $this->redirectResponse($request);
        }
    }
}
