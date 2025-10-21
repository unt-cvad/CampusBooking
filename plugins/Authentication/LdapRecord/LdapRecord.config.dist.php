<?php

/**
 * LdapRecord plugin configuration (distribution/sample)
 *
 * Copy this file to `LdapRecord.config.php` and edit values to match your
 * environment. Each setting below includes a short description and the
 * expected value format.
 */

return [
    'settings' => [
        // Comma-separated list of LDAP/AD domain controllers or LDAP hosts.
        // Example: 'dc1.example.org,dc2.example.org'
        'domain.controllers' => '',

        // LDAP port (389 for plain LDAP, 636 for LDAPS, 3268 for Global Catalog)
        'port' => 389,

        // Bind username used for searching/binding to LDAP. Can be a UPN
        // (user@domain) or DN depending on your LDAP setup.
        'username' => '',

        // Bind password for the above username. Keep this secure — if you
        // place secrets in this file consider restricting file permissions.
        'password' => 'changeme',

        // Base DN used for searches (the root of your user tree)
        // Example: 'OU=Users,DC=example,DC=org'
        'basedn' => '',

        // LDAP protocol version (usually 3)
        'version' => 3,

        // Use SSL (LDAPS) when connecting to the LDAP server. If true, port
        // is commonly 636.
        'use.ssl' => false,

        // Account suffix appended to usernames when forming UPNs
        // Example: '@example.org'. Only used when configuration expects UPN.
        'account.suffix' => '',

        // When a user is not found in LDAP, should the plugin attempt to
        // authenticate against the local database (fallback)? true/false
        'database.auth.when.ldap.user.not.found' => true,

        // Attribute mapping instructs the plugin how to map LDAP attributes
        // to LibreBooking user fields. Format: 'local=ldap,local2=ldap2'.
        // Default maps common AD attributes to LibreBooking fields.
        'attribute.mapping' => 'sn=sn,givenname=givenname,mail=mail,telephonenumber=telephonenumber,physicaldeliveryofficename=physicaldeliveryofficename,title=title',

        // Comma-separated list of AD groups a user must belong to in order
        // to be allowed access. Empty string disables this check.
        'required.groups' => '',

        // Whether to synchronize AD groups into LibreBooking (true/false).
        'sync.groups' => false,

        // If true, the plugin will assume Single Sign-On (SSO) is used and
        // will not show or expect a password during login.
        'use.sso' => false,

        // If true, usernames will NOT be cleaned (stripped of domain
        // components) — preserves original username strings.
        'prevent.clean.username' => false,
    ],
];