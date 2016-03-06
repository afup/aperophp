<?php

namespace Aperophp\Meetup;

class UserTransformer
{
    /**
     * @param array $rsvp
     *
     * @return array
     *
     * @throws \Exception
     */
    public function transform(array $rsvp)
    {
        if ($rsvp['response'] != "yes") {
            return null;
        }

        return array(
            'meetup_com_id' => $rsvp['member']['member_id'],
            'firstname' => $rsvp['member']['name'],
        );
    }
}
