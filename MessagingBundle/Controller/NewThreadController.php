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
use Miliooo\Messaging\Form\FormFactory\NewThreadMessageFormFactory;
use Miliooo\Messaging\Form\FormHandler\NewSingleThreadFormHandler;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Miliooo\Messaging\User\ParticipantProviderInterface;

/**
 * Controller for adding new threads.
 *
 * This controller is responsible for creating new threads for the logged in user
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadController
{
    protected $formFactory;
    protected $participantProvider;
    protected $formHandler;
    protected $templating;

    /**
     * Constructor.
     *
     * @param NewThreadMessageFormFactory  $formFactory         A formfactory instance for a new thread
     * @param NewSingleThreadFormHandler   $formHandler         A new single thread formhandler instance
     * @param ParticipantProviderInterface $participantProvider A participant provider
     * @param EngineInterface              $templating          A templating engine instance
     */
    public function __construct(NewThreadMessageFormFactory $formFactory, NewSingleThreadFormHandler $formHandler, ParticipantProviderInterface $participantProvider, EngineInterface $templating)
    {
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
        $this->participantProvider = $participantProvider;
        $this->templating = $templating;
    }

    /**
     * Create a new thread
     *
     * This method is responsible for showing the form for creating a new thread
     * When the form is submitted it has to process the creation of that thread
     * and return an response.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function createAction(Request $request)
    {
        $sender = $this->participantProvider->getAuthenticatedParticipant();
        $form = $this->formFactory->create($sender);
        $this->formHandler->process($form);
        $twig = 'MilioooMessagingBundle:NewThread:new_thread.html.twig';

        return $this->templating->renderResponse($twig, array('form' => $form->createView()));
    }
}
