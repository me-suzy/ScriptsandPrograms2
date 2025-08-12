<?php   
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com
    /***
     * This event simply transfert you to an empty page.
     *
     * @package PASEvents
     * @author Philippe Lewicki  <phil@sqlfusion.com>
     * @copyright  SQLFusion LLC 2001-2004
     * @version 3.0
     */

  if (strlen($goto) > 0) {
    $nextpage = new Display($goto);
    $this->setDisplayNext($nextpage);
  }

?>