<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Helpers\FlashMessages;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * A flash message provider.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class FlashMessageProvider implements FlashMessageProviderInterface
{
    /**
     * A flash bag instance.
     *
     * @var FlashBagInterface
     */
    protected $flashBag;

    /**
     * A translator instance.
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * The success key
     *
     * @var string
     */
    protected $successKey;

    /**
     * The failure key
     *
     * @var string
     */
    protected $errorKey;


    /**
     * @param FlashBagInterface   $flashBag   A flash bag instance
     * @param TranslatorInterface $translator A translator instance
     * @param string              $successKey They key for success messages for example success
     * @param string              $errorKey   The key for error messages for example error
     */
    public function __construct(FlashBagInterface $flashBag, TranslatorInterface $translator, $successKey, $errorKey)
    {
        $this->flashBag = $flashBag;
        $this->translator = $translator;
        $this->successKey = $successKey;
        $this->errorKey = $errorKey;
    }

    /**
     * {@inheritdoc}
     */
    public function addFlash($type, $translationKey, $parameters = [])
    {
        $key = $type.'Key';
        $message = $this->translate($translationKey, $parameters);
        $this->flashBag->add($this->$key, $message);
    }

    /**
     * @param string $message    The message to translate
     * @param array  $parameters Optional parameters
     *
     * @return string The translated string.
     */
    protected function translate($message, $parameters = [])
    {
        return $this->translator->trans($message, $parameters, 'MilioooMessagingBundle');
    }
}
