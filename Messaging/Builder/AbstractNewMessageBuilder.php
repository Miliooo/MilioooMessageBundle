<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder;

/**
 * AbstractNewMessageBuilder.
 * 
 * The AbstractNewMessagebuilder is responsible for helping to build new thread
 * and reply messages.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class AbstractNewMessageBuilder
{
    /**
     * Fully qualified namespace of the custom message class
     *
     * @var string
     */
    protected $messageClass;

    /**
     * Fully qualified names of the message meta class
     *
     * @var string
     */
    protected $messageMetaClass;

    /**
     * Fully qualified name of the thread class
     *
     * @var string
     */
    protected $threadClass;

    /**
     * Fully qualified name of the thread meta class
     *
     * @var string
     */
    protected $threadMetaClass;

    /**
     * Sets the message class
     *
     * @param string $messageClass Fully qualified name of message class
     */
    public function setMessageClass($messageClass)
    {
        $this->messageClass = $messageClass;
    }

    /**
     * Sets the message meta class
     *
     * @param string $messageMetaClass Fully qualified name the message meta class
     */
    public function setMessageMetaClass($messageMetaClass)
    {
        $this->messageMetaClass = $messageMetaClass;
    }

    /**
     * Sets the thread class
     *
     * @param string $threadClass Fully qualified name of the thread class
     */
    public function setThreadClass($threadClass)
    {
        $this->threadClass = $threadClass;
    }

    /**
     * Sets the thread meta class
     *
     * @param string $threadMetaClass Fully qualified name of the thread meta class
     */
    public function setThreadMetaClass($threadMetaClass)
    {
        $this->threadMetaClass = $threadMetaClass;
    }
}
