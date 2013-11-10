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
use Miliooo\Messaging\Model\MessageInterface;
use Miliooo\Messaging\Repository\MessageRepositoryInterface;

/**
 * Doctrine ORM Repository class for messages
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class MessageRepository extends EntityRepository implements MessageRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function save(MessageInterface $message, $flush = true)
    {
        $em = $this->getEntityManager();
        $em->persist($message);

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
}
