<?php

class LdapRecordOptions
{
    private $config = [];

    public function __construct()
    {
        // Directly load the user-provided config file.
        $configPath = ROOT_DIR . '/plugins/Authentication/LdapRecord/LdapRecord.config.php';
        if (file_exists($configPath)) {
            // The config file returns an array. We need the nested part.
            $configFile = require($configPath);
            $this->config = $configFile['settings']['LdapRecord'] ?? [];
        }
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