<?php

class LdapRecordUser
{
    private $fname;
    private $lname;
    private $mail;
    private $phone;
    private $institution;
    private $title;
    private $groups;

    public function __construct($entry, LdapRecordOptions $options)
    {
        Log::Debug('LdapRecordUser constructed for entry: ' . $entry->getDn());

        $this->fname = $entry->getFirstAttribute('givenname');
        $this->lname = $entry->getFirstAttribute('sn');
        $this->mail = strtolower($entry->getFirstAttribute('mail'));
        $this->phone = $entry->getFirstAttribute('telephonenumber');
        $this->institution = $entry->getFirstAttribute('company');
        $this->title = $entry->getFirstAttribute('title');
        
        $this->groups = [];
        if ($options->syncGroups()) {
            Log::Debug("LdapRecordUser: Group sync is enabled. Fetching groups.");
            $ldapGroups = $entry->groups()->get();
            Log::Debug("LdapRecordUser: Found " . count($ldapGroups) . " groups in AD.");
            foreach($ldapGroups as $ldapGroup) {
                $groupName = $ldapGroup->getFirstAttribute('cn');
                if (!empty($groupName)) {
                    $this->groups[] = $groupName;
                    Log::Debug("LdapRecordUser: Adding group '$groupName' to sync list.");
                }
            }
        } else {
            Log::Debug("LdapRecordUser: Group sync is disabled.");
        }
    }

    public function GetFirstName()
    {
        return $this->fname;
    }

    public function GetLastName()
    {
        return $this->lname;
    }

    public function GetEmail()
    {
        return $this->mail;
    }

    public function GetPhone()
    {
        return $this->phone;
    }

    public function GetInstitution()
    {
        return $this->institution;
    }

    public function GetTitle()
    {
        return $this->title;
    }

    public function GetGroups()
    {
        return $this->groups;
    }
}