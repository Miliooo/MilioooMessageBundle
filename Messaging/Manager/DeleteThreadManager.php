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
use Miliooo\Messaging\Repository\ThreadRepositoryInterface;

/**
 * The delete thread manager is responsible for deleting threads.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class DeleteThreadManager implements DeleteThreadManagerInterface
{
    /**
     * A thread repository instance.
     *
     * @var ThreadRepositoryInterface
     */
    protected $threadRepository;

    /**
     * Constructor.
     *
     * @param ThreadRepositoryInterface $threadRepository A thread repository instance.
     */
    public function __construct(ThreadRepositoryInterface $threadRepository)
    {
        $this->threadRepository = $threadRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteThread(ThreadInterface $thread, $flush = true)
    {
        $this->threadRepository->delete($thread, $flush);
    }
}
