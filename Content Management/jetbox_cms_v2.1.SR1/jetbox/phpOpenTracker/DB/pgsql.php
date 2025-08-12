<?php
//
// phpOpenTracker - The Website Traffic and Visitor Analysis Solution
//
// Copyright 2000 - 2004 Sebastian Bergmann. All rights reserved.
//
// Licensed under the Apache License, Version 2.0 (the "License");
// you may not use this file except in compliance with the License.
// You may obtain a copy of the License at
//
//   http://www.apache.org/licenses/LICENSE-2.0
//
// Unless required by applicable law or agreed to in writing, software
// distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
// See the License for the specific language governing permissions and
// limitations under the License.
//
// $Id: pgsql.php,v 1.16.4.1.2.3 2004/01/24 19:59:40 bergmann Exp $
//

/**
* phpOpenTracker PostgreSQL Database Handler
*
* @author   Sebastian Bergmann <sb@sebastian-bergmann.de>
* @version  $Revision: 1.16.4.1.2.3 $
* @since    phpOpenTracker 1.0.0
*/
class phpOpenTracker_DB_pgsql extends phpOpenTracker_DB {
  /**
  * Constructor.
  *
  * @access public
  */
  function phpOpenTracker_DB_pgsql() {
    $this->phpOpenTracker_DB();

    $connectionString = sprintf(
      "%s %s dbname=%s user=%s password=%s",
      ($this->config['db_host'] == 'socket')  ? '' : 'host=' . $this->config['db_host'],
      ($this->config['db_port'] == 'default') ? '' : 'port=' . $this->config['db_port'],
      $this->config['db_database'],
      $this->config['db_user'],
      $this->config['db_password']
    );

    if (!$this->connection = @pg_connect($connectionString)) {
      return phpOpenTracker::handleError(
        'Could not connect to database.',
        E_USER_ERROR
      );
    }
  }

  /**
  * Fetches a row from the current result set.
  *
  * @access public
  * @return array
  */
  function fetchRow() {
    $row = @pg_fetch_array($this->result);

    if (is_array($row)) {
      return $row;
    }

    return false;
  }

  /**
  * Performs an SQL query.
  *
  * @param  string           $query
  * @param  optional mixed   $limit
  * @param  optional boolean $warnOnFailure
  * @access public
  */
  function query($query, $limit = false, $warnOnFailure = true) {
    if ($limit != false) {
      $query .= ' LIMIT ' . $limit;
    }

    if ($this->config['debug_level'] > 1) {
      $this->debugQuery($query);
    }

    @pg_freeresult($this->result);
    $this->result = @pg_exec($this->connection, $query);

    if (!$this->result && $warnOnFailure) {
      phpOpenTracker::handleError(
        @pg_errormessage($this->connection),
        E_USER_ERROR
      );
    }
  }

  /**
  * Prepares a string for an SQL query.
  *
  * @param  string $string
  * @return string
  * @access public
  */
  function prepareString($string) {
    return str_replace(
      array("'",  '\\'),
      array("''", '\\\\'),
      substr($string, 0, 254)
    );
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>
