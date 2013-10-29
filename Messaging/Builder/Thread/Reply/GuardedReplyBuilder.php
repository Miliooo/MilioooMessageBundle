<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\Reply;

/**
 * The guarded reply builder is responsible for creating a valid reply thread object.
 *
 * It only builds a valid reply object when all the requirements are met.
 * This makes our model much more safer, even if we don't use the forms.
 *
 * Ideally the forms should catch most exceptions... Because throwing an exception
 * is quite expensive.
 *
 * It also doesn't take into account is satisified by specifications.
 * The only guards we care of are guards which would make incomplete or broken objects.
 *
 * The specifications should be handles by the forms.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class GuardedReplyBuilder extends ReplyBuilder
{
    public function build()
    {
        parent::build();
    }
}
