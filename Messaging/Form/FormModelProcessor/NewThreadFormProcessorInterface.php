<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Form\FormModelProcessor;

use Miliooo\Messaging\Form\FormModel\NewThreadInterface;

/**
 * Interface used by new thread form processors.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface NewThreadFormProcessorInterface
{
    /**
     * Processes a NewThreadFormModel instance
     *
     * @param NewThreadInterface $formModel The form model we process
     */
    public function process(NewThreadInterface $formModel);
}
