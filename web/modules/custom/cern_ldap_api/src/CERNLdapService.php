<?php

namespace Drupal\cern_ldap_api;

/**
 * CERNLdapService is a service to contact CERN Ldap service
 */

Class CERNLdapService {
  private $server_host = "xldap.cern.ch";
  private $server_port = '389';
  private $conn = NULL;

  /**
   * Constructs the service object with initialized connection with ldap server
   */
  public function __construct() {
    $this->conn = ldap_connect($this->server_host, $this->server_port);
  }

  /**
   * Resolves emails for an e-group
   * @param string $egroup
   *   The egroup name in simple formar, ex: drupal-admins
   * @param array &$list_emails
   *   Reference variable that will store all the emails extracted from the e-group
   * @param array &$entries_visited
   *   Reference array to store already visited entries and avoid cycles
   * @param bool $recursive
   *   Flag to enable/disable recursive lookup of inner e-groups
   */
  public function resolveEgroup($egroup, &$list_emails, &$entries_visited, $recursive=TRUE) {
    //If not first add the egroup to the visited list
    $entries_visited["$egroup"] = "$egroup";
    
    $result = ldap_search($this->conn, "OU=e-groups,OU=Workgroups,DC=cern,DC=ch", "CN=$egroup", array('member'), NULL, 0);
    # Check e-group existence
    if(!$result || !ldap_count_entries($this->conn, $result)){
      return;
    }
    $entries = $this->cleanUpEntry(ldap_get_entries($this->conn, $result));
    foreach ($entries as $entry){
      //For each entry traverse the members
      foreach ($entry["member"] as $member){
        //Check if member was already processed
        if(array_key_exists("$member", $entries_visited)){
           continue;
        }
        $entries_visited["$member"] = "$member";
        //If e-group and flag enabled, recurse
        if ($this->isEgroup($member) && $recursive){
          $egroupName = $this->getEgroupNameFromDN($member);
          $this->resolveEgroup($egroupName, $list_emails, $entries_visited, $recursive);
          continue;
        }
        //Otherwise check email
        $email = $this->getEmailAddress($member);
        # If email was detected add it to the final list
        if($email){
          $list_emails["$email"] = $email;
        }
      }
    }
  }

  /**
   * Returns the email address from an specific LDAP entry
   * @param string $entry
   *   Entry in the format of full DN
   */
  protected function getEmailAddress($entry) {
    $result = ldap_search($this->conn, $entry, "CN=*", array('mail'), NULL, 0);
    $entries = $this->cleanUpEntry(ldap_get_entries($this->conn, $result));    
    return (array_key_exists('mail', $entries[0])) ? $entries[0]['mail'][0] : "";
  
  }

  /**
   * Cleaup ldap_get_entries output, removes count element from array From php.net
   */
  protected function cleanUpEntry( $entry ) {
    foreach($entry as $key=>$val) {
      # (int)0 == "count", so we need to use ===
      if($key === "count")
        unset($entry[$key]);
      elseif(is_array($val))
        $entry[$key] = $this->cleanUpEntry($entry[$key]);
    }
    return $entry;
    
  }

  /**
   * Checks whether a DN passed as parameter is an e-group or not
   */
  protected function isEgroup($entry){
    return preg_match( "/,OU=e-groups,OU=Workgroups,DC=cern,DC=ch$/", $entry);
  }


  /**
   * Returns the name of an e-group from the full DN
   */
  function getEgroupNameFromDN($entry){
    $matches = array();
    preg_match( "/CN=(.*),OU=e-groups,OU=Workgroups,DC=cern,DC=ch$/", $entry, $matches);
    if (isset($matches[1])){
      return $matches[1];
    }
    return "";
  }
  
  


  


}
