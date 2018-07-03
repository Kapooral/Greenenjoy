<?php

namespace  Greenenjoy\CoreBundle\Roles;

class Profil
{
    const ADMIN = 'ROLE_ADMIN';

    static public function getValues()
    {
        $values[] = Profil::ADMIN;

        return $values;
    }
}