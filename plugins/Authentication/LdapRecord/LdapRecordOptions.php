<?php

class LdapRecordOptions
{
    private $config = [];

    public function __construct()
    {
        Log::Debug('LdapRecordOptions constructed.');
        $configPath = ROOT_DIR . '/plugins/Authentication/LdapRecord/LdapRecord.config.php';
        if (file_exists($configPath)) {
            $configFile = require($configPath);
            $this->config = $configFile['settings'] ?? [];
        }
    }

    public function getHosts()
    {
        $controllers = $this->config['domain.controllers'] ?? '';
        return !empty($controllers) ? explode(',', $controllers) : [];
    }

    public function getBaseDn()
    {
        return $this->config['basedn'] ?? '';
    }

    public function getAdminUsername()
    {
        return $this->config['username'] ?? null;
    }

    public function getAdminPassword()
    {
        return $this->config['password'] ?? null;
    }

    public function getPort()
    {
        return $this->config['port'] ?? 389;
    }

    public function isSsl()
    {
        return ($this->config['use.ssl'] ?? false) === true || ($this->config['use.ssl'] ?? 'false') === 'true';
    }

    public function isTls()
    {
        return ($this->config['use.tls'] ?? false) === true || ($this->config['use.tls'] ?? 'false') === 'true';
    }

    public function retryAgainstDatabase()
    {
        return ($this->config['database.auth.when.ldap.user.not.found'] ?? false) === true || ($this->config['database.auth.when.ldap.user.not.found'] ?? 'false') === 'true';
    }

    public function getAccountSuffix()
    {
        return $this->config['account.suffix'] ?? '';
    }

    public function syncGroups()
    {
        return ($this->config['sync.groups'] ?? false) === true || ($this->config['sync.groups'] ?? 'false') === 'true';
    }

    public function getAttributeMapping()
    {
        $mapping = [];
        $mapString = $this->config['attribute.mapping'] ?? '';
        $pairs = explode(',', $mapString);
        foreach ($pairs as $pair) {
            $parts = explode('=', $pair);
            if (count($parts) == 2) {
                $mapping[trim($parts[0])] = trim($parts[1]);
            }
        }
        return $mapping;
    }
}