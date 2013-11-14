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
use Miliooo\Messaging\Specifications\CanDeleteThreadSpecification;
use Miliooo\Messaging\User\ParticipantInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * A delete thread manager which uses the can delete thread specification to decide whether to allow the deletion of
 * a thread.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class DeleteThreadManagerSpecificationAware implements DeleteThreadManagerSecureInterface
{
    /**
     * A delete thread manager instance.
     *
     * @var DeleteThreadManagerInterface
     */
    protected $deleteThreadManager;

    /**
     * A can delete thread specification instance.
     *
     * @var CanDeleteThreadSpecification
     */
    protected $canDeleteThread;

    /**
     * Constructor.
     *
     * @param DeleteThreadManagerInterface $deleteThreadManager
     * @param CanDeleteThreadSpecification $canDeleteThread
     */
    public function __construct(
        DeleteThreadManagerInterface $deleteThreadManager,
        CanDeleteThreadSpecification $canDeleteThread
    ) {
        $this->deleteThreadManager = $deleteThreadManager;
        $this->canDeleteThread = $canDeleteThread;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteThread(ParticipantInterface $participant, ThreadInterface $thread)
    {
        if (!$this->canDeleteThread->isSatisfiedBy($participant, $thread)) {
            throw new AccessDeniedException('not authorised to delete this thread');
        }

        $this->deleteThreadManager->deleteThread($thread);
    }
}
