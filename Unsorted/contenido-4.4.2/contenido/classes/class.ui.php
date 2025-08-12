<?php

/*****************************************
* File      :   $RCSfile: class.ui.php,v $
* Project   :   Contenido
* Descr     :   Contenido UI Classes
*
* Author    :   $Author: timo.hummel $
*               
* Created   :   20.05.2003
* Modified  :   $Date: 2003/09/24 16:10:50 $
*
* © four for business AG, www.4fb.de
*
* $Id: class.ui.php,v 1.9 2003/09/24 16:10:50 timo.hummel Exp $
******************************************/

class UI_Left_Top
{

	var $link;
	var $javascripts;
	
	function UI_Left_Top ()
	{
	}
	
	function setLink ($link)
	{
		$this->link = $link;
	}
	
	function setJS ($type, $script)
	{
		$javascripts[$type] = $script;
	}
	
	function render()
	{
		global $sess, $cfg;
		
		$tpl = new Template;
		
		$tpl->reset();
		$tpl->set('s', 'SESSID', $sess->id);
		
		$scripts = "";
		
		if (is_array($this->javascripts))
		{
			foreach ($this->javascripts as $script)
			{
				$scripts .= '<script language="javascript">'.$script.'</script>';
			}
		}
		
		if (is_object($this->link))
		{
			$tpl->set('s', 'LINK', $this->link->render() . $this->additional);
		} else {
			$tpl->set('s', 'LINK', '');
		}
		
		$tpl->set('s', 'JAVASCRIPTS', $scripts);	
		$tpl->set('s', 'CAPTION', $this->caption);
		$tpl->generate($cfg['path']['templates'] . $cfg['templates']['generic_left_top']); 
		
		
	}
	
	function setAdditionalContent ($content)
	{
		$this->additional = $content;
	}
	
}

class UI_Menu
{
	var $link;
	var $title;
	var $caption;
	var $javascripts;	
	var $type;
	var $image;
	var $alt;
	var $actions;
	var $padding;
	var $imagewidth;
 	var $extra;
 	var $border;
 	
	function UI_Menu ()
	{
		$this->padding = 2;
		$this->border = 0;
	}

	function setTitle ($item, $title)
	{
		$this->title[$item] = $title;
	}

	function setImage ($item, $image, $maxwidth = 0)
	{
		$this->image[$item] = $image;
		$this->imagewidth[$item] = $maxwidth;
	}
	
	function setExtra ($item, $extra)
	{
		$this->extra[$item] = $extra;
	}
		
	function setLink ($item, $link)
	{
		$this->link[$item] = $link;
	}
	
	function setActions ($item, $key, $action)
	{
		$this->actions[$item][$key] = $action;
	}
	
	function setPadding ($padding)
	{
		$this->padding = $padding;
	}
	
	function setBorder ($border)
	{
		$this->border = $border;
	}
	
	function render($print = true)
	{
		global $sess, $cfg;
		
		$tpl = new Template;
		
		$tpl->reset();
		$tpl->set('s', 'SID', $sess->id);
		
		$scripts = "";
		
		if (is_array($this->javascripts))
		{
			foreach ($this->javascripts as $script)
			{
				$scripts .= '<script language="javascript">'.$script.'</script>';
			}
		}
		
		$tpl->set('s', 'JSACTIONS', $scripts);
		$tpl->set('s', 'CELLPADDING', $this->padding);
		$tpl->set('s', 'BORDER', $this->border);
		$tpl->set('s', 'BORDERCOLOR', $cfg['color']['table_border']);
		
		if (is_array($this->link))
		{
    		foreach ($this->link as $key => $value)
    		{
    		
    			if ($value != NULL)
    			{
    				if ($this->imagewidth[$key] != 0)
    				{
    					$value->setContent('<img border="0" src="'.$this->image[$key].'" width="'.$this->imagewidth[$key].'">');
    					$img = $value->render();
    				} else {
    					$value->setContent('<img border="0" src="'.$this->image[$key].'">');
    					$img = $value->render();
    				}    			
    				$value->setContent($this->title[$key]);
    				$link = $value->render();
    			} else {
    				$link = $this->title[$key];
    				
    				if ($this->image[$key] != "")
    				{
    					if ($this->imagewidth[$key] != 0)
    					{
    						$img = '<img border="0" src="'.$this->image[$key].'" width="'.$this->imagewidth[$key].'">';
    					} else {
    						$img = '<img border="0" src="'.$this->image[$key].'">';
    					}
    				} else {
    					$img = "&nbsp;";
    				}
    			}
    			
        	    $dark = !$dark;
            	if ($dark) {
                	$bgColor = $cfg["color"]["table_dark"];
            	} else {
    	            $bgColor = $cfg["color"]["table_light"];
            	}
            	
        		$tpl->set('d', 'ICON', $img);
        		$tpl->set('d', 'NAME', $link);
        		
        		if ($this->extra[$key] != "")
        		{
        			$tpl->set('d', 'EXTRA', $this->extra[$key]);
        		}
        		

				$fullactions = "";        		
        		if (is_array($this->actions[$key]))
        		{
        			
        			foreach ($this->actions[$key] as $key => $singleaction)
        			{
        				$fullactions .= $singleaction;
        			}
        		}
        		$tpl->set('d', 'ACTIONS', $fullactions);
        		$tpl->set('d', 'BGCOLOR',  $bgColor);
        		$tpl->next();
    		}
			
		}
			
		$rendered = $tpl->generate($cfg['path']['templates'] . $cfg['templates']['generic_menu'],true);
						
		if ($print == true)
		{
			echo $rendered;	
		} else {
			return $rendered;
		} 
		
		
	}
	
}

class UI_Table_Form
{
	var $items;
	var $captions;
	var $id;
	var $rownames;
	
	var $formname;
	var $formmethod;
	var $formaction;
	var $formvars;
	
	var $tableid;
	var $tablebordercolor;
	
	var $header;
	var $cancelLink;
	
	
	function UI_Table_Form ($name, $action = "", $method = "post")
	{
		global $sess, $cfg;
		
		$this->formname = $name;
		
		if ($action == "")
		{
			$this->formaction = "main.php";
		}
		
		$this->formmethod = $method;

		$this->tableid = "";
		$this->tablebordercolor = $cfg['color']['table_border'];
		
	}
	
	function setVar ($name, $value)
	{
		$this->formvars[$name] = $value;
	}
	
	function add ($caption, $field, $rowname = "")
	{
		if ($field == "")
		{
			$field = "&nbsp;";
		}
		
		if ($caption == "")
		{
			$caption = "&nbsp;";
		}
		
		$this->id++;
		$this->items[$this->id] = $field;
		$this->captions[$this->id] = $caption; 
		
		if ($rowname == "")
		{
			$rowname = $this->id;
		}
		
		$this->rownames[$this->id] = $rowname;
	}

	function addCancel ($link)
	{
		$this->cancelLink = $link;
	}
		
	function addHeader ($header)
	{
		$this->header = $header;
	}
	
	function render ($return = true)
	{
		global $sess, $cfg;
		
		$tpl = new Template;
		
		$form  = '<form enctype="multipart/form-data" style="margin:0px" name="'.$this->formname.'" method="'.$this->formmethod.'" action="'.$this->formaction.'">'."\n";
		$this->formvars["contenido"] = $sess->id;
		
		if (is_array($this->formvars))
		{
			foreach ($this->formvars as $key => $value)
			{
                 $form .= '<input type="hidden" name="'.$key.'" value="'.$value.'">'."\n";
			}
		}
		
		$tpl->set('s', 'FORM', $form);
		$tpl->set('s', 'ID', $this->tableid);
		$tpl->set('s', 'BORDERCOLOR', $this->tablebordercolor);
		$tpl->set('s', 'SUBMITTEXT', i18n("Save changes"));
		
		if ($this->header != "")
		{
			$header  = '<tr class="text_medium" style="background-color: '.$cfg["color"]["table_header"].';">';
			$header .= '<td colspan="2" valign="top" style="border: 0px; border-top:1px; border-right:1px;border-color: '.$cfg["color"]["table_border"].'; border-style: solid;">'.$this->header.'</td></tr>';
		}
		
		$tpl->set('s', 'HEADER', $header);
		
		if (is_array($this->items))
		{
			foreach ($this->items as $key => $value)
			{
				$tpl->set('d', 'CATNAME', $this->captions[$key]);
				$tpl->set('d', 'CATFIELD', $value);
				$tpl->set('d', 'ROWNAME', $this->rownames[$key]);
				
				$dark = !$dark;
            	if ($dark) {
                	$bgColor = $cfg["color"]["table_dark"];
            	} else {
    	            $bgColor = $cfg["color"]["table_light"];
            	}
            	
            	$tpl->set('d', 'BGCOLOR', $bgColor);
            	$tpl->set('d', 'BORDERCOLOR', $this->tablebordercolor);
				$tpl->next();
			}
		}	
		
		$tpl->set('s', 'CONTENIDOPATH',$cfg["path"]["contenido_fullhtml"]);

		if ($this->cancelLink != "")
		{
			$img = '<img src="'.$cfg["path"]["contenido_fullhtml"].'images/but_cancel.gif" border="0">';
			
			$tpl->set('s', 'CANCELLINK', '<a href="'.$this->cancelLink.'">'.$img.'</a>'); 	
		} else {
			$tpl->set('s', 'CANCELLINK','');
		}
		
		$rendered = $tpl->generate($cfg["path"]["contenido"].$cfg['path']['templates'] . $cfg['templates']['generic_table_form'],true);
		
		if ($return == true)
		{
			return ($rendered);
		} else {
			echo $rendered;
		}
	}

		
}

class UI_Form
{
	var $items;
	var $content;
	var $id;
	var $rownames;
	
	var $formname;
	var $formmethod;
	var $formaction;
	var $formvars;
	
	var $tableid;
	var $tablebordercolor;
	
	var $header;
	
	function UI_Form ($name, $action = "", $method = "post")
	{
		global $sess, $cfg;
		
		$this->formname = $name;
		
		if ($action == "")
		{
			$this->formaction = "main.php";
		}
		
		$this->formmethod = $method;

	}
	
	function setVar ($name, $value)
	{
		$this->formvars[$name] = $value;
	}
	
	function add ($field, $content = "")
	{

		$this->id++;
		$this->items[$this->id] = $field;
		$this->content[$this->id] = $content; 
		
	}
	
	function render ($return = true)
	{
		global $sess, $cfg;
		
		$tpl = new Template;
		
		$form  = '<form style="margin:0px" name="'.$this->formname.'" method="'.$this->formmethod.'" action="'.$this->formaction.'">'."\n";
		$this->formvars["contenido"] = $sess->id;
		
		if (is_array($this->formvars))
		{
			foreach ($this->formvars as $key => $value)
			{
                 $form .= '<input type="hidden" name="'.$key.'" value="'.$value.'">'."\n";
			}
		}
		
		$tpl->set('s', 'FORM', $form);
		
		if (is_array($this->items))
		{
			foreach ($this->items as $key => $value)
			{
				$content .= $this->content[$key];
			}
		}

		$tpl->set('s', 'CONTENT', $content);
		
		$rendered = $tpl->generate($cfg['path']['templates'] . $cfg['templates']['generic_form'],true);
		
		if ($return == true)
		{
			return ($rendered);
		} else {
			echo $rendered;
		}
	}

		
}

class UI_Page
{
	
	var $scripts;
	var $content;
	var $margin;
	
	function UI_Page ()
	{
		$this->margin = 10; 
	}
	
	function setMargin ($margin)
	{
		$this->margin = $margin;
	}
	
	function addScript ($name, $script)
	{
		$this->scripts[$name] = $script;
	}
	
	function setReload ()
	{
		$this->scripts["__reload"] =
			'<script type="text/javascript">'.
			"parent.frames['left_bottom'].location.reload();"
			."</script>";
	}
	
	function setContent ($content)
	{
		$this->content = $content;
	}
	
	function setMessageBox ()
	{
		global $sess;
		$this->scripts["__msgbox"] = 
		   '<script type="text/javascript" src="scripts/messageBox.js.php?contenido='.$sess->id.'"></script>'.
		   '<script type="text/javascript"> window.onerror = foo;
            function foo(){return true;}

            /* Session-ID */
            var sid = "'.$sess->id.'";

            /* Create messageBox
               instance */
            box = new messageBox("", "", "", 0, 0);

           </script>';
	}    
	
	function render ($print = true)
	{
		global $sess, $cfg;
		
		$tpl = new Template;
		
		if (is_array($this->scripts))
		{
			foreach ($this->scripts as $key => $value)
			{
				$scripts .= $value;
			}
		}
		
		$tpl->set('s', 'SCRIPTS', $scripts);
		$tpl->set('s', 'CONTENT', $this->content);
		$tpl->set('s', 'MARGIN', $this->margin);
		
		$rendered = $tpl->generate($cfg['path']['templates'] . $cfg['templates']['generic_page'],false);
		
		if ($print == true)
		{
			echo $rendered;
		} else {
			return $rendered;
		}
	}

		
}

class Link
{
	
	var $link;
	var $title;
	var $targetarea;
	var $targetframe;
	var $targetaction;
	var $targetarea2;
	var $targetframe2;
	var $targetaction2;
	var $caption;
	var $javascripts;	
	var $type;
	var $custom;
	var $content;
	
	function setLink ($link)
	{
		$this->link = $link;
		$this->type = "link";
	}
	
	function setCLink ($targetarea, $targetframe, $targetaction)
	{
		$this->targetarea = $targetarea;
		$this->targetframe = $targetframe;
		$this->targetaction = $targetaction;
		$this->type = "clink";
	}
	
	function setMultiLink ($righttoparea, $righttopaction, $rightbottomarea, $rightbottomaction)
	{
		$this->targetarea = $righttoparea;
		$this->targetframe = 3;
		$this->targetaction = $righttopaction;
		$this->targetarea2 = $rightbottomarea;
		$this->targetframe2 = 4;
		$this->targetaction2 = $rightbottomaction;
		$this->type = "multilink";
	}	
	
	function setAlt ($alt)
	{
		$this->alt = $alt;
	}
	
	function setCustom ($key, $value)
	{
		$this->custom[$key] = $value;
	}
	
	function setImage ($image)
	{
		$this->images = $image;
	}
	
	function setJavascript ($js)
	{
		$this->javascripts = $js;	
	}
	
	function setContent ($content)
	{
		$this->content = $content;
	}
	
	function render ()
	{
			global $sess;
			
			if ($this->alt != "")
        	{
        		$alt = 'alt="'.$this->alt.'" title="'.$this->alt.'" ';	
        	} else {
        		$alt = " ";
        	}
        	
        	if (is_array($this->custom))
        	{
        		foreach ($this->custom as $key => $value)
        		{
        			$custom .= "&$key=$value";
        		}
        	}
        	
        	switch ($this->targetframe)
        	{
        		case 1: $target = "left_top"; break;
        		case 2: $target = "left_bottom"; break;
        		case 3: $target = "right_top"; break;
        		case 4: $target = "right_bottom"; break;
        		default: $target = "";
        	}
        	
    		switch ($this->type)
    		{
    			case "link":
    				$link =  '<a target="'.$target.'"'.$alt.'href="'.$this->link.'">';
    				break;
    			case "clink":
    				
    				$link = '<a target="'.$target.'"'.$alt.'href="main.php?area='.$this->targetarea.
                                           '&frame='.$this->targetframe.
                                           '&action='.$this->targetaction.$custom."&contenido=".$sess->id.
                                           '">';
                    break;
    			case "multilink":
    				$tmp_mstr = '<a '.$alt.'href="javascript:conMultiLink(\'%s\', \'%s\', \'%s\', \'%s\')">';
    				$mstr = sprintf($tmp_mstr, 'right_top',
                                       $sess->url("main.php?area=".$this->targetarea."&frame=".$this->targetframe."&action=".$this->targetaction.$custom),
                                       'right_bottom',
                                       $sess->url("main.php?area=".$this->targetarea2."&frame=".$this->targetframe2."&action=".$this->targetaction2.$custom));
    				$link = $mstr;
    				break;                                       
    		}

    	return ($link.$this->content."</a>");
	}
	
	
}
?>