<?php

// LibreBooking configuration file edited at 2025-10-08T15:04:56+00:00

return [
    'settings' => [
        'domain.controllers' => 'dcpd-unt-pri-01.unt.ad.unt.edu',
        'port' => 389,
        'username' => 'cvad_booked_unt@unt.ad.unt.edu',
        'password' => '1vFrAYL!ofwHYdVD',
        'basedn' => 'OU=UNT,DC=unt,DC=ad,DC=unt,DC=edu',
        'version' => 3,
        'use.ssl' => false,
        'account.suffix' => '@unt.ad.unt.edu',
        'database.auth.when.ldap.user.not.found' => true,
        'attribute.mapping' => 'sn=sn,givenname=givenname,mail=mail,telephonenumber=telephonenumber,physicaldeliveryofficename=physicaldeliveryofficename,title=title',
        'required.groups' => '',
        'sync.groups' => false,
        'use.sso' => false,
        'prevent.clean.username' => false,
    ],
];
