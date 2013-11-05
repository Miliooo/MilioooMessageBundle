<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider;

use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Interface for thread providers.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ThreadProviderInterface
{

    /**
     * Finds a thread by unique id.
     *
     *
     * @param integer $threadId The unique thread id
     *
     * @return ThreadInterface|null The thread when found or null when not found
     */
    public function findThreadById($threadId);
}
