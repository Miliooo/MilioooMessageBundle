<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Miliooo\Messaging\ThreadProvider\Folder;

use Pagerfanta\Pagerfanta;
use Miliooo\Messaging\User\ParticipantInterface;

/**
 * Archived provider for pagerfanta pagination
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
interface ArchivedProviderPagerFantaInterface
{
    /**
     * Gets paginated archived threads
     *
     * @param ParticipantInterface $participant The participant for whom we get the archived threads
     * @param integer $currentPage The page we are on
     *
     * @return Pagerfanta The pager fanta object with the current page and max per page items set.
     */
    public function getArchivedThreadsPagerfanta(ParticipantInterface $participant, $currentPage);
}
