<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ValueObjects;

use Miliooo\Messaging\Model\MessageMetaInterface;

/**
 * Read Status value object.
 *
 * This value objects makes sure the read statuses are valid. A read status is valid if it's one of the
 * MessageMetaInterface class constants.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ReadStatus
{
    protected $readStatus;

    /**
     * Constructor.
     *
     * @param integer $readStatus
     */
    public function __construct($readStatus)
    {
        if ($this->isValidReadStatus($readStatus)) {
            $this->readStatus = $readStatus;
        }
    }

    /**
     * Returns the read status of a message
     *
     * @return integer The read status of a message
     */
    public function getReadStatus()
    {
        return $this->readStatus;
    }

    /**
     * Checks if the given read status is a valid read status.
     *
     * @param mixed $readStatus The read status we check
     *
     * @return true if the read status is valid.
     *
     * @throws \InvalidArgumentException If the read status is invalid.
     */
    protected function isValidReadStatus($readStatus)
    {
        if (!is_integer($readStatus) || !in_array(
            $readStatus,
            [
            MessageMetaInterface::READ_STATUS_NEVER_READ,
            MessageMetaInterface::READ_STATUS_MARKED_UNREAD,
            MessageMetaInterface::READ_STATUS_READ
            ],
            true
            )
        ) {
            throw new \InvalidArgumentException('Read status is not valid');
        }

        return true;
    }
}
