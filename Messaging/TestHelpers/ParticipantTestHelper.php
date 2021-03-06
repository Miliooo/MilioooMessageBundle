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
 * Helper class to create new participants for tests
 *
 * The constructor argument $participantId is what we return in getParticipantId()
 * This makes it easy to create unique participants
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ParticipantTestHelper implements ParticipantInterface
{
    protected $participantId;

    /**
     * Constructor.
     *
     * @param string $participantId
     */
    public function __construct($participantId)
    {
        $this->participantId = $participantId;
    }

    /**
     * {@inheritdoc}
     */
    public function getParticipantId()
    {
        return $this->participantId;
    }
}
