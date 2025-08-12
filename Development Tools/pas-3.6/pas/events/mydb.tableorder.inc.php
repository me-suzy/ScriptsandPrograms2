<?php 
// Copyright 2001 - 2004 SQLFusion LLC           info@sqlfusion.com

  /**   Event Mydb.tableorder
  *
  * This event set the value to display the fields in a specific order..
  * It works with the reportTable objects.
  * <br>- param int recordpos position number in the result set of the first record to display
  * <br>- param string orderfield name of the field to order on
  * <br>- param string orderdir direction of the order (ASC, DESC)
  * <br>- param string goto url of the page to go to display the order
  *
  * @package PASEvents
  * @author Philippe Lewicki  <phil@sqlfusion.com>
  * @copyright  SQLFusion LLC 2001-2004
  * @version 3.0
  */

  $page = basename($goto) ;
  $disp = new Display($goto);

  $disp->addParam("recordpos", $recordpos) ;
  $disp->addParam("orderfield", $orderfield) ;
  $disp->addParam("orderdir", $orderdir) ;
  $disp->addParam("mydb_num", $mydb_num) ;
  $disp->save("displayTableOrder")  ;

  $this->setDisplayNext($disp) ;

?>