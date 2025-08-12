<?php

/**
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      not_documented_yet
*/

   /**
    *
    * @version      $Id: class.datagrid2smarty.php,v 1.6 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      not_documented_yet
    */
class datagrid2smarty {

    var $datagrid         = null;
	var $translations     = array (
	                            "hits"            => "Hits",
	                            "showing entries" => "Showing entries from %d to %d",
	                            "per page"        => "per Page"
	                        );
	
	function datagrid2smarty (&$datagrid) {
	    $this->datagrid = $datagrid;
	}
	
	function assign (&$smarty, $params) {
	    assert ($this->datagrid != null);
        $this->datagrid->setTranslations ($this->translations);

		$smarty->assign ('data',          $this->datagrid->getData());
		$smarty->assign ('searchRow',     $this->datagrid->getSearchRow());
		$smarty->assign ('headline',      $this->datagrid->getHeadline($params['command'],""));
		$smarty->assign ('hiddenFields',  $this->datagrid->getHiddenFields());
        list ($colgroup, $colspan) = $this->datagrid->getColGroup();
	    $smarty->assign ('colGroup',      $colgroup);
	    $smarty->assign ('colspan',       $colspan);
	    $smarty->assign ('navigation',    $this->datagrid->getNavigation($params['command'],""));
	}
}

?>
