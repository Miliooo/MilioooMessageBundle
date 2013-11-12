<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\User;

use Miliooo\Messaging\User\ParticipantProviderSecurityToken;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for Miliooo\Messaging\User\ParticipantProviderSecurityToken
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ParticipantProviderSecurityTokenTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var ParticipantProviderSecurityToken
     */
    private $participantProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $securityToken;

    public function setUp()
    {
        $this->securityToken = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->participantProvider = new ParticipantProviderSecurityToken($this->securityToken);
    }

    public function testInterface()
    {
        $this->assertInstanceOf('Miliooo\Messaging\User\ParticipantProviderInterface', $this->participantProvider);
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @expectedExceptionMessage You must be logged in with a participant interface
     */
    public function testGetAuthenticatedParticipantTokenReturnsString()
    {
        $this->securityToken->expects($this->once())->method('getUser')->will($this->returnValue('anon'));
        $this->participantProvider->getAuthenticatedParticipant();
    }

     /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @expectedExceptionMessage You must be logged in with a participant interface
     */
    public function testGetAuthenticatedParticpantReturnsObjectNotInstanceParticipantInterface()
    {
        $obj = new \stdClass;
        $this->securityToken->expects($this->once())->method('getUser')->will($this->returnValue($obj));
        $this->participantProvider->getAuthenticatedParticipant();
    }

    public function testGetAuthenticatedParticipantReturnsParticipant()
    {
        $loggedInUser = new ParticipantTestHelper('user');
        $this->securityToken->expects($this->once())->method('getUser')
            ->will($this->returnValue($loggedInUser));
        $this->assertEquals($loggedInUser, $this->participantProvider->getAuthenticatedParticipant());
    }
}
