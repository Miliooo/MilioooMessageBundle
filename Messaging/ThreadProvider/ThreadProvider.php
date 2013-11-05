<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider;

use Doctrine\ORM\EntityManager;

/**
 * The thread provider is responsible for providing threads
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadProvider implements ThreadProviderInterface
{
    protected $entityManager;
    protected $threadClass;

    /**
     * Constructor.
     *
     * @param EntityManager $entityManager The entity manager
     * @param string        $threadClass   The FQCN of the thread model
     */
    public function __construct(EntityManager $entityManager, $threadClass)
    {
        $this->entityManager = $entityManager;
        $this->threadClass = $threadClass;
    }

    /**
     * {@inheritdoc}
     */
    public function findThreadById($id)
    {
        $repository = $this->entityManager->getRepository($this->threadClass);

        $thread = $repository->find($id);

        return is_object($thread) ? $thread : null;
    }
}
