<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Entity;

use Miliooo\Messaging\Model\Message as ModelMessage;

/**
 * Abstract class Message.
 *
 * This class is needed for doctrine xml mapping
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class Message extends ModelMessage
{
}
