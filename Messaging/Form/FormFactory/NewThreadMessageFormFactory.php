<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormFactory;

use Miliooo\Messaging\Form\FormModel\NewThreadInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
/**
 * The form factory for new thread messages
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadMessageFormFactory extends AbstractMessageFormFactory
{
    protected $transformer;

    /**
     * Constructor.
     *
     * @param FormFactoryInterface $formFactory    A form factory instance
     * @param AbstractType         $formType       The form type
     * @param string               $formName       Name of the form
     * @param string               $modelClassName FQCN of the form model
     * @param DataTransformerInterface $transformer An user to username transformer
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        AbstractType $formType,
        $formName,
        $modelClassName,
        DataTransformerInterface $transformer
    ) {
        $this->transformer = $transformer;

        parent::__construct($formFactory, $formType, $formName, $modelClassName);
    }

    /**
     * Creates a new thread form from a form type with a form model set.
     *
     * @param ParticipantInterface $sender    The sender
     * @param string               $recipient A recipient
     *
     * @return Form
     */
    public function create(ParticipantInterface $sender, $recipient = "")
    {
        $formModel = $this->createNewFormModel();
        $formModel->setSender($sender);

        if ($recipient) {
            $this->maybeSetRecipient($recipient, $formModel);
        }

        return $this->formFactory->createNamed($this->formName, $this->formType, $formModel);
    }

    /**
     * Creates a new form model object from the modelClassName
     *
     * @return NewThreadInterface
     */
    protected function createNewFormModel()
    {
        return new $this->modelClassName;
    }

    /**
     * Maybe sets a recipient
     * @param string $recipient
     * @param $formModel
     */
    protected function maybeSetRecipient($recipient, $formModel)
    {
        try {
            $recipient = $this->transformer->reverseTransform($recipient);
        } catch (TransformationFailedException $e) {
            return;
        }


        if ($recipient instanceof ParticipantInterface) {
            $formModel->setRecipient($recipient);
        }
    }
}
