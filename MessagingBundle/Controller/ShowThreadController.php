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
use Miliooo\Messaging\ThreadProvider\SecureThreadProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\Response;
use Miliooo\Messaging\User\ParticipantProviderInterface;
use Miliooo\Messaging\Form\FormFactory\ReplyMessageFormFactory;
use Miliooo\Messaging\Form\FormHandler\NewReplyFormHandler;
use Miliooo\Messaging\Manager\ReadStatusManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Miliooo\Messaging\Model\MessageMetaInterface;
use Miliooo\Messaging\ValueObjects\ReadStatus;
use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Controller for showing a single thread.
 *
 * This controller is responsible for showing a single thread to the user.
 * It should only show the thread if the user has permissions to view that thread.
 *
 */
class ShowThreadController
{
    protected $formFactory;
    protected $formHandler;
    protected $threadProvider;

    /**
     * @var EngineInterface
     */
    protected $templating;

    protected $participantProvider;
    /**
     * @var ReadStatusManagerInterface
     */
    protected $readStatusManager;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param ReplyMessageFormFactory       $formFactory         A reply form factory
     * @param NewReplyFormHandler           $formHandler         A reply form handler
     * @param SecureThreadProviderInterface $threadProvider      A secure thread provider instance
     * @param EngineInterface               $templating          A templating engine
     * @param ParticipantProviderInterface  $participantProvider A participant provider
     * @param ReadStatusManagerInterface    $readStatusManager   A read status manager instance
     * @param RouterInterface               $router              A router instance
     */
    public function __construct(
        ReplyMessageFormFactory $formFactory,
        NewReplyFormHandler $formHandler,
        SecureThreadProviderInterface $threadProvider,
        EngineInterface $templating,
        ParticipantProviderInterface $participantProvider,
        ReadStatusManagerInterface $readStatusManager,
        RouterInterface $router
    ) {
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
        $this->threadProvider = $threadProvider;
        $this->templating = $templating;
        $this->participantProvider = $participantProvider;
        $this->readStatusManager = $readStatusManager;
        $this->router = $router;
    }

    /**
     * Shows a single thread and allows the user to reply on it
     *
     * @param integer $threadId The unique thread id
     *
     * @throws NotFoundHttpException
     *
     * @return Response
     */
    public function showAction($threadId)
    {
        //get the logged in user
        $loggedInUser = $this->participantProvider->getAuthenticatedParticipant();
        //get the thread
        $thread = $this->getThreadForLoggedInUser($loggedInUser, $threadId);
        $form = $this->formFactory->create($thread, $loggedInUser);

        //check form submit..
        $response = $this->maybeProcessForm($form, $threadId);
        if ($response) {
            return $response;
        }
        //do some pre thread processing.
        $thread = $this->doThreadProcessing($loggedInUser, $thread);

        return $this->createResponse($thread, $form);
    }

    /**
     * Gets the thread for the logged in user.
     *
     * This function gets the thread for the logged in user and throws an exception when no thread is found.
     *
     * @param ParticipantInterface $loggedInUser The user for whom we lookup the thread
     * @param integer              $threadId     The unique thread id
     *
     * @return ThreadInterface The thread
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    protected function getThreadForLoggedInUser(ParticipantInterface $loggedInUser, $threadId)
    {
        $thread = $this->threadProvider->findThreadForParticipant($loggedInUser, $threadId);
        if (!$thread) {
            throw new NotFoundHttpException('Thread not found');
        }

        return $thread;
    }

    /**
     * Checks if the form is valid returns a redirect response if valid or false if not submitted or not valid
     *
     * @param FormInterface $form     The form we process
     * @param integer       $threadId The unique thread id
     *
     * @return RedirectResponse|false
     */
    protected function maybeProcessForm(FormInterface $form, $threadId)
    {
        $submittedAndValidForm = $this->formHandler->process($form);
        if ($submittedAndValidForm) {
            $url = $this->router->generate('miliooo_message_thread_view', ['threadId' => $threadId]);

            return new RedirectResponse($url);
        }

        return false;
    }

    /**
     * doThreadProcessing.
     *
     * Here we do some thread actions before sending it to the view.
     *
     * We are marking the messages of the thread as read since we'll show those messages to the user.
     *
     * @param ParticipantInterface $loggedInUser
     * @param ThreadInterface      $thread
     *
     * @return threadInterface
     */
    protected function doThreadProcessing(ParticipantInterface $loggedInUser, ThreadInterface $thread)
    {
        $this->readStatusManager->updateReadStatusForMessageCollection(
            new ReadStatus(MessageMetaInterface::READ_STATUS_READ),
            $loggedInUser,
            $thread->getMessages()->toArray()
        );

        return $thread;
    }

    /**
     * Creates the response.
     *
     * @param ThreadInterface $thread
     * @param FormInterface   $form
     *
     * @return Response
     */
    protected function createResponse(ThreadInterface $thread, FormInterface $form)
    {
        return $this->templating->renderResponse(
            'MilioooMessagingBundle:ShowThread:show_thread.html.twig',
            [
                'thread' => $thread,
                'form' => $form->createView()
            ]
        );
    }
}
