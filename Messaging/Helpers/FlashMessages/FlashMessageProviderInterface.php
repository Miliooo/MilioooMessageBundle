<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Helpers\FlashMessages;

/**
 * The flash message provider is responsible for providing translated flash keys to the user.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface FlashMessageProviderInterface
{
    const TYPE_SUCCESS = 'success';
    const TYPE_ERROR = 'error';

    /**
     * Adds a flash message we will display to the user.
     *
     * @param string $type           One of the class constants type
     * @param string $translationKey A translation string
     */
    public function addFlash($type, $translationKey);
}
