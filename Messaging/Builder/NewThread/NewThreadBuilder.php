<?php

/*
 * This file is part of the MilioooMessageBundle package.
 * 
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\Builder\NewThread;

/**
 * The builder for a new thread.
 *
 * This class is responsable for creating a new thread object from the setters.
 *
 * Since this is the last step before saving the object to the database we want
 * to make sure it's a valid thread object.
 *
 * That's why this class is abstract and we use the guarded classes.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
abstract class NewThreadBuilder
{
    //put your code here
}
