<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\User;

use Miliooo\Messaging\User\ParticipantProviderSecurityContext;
use Miliooo\Messaging\TestHelpers\ParticipantTestHelper;

/**
 * Test file for Miliooo\Messaging\User\ParticipantProviderSecurityContext
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ParticipantProviderSecurityContextTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The class under test
     *
     * @var ParticipantProviderSecurityContext
     */
    private $participantProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $securityContext;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $securityToken;

    public function setUp()
    {
        $this->securityContext = $this->getMock('Symfony\Component\Security\Core\SecurityContextInterface');
        $this->securityToken = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->participantProvider = new ParticipantProviderSecurityContext($this->securityContext);
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
        $this->expectsToken();
        $this->securityToken->expects($this->once())->method('getUser')->will($this->returnValue('anon'));
        $this->participantProvider->getAuthenticatedParticipant();
    }

    /**
     * @expectedException \Symfony\Component\Security\Core\Exception\AccessDeniedException
     * @expectedExceptionMessage You must be logged in with a participant interface
     */
    public function testGetAuthenticatedParticpantReturnsObjectNotInstanceParticipantInterface()
    {
        $this->expectsToken();
        $obj = new \stdClass;
        $this->securityToken->expects($this->once())->method('getUser')->will($this->returnValue($obj));
        $this->participantProvider->getAuthenticatedParticipant();
    }

    public function testGetAuthenticatedParticipantReturnsParticipant()
    {
        $this->expectsToken();
        $loggedInUser = new ParticipantTestHelper('user');
        $this->securityToken->expects($this->once())->method('getUser')
            ->will($this->returnValue($loggedInUser));
        $this->assertEquals($loggedInUser, $this->participantProvider->getAuthenticatedParticipant());
    }

    protected function expectsToken()
    {
        $this->securityContext->expects($this->once())->method('getToken')
            ->will($this->returnValue($this->securityToken));
    }
}
