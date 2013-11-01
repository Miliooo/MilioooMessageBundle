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
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Miliooo\Messaging\Form\FormHandler\NewSingleThreadFormHandler;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of NewThreadController
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadController
{
    private $formFactory;
    private $securityToken;
    private $formHandler;
    private $templating;

    /**
     * Constructor.
     *
     * @param NewThreadMessageFormFactory $formFactory   A formfactory instance for a new thread
     * @param NewSingleThreadFormHandler  $formHandler   A new single thread formhandler instance
     * @param TokenInterface              $securityToken A security token instance
     * @param EngineInterface             $templating    A templating engine instance
     */
    public function __construct(NewThreadMessageFormFactory $formFactory, NewSingleThreadFormHandler $formHandler, TokenInterface $securityToken, EngineInterface $templating)
    {
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
        $this->securityToken = $securityToken;
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
        $sender = $this->securityToken->getUser();
        $form = $this->formFactory->create($sender);
        $this->formHandler->process($form);

        return $this->templating->renderResponse('MilioooMessagingBundle:NewThread:new_thread.html.twig', array('form' => $form->createView()));
    }
}
