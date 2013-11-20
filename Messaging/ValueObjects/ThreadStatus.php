<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ValueObjects;

use Miliooo\Messaging\Model\ThreadMetaInterface;

/**
 * Thread Status value object.
 *
 * This value objects makes sure the thread statuses are valid. A thread status is valid if it's one of the
 * ThreadMetaInterface class constants.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */

class ThreadStatus
{
    /**
     * @var integer The thread status integer
     */
    private $threadStatus;

    /**
     * Constructor.
     *
     * @param integer $threadStatus
     */
    public function __construct($threadStatus)
    {
        if ($this->isValidThreadStatus($threadStatus)) {
            $this->threadStatus = $threadStatus;
        }
    }

    /**
     * Returns the thread status value.
     *
     * @return integer The thread status integer value
     */
    public function getThreadStatus()
    {
        return $this->threadStatus;
    }

    /**
     * Checks if the given thread status is valid.
     *
     * @param integer $threadStatus One of the thread status constants
     *
     * @return true if the read status is valid.
     *
     * @throws \InvalidArgumentException If the read status is invalid.
     */
    private function isValidThreadStatus($threadStatus)
    {
        if(!is_integer($threadStatus) || !in_array(
                $threadStatus,
                [
                ThreadMetaInterface::STATUS_ACTIVE,
                ThreadMetaInterface::STATUS_ARCHIVED
                ],
                true
            )
        ) {
            throw new \InvalidArgumentException('Invalid thread status');
        }

        return true;
    }
}
