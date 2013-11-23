<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Form type for starting a new thread
 *
 * Notice this form type does not supply all the required fields for the form model.
 * This is because some settings gets set without the form.
 *
 * In the form factory we set the sender of a new thread. (by the controller)
 * In the form handler we set the creation date of the new thread and message
 * That's because the datetime is the datetime the form is submitted.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewThreadFormType extends AbstractType
{

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting form the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('recipient', 'username_selector', ['label' => 'form.label.recipient'])
            ->add('subject', 'text', ['label' => 'form.label.subject'])
            ->add('body', 'textarea', ['label' => 'form.label.body']);
    }

    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolverInterface $resolver The resolver for the options.
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'intention' => 'add_new_thread',
            'translation_domain' => 'MilioooMessagingBundle'
        ]);
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'miliooo_message_new_thread';
    }
}
