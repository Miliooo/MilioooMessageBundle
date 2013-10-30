<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\TestHelpers;

use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Description of ParticipantTest
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ParticipantTestHelper implements ParticipantInterface
{
    protected $participantId;

    public function __construct($participantId)
    {
        $this->participantId = $participantId;
    }

    public function getParticipantId()
    {
        return $this->participantId;
    }
}
