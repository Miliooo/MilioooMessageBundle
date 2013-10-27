<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Model;

use Miliooo\Messaging\Model\ParticipantInterface;

/**
 * The interface class used by the thread model
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ThreadInterface
{
    /**
     * Gets the unique id of the thread
     *
     * @return integer The unique id
     */
    public function getId();

    /**
     * Sets the subject of the thread
     *
     * @param string $subject The subject of the thread
     */
    public function setSubject($subject);

    /**
     * Gets the subject of the thread
     *
     * @return string The subject of the thread
     */
    public function getSubject();

    /**
     * Sets the participant who created the thread
     *
     * @param ParticipantInterface $participant The participant who created the thread
     */
    public function setCreatedBy(ParticipantInterface $participant);

    /**
     * Gets the participant who created the thread
     */
    public function getCreatedBy();

    /**
     * Sets the datetime when the thread was created
     *
     * @param \DateTime $createdAt The creation datetime of the thread
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Gets the datetime when the thread was created
     */
    public function getCreatedAt();
}
