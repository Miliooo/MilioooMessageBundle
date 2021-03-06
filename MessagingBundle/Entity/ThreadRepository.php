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
            //we want all the thread meta since this gets passed to the builder too, we get the recipients from there.
            ->innerJoin('t.threadMeta', 'tm')
            ->setParameter('participant', $participant)
            ->leftJoin('t.messages', 'm')
            ->leftJoin('m.messageMeta', 'mm', Join::WITH, 'mm.participant = :participant')
            ->where('t.id = :id')
            ->setParameter('id', $id, \PDO::PARAM_INT)
            ->getQuery()
            ->getOneOrNullResult();
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
            ->orderBy('t.id', 'DESC');
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
            ->orderBy('t.id', 'DESC');
    }

    /**
     * {@inheritdoc}
     */
    public function getUnreadMessageCountForParticipant(ParticipantInterface $participant)
    {

        return $this->createQueryBuilder('t')
            ->select('SUM(tm.unreadMessageCount) as total')
            ->innerJoin('t.threadMeta', 'tm', Join::WITH, 'tm.participant = :participant')
            ->setParameter('participant', $participant)
            ->getQuery()
            ->getSingleScalarResult();
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
    public function delete(ThreadInterface $thread, $flush = true)
    {
        $em = $this->getEntityManager();
        $em->remove($thread);

        if ($flush) {
            $em->flush();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getArchivedThreadsForParticipantQueryBuilder(ParticipantInterface $participant)
    {
        //not sure if this is right... we want both metas to get the last message date sorting right...
        //maybe consider to use one meta...
        return $this->createQueryBuilder('t')
            ->select('t', 'tm', 'tma')
            ->innerJoin('t.threadMeta', 'tma', Join::WITH, 't.id = tma.thread')
            ->innerJoin('t.threadMeta', 'tm', Join::WITH, 'tm.participant = :participant')
            ->setParameter('participant', $participant)
            ->andWhere('tm.status = :status')
            ->setParameter('status', ThreadMetaInterface::STATUS_ARCHIVED, \PDO::PARAM_INT)
            ->orderBy('t.id', 'DESC');
    }

    /**
     * {@inheritdoc}
     */
    public function getArchivedThreadsForParticipant(ParticipantInterface $participant)
    {
        return $this->getArchivedThreadsForParticipantQueryBuilder($participant)
                ->getQuery()
                ->execute();
    }
}
