<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\User;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Participant provider from the security token.
 *
 * This is the participant provider implementation using the security token
 * from the symfony security context.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ParticipantProviderSecurityToken implements ParticipantProviderInterface
{
    protected $securityToken;

    /**
     * Constructor.
     *
     * @param TokenInterface $securityToken A security token
     */
    public function __construct(TokenInterface $securityToken)
    {
        $this->securityToken = $securityToken;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthenticatedParticipant()
    {
        $participant = $this->securityToken->getUser();

        if (!is_object($participant) || !$participant instanceof ParticipantInterface) {
            throw new AccessDeniedException('You must be logged in with a participant interface');
        }

        return $participant;
    }
}
