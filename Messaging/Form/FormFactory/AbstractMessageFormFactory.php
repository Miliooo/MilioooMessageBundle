<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormFactory;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Instanciates message forms
 *
 * @author Thibault Duplessis <thibault.duplessis@gmail.com>
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class AbstractMessageFormFactory
{
    /**
     * The Symfony form factory
     *
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * The message form type
     *
     * @var AbstractType
     */
    protected $formType;

    /**
     * The name of the form
     *
     * @var string
     */
    protected $formName;

    /**
     * The FQCN of the message model
     *
     * @var string
     */
    protected $messageClass;

    /**
     * Constructor.
     *
     * @param FormFactoryInterface $formFactory  A form factory instance
     * @param AbstractType         $formType     The form type
     * @param string               $formName     Name of the form
     * @param string               $messageClass FQCN of the form model
     */
    public function __construct(FormFactoryInterface $formFactory, AbstractType $formType, $formName, $messageClass)
    {
        $this->formFactory = $formFactory;
        $this->formType = $formType;
        $this->formName = $formName;
        $this->messageClass = $messageClass;
    }

    /**
     * Creates a new instance of the form model
     *
     * @return AbstractMessage
     */
    protected function createModelInstance()
    {
        $class = $this->messageClass;

        return new $class();
    }
}
