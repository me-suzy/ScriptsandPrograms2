<?php

/**
* Controller Class to derive your controllers from
*
* @author       Carsten Graef <evandor@gmx.de>
* @copyright    evandor media 2005
* @package      EasyFramework
*/

   /**
    * Easy Framework: Controller Superclass
    *
    * @version      $Id: easy_controller.class.php,v 1.5 2005/08/06 06:57:08 carsten Exp $
    * @author       Carsten Graef <evandor@gmx.de>
    * @copyright    evandor media 2005
    * @package      EasyFramework
    */
    class easy_controller {

        /**
        * The model to use. See easy_model.class.php.
        *
        * @var class $_model
        */
    	var $model  		= null;

       /**
        * params
        *
        * @var array $params
        */
        var $params		= array();

        /**
        * The view to use
        *
        * @var string view
        */
        var $view   		= null;

    var $model_state 	= "new";
	var $start          = null;
	var $timer_only     = true;
	
	var $smarty         = null;
	var $transitions    = null;
	    
    var $AuthoriseClass = null;

	function easy_controller($transitions = null) {

		if (class_exists("Smarty"))
    		$this->smarty = new Smarty;

		$this->transitions = $transitions;
    }

   	function run ($use_model) {
    	global $easy;
    	global $USE_AUTO_PROFILING,$MODEL_AUTO_PRINT_R;

    	//$this->doRequest();
		$this->params = $_REQUEST;

        $this->manage_history();

        $this->getModel($use_model);

        // no command given, maybe only show view?
        if (!isset($this->params['command'])) {
            assert ('isset ($this->params["view"])');
            //die (var_dump ($this->view));
            if (strtolower(substr ($this->params['view'], strlen ($this->params['view'])-6, 6)) == "smarty") {
                return $this->renderSmarty($this->params['view']);
            }
		    else
                return $this->render($this->params['view']);
        }

		assert ('$this->model->command != null');

        // add params to model (and validate)
        list ($this->model->entry, $dummy) = $this->model->addParams2Model ($this->model->entry, $_REQUEST);

        $this->handleModel(); // overwritten by descendend class

        // Serialize Model
		$this->serializeModel();

		if (strtolower(substr ($this->view, strlen ($this->view)-6, 6)) == "smarty") {
            return $this->renderSmarty($this->view);
        }
		else {
            return $this->render($this->view);
        }
    }

	// to be compatible to older versions.
	// !!! should be removed before the first official release
	function doGet () {
		$this->doRequest(); //
	}

    function doRequest() {
		// !!! what about $this->params = $_GET?
		//var_dump ($this->model);
		//die (var_dump ($_REQUEST));
		
		/*if (strtoupper($_SERVER["REQUEST_METHOD"]) == "GET") {
			if (isset ($_SERVER['QUERY_STRING'])) {
    	        $this->query = new query_string ($_SERVER['QUERY_STRING']);    
        	    parse_str ($this->query->get(), $this->params);
        	}
		}
		else {
			$this->params = $_POST;
		}*/
    }
    
    function manage_history () {
        // no command, so return
        if (!isset($this->params['command'])) return;
        
        if ($this->params['command'] == "return") {
    	    array_pop($_SESSION["EASY_HISTORY"]);
            return;
    	}
    	
	    $_SESSION["EASY_HISTORY"][] = $this->params;

        $hist_length = count($_SESSION["EASY_HISTORY"]);
        if ($hist_length > 1) {
    	    if ($_SESSION["EASY_HISTORY"][$hist_length-2]['command'] ==
	        		$this->params['command']) {
    	    	array_pop($_SESSION["EASY_HISTORY"]);
	    	    return;
            }
        }
    	if ($hist_length > 10)
    		array_shift($_SESSION["EASY_HISTORY"]);
    }

    function get_params() { return $this->params; }

    function getModel($name) {
    	if ($this->model != null) die ("Model exists");
		// easy_model_id exists (sent via parameter) ?
       	(isset ($this->params['easy_model_id'])) ? $use_model_id = $this->params['easy_model_id'] : $use_model_id = "";
       	if ($use_model_id == "") {
       	    $this->model = new $name($this->smarty, $this->AuthoriseClass);
       	    $this->model_state = "new";
       	}
       	else {
       	    $this->model_state = "restored";
			$this->model = unserialize($_SESSION['easy_models'][$use_model_id]);
        }
    }
	
	function setView ($file = null) {
   		$this->view = $file;
	}
	
	function setViewByTransition ($command, $result = "success") {
        assert ('!is_null($this->transitions)');
        assert ('isset ($this->transitions[$command])');
        $this->view = $this->transitions[$command][$result];
	}

	function getView (){
		return $this->view;
	}
	
	//$this->getNextView($done, "views/show_categories.smarty");
    function getNextView ($success, $default, $params) {
        global $easy;
        
        $view = $default;
        
        if ($success && isset($params['successview'])) {
            $view = $params['successview'];
        }
        if (!$success && isset($params['failedview'])) {   
	        $view = $params['failedview'];
	    }
	    
	    if ($success && isset($params['successpage'])) {
	        $this->redirect ($params['successpage'], '', $params);
        }
        if (!$success && isset($params['failedpage'])) {   
	        $this->redirect ($params['failedpage'], '', $params);
	    }
	    
        //$easy->logger->log ("next view: ".$view);
        //die ($view);
	    $this->setView ($view);
    }

	// abstract function 
    function handleModel () {
    }

	function serializeModel () {
		//echo "*".$this->model->get_model_id()."*";
		/*if ($this->model->get_model_id() != null)
			$_SESSION['easy_models'][$this->model->get_model_id()] = 
				serialize ($this->model);*/
	}
		
    function render ($tpl) {
		if ($tpl != null) { // normal execution
	        //$tpl = "none.tpl";
	        assert ("file_exists('$tpl')");
			include ($tpl);
			return null;
		}
		else { // no template available, so return model
			return $this->model;
		}
    }

    function renderSmarty ($tpl) {
    	die ("smarty currently not supported");
		//$this->smarty->display(DOC_ROOT.dirname($_SERVER['SCRIPT_NAME'])."/".$tpl);
    }


    function clean_up () {
    	global $easy;
    	global $USE_AUTO_PROFILING,$MODEL_AUTO_PRINT_R,$SESSION_AUTO_PRINT_R,
    	       $USE_PROFILING_STATS;

		// Thing about level of profiling, level of debugging, logging aso.
		if ($USE_AUTO_PROFILING) {
			$easy->stop_profiling();
			$profile_html = $easy->get_profiling_html();
			$fh = fopen ("debug/profiler.html", "wb");
			fwrite ($fh, $profile_html);
			fclose ($fh); 
		}
		// find better name than auto_print_r
		if ($MODEL_AUTO_PRINT_R) {
			$model_vars = print_r ($this->model, true);
			$fh = fopen ("debug/model.php", "wb");
			fwrite ($fh, "<pre>".htmlspecialchars($model_vars)."</pre>");
			fclose ($fh); 
					
		}
		if ($SESSION_AUTO_PRINT_R) {
			$session_vars = print_r ($_SESSION, true);
			$patterns[0] = "/\[password\] => (.)*/";
			$patterns[1] = "/\[passwort\] => (.)*/";
			$patterns[2] = "/\[pass\] => (.)*/";
			$patterns[3] = "/\[passwd\] => (.)*/";

			$replacements[0] = "[password] => ****";
			$replacements[1] = "[passwort] => ****";
			$replacements[2] = "[pass] => ****";
			$replacements[3] = "[passwd] => ****";

			$session_vars = preg_replace($patterns, $replacements, $session_vars);
			
			$fh = fopen ("debug/session.php", "wb");
			fwrite ($fh, "<pre>".htmlspecialchars($session_vars)."</pre>");
			fclose ($fh); 
		}
		if ($USE_PROFILING_STATS && $this->timer_only) {
		    $end   = $this->getmicrotime(microtime());
			$start = $this->getmicrotime($this->start);
			$time  = str_replace (",",".",round ($end - $start,3));
			$fh = fopen ("debug/timestats.log", "ab");
			fwrite ($fh, $time."\n");
			fclose ($fh); 
		}
    }

    function goBack($page) {
		$params = $_SESSION["EASY_HISTORY"][count($_SESSION["EASY_HISTORY"])-2];
        $full_path = "Location: http://".$_SERVER['HTTP_HOST']
                      .dirname($_SERVER['SCRIPT_NAME'])
                      ."/".$page."?";
        foreach ($params AS $param => $value) {
        	$full_path .= "&".$param."=".$value;
        }
        //die($full_path);
		header($full_path);
		exit;
    	
    }
    
	// !!! Alternatives???
    function create_form_start ($command, $action, $method = "post", $onSubmit = "Validate") {
    	$js_onSubmit = "";
    	
	    return '<form name="Formular" action="'.$action.'" method="'.$method.'" '.$js_onSubmit.'>
		  <input type="hidden" name="easy_model_id"  value="'.$this->model->get_model_id().'">
		  <input type="hidden" name="next_token"     value="'.$this->model->get_next_token().'">
          <input type="hidden" name="command"        value="'.$command.'">
        ';
	}

    function redirect ($page, $command, &$params) {
		$full_path = "Location: http://".$_SERVER['HTTP_HOST']
                      .dirname($_SERVER['SCRIPT_NAME'])
                      ."/".$page."?command=$command";
        foreach ($params AS $param => $value) {
        	if ($param != "command")
	        	$full_path .= "&".$param."=".urlencode($value);
        }
        //die($full_path);
        header($full_path);
		exit;
    }

	function getmicrotime($microtime){ 
	    list($usec, $sec) = explode(" ",$microtime); 
    	return ((float)$usec + (float)$sec); 
    } 

    function registerAuthoriseClass (&$class) {
        $this->AuthoriseClass = $class;    
    }
}
?>