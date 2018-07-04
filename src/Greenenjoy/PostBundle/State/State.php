<?php

namespace  Greenenjoy\PostBundle\State;

class State
{
    const STANDBY = 'standby';
    const POSTED = 'posted';

    static public function getValues()
    {
        $values[] = State::STANDBY;
        $values[] = State::POSTED;

        return $values;
    }
}