<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormHandler;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormInterface;

/**
 * Description of AbstractFormHandler
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class AbstractFormHandler
{
    /**
     * The request the form will process
     *
     * @var Request
     */
    protected $request;

    /**
     * Constructor.
     *
     * @param Request $request The request the form will process
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Processes a form
     *
     * @param FormInterface $form The form we process
     *
     * @return boolean false if not processed true if processed
     */
    public function process(FormInterface $form)
    {
        if ('POST' !== $this->request->getMethod()) {
            return false;
        }

        $form->handleRequest($this->request);

        if (!$form->isValid()) {
            return false;
        }

        $this->doProcess($form);

        return true;
    }

    /**
     * Do the processing of the valid form.
     *
     * @param FormInterface $form
     */
    abstract public function doProcess(FormInterface $form);
}
