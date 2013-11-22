<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Controller;

use Miliooo\Messaging\Form\FormFactory\NewThreadMessageFormFactory;
use Miliooo\Messaging\Form\FormHandler\NewSingleThreadFormHandler;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Miliooo\Messaging\User\ParticipantProviderInterface;
use Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProviderInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Controller for adding new threads.
 *
 * This controller is responsible for creating new threads for the logged in user
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadController
{
    /**
     * A new thread message form factory instance.
     *
     * @var NewThreadMessageFormFactory
     */
    protected $formFactory;

    /**
     * A new single thread form handler.
     *
     * @var NewSingleThreadFormHandler
     */
    protected $formHandler;

    /**
     * A participant provider.
     *
     * @var ParticipantProviderInterface
     */
    protected $participantProvider;

    /**
     * An templating instance.
     *
     * @var EngineInterface
     */
    protected $templating;

    /**
     * A flash message provider.
     *
     * @var FlashMessageProviderInterface
     */
    protected $flashMessageProvider;

    /**
     * A router instance.
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * Constructor.
     *
     * @param NewThreadMessageFormFactory   $formFactory          A formfactory instance for a new thread
     * @param NewSingleThreadFormHandler    $formHandler          A new single thread formhandler instance
     * @param ParticipantProviderInterface  $participantProvider  A participant provider
     * @param EngineInterface               $templating           A templating engine instance
     * @param FlashMessageProviderInterface $flashMessageProvider A flash message provider instance
     * @param RouterInterface               $router               A router instance
     */
    public function __construct(
        NewThreadMessageFormFactory $formFactory,
        NewSingleThreadFormHandler $formHandler,
        ParticipantProviderInterface $participantProvider,
        EngineInterface $templating,
        FlashMessageProviderInterface $flashMessageProvider,
        RouterInterface $router
        ) {
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
        $this->participantProvider = $participantProvider;
        $this->templating = $templating;
        $this->flashMessageProvider = $flashMessageProvider;
        $this->router = $router;

    }

    /**
     * Create a new thread
     *
     * This method is responsible for showing the form for creating a new thread
     * When the form is submitted it has to process the creation of that thread
     * and return an response.
     *
     * @return Response
     */
    public function createAction()
    {
        $sender = $this->participantProvider->getAuthenticatedParticipant();
        $form = $this->formFactory->create($sender);
        $processed = $this->formHandler->process($form);
        if ($processed) {
            return $this->doProcessValidForm();
        }

        $twig = 'MilioooMessagingBundle:NewThread:new_thread.html.twig';

        return $this->templating->renderResponse($twig, ['form' => $form->createView()]);
    }

    /**
     * Processes a valid form.
     *
     * Here we process a valid submitted form.
     * We add a flash message and redirect the user to the new thread page.
     *
     * @return RedirectResponse
     */
    protected function doProcessValidForm()
    {
        $this->flashMessageProvider->addFlash(
            FlashMessageProviderInterface::TYPE_SUCCESS,
            'thread_created_success'
        );

        $url = $this->router->generate('miliooo_message_thread_new');

        return new RedirectResponse($url);
    }
}
