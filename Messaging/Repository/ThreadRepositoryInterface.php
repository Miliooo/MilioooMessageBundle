<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Repository;

use Miliooo\Messaging\User\ParticipantInterface;
use Miliooo\Messaging\Model\ThreadInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Interface for threadRepository instances
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ThreadRepositoryInterface
{
    /**
     * Gets the inbox threads for a given participant
     *
     * @param ParticipantInterface $participant The participant
     *
     * @return ThreadInterface[]|null Array of thread interfaces or null when no threads found
     */
    public function getInboxThreadsForParticipant(ParticipantInterface $participant);

    /**
     * Gets the querybuilder for the inbox threads for a given participant
     *
     * @param ParticipantInterface $participant
     *
     * @return QueryBuilder
     */
    public function getInboxThreadsForParticipantQueryBuilder(ParticipantInterface $participant);

    /**
     * Gets the outbox threads for a given participant
     *
     * @param ParticipantInterface $participant
     *
     * @return ThreadInterface[]|null Array of thread interfaces or null when no threads found
     */
    public function getOutboxThreadsForParticipant(ParticipantInterface $participant);

    /**
     * Gets the querybuilder for the outbox threads for a given participant
     *
     * @param ParticipantInterface $participant
     *
     * @return QueryBuilder
     */
    public function getOutboxThreadsForParticipantQueryBuilder(ParticipantInterface $participant);

    /**
     * Gets the archived threads for a given participant
     *
     * @param ParticipantInterface $participant
     *
     * @return ThreadInterface[]|null Array of thread interfaces or null when no threads found
     */
    public function getArchivedThreadsForParticipant(ParticipantInterface $participant);

    /**
     * Gets the querybuilder for the archived threads for a given participant
     *
     * @param ParticipantInterface $participant
     *
     * @return QueryBuilder
     */
    public function getArchivedThreadsForParticipantQueryBuilder(ParticipantInterface $participant);

    /**
     * Finds a thread by it's unique id
     *
     * @param integer $id The unique id
     *
     * @return ThreadInterface|null
     */
    public function findThread($id);

    /**
     * Finds a thread by it's unique id optimized for a given participant.
     *
     * This is essentially the same as findThread but now it's optimized to only return one query. And not do dozens
     * of query calls. If the participant is not part of the thread (this can happen if you allow people to see
     * threads if they are not participants, eg an admin) Then this query should still work since we use a left join
     * on the metas.
     *
     * @param $id
     * @param ParticipantInterface $participant
     *
     * @return ThreadInterface|null
     */
    public function findThreadForParticipant($id, ParticipantInterface $participant);

    /**
     * Saves a thread to the storage engine
     *
     * @param ThreadInterface $thread The thread we save
     * @param boolean $flush Whether to flush or not, defaults to true
     */
    public function save(ThreadInterface $thread, $flush = true);

    /**
     * Calls flush to the entityManager.
     *
     * If you want to persist multiple objects but only flush once you can call this method.
     * This calls flush to the entity manager and all persisted objects will be saved.
     */
    public function flush();

    /**
     * Deletes a thread.
     *
     * @param ThreadInterface $thread The thread we want to delete
     * @param boolean         $flush  Whether to flush or not, defaults to true
     */
    public function delete(ThreadInterface $thread, $flush = true);
}
