<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Tests\Helpers\FlashMessages;

use Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProvider;
use Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProviderInterface;

/**
 * Test file for the flash message provider.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class FlashMessageProviderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var FlashMessageProvider
     */
    private $flashProvider;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $flashBag;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $translator;

    const SUCCESS_KEY = 'success';

    const ERROR_KEY = 'failure';

    const TRANSLATE_KEY = 'translate_key';

    const TRANSLATED_STRING = 'translated_string';

    public function setUp()
    {
        $this->flashBag = $this->getMock('Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface');
        $this->translator = $this->getMock('Symfony\Component\Translation\TranslatorInterface');
        $this->flashProvider = new FlashMessageProvider(
            $this->flashBag,
            $this->translator,
            self::SUCCESS_KEY,
            self::ERROR_KEY
        );
    }

    public function testInterface()
    {
        $this->assertInstanceOf(
            'Miliooo\Messaging\Helpers\FlashMessages\FlashMessageProviderInterface',
            $this->flashProvider
        );
    }

    public function testAddFlashWithTypeSuccess()
    {
        $this->expectsTranslatorTranslates();

        $this->flashBag->expects($this->once())
            ->method('add')
            ->with(self::SUCCESS_KEY, self::TRANSLATED_STRING);


        $this->flashProvider->addFlash(
            FlashMessageProviderInterface::TYPE_SUCCESS, self::TRANSLATE_KEY, []);
    }

    public function testAddFlashWithTypeFailure()
    {
        $this->expectsTranslatorTranslates();

        $this->flashBag->expects($this->once())
            ->method('add')
            ->with(self::ERROR_KEY, self::TRANSLATED_STRING);


        $this->flashProvider->addFlash(
            FlashMessageProviderInterface::TYPE_ERROR, self::TRANSLATE_KEY, []);
    }

    protected function expectsTranslatorTranslates()
    {
        $this->translator->expects($this->once())
            ->method('trans')
            ->with(self::TRANSLATE_KEY, [], 'MilioooMessagingBundle')
            ->will($this->returnValue(self::TRANSLATED_STRING));
    }
}
