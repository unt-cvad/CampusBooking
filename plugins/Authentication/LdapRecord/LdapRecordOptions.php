<?php

require_once(ROOT_DIR . '/lib/Config/namespace.php');

class LdapRecordOptions
{
    private $_options = [];

    public function __construct()
    {
        require_once(dirname(__FILE__) . '/LdapRecord.config.php');

        Configuration::Instance()->Register(
            dirname(__FILE__) . '/LdapRecord.config.php',
            '',
            LdapRecordConfigKeys::CONFIG_ID,
            false,
            LdapRecordConfigKeys::class
        );
    }


    public function getHosts()
    {
        return $this->config['hosts'] ?? [];
    }

    public function getBaseDn()
    {
        return $this->config['base_dn'] ?? '';
    }

    public function getAdminUsername()
    {
        return $this->config['admin_username'] ?? null;
    }

    public function getAdminPassword()
    {
        return $this->config['admin_password'] ?? null;
    }
    
    public function getPort()
    {
        return $this->config['port'] ?? 389;
    }

    public function isSsl()
    {
        return $this->config['use_ssl'] ?? false;
    }

    public function isTls()
    {
        return $this->config['use_tls'] ?? false;
    }

    public function retryAgainstDatabase()
    {
        return $this->config['database_fallback'] ?? false;
    }
}