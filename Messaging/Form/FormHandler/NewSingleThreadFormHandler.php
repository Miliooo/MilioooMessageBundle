<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormHandler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Miliooo\Messaging\Form\FormModel\NewThreadSingleRecipient;
use Miliooo\Messaging\Form\FormModel\NewThreadInterface;
use Miliooo\Messaging\Form\FormModelProcessor\NewThreadFormProcessorInterface;

/**
 * Form handler for single threads.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewSingleThreadFormHandler extends AbstractFormHandler
{
    /**
     * A processor instance.
     *
     * The processor is responsible for processing the valid form model
     *
     * @var NewThreadFormProcessorInterface
     */
    protected $newThreadProcessor;

    /**
     * Constructor.
     *
     * @param Request                         $request   The request the form will process
     * @param NewThreadFormProcessorInterface $processor A new thread form model processor
     */
    public function __construct(Request $request, NewThreadFormProcessorInterface $processor)
    {
        parent::__construct($request);
        $this->newThreadProcessor = $processor;
    }

    /**
     * Processes the form with the request
     *
     * @param Form $form A form instance
     *
     * @return Message|false the last message if the form is valid, false otherwise
     */
    public function doProcess(FormInterface $form)
    {
        $newThreadFormModel = $this->getFormData($form);
        //we want the creation date to be the same as the submit date..
        $newThreadFormModel->setCreatedAt(new \DateTime('now'));
        $this->newThreadProcessor->process($newThreadFormModel);
    }

    /**
     * Gets the form data
     *
     * Helper function to get autocompletion
     *
     * @param Form $form
     *
     * @return NewThreadSingleRecipient
     */
    protected function getFormData(FormInterface $form)
    {
        $data = $form->getData();

        if (!$data instanceof NewThreadInterface) {
            throw new \InvalidArgumentException('Form data needs to implement NewThreadInterface');
        }

        return $data;
    }
}
