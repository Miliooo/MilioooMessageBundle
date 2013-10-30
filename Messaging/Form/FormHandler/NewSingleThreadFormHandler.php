<?php

/*
 * This file is part of the MilioooMessageBundle package.
 *
 * (c) Michiel boeckaert <boeckaert@gmail.com>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

/**
 * Description of NewThreadSingleRecipientFormHandler
 *
 * @author Michiel Boeckaert <boeckaert@gmail.com>
 */
class NewSingleThreadFormHandler
{
      /**
     * The request the form will process
     *
     * @var Request
     */
    protected $request;

    /**
     * A participant provider instance
     *
     * @var ParticipantProvider
     */
    protected $participantProvider;

    /**
     * Constructor.
     *
     * @param Request $request The request the form will process
     * @param ParticipantProvider $participantProvider A participantprovider instance
     */
    public function __construct(Request $request, ParticipantProviderInterface $participantProvider)
    {
        $this->request = $request;
        $this->participantProvider = $participantProvider;
    }

    /**
     * Processes the form with the request
     *
     * @param Form $form A form instance
     *
     * @return Message|false the last message if the form is valid, false otherwise
     */
    public function process(Form $form)
    {
        if ('POST' !== $this->request->getMethod()) {
            return false;
        }

        $form->bind($this->request);

        if ($form->isValid()) {


            /*
             * At this point we created the form model and know it's valid...
             *
             * The logical step now is to create the thread object and store it
             *
             * But if we allow the creation of multiple threads that would be a prime candidate for
             * job queues.
             *
             * The solution i'm trying is to add a form model processor which have the method process.
             *
             * The StoreNewThreadProcessor stores the new thread to the database
             * The queueNewThreadProcessor stores the form model somewhere.
             *
             * - We can then recreate that form model object
             * - Call the storenewthreadprocessor and process it that way.
             *
             *
             */
            $thread = $this->createThreadObjectFromFormData($form);
            $this->persistThread($thread);

            return $thread->getLastMessage();
        }

        return false;
    }


}
