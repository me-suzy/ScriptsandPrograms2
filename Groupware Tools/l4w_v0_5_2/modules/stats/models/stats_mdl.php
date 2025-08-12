<?php

  /**
    * $Id: stats_mdl.php,v 1.12 2005/08/04 15:48:30 carsten Exp $
    *
    * Model for users. See easy_framework for more information
    * about controlers and models.
    * @package stats
    */
    
  /**
    *
    * Users Model Class
    * @package stats
    */
    class stats_model extends l4w_model {

        
       /**
        * Constructor.
        *
        * Defines the models attributes
        * 
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        class $smarty smarty instance, can be null
        * @param        class $AuthoriseClass instance to control authorisation, can be null.
        * @package      easy_framework
        * @since        0.4.0
        * @version      0.4.1
        */
        function stats_model ($smarty, $AuthoriseClass) {

            // parents constructor
            parent::easy_model($smarty); // call of parents constructor!
            
            $this->command  = new easy_string  ("show_requests", 
                array ("show_requests",
                       "show_workflow_history"
                            ));
                 
            // precise defaults and definitions of field entries           
            include ('fields_definition.inc.php');
            //$this->entry['use_user']          = new easy_integer (null,0);                            
        }


       /**
        *
        * @access       public
        * @param        array array holding the requests parameters
        * @package      stats
        * @since        0.5.2
        * @version      0.5.2
        */    
        function pageStatsGraph ($length, $from, $till) {

            $query = "
                    SELECT SUM(counter), s.day FROM ".TABLE_PREFIX."page_stats s
                    GROUP BY day 
                    ORDER BY day ASC
                ";
            return $this->getGraph($length, $from, $till, $query);
        }
             
       /**
        *
        * @access       public
        * @param        array array holding the requests parameters
        * @package      stats
        * @todo         get rid of hardcoded ticket and id
        * @since        0.5.2
        * @version      0.5.2
        */    
        function workflowGraph () {

            $query = "
                    SELECT h.old_value AS y_old, h.new_value AS y_new, tstamp AS x from ".TABLE_PREFIX."history h
                    where col='state' AND
                          object_type='".$this->entry['type']->get()."' AND
                          object_id=".$this->entry['id']->get()."
                    order by tstamp asc;
                ";
            //echo $query;
            
            // get creation date
            $query_first = "
                SELECT created FROM ".TABLE_PREFIX."metainfo
                WHERE object_type='".$this->entry['type']->get()."' AND
                      object_id=".$this->entry['id']->get()."
            ";   
            if (!$res = $this->ExecuteQuery ($query_first, 'problem getting first entry', __FILE__, __LINE__)) 
                return "failure";
            $row = mysql_fetch_array($res);
            $creation_date = explode (" ", $row['created']);
            $first['x'] = $creation_date[0];
            $first['y'] = 0; // dummy
            
            // get values for "last" object
            $last['x'] = date ("Y-m-d");
            $last['y'] = 0; // dummy
            
            return $this->getGanttGraph($query, $first, $last);
        }

       /**
        *
        * @access       public
        * @param        array array holding the requests parameters
        * @package      stats
        * @since        0.5.2
        * @version      0.5.2
        */    
        function getGraph ($length, $from, $till, $query) {
    		//global $timescale_condition;
    
            $datay = array();
            $datax = array();
    		$use_title = '';
    
            switch ($length) {
            	case 1:
            	    $scale      = 60*60*24;
            		
            		//$start_date = $von; //date ("Y-m-d", $start_time);
    				$start_time = mktime (1,1,1,substr($from,3,2), 
    										    substr($from,0,2),
    											substr($from,7,4));
    				$start_date = date ("Y-m-d", $start_time);
    				
    				$point2stop = (time()+$scale);
    				$point2stop = mktime (1,1,1,substr($till,3,2), 
    										    substr($till,0,2),
    											substr($till,7,4)) + ($scale);
    				if ($point2stop < $start_time) {
    				    die ("Endedatum liegt vor Anfangsdatum!");
    				}
            		$timescale_condition = "(timestamp >= '$start_date')";
            		break;
            }
    
    		$use_title = "Übersicht ";
    		
    		//echo $query;
    		$res = mysql_query ($query);
    		$executed_query = $query;
            echo mysql_error();
            
    		$mydata = array();
    		$i = 0;
            while ($row = mysql_fetch_array ($res)) {
            	//$mydata[str_replace ('-','',$row['timestamp'])] = $row[0];
            	//$mydata[$i]['ts']    = str_replace ('-','',$row['timestamp']);
    			$mydata[$i]['ts']    = $row['day'];
    			$mydata[$i]['value'] = $row[0];
    			$i++;
            }
    
            $time = $start_time;
            $i    = 0;
    		$target_array = array();
    		$alt_array    = array();
    		//echo "*".date("d.m.Y H:i:s",time()+$scale);
            while ($time < $point2stop) {
    
               	$datay[$i] = 0;
                $ts0 = date("Ymd", $time);
                $ts1 = date("Ymd", $time + $scale);
    			//$target_array[$i] = "stats2.php?use_type=".$type."&ts0=".$ts0."&ts1=".$ts1."&from=".$from."&till=".$till."&length=".$length;
    			$alt_array[$i]    = "Details";
    
                foreach ($mydata AS $key => $datum) {
                	//echo "Comparing: Datum: ".$datum['ts'].", ts0: ".$ts0.", ts1: ".$ts1." ";
                	if ($datum['ts'] >= $ts0 && $datum['ts'] < $ts1) {
                		$datay[$i] += $datum['value'];
                	}
                }
    			$datax[$i] = date ("d.m", $time);
    			
            	$time += $scale;
            	$i++;
            }
    
         	// Setup the graph 
    		$graph = new Graph(550,200); 
    		$graph->img->SetMargin(30,20,60,20); 
    		$graph->SetMarginColor('white'); 
    		$graph->SetScale("linlin"); 
    
    		// Setup title 
    		$graph->title->Set($use_title); 
    		$graph->title->SetFont(FF_VERDANA,FS_NORMAL,11); 
    
    		// Note: requires jpgraph 1.12p or higher 
    		//$graph->SetBackgroundGradient('blue','navy',GRAD_HOR,BGRAD_PLOT); 
    		//$graph->tabtitle->Set('Region 1' ); 
    		//$graph->tabtitle->SetWidth(TABTITLE_WIDTHFULL); 
    
    		// Enable X and Y Grid 
    		$graph->xgrid->Show(); 
    		$graph->xaxis->SetTickLabels($datax);
    	    //$graph->xaxis->SetLabelAngle(50);
    		//$graph->xgrid->SetColor('gray@0.5'); 
    		//$graph->ygrid->SetColor('gray@0.5'); 
    
    		// Format the legend box 
    		$graph->legend->SetColor('navy'); 
    		$graph->legend->SetFillColor('white'); 
    		$graph->legend->SetLineWeight(1); 
    		$graph->legend->SetFont(FF_ARIAL,FS_NORMAL,8); 
    		$graph->legend->SetShadow('#eeeeee',1); 
    		//$graph->legend->SetAbsPos(15,120,'right','bottom'); 
    
    		// Create the line plots 
    		
    		$p1 = new LinePlot($datay); 
    		$p1->SetColor("blue"); 
    		//$p1->SetFillColor("#bfbfff"); 
    		$p1->SetWeight(2); 
    		$p1->mark->SetType(MARK_DIAMOND,5,0.6); 
    	    $p1->mark->SetColor('red');
    		$p1->SetLegend('test'); 
    		
    	
    		$bp = new BarPlot($datay);
    		$bp->SetFillColor('#eeeeee');
    			
    		$graph->Add($p1); 
    		$graph->Add($bp);
    		
    		//$graph->Add($bplot);
            
            return $graph;
        }       
	
       /**
        *
        * @access       public
        * @param        array array holding the requests parameters
        * @package      stats
        * @since        0.5.2
        * @version      0.5.2
        */    
        function getDevelopmentGraph ($query) {
    
            $datay = array();
            $datax = array();
    		$use_title = '';
    
    		$use_title = "Übersicht ";
    		
    		//echo $query;
    		$res = mysql_query ($query);
    		$executed_query = $query;
            echo mysql_error();
            
    		$mydata = array();
    		$i      = 0;
    		$last_x = 0;
    		$last_y = 0;
            while ($row = mysql_fetch_array ($res)) {
                if ($last_x != 0) {
        			$datax[$i] = $row['x'];
        			$datay[$i] = $last_y;
        			$i++;                    
                }    
    			$datax[$i] = $row['x'];
    			$datay[$i] = $row['y'];
    			$last_x    = $row['x'];
    			$last_y    = $row['y'];
    			$i++;
            }
			$datax[$i] = date("YmdHis");
			$datay[$i] = $last_y;
    
            //var_dump ($mydata);
            //die();
            
         	// Setup the graph 
    		$graph = new Graph(550,200); 
    		$graph->img->SetMargin(30,20,60,20); 
    		$graph->SetMarginColor('white'); 
    		$graph->SetScale("linlin"); 
    
    		// Setup title 
    		$graph->title->Set($use_title); 
    		$graph->title->SetFont(FF_VERDANA,FS_NORMAL,11); 
        
    		// Enable X and Y Grid 
    		$graph->xgrid->Show(); 
    		$graph->xaxis->SetTickLabels($datax);
    
    		// Format the legend box 
    		$graph->legend->SetColor('navy'); 
    		$graph->legend->SetFillColor('white'); 
    		$graph->legend->SetLineWeight(1); 
    		$graph->legend->SetFont(FF_ARIAL,FS_NORMAL,8); 
    		$graph->legend->SetShadow('#eeeeee',1); 
    		//$graph->legend->SetAbsPos(15,120,'right','bottom'); 
    
    		// Create the line plots 
    		
    		$p1 = new LinePlot($datay); 
    		$p1->SetColor("blue"); 
    		//$p1->SetFillColor("#bfbfff"); 
    		$p1->SetWeight(2); 
    		$p1->mark->SetType(MARK_DIAMOND,5,0.6); 
    	    $p1->mark->SetColor('red');
    		$p1->SetLegend('test'); 
    	
    		//$bp = new BarPlot($datay);
    		//$bp->SetFillColor('#eeeeee');
    			
    		$graph->Add($p1); 
    		//$graph->Add($bp);
    		
    		//$graph->Add($bplot);
            
            return $graph;
        }       

       /**
        *
        * @access       public
        * @param        array array holding the requests parameters
        * @package      stats
        * @since        0.5.2
        * @version      0.5.2
        */    
        function getGanttGraph ($query, $first, $last) {
    
            $datay = array();
            $datax = array();
    		$use_title = "Übersicht ";
    		
    		//echo $query;
    		$res = mysql_query ($query);
    		$executed_query = $query;
            echo mysql_error();
            
    		$mydata = array();

    		// add first entry
    		$datax[0] = $first['x'];
    		
    		$i      = 1;
            while ($row = mysql_fetch_array ($res)) {
                $date = explode (" ", $row['x']);
    			$datax[$i] = $date[0];
    			$datay[$i] = $row['y_new'];
    			if ($i == 1)
    			    $datay[0] = $row['y_old'];
    			$i++;
            }
            
            // add last entry
    		$datax[$i] = $last['x'];
    		$datay[$i] = $datay[($i-1)];

			//$datax[$i] = date("YmdHis");
			//$datay[$i] = $last_y;
                
         	// Setup the graph 
    		/*$graph = new Graph(550,200); 
    		$graph->img->SetMargin(30,20,60,20); 
    		$graph->SetMarginColor('white'); 
    		$graph->SetScale("linlin");*/ 
    
            $graph = new GanttGraph(0,0,"auto");
            $graph->SetShadow();
            
            // Add title and subtitle
            $graph->title->Set("A nice main title");
            $graph->title->SetFont(FF_ARIAL,FS_BOLD,12);
            $graph->subtitle->Set("(Draft version)");
            
            // Show day, week and month scale
            $graph->ShowHeaders(GANTT_HDAY | GANTT_HWEEK | GANTT_HMONTH);
            
            // Instead of week number show the date for the first day in the week
            // on the week scale
            $graph->scale->week->SetStyle(WEEKSTYLE_FIRSTDAY);
            
            // Make the week scale font smaller than the default
            $graph->scale->week->SetFont(FF_FONT0);
            
            // Use the short name of the month together with a 2 digit year
            // on the month scale
            $graph->scale->month->SetStyle(MONTHSTYLE_SHORTNAMEYEAR2);

            for ($i=1; $i < count ($datax); $i++) {
                // Format the bar for the first activity
                // ($row,$title,$startdate,$enddate)
                $start = $datax[($i-1)];
                $end   = $datax[$i];
                $tmp1  = explode ("-", $end);
                $tmp2  = explode ("-", $start);
                $diff  = mktime (0,0,0,$tmp1[1], $tmp1[2], $tmp1[0]) - mktime (1,1,1,$tmp2[1], $tmp2[2], $tmp2[0]);
                $activity  = new GanttBar(($i-1),get_state_name('ticket', $datay[($i-1)]),$start ,$end, (string)round($diff / (60*60*24))."d");
            
                // Yellow diagonal line pattern on a red background
                $activity->SetPattern(BAND_RDIAG,"yellow");
                $activity->SetFillColor("red");
            
                // Finally add the bar to the graph
                $graph->Add($activity);
            }
    			
            
            return $graph;
        }       

       /**
        * Show all entries.
        *
        * @access       public
        * @author       Carsten Graef <evandor@gmx.de>
        * @copyright    evandor media 2004
        * @param        array array holding the requests parameters
        * @package      easy_framework
        * @since        0.1.0
        * @version      0.1.0
        */
        function collect_basic_stats ($params) {
            global $db_hdl, $logger, $gacl_api;
                        
            $logger->log ('Call of function '.__CLASS__.'::'.__FUNCTION__, 7);

            // --- security -----------------------------------------
            /*if (!$gacl_api->acl_check('Usermanager', 'Show Usermanager', 'Person', $_SESSION['user_id'])) {
                die ("security check failed in ".__FILE__);    
            } */

            // --- handle gui elements ------------------------------
            list ($this->entry, $omitted) = $this->handleGUIElements ($params);

                        
            return "success";
        }
            
    }   

?>