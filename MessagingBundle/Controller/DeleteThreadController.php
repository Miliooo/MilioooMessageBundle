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
use Miliooo\Messaging\Manager\DeleteThreadManagerSecureInterface;
use Miliooo\Messaging\ThreadProvider\ThreadProviderInterface;
use Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProviderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Miliooo\Messaging\Model\ThreadInterface;
use Symfony\Component\HttpFoundation\Response;
use Miliooo\Messaging\User\ParticipantProviderInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * The delete thread controller is responsible for deleting threads from the storage engine.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class DeleteThreadController
{
    /**
     * A templating engine
     *
     * @var EngineInterface
     */
    private $templating;

    /**
     * A delete thread manager instance.
     *
     * @var DeleteThreadManagerSecureInterface
     */
    private $deleteThreadManager;

    /**
     * A thread provider instance.
     *
     * @var ThreadProviderInterface
     */
    private $threadProvider;

    /**
     * A flash message provider.
     *
     * @var flashMessageProviderInterface
     */
    private $flashMessageProvider;

    /**
     * A routing instance.
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * A participant provider.
     *
     * @var ParticipantProviderInterface
     */
    private $participantProvider;


    /**
     * Constructor.
     *
     * @param EngineInterface                    $templating
     * @param DeleteThreadManagerSecureInterface $deleteThreadManager
     * @param ThreadProviderInterface            $threadProvider
     * @param FlashMessageProviderInterface      $flashMessageProvider
     * @param RouterInterface                    $router
     * @param ParticipantProviderInterface       $participantProvider
     */
    public function __construct(
        EngineInterface $templating,
        DeleteThreadManagerSecureInterface $deleteThreadManager,
        ThreadProviderInterface $threadProvider,
        FlashMessageProviderInterface $flashMessageProvider,
        RouterInterface $router,
        ParticipantProviderInterface $participantProvider
        ) {
        $this->templating = $templating;
        $this->deleteThreadManager = $deleteThreadManager;
        $this->threadProvider = $threadProvider;
        $this->flashMessageProvider = $flashMessageProvider;
        $this->router = $router;
        $this->participantProvider = $participantProvider;
    }

    /**
     * Deletes a thread.
     *
     * Deletes a thread and returns the user to the inbox with a success or error flash message.
     *
     * @param integer $threadId The unique id of the thread
     *
     * @return Response
     */
    public function deleteAction($threadId)
    {
        $loggedInUser = $this->participantProvider->getAuthenticatedParticipant();
        $thread = $this->threadProvider->findThreadById($threadId);

        if ($thread) {
            $this->doThreadDelete($loggedInUser, $thread);
        } else {
            $this->doThreadNotFound();
        }

        $url = $this->router->generate('miliooo_message_inbox');

        return new RedirectResponse($url);
    }

    /**
     * Deletes the thread and adds a flash.
     *
     * @param ParticipantInterface $loggedInUser
     * @param ThreadInterface      $thread
     */
    protected function doThreadDelete(ParticipantInterface $loggedInUser,ThreadInterface $thread)
    {
        //helper to decide if we need to add success flash
        $access = true;

        try {
            $this->deleteThreadManager->deleteThread($loggedInUser, $thread);
        } catch (AccessDeniedException $e) {

            //add no permission flash
            $this->flashMessageProvider->addFlash(
                FlashMessageProviderInterface::TYPE_ERROR,
                'flash.thread_delete_no_permission',
                []
            );
            //set access to false
            $access = false;
        }

        if ($access) {
            //add success to the flash
            $this->flashMessageProvider->addFlash(
                FlashMessageProviderInterface::TYPE_SUCCESS,
                'flash.thread_deleted_success',
                []
            );
        }
    }

    /**
     * Adds an error flash.
     */
    protected function doThreadNotFound()
    {
        //add thread not found to the flash
        $this->flashMessageProvider->addFlash(
            FlashMessageProviderInterface::TYPE_ERROR,
            'flash.thread_not_found',
            []
        );
    }
}
