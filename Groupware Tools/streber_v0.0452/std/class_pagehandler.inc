<?php
# streber - a php5 based project management system  (c) 2005 Thomas Mann / thomas@pixtur.de
# Distributed under the terms and conditions of the GPL as stated in _docs/license.html


/**
 * Representation of an internal page (like 'home') or form ('taskEdit')
 *
 * @includedby:     *
 *
 * @author:         Thomas Mann
 * @uses:
 * @usedby:
 *
 */
class PageHandle {
    #--- members ----
    public  $id=NULL;
    public  $title='';
    public  $req=NULL;
    public  $type='norm';
    public  $rights_required=0;
    public  $valid_for_anonymous=0;
    public  $ignore_from_handles=0;     # this blocks invalid from-handle-warnings

    public function __construct($args=NULL)
    {
        global $PH;
        if(!$args) {
            throw new myException("can't create page-handle without params");
        }
        foreach($args as $key=>$value) {
            is_null($this->$key);       # cause a notice-message if undefined member used
            $this->$key= $value;
        }

        if(!$this->id || !$this->req) {
            throw new myException("A PageHandle needs at lest 'id' and 'required' as params");
        }
        $PH->addPage($this);
    }
}


/**
* pages containing forms. Pass from-handle
*/
class PageHandleForm extends PageHandle{
    public $type='form';
}

/**
* pages for submitting data, instantly rendering another page, Pass from-handle
*/
class PageHandleSubm extends PageHandle{
    public $type='subm';
}

/**
* pages for function processing, instantly rendering another pages, Pass from-handle
*/
class PageHandleFunc extends PageHandle{
    public $type='func';
}


class PageHandler {
    PUBLIC      $hash;
    PUBLIC      $message;
    public      $cur_page_md5=NULL;
    public      $cur_page_id;
    PUBLIC      $options;
    public      $go_submit=false;   # this is put into go-hidden-field
    public      $cur_page=NULL;     # page-handle object
    public      $page_params=NULL;  # params of the currently rendered page (used for client-view-url)

    public function __construct()
    {
        global $PH;
        if(isset($PH)) {
            trace("NOTICE","PageHandle can only be created once");
        }
        else {
            $this->hash=array();
#            $PH=$this;
        }
        $this->options=array();
    }

    public function addPage(PageHandle $phandle)
    {
        if(!$phandle || !is_object($phandle)){
            throw new myException("PageHandler::addPage requires a pageHandle as argument");
        }
        if(!isset($phandle->id) || $phandle->id=='') {
            throw new myException("PageHandler::addPage. PageHandle needs a valid id");
        }
        if(isset($this->hash[$phandle->id])) {
            trace("WARNING","Pagehandle '$phandle->id' has already been added");
        }
        $this->hash[$phandle->id]=$phandle;
    }


    /**
    * returns url of the curret page (with all necessary parameters)
    *
    * This function only returns valid Links for 'Normal' pages (not for forms, etc)
    * for all other, it returns NULL
    *
    * if flag_exit is set, returns link to valid original page
    */
    public function getClientViewUrl($flag_exit=false) 
    {
        if(get_class($this->cur_page) == 'PageHandle') {
            if(!isset($this->page_params) || !$params=$this->page_params) {
                $params=array();
            }
            if(!$flag_exit) {
                $params['show_client_view']=1;
            }
            
            return $this->getUrl($this->cur_page->id,$params);
        }
    }

    /**
    * return valid url to this page, checks rights & param
    */
    public function getUrl($id=NULL, $params=NULL)
    {
        global $auth;

        if(!$id || !isset($this->hash[$id]) ) {
            throw new myException("PageHandle::getUrl id '$id' is not valid");
        }
        $phandle= $this->hash[$id];

        ### enough rights? ###
        if($phandle->rights_required && !($phandle->rights_required & $auth->cur_user->user_rights)) {
            return NULL;
        }
        else {
            $str_params='';
            if($params) {
                $str_params='';
                foreach($params as $key=>$value) {
                    $str_params.='&'.$key. '='.$value;
                }
            }
            if($this->cur_page_md5) {
                $str_params.= '&from='.$this->cur_page_md5;
            }
            $buffer= "index.php?go={$id}{$str_params}";
            return $buffer;
        }
    }

    /**
    * getLink (return nothing, if not enough user-rights)
    */
    public function getLink($id=NULL, $name=NULL, $params=NULL,$style=NULL)
    {
        ### try to get url ###
        if($url=$this->getUrl($id,$params)) {
            if(!$name && $this->hash[$id]->title) {
                $name= $this->hash[$id]->title;
            }
            $class=$style
                ? "class='$style'"
                : '';

            $buffer= '<a '.$class.' href="'. $url. '">'. $name .'</a>';
            return $buffer;
        }
        ### probably not enough rights ###
        else {
            return NULL;
        }
    }
    #--------------------------------------------------------------------
    # getPage / checks the id and returns valid page, DOES NOT CHECK FOR RIGHTS
    #--------------------------------------------------------------------
    public function getPage($id)
    {
        global $auth;

        if(!$id || !isset($this->hash[$id]) ) {
            throw new myException("PageHandle::getPage id '$id' is not valid");
        }
        return $this->hash[$id];
    }

    #--------------------------------------------------------------------
    # getValidPage / checks the id and returns valid page, CHECK FOR RIGHTS
    #--------------------------------------------------------------------
    public function getValidPage($id)
    {
        global $auth;

        if(!$id || !isset($this->hash[$id]) ) {
            throw new myException("PageHandle::getPage id '$id' is not valid");
        }

        ### check sufficient user-rights ###
        $handle=$this->hash[$id];
        if($handle->rights_required && !($handle->rights_required & $auth->cur_user->user_rights)) {
            return NULL;
        }
        return $this->hash[$id];
    }

    #--------------------------------------------------------------------
    # getPage / checks the id and returns valid page-id
    #--------------------------------------------------------------------
    public function getValidPageId($id)
    {
        global $auth;

        if(!$id || !isset($this->hash[$id]) ) {
            throw new myException("PageHandle::getPage id '$id' is not valid");
        }

        ### check sufficient user-rights ###
        $handle=$this->hash[$id];
        if($handle->rights_required && !($handle->rights_required & $auth->cur_user->user_rights)) {
            return NULL;
        }
        return $id;
    }




    /**
    * getHandle for the current page
    *
    * A FromHandle links an intern url (including a parameter-list) to an MD5-checksum. Those
    * pairs are stored server sided in './tmp/from_pages.lst'. The from_handle is stored by
    * the page-handler and is appended as GET-parameter to all urls created afterwards.
    *  Additionally it's automatically added as hidden-field at the beginning of PageContentOpen()
    * to pass it on form-submit.
    * For all start-pages (pages with lists), the from-handle should be set by this function before
    * creating a page-object.
    *
    */
    public function defineFromHandle($params=NULL)
    {
        global $auth;


        #--- create new md5-handle and store page-id and params in local file
        if(!$params) {
            $params=array();
        }
        $params['id']=$this->cur_page_id;
        $from= http_build_query($params);
        $md5= md5($from);

        $this->cur_page_md5= $md5;

        $flag_already_stored=false;

        ### use modified version of user-cookie as filename ###
        $filename= confGet('DIR_TEMP').md5($auth->cur_user->cookie_string);

        ### read current from-handles ###
        if(is_readable($filename)) {
        	if (!($FH = fopen ( $filename, 'r'))) {
                die ('could not open page-history. This might be cause by insufficient directory rights.');
	        }
	        $data = fread ($FH, 64000);
    	    fclose ($FH);

            $arr= split("\n",$data);

            ### convert to assoc. array and look for md5 ###
            $stored_handles=array();
            foreach($arr as $line) {
                $tmp_arr=split("\|",$line);
                if(count($tmp_arr)==2) {
                    $stored_handles[$tmp_arr[0]]=$tmp_arr[1];
                }
                if(count($stored_handles) > MAX_STORED_FROM_HANDLES) {
                    break;
                }
            }
            ### current from-handle already in there ###
            if(isset($stored_handles[$md5])) {
                $flag_already_stored= true;
            }

        }
        else {
            $arr=array();
        }

        ### add handle and write to file ###
        if(!$flag_already_stored) {
            $count= count($arr);

            ### if full remove last ###
            if($count > MAX_STORED_FROM_HANDLES) {
                array_unshift($arr, $md5.'|'.$from);
            }
            ### append ###
            else {
                array_unshift($arr, $md5.'|'.$from);
            }

        	$result= @rename($filename, $filename."_tmp");     # surpressing FILE-EXISTs notice
        	$FH=fopen ($filename."_tmp","w");
        	fputs ($FH, join($arr,"\n"));                       # join the array
        	fclose ($FH);
        	$result= @rename($filename."_tmp", $filename);     # surpressing FILE-EXISTs notice
        }
        #--- write to file --
    	#$result=chmod ("$tmp_dir/$filename", 0777);
        #$result=unlink("$tmp_dir/$filename");
    	#$result=chmod ("$tmp_dir/$filename", 0664);

        $this->page_params=$params;     # keep for client-view-url
        return $md5;
    }



    #--------------------------------------------------------------
    # returns param-array for from-handle
    #--------------------------------------------------------------
    public function getFromParams($from_handle=NULL)
    {
        global $auth;
        if(!$from_handle) throw new myException("getFromParams requires a from-string");

        ### use modified version of user-cookie as filename ###
        $filename= confGet('DIR_TEMP').md5($auth->cur_user->cookie_string);


        ### read current from-handles ###
        if(is_readable($filename)) {
        	if (!($FH = fopen ( $filename, 'r'))) {
                die ('could not open page-history. This might be cause by insufficient directory rights.');
	        }
	        $data = fread ($FH, 64000);
    	    fclose ($FH);

            $arr= split("\n",$data);

            ### convert to assoc. array and look for md5 ###
            $stored_handles=array();
            foreach($arr as $line) {
                $tmp_arr=split("\|",$line);
                if(count($tmp_arr)==2) {
                    $stored_handles[$tmp_arr[0]]=$tmp_arr[1];
                }
                if(count($stored_handles) > MAX_STORED_FROM_HANDLES) {
                    break;
                }
            }
            ### current from-handle already in there ###
            if(isset($stored_handles[$from_handle])) {

                $params= array();
                parse_str($stored_handles[$from_handle], $params);
                return $params;
            }
        }
        return NULL;
    }

    #--------------------------------------------------------------------
    # showFromPage if available
    #--------------------------------------------------------------------#
    # NOTE returns false if $from is not available
    public function showFromPage($from_handle=NULL)
    {
        if(!$from_handle) {
            $from_handle= get('from');
            if(!$from_handle) {
                return false;
            }
        }
        global $PH;
        if($params= $PH->getFromParams($from_handle)) {
            $id= $PH->getPage($params['id'])->id;         # be sure the page-id is value
            unset($params[$id]);            # don't pass the id as param
            $PH->show($id,$params);
        }
        else {
            return false;
        }
        return true;
    }


    #--------------------------------------------------------------------
    # show()
    #--------------------------------------------------------------------
    public function show($id=NULL, $params=NULL,$fn_argument=NULL)
    {
        global $auth;
        if(!$id || !isset($this->hash[$id]) ) {
            #throw new myException("PageHandle::call() id '$id' is not valid");
            $this->show('error');
            return;
        }
        $handle=$this->hash[$id];

        ### not authenticated ###
        if(!isset($auth) || !$auth->cur_user) {
            if(!$handle->valid_for_anonymous)
            {
                $this->message="As an anonymous user you have not enough rights to view page '$id'";
                $this->show('loginForm');
                exit;
            }
        }


        ### fake client-view ###
        if($client_view=get('show_client_view')) {
            
            ### just a tempary change 
            $auth->cur_user->theme= confGet('THEME_CLIENT_VIEW');
            
            $auth->cur_user->user_rights=0;
        }


        ### check sufficient user-rights ###
        if($handle->rights_required && !($handle->rights_required & $auth->cur_user->user_rights)) {
            $this->abortWarning("insufficient rights");
        }

    	require_once($handle->req);


        #--- set page-handler-curpage ---
        $keep_cur_page_id= $this->cur_page_id;  # show() might be called again, so we have to keep the page_id

        $this->cur_page_id= $id;

        $keep_cur_page= $this->cur_page;
        $this->cur_page= $handle;


        ### submit ###
        if($handle->type='subm') {
            $tmp= get('from');
            if($tmp) {
                $this->cur_page_md5=$tmp;
            }
        }

        #--- set params ---
        if($params) {
            global $vars;
            foreach($params as $key=>$value) {
                $vars[$key]=$value;
            }
            $vars['go']=$id;
        }
        #--- use id as function-name ----
        if(function_exists($id)) {
            if($fn_argument) {
                $id($fn_argument);  # pass additional paramenter (eg. non-db-objects to xxxNew()-functions)
            }
            else {
                $id();
            }
        }
        else {

            $this->abortWarning("ERROR: page-call to undefined functions '$id'",ERROR_FATAL);
        }


        $this->cur_page_id= $keep_cur_page_id;
        $this->cur_page= $keep_cur_page;
    }


    #--------------------------------------------------------------------
    # abort and show error
    # - tries to display from page first
    # - otherwise shows 'home'
    #-------------------------------------------------------------------
    public function abortWarning($warning='unspecified error...',$type=ERROR_WARNING) {

        if($type == ERROR_WARNING) {
            $this->message="WARNING: operation aborted ($warning)";
        }
        else if($type == ERROR_FATAL) {
            $this->message="FATAL: operation aborted with an fatal error ($warning). Please help us by  <a href='http://streber.sourceforge.net'>reporting this bug</a>";
        }
        else if($type == ERROR_BUG) {
            $this->message="FATAL: operation aborted with an fatal error ($warning). Please help us by  <a href='http://streber.sourceforge.net'>reporting this bug</a>";
        }
        else if($type == ERROR_RIGHTS) {
            $this->message="Error: Insuffient rights";
        }
        else if($type == ERROR_DATASTRUCTURE) {
            $this->message="FATAL: operation aborted with an fatal data-base structure error ($warning). This may have happened do to an inconsistency in your database. We strongly suggest to rewind to a recent back-up. Please help us by  <a href='http://streber.sourceforge.net'>reporting this bug</a>";
        }
        else if($type == ERROR_NOTE) {
            $this->message="NOTE: $warning";
        }
        else {
            $this->message="ERROR: $warning";
        }


        if(!$this->showFromPage()) {
            $this->show('home');
        }
        exit;
    }

    public function getWikiLink() {
        return "<a href='http://wiki.pixtur.de/index.php/{$this->cur_page_id}'>Wiki+Help</a>";
    }
}

$PH=new PageHandler();

?>
