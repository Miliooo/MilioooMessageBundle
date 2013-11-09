<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Repository;

use Miliooo\Messaging\Model\MessageInterface;

/**
 * Interface for threadRepository instances
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface MessageRepositoryInterface
{
    /**
     * Saves a message to the storage engine
     *
     * @param MessageInterface $message The message we save
     * @param bool $flush Whether to flush or not defaults to true
     */
    public function save(MessageInterface $message, $flush = true);
}
