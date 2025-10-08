<?php

use LdapRecord\Connection;
use LdapRecord\Container;
use LdapRecord\Models\ActiveDirectory\User as LdapUserModel;

require_once(ROOT_DIR . 'plugins/Authentication/LdapRecord/namespace.php');

class LdapRecord extends Authentication implements IAuthentication
{
    private $authToDecorate;
    private $options;
    private $password;

    public function __construct(IAuthentication $authentication)
    {
        Log::Debug('LdapRecord plugin constructed.');
        $this->authToDecorate = $authentication;
        $this->options = new LdapRecordOptions();

        $connection = new Connection([
            'hosts' => $this->options->getHosts(),
            'base_dn' => $this->options->getBaseDn(),
            'username' => $this->options->getAdminUsername(),
            'password' => $this->options->getAdminPassword(),
            'port' => $this->options->getPort(),
            'use_ssl' => $this->options->isSsl(),
            'use_tls' => $this->options->isTls(),
        ]);
        
        Container::addConnection($connection, 'default');
    }

    public function Validate($username, $password)
    {
        Log::Debug("LdapRecord plugin: Starting authentication attempt for user '$username'.");
        $this->password = $password;

        try {
            $connection = Container::getConnection('default');
            $connection->connect();

            $userPrincipalName = $username . $this->options->getAccountSuffix();
            Log::Debug("LdapRecord plugin: Attempting to authenticate with UPN '$userPrincipalName'.");

            if ($connection->auth()->attempt($userPrincipalName, $password)) {
                Log::Debug("LdapRecord plugin: User '$username' successfully authenticated against LDAP.");
                return true;
            }
        } catch (\Exception $e) {
            Log::Error("LdapRecord plugin: Authentication failed for user '$username'. Message: " . $e->getMessage());
        }

        if ($this->options->retryAgainstDatabase()) {
            Log::Debug("LdapRecord plugin: LDAP authentication failed. Falling back to database authentication for '$username'.");
            return $this->authToDecorate->Validate($username, $password);
        }

        return false;
    }

    public function Login($username, $loginContext)
    {
        $this->Synchronize($username);

        $userRepo = new UserRepository();
        $user = $userRepo->LoadByUsername($username);
        if ($user) {
            $user->Deactivate();
            $user->Activate();
            $userRepo->Update($user);
        }

        return $this->authToDecorate->Login($username, $loginContext);
    }

    private function Synchronize($username)
    {
        try {
            $ldapUser = LdapUserModel::where('samaccountname', '=', $username)->first();

            if (!$ldapUser) {
                Log::Error("LdapRecord: Could not find user '$username' for synchronization.");
                return;
            }
            
            Log::Debug("LdapRecord plugin: Creating LdapRecordUser object for user '$username'.");
            $user = new LdapRecordUser($ldapUser, $this->options);

            $registration = new Registration();
            $registration->Synchronize(
                new AuthenticatedUser(
                    $username,
                    $user->GetEmail(),
                    $user->GetFirstName(),
                    $user->GetLastName(),
                    $this->password,
                    Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE),
                    Configuration::Instance()->GetDefaultTimezone(),
                    $user->GetPhone(),
                    $user->GetInstitution(),
                    $user->GetTitle(),
                    $user->GetGroups()
                ),
                true 
            );

            Log::Debug("LdapRecord plugin: Synchronization complete for user '$username'.");

        } catch (\Exception $e) {
            Log::Error("LdapRecord plugin: An error occurred during synchronization for '$username'. Message: " . $e->getMessage());
        }
    }
    
    // ... other methods are unchanged
}