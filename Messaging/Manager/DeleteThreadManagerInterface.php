<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Manager;

use Miliooo\Messaging\Model\ThreadInterface;

/**
 * The delete thread manager is responsible for deleting threads.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface DeleteThreadManagerInterface
{

    /**
     * Deletes a thread.
     *
     * @param ThreadInterface $thread The thread we want to delete
     * @param boolean         $flush  Whether to flush, defaults to true
     */
    public function deleteThread(ThreadInterface $thread, $flush=true);

}
