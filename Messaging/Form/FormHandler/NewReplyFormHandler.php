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
use Miliooo\Messaging\Form\FormModelProcessor\NewReplyFormProcessorInterface;
use Miliooo\Messaging\Form\FormModel\ReplyMessageInterface;

/**
 * Form handler for new replies.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewReplyFormHandler extends AbstractFormHandler
{
    /**
     * A reply form processor
     * @var NewReplyFormProcessorInterface
     */
    protected $replyFormProcessor;

    /**
     * Constructor.
     *
     * @param Request                        $request            The request the form will process
     * @param NewReplyFormProcessorInterface $replyFormProcessor A reply form model processor
     */
    public function __construct(Request $request, NewReplyFormProcessorInterface $replyFormProcessor)
    {
        parent::__construct($request);
        $this->replyFormProcessor = $replyFormProcessor;
    }

    /**
     * Processes the form with the request
     *
     * @param FormInterface $form A form instance
     *
     */
    public function doProcess(FormInterface $form)
    {
        $replyThreadFormModel = $this->getFormData($form);
        $replyThreadFormModel->setCreatedAt(new \DateTime('now'));
        $this->processFormModelExtra($replyThreadFormModel);
        $this->replyFormProcessor->process($replyThreadFormModel);
    }

    /**
     * Gets the form data
     *
     * Helper function to get auto completion
     *
     * @param FormInterface $form
     *
     * @return ReplyMessageInterface
     *
     * @throws \InvalidArgumentException when wrong form data
     */
    protected function getFormData(FormInterface $form)
    {
        $data = $form->getData();

        if (!$data instanceof ReplyMessageInterface) {
            throw new \InvalidArgumentException('Form data needs to implement ReplyMessageInterface');
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
     * $newThreadFormModel->setIpAddress = $this->request->getClientIp();
     *
     * @param ReplyMessageInterface $replyThreadFormModel
     */
    protected function processFormModelExtra(ReplyMessageInterface $replyThreadFormModel)
    {

    }
}
