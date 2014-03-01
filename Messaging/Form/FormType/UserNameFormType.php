<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormType;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Form type for representing an user object by its username
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class UserNameFormType extends AbstractType
{
    /**
     * @var DataTransformerInterface
     */
    protected $dataTransformer;

    /**
     * Constructor.
     *
     * @param DataTransformerInterface $dataTransformer
     */
    public function __construct(DataTransformerInterface $dataTransformer)
    {
        $this->dataTransformer = $dataTransformer;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this->dataTransformer);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'text';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'miliooo_messaging_user_transformer';
    }
}
