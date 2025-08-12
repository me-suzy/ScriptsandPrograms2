<?php
/**
*    
*       (c) www.sourceworkshop.com 
*	@class:rowset_pager
*	@version: 1.1
*	@author: Konstantin Atanasov
*	@file: rowset_pager.php
*	@description: manage rowset pages
*	@notes: need MySQL, PHP4, mysql extension must be instaled
*       
*        usage:
*            create MySQL connection 
*            $rs =  mysql_query(sql)  where sql is valid SELECT statement
*            include this script
*        create object:
*            $rp  = new rowset_pager($rs); 
*            // first parameter must be valid resource indentifier returned from 
*                    mysql_query function
*            use: $rp->showPageNavigator($url) to show navigator in page
*                    $url - current page url
*            $rp->fetch_array() - iterate over a recordset, show only current page records
*            $rp->goPage(page)  - set current page 
*        
*/
/*
NO WARRANTY
11. BECAUSE THE PROGRAM IS LICENSED FREE OF CHARGE, THERE IS NO WARRANTY FOR THE PROGRAM, TO THE EXTENT PERMITTED BY APPLICABLE LAW. EXCEPT WHEN OTHERWISE STATED IN WRITING THE COPYRIGHT HOLDERS AND/OR OTHER PARTIES PROVIDE THE PROGRAM "AS IS" WITHOUT WARRANTY OF ANY KIND, EITHER EXPRESSED OR IMPLIED, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE. THE ENTIRE RISK AS TO THE QUALITY AND PERFORMANCE OF THE PROGRAM IS WITH YOU. SHOULD THE PROGRAM PROVE DEFECTIVE, YOU ASSUME THE COST OF ALL NECESSARY SERVICING, REPAIR OR CORRECTION.
*/

//
//    config constants
//
define ("ICONS_PATH","icons/");
define ("PREV_PAGE_ICON","left_arrow.gif");
define ("NEXT_PAGE_ICON","right_arrow.gif");


    class rowset_pager {
        var
           $currentPage = 0,
           $db_engine = null,
           $lastPage = 0,
           $nextPage = 0,
           $prevPage = 0,
           $rowset = null,
           $url,
           $pageLen = 10,
           $pagesCount = 0,
           $currentRow = 0,
           $rowsCount = 0;
    
        /**
        *    constructor
        *    @parameter    reference to $db_engine object
        *    @parameter    mysql rowset reference
        *    @parameter    startPage    start page value
        */
        function rowset_pager(&$rowset,$startPage = 0) {
            if ($rowset == null) { return null; }
            $this->rowset = &$rowset;
            if (isset($_GET['page'])) { // go to page if set page in url request 
                $startPage = $_GET['page'];
            }
            $this->init();
            $this->goPage($startPage);
            return this;
        }
        
        /**
        *    set rowset 
        *
        */
        function setRowset($rs) {
            $this->rowset = $rs;
            $this->init();
        }
        
        /**
        *        return rowset reference
        *
        */
        function getRowset() {
            return $this->rowset;
        }
        
        /**
        *    set last page value
        *
        */
        function init() {    
            $this->rowsCount = mysql_num_rows($this->rowset);
            $this->lastPage = (integer)floor($this->rowsCount / $this->pageLen);
        }
        
        /**
        *    go to page
        *
        */
        function goPage($page) {
            if (($page > $this->lastPage) || ($page < 0)) { return FALSE;}
            $this->currentPage = $page;
            $this->setCurrentRow($this->pageLen * $this->currentPage);
        }
        
        /**
        *    set current record 
        *    return FALSE when current record > last page record
        */
        function setCurrentRow($row) {    
              if (($row > $this->getLastPageRow()) || ($row < $this->getFirstPageRow())) {
                return FALSE;
              } else {
                  $this->currentRow = $row;
                  $seek = $this->currentRow -1;
                  if ($seek < 0) { $seek = 0;}
                  if ($seek >= $this->rowsCount) { $seek = $this->rowsCount -1;}
                  mysql_data_seek($this->rowset,$seek);
                  return $this->currentRow;
              }
        }    
        
        /**
        *    process commands sends in URL 
        *
        *
        */
        function processCmd() {    
            $cmd = $_GET['pager_cmd'];
            $page = $_GET['page'];
            switch ($cmd) {
                case "goNext": { $this->goNextPage();break;}
                case "goPrev": { $this->goPrevPage();break;}
                case "goLast": { $this->goLastPage();break;}
                case "goPage": { $this->goPage($page);break;}
            }
        }
        
        /**
        *    return go page html;
        *
        */
        function showGoPage($url = "",$URLParams = "",$cssClass = 'goPageField') {
            $html = "<FORM action='$url$URLParams' method=get>
                    <INPUT height=8px class=$cssClass value=$this->currentPage name=page type=text size=4 maxLenght=4 
                        style='border:1px solid grey'>
                    <INPUT class=$cssClass name=pager_cmd type=hidden size=4 maxLenght=4 style='border:1px solid grey'>
                    </FORM>";
                    
            return $html;
        }
        
        /**
        *    return page navigator html
        *
        */
        function showPageNavigator($url = "",$URLParams = "",
                                   $showPages = 7,$cssClass = 'pageNavigator',
                                   $withGoPage = false) {
            $this->url = $url; 
            $begin_page_part = ($showPages - 1) / 2;
            
            $startPage = $this->currentPage - 1 - $begin_page_part;  
            if ($startPage < 1) { $startPage = 1; } 
                                            
            $endPage = $this->currentPage + 1 + $begin_page_part;
            if ($endPage > $this->lastPage) { $endPage = $this->lastPage; }
            
            if ($withGoPage == true) {
                $goPage = $this->showGoPage($url,$URLParams);
            }
            for($i = $startPage; $i < $endPage;$i++) {
                $pagesHtml = $pagesHtml .  $this->getGoPageLink($i,$cssClass,"",$URLParams); 
            }
           
            if ($this->currentPage == 0) { 
                $prevPageLink = "";
                $firstPageLink = ""; 
            } else {
                $prevPageLink  = $this->getGoPrevPageLink($cssClass,$URLParams);
                $firstPageLink = $this->getGoPageLink(0,$cssClass,"1",$URLParams);
            }
        
            if ($this->currentPage  == $this->lastPage) { 
                $nextPageLink = "";
                $lastPageLink = ""; 
            } else {
                $nextPageLink  = $this->getGoNextPageLink($cssClass,$URLParams);
                $lastPageLink  = $this->getGoPageLink($this->getLastPage(),$cssClass,"",$URLParams);
            }
            $endHtml =  "<SPAN>$goPage</SPAN>";//<TR></TABLE>";
            
            $html = $firstPageLink . $prevPageLink . " &nbsp;$pagesHtml " . $nextPageLink . $lastPageLink;
            return $html;
        }
        
        /**
        *    return go page link html
        *
        */
        function getGoPageLink($page = 0,$cssClass = "pageNavigator",$label = "",$LinkParams = "") {
            if ($label == "") {    
                $label =$this->getLinkLabel($page);
            } 
            $html = "<A href='$this->url?pager_cmd=goPage&page=$page$LinkParams' target='_self' class=$cssClass >&nbsp;$label&nbsp</A>";
            return $html;
        }
        
        /**
        *    return netx page link html
        *
        */
        function getGoNextPageLink($cssClass = "pageNavigator",$URLParams = "") {
            return $this->getGoPageLink($this->currentPage + 1,$cssClass,
                "<IMG align=bottom valign=absbottom class=navigatorImage src=" . ICONS_PATH .
                 NEXT_PAGE_ICON . ">",$URLParams);
        }
        
        /**
        *    return prev page link html
        *
        */
        function getGoPrevPageLink($cssClass = "pageNavigator",$URLParams = "") {
            return $this->getGoPageLink($this->currentPage - 1,$cssClass,
                "<IMG align=bottom valign=absbottom class=navigatorImage src=" . ICONS_PATH  .
                    PREV_PAGE_ICON . ">",$URLParams);
        }    
    
        /**
        *    return first page row value
        *
        */  
        function getFirstPageRow() {
            return $this->pageLen * $this->currentPage;
        }
        
        /**
        *    return last page row value
        *
        */
        function getLastPageRow() {
            $lastPageRow = $this->getFirstPageRow() + $this->pageLen;
            if ($lastPageRow > $this->rowsCount) { $lastPageRow = $this->rowsCount; }    
            return $lastPageRow;
        }
        
        /**
        *    return next row value
        *
        */
        function goNextRow() {
            return $this->setCurrentRow($this->currentRow + 1);
        }
        
        /**
        *    return one record from recordset as array or FALSE if
        *    
        */
        function fetch_array() {
            if ($this->goNextRow() == FALSE) { return FALSE; }    
            $row = mysql_fetch_array($this->rowset);
            return $row;
        }
        
        
        /**
        *
        *
        */    
        function goNextPage() {
            $this->goPage($this->currentPage+1);
        }
        
        /**
        *     prev page
        *
        */
        function goPrevPage() {
            $this->goPage($this->currentPage - 1);
        }
        
        /**
        *    go to last page
        *
        */
        function goLastPage() {
            $this->goPage($this->lastPage);
        }
        
        /**
        *    get last page
        *
        */
        function getLastPage() {
            return $this->lastPage ;
        }
        
        /**
        *    get next page value
        *
        */                
        function getNextPage() {
            if (($this->currentPage +1) <= $this->getLastPage()) {
               return $this->currentPage + 1;
            } else {
                return $this->currentPage;
            }
        }
        
        /**
        *    get prev page value
        *
        */      
        function getPrevPage() {
            if (($this->currentPage - 1) >= 0) {
                return $this->currentPage - 1;
            }  else {
                $this->currentPage = 0;
            } 
        }

        /**
        *    set lenght of page
        *
        */
        function setPageLen($length = 20) {
            $this->pageLen = $length;
            $this->init();
        }
        
        /**
        *    return link label
        *
        */
        function getLinkLabel($page) {
            $label = $page + 1;
            if ($page == $this->currentPage) {
                $label = "<SPAN class=currentPage>$label</SPAN>";
            } 
            if (($page == "") || (isset($page) == FALSE)) {
                $label = "";
            }
            return $label;
        }
        
        
        
        
 } // end class
?>
