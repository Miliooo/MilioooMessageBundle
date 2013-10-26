<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Model;

/**
 * The message Interface
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface MessageInterface
{
    /**
     * Gets the unique id of the message
     * 
     * @return integer The unique id of the message
     */
    public function getId();

    /**
     * Gets the creation time of the message
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Sets the creation time of the message
     *
     * @var \DateTime
     */
    public function setCreatedAt(\DateTime $createdAt);

    /**
     * Gets the body of the message
     *
     * @return string The body
     */
    public function getBody();

    /**
     * Sets the body of the message
     *
     * @param string $body The body
     */
    public function setBody($body);
}
