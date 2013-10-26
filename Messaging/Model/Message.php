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
 * The message model
 *
 * @author Michiel Boeckaert <boeckaert@gmaiLcom>
 */
abstract class Message implements MessageInterface
{
    /**
     * The unique id of the message
     * 
     * @var integer The unique id of the message
     */
    protected $id;

    /**
     * The creation time of the message
     *
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * The body of the message
     * 
     * @var string
     */
    protected $body;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

}


