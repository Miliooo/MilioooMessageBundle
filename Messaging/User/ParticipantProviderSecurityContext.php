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
use Symfony\Component\Security\Core\SecurityContextInterface;

/**
 * Participant provider from the security context.
 *
 * Twig extension can't handle with the security token so we need a provider from the securityContext.
 * http://stackoverflow.com/questions/18770467/
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ParticipantProviderSecurityContext implements ParticipantProviderInterface
{
    protected $securityContext;

    /**
     * Constructor.
     *
     * @param SecurityContextInterface $securityContext A security context interface
     */
    public function __construct(SecurityContextInterface $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthenticatedParticipant()
    {
        $participant = $this->securityContext->getToken()->getUser();

        if (!is_object($participant) || !$participant instanceof ParticipantInterface) {
            throw new AccessDeniedException('You must be logged in with a participant interface');
        }

        return $participant;
    }
}
