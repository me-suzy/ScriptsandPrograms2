<?php
//
// +---------------------------------------------------------------------+
// | phpOpenTracker - The Website Traffic and Visitor Analysis Solution  |
// +---------------------------------------------------------------------+
// | Copyright (c) 2000-2003 Sebastian Bergmann. All rights reserved.    |
// +---------------------------------------------------------------------+
// | This source file is subject to the phpOpenTracker Software License, |
// | Version 1.0, that is bundled with this package in the file LICENSE. |
// | If you did not receive a copy of this file, you may read it online  |
// | at http://www.phpopentracker.de/license.html.                       |
// +---------------------------------------------------------------------+
//
// $Id: search_engines.php,v 1.8 2003/09/19 07:17:29 bergmann Exp $
//

/**
* Stores information about search engines and search engine keywords.
*
* @author   Sebastian Bergmann <sb@sebastian-bergmann.de>
* @version  $Revision: 1.8 $
* @since    phpOpenTracker-Search_Engines 1.0.0
*/
class phpOpenTracker_LoggingEngine_Plugin_search_engines extends phpOpenTracker_LoggingEngine_Plugin {
  /**
  * @var string $table
  */
  var $table = 'pot_search_engines';

  /**
  * @return array
  * @access public
  */
  function post() {
    if ($this->container['first_request'] &&
        !empty($this->container['referer_orig'])) {
      if (!$ignoreRules = @file(POT_CONFIG_PATH . 'search_engines.ignore.ini')) {
        return phpOpenTracker::handleError(
          sprintf(
            'Cannot open "%s".',
            POT_CONFIG_PATH . 'search_engines.ignore.ini'
          ),
          E_USER_ERROR
        );
      }

      if (!$matchRules = @file(POT_CONFIG_PATH . 'search_engines.match.ini')) {
        return phpOpenTracker::handleError(
          sprintf(
            'Cannot open "%s".',
            POT_CONFIG_PATH . 'search_engines.match.ini'
          ),
          E_USER_ERROR
        );
      }

      $ignore = false;

      foreach ($ignoreRules as $ignoreRule) {
        if (preg_match(trim($ignoreRule), $this->container['referer_orig'])) {
          $ignore = true;
          break;
        }
      }

      if (!$ignore) {
        foreach ($matchRules as $matchRule) {
          if (preg_match(trim($matchRule), $this->container['referer_orig'], $tmp)) {
            $keywords = $tmp[1];
          }
        }

        $searchEngineName = phpOpenTracker_Parser::match(
          $this->container['referer_orig'],
          phpOpenTracker_Parser::readRules(
            POT_CONFIG_PATH . 'search_engines.group.ini'
          )
        );
      }

      if (isset($keywords) && isset($searchEngineName)) {
        $this->db->query(
          sprintf(
            "INSERT INTO %s
                         (accesslog_id,
                          search_engine, keywords)
                  VALUES ('%d',
                          '%s', '%s')",

            $this->table,
            $this->container['accesslog_id'],
            $this->db->prepareString($searchEngineName),
            $this->db->prepareString($keywords)
          )
        );

        $this->container['referer']      = '';
        $this->container['referer_orig'] = '';
        $this->container['referer_id']   = 0;
      }
    }

    return array();
  }
}

//
// "phpOpenTracker essenya, gul meletya;
//  Sebastian carneron PHP."
//
?>
