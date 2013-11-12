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
use Miliooo\Messaging\Model\ThreadMetaInterface;

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
        return $this->getInboxThreadsForParticipantQueryBuilder($participant)
            ->getQuery()
            ->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function getOutboxThreadsForParticipant(ParticipantInterface $participant)
    {
        return $this->getOutboxThreadsForParticipantQueryBuilder($participant)
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
    public function findThreadForParticipant($id, ParticipantInterface $participant)
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'tm', 'm', 'mm')
            ->leftJoin('t.threadMeta', 'tm', Join::WITH, 'tm.participant = :participant')
            ->setParameter('participant', $participant)
            ->leftJoin('t.messages', 'm')
            ->leftJoin('m.messageMeta', 'mm', Join::WITH, 'mm.participant = :participant')
            ->where('t.id = :id')
            ->setParameter('id', $id, \PDO::PARAM_INT)
            ->getQuery()
            ->getSingleResult();
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

    /**
     * {@inheritdoc}
     */
    public function flush()
    {
        $em = $this->getEntityManager();
        $em->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getInboxThreadsForParticipantQueryBuilder(ParticipantInterface $participant)
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'tm')
            ->innerJoin('t.threadMeta', 'tm', Join::WITH, 'tm.participant = :participant')
            ->setParameter('participant', $participant)
            ->andWhere('tm.status = :status')
            ->setParameter('status', ThreadMetaInterface::STATUS_ACTIVE, \PDO::PARAM_INT)
            ->andWhere('tm.lastMessageDate IS NOT NULL')
            ->orderBy('tm.lastMessageDate', 'DESC');
    }

    /**
     * {@inheritdoc}
     */
    public function getOutboxThreadsForParticipantQueryBuilder(ParticipantInterface $participant)
    {
        return $this->createQueryBuilder('t')
            ->select('t', 'tm')
            ->innerJoin('t.threadMeta', 'tm', Join::WITH, 'tm.participant = :participant')
            ->setParameter('participant', $participant)
            ->andWhere('tm.status = :status')
            ->setParameter('status', ThreadMetaInterface::STATUS_ACTIVE, \PDO::PARAM_INT)
            ->andWhere('tm.lastParticipantMessageDate IS NOT NULL')
            ->orderBy('tm.lastParticipantMessageDate', 'DESC');
    }
}
