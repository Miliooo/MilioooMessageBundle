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

/**
 * Description of NewThreadController
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadController
{
    protected $formFactory;
    protected $securityToken;
    protected $formHandler;
    protected $templating;

    /**
     * Constructor.
     *
     */
    public function __construct(NewThreadMessageFormFactory $formFactory, NewSingleThreadFormHandler $formHandler, TokenInterface $securityToken, EngineInterface $templating)
    {
        $this->formFactory = $formFactory;
        $this->formHandler = $formHandler;
        $this->securityToken = $securityToken;
        $this->templating = $templating;
    }

    public function createAction(Request $request)
    {
        $sender = $this->securityToken->getUser();
        //the form new_thread_form.factory')->create();
        $form = $this->formFactory->create($sender);
        $processed = $this->formHandler->process($form);

        return $this->templating->renderResponse('MilioooMessagingBundle:NewThread:new_thread.html.twig', array('form' => $form->createView()));
    }
}
