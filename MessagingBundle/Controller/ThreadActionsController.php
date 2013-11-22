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
     * Constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param Request $request The request
     */
    public function threadAction(Request $request)
    {
        $threadIds = $this->getThreadIds($request);
        $threadAction = $this->getThreadAction($request);

        //no action needed here... probably no threads selected but pressed submit
        if (!is_array($threadIds) || !$threadAction) {
            exit('invalid arguments');
        }


        var_dump($threadIds);
        var_dump($threadAction);


        exit('test');
    }

    /**
     * Gets the thread ids from the request.
     *
     * @param Request $request
     *
     * @return array|false An array with thread ids selected or false when no thread id array given.
     */

    protected function getThreadIds(Request $request)
    {
        $threadIds = $request->request->get('selected_threads');

        if (empty($threadIds) || !is_array($threadIds)) {
            return false;
        }

        return $threadIds;
    }

    /**
     * Gets the thread action from the request.
     *
     * @param Request $request
     *
     * @return string|false
     */
    protected function getThreadAction(Request $request)
    {
        $threadAction = $request->request->get('thread_action');
        if (empty($threadAction) || !is_string($threadAction)) {
            return false;
        }

        return $threadAction;
    }
}
