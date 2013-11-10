<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Miliooo\Messaging\Model\ThreadInterface;
use Miliooo\Messaging\Repository\ThreadRepositoryInterface;
use Miliooo\Messaging\User\ParticipantInterface;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Doctrine ORM Repository class for threads
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadRepository extends EntityRepository implements ThreadRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getInboxThreadsForParticipant(ParticipantInterface $participant)
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'tm')
            ->innerJoin('t.threadMeta', 'tm', Join::WITH, 'tm.participant = :participant')
            ->setParameter('participant', $participant)
            ->andWhere('tm.lastMessageDate IS NOT NULL')
            ->orderBy('tm.lastMessageDate', 'DESC')
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function getOutboxThreadsForParticipant(ParticipantInterface $participant)
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'tm')
            ->innerJoin('t.threadMeta', 'tm', Join::WITH, 'tm.participant = :participant')
            ->setParameter('participant', $participant)
            ->andWhere('tm.lastParticipantMessageDate IS NOT NULL')
            ->orderBy('tm.lastParticipantMessageDate', 'DESC')
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function findThread($id)
    {
        $thread = $this->find($id);

        return is_object($thread) ? $thread : null;
    }

    /**
     * {@inheritdoc}
     */
    public function save(ThreadInterface $thread, $flush = true)
    {
        $em = $this->getEntityManager();
        $em->persist($thread);

        if ($flush) {
            $em->flush();
        }
    }
}
