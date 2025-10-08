<?php

use LdapRecord\Connection;
use LdapRecord\Container;
use LdapRecord\Models\ActiveDirectory\User as LdapUser;

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

        $connectionOptions = [
            'hosts' => $this->options->getHosts(),
            'base_dn' => $this->options->getBaseDn(),
            'username' => $this->options->getAdminUsername(),
            'password' => $this->options->getAdminPassword(),
            'port' => $this->options->getPort(),
            'use_ssl' => $this->options->isSsl(),
            'use_tls' => $this->options->isTls(),
        ];
        
        $connection = new Connection($connectionOptions);
        Container::addConnection($connection);
    }

    public function Validate($username, $password)
    {
        Log::Debug("LdapRecord plugin: Starting authentication attempt for user '$username'.");
        $this->password = $password;

        try {
            $connection = Container::getConnection();
            Log::Debug("LdapRecord plugin: Connecting to LDAP server.");
            $connection->connect();
            Log::Debug("LdapRecord plugin: Connection successful.");

            $userPrincipalName = $username . $this->options->getAccountSuffix();
            Log::Debug("LdapRecord plugin: Attempting to authenticate with UPN '$userPrincipalName'.");

            if ($connection->auth()->attempt($userPrincipalName, $password)) {
                Log::Debug("LdapRecord plugin: User '$username' successfully authenticated against LDAP.");
                return true;
            } else {
                Log::Debug("LdapRecord plugin: Authentication failed for user '$username'.");
            }
        } catch (\LdapRecord\Auth\BindException $e) {
            Log::Error("LdapRecord plugin: Bind exception during authentication for user '$username'. Message: " . $e->getMessage());
        } catch (\Exception $e) {
            Log::Error("LdapRecord plugin: An unexpected error occurred during authentication for user '$username'. Message: " . $e->getMessage());
        }

        if ($this->options->retryAgainstDatabase()) {
            Log::Debug("LdapRecord plugin: LDAP authentication failed. Falling back to database authentication for user '$username'.");
            return $this->authToDecorate->Validate($username, $password);
        }

        return false;
    }

    public function Login($username, $loginContext)
    {
        Log::Debug("LdapRecord plugin: Synchronizing user '$username'.");
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
            Log::Debug("LdapRecord plugin: Searching for user '$username' in Active Directory.");
            $ldapUser = LdapUser::where('samaccountname', '=', $username)->first();

            if (!$ldapUser) {
                Log::Error("LdapRecord plugin: Could not find user '$username' in Active Directory after successful authentication.");
                return;
            }

            Log::Debug("LdapRecord plugin: Found user. Synchronizing attributes.");
            
            $groups = [];
            if ($this->options->syncGroups()) {
                Log::Debug("LdapRecord plugin: Group sync is enabled. Fetching groups for user '$username'.");
                $ldapGroups = $ldapUser->groups()->get();
                Log::Debug("LdapRecord plugin: Found " . count($ldapGroups) . " groups in AD.");
                foreach($ldapGroups as $ldapGroup) {
                    $groupName = $ldapGroup->getFirstAttribute('cn');
                    $groups[] = $groupName;
                    Log::Debug("LdapRecord plugin: Syncing group '$groupName'.");
                }
            }

            $registration = new Registration();
            $registration->Synchronize(
                new AuthenticatedUser(
                    $username,
                    $ldapUser->getFirstAttribute('mail'),
                    $ldapUser->getFirstAttribute('givenname'),
                    $ldapUser->getFirstAttribute('sn'),
                    $this->password,
                    Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE),
                    Configuration::Instance()->GetDefaultTimezone(),
                    $ldapUser->getFirstAttribute('telephonenumber'),
                    $ldapUser->getFirstAttribute('company'),
                    $ldapUser->getFirstAttribute('title'),
                    $groups
                )
            );
             Log::Debug("LdapRecord plugin: Synchronization complete for user '$username'.");

        } catch (\Exception $e) {
            Log::Error("LdapRecord plugin: An error occurred during user synchronization for '$username'. Message: " . $e->getMessage());
        }
    }
    
    // ... all other methods remain the same
}