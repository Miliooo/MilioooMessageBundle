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
     * The request when the form was valid.
     *
     * @var Request
     */
    protected $request;

    /**
     * Constructor.
     *
     * @param Request                         $request   The request the form will process
     * @param NewThreadFormProcessorInterface $processor A new thread form model processor
     */
    public function __construct(Request $request, NewThreadFormProcessorInterface $processor)
    {
        $this->request = $request;

        parent::__construct($request);
        $this->newThreadProcessor = $processor;
    }

    /**
     * Processes the form with the request
     *
     * @param FormInterface $form A form instance
     */
    public function doProcess(FormInterface $form)
    {
        //here we have the valid Form Model. This needs to be an instance of NewThreadInterface.

        $newThreadFormModel = $this->getFormData($form);

        //we update the createdAt to use the datetime when the form was successfully submitted
        $newThreadFormModel->setCreatedAt(new \DateTime('now'));
        $this->processFormModelExtra($newThreadFormModel);

        $this->newThreadProcessor->process($newThreadFormModel);
    }

    /**
     * Gets the form data
     *
     * Helper function to get auto completion
     *
     * @param FormInterface $form
     *
     * @return NewThreadInterface
     *
     * @throws \InvalidArgumentException when form data is not the expected data
     */
    protected function getFormData(FormInterface $form)
    {
        $data = $form->getData();

        if (!$data instanceof NewThreadInterface) {
            throw new \InvalidArgumentException('Form data needs to implement NewThreadInterface');
        }

        return $data;
    }

    /**
     * Helper function if you need to extend this class.
     *
     * Here you have your custom form model which implements the NewThreadInterface.
     * If you need extra processing of this valid form model you can extend this class and overwrite this function.
     *
     * An example...
     * You want to store the ip address from the client.
     * $newThreadFormModel->setIpAddress = $this->request->getClientIp();     *
     *
     * @param NewThreadInterface $newThreadFormModel
     */
    protected function processFormModelExtra(NewThreadInterface $newThreadFormModel)
    {

    }
}
