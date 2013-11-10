<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Message;

use Miliooo\Messaging\Builder\Model\ReplyBuilderModel;
use Miliooo\Messaging\Model\ThreadInterface;

/**
 * Builder for reply messages
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ReplyBuilderInterface
{
    /**
     * Builds a new thread object with a reply message
     *
     * @param ReplyBuilderModel $builderModel
     *
     * @return ThreadInterface The thread with the new message
     */
    public function build(ReplyBuilderModel $builderModel);
}
