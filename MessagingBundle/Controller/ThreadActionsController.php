<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\MessagingBundle\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * This class is responsible for handling thread actions.
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class ThreadActionsController
{
    /**
     * @param Request $request The request
     */
    public function threadAction(Request $request)
    {
        $data = $request->query->all();
        var_dump($data);
        exit('test');
    }
}
