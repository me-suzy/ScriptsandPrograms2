<?php

/*

<%tv VAR%> - insert variable
<%tag TAG%> - insert tag
<%tvh VAR%> - 

<%tinclude FILENAME%> - include filename

<%tif BOOL%> <%tendif BOOL%> - if causion

<%tloop NAME%>
	<%tlv VAR%> - loop
<%tendloop NAME%>



*/




//-------------------------------------------------------------------------------
// TemplateTag interface
//-------------------------------------------------------------------------------

class TemplateTag
{
	var $name = '';

	function TemplateTag( $name )
	{
		$this->name = $name;
	}
	
	function Generate( $tag_data )
	{
		return "<b>Dummy template tag: $this->name</b>";
	}
}

//-------------------------------------------------------------------------------
// TagSelect class
//
//		$data 		- array( associated_array() )
//		$tag_name 	- by default set as SELECT HTML element name.
//-------------------------------------------------------------------------------

class TagSelect extends TemplateTag
{
	var $data = array();
	var $element_name = '';
	var $element_id = '';
	var $value_assoc = '';
	var $name_assoc = '';
	var $selected = '';
	var $add = '';

	function TagSelect( $data, $tag_name, $add="" )
	{
		parent::TemplateTag( $tag_name );
		$this->data = $data;
		$this->element_name = $tag_name;
		$this->add = $add;
	}
	
	function SetElementName( $name )
	{
		$this->element_name = $name;
	}
	
	function SetElementId( $id )
	{
		$this->element_id = $id;
	}
	
	function SetName( $name )
	{
		$this->name_assoc = $name;
	}
	
	function SetValue( $value )
	{
		$this->value_assoc = $value;
	}
	
	function SetSelected( $value )
	{
		$this->selected = $value;
	}
	
	function Generate( $tag_data )
	{
		//if( $tag_data[1] == 'selected_id' )
		//	return $this->selected;
	
		$params = implode(' ', $tag_data);
		$params = trim( $params );
		
		$id = $this->element_name;
		if( $this->element_id != '' )
			$id = $this->element_id;
		
			$add = $this->add;
				
		$rc = "<select name=\"$this->element_name\" $add";
		
		if( $params != '' )
			$rc .= ' ' . $params;
		$rc .= ">\n";
		
		if( is_array($this->data) )
			foreach( $this->data as $item )
			{
				$value = $item[ $this->value_assoc ];
				$name = $item[ $this->name_assoc ];
				
				if( $value == $this->selected )
					$rc .= "\t<option value=\"$value\" selected>$name</option>\n";
				else
					$rc .= "\t<option value=\"$value\">$name</option>\n";
			}
		
		$rc .= "</select>\n";
		return $rc;
	}
}

//-------------------------------------------------------------------------------
// TagNavigator class
//
//		$count 		- global count of elements
//		$onpage 	- max elements on page
//		$tag_name 	- by default set as SELECT HTML element name.
//-------------------------------------------------------------------------------

class TagNavigator extends TemplateTag
{
	var $count = 0;
	var $onpage = 0;
	var $pageno = 1;
	var $pages = 1;
	var $passthrough = '';

	function TagNavigator( $count, $onpage, $tag_name )
	{
		parent::TemplateTag( $tag_name );
		$this->count = $count;
		$this->onpage = $onpage;
		
		if( $this->count > 0 && $this->onpage > 0 )
		{
			$this->pages = floor( $this->count / $this->onpage );
			if( ( $this->count % $this->onpage ) > 0 )
				$this->pages++;
		}
			
		global $PHP_SELF;
		$this->target = $PHP_SELF;
	}
	
	function SetPage( $pageno )
	{
		if( $pageno < 1 )
			$pageno = 1;
		$this->pageno = $pageno;
		if( $this->pages < $this->pageno )
			$this->pageno = $this->pages;
	}
	
	function InPage( $no )
	{
		$minno = ( $this->pageno - 1 ) * $this->onpage;
		$maxno = $this->pageno * $this->onpage;
		if( $maxno >= $this->count )
			$maxno = $this->count;
		return $minno <= $no && $no < $maxno;
	}
	
	function GetStartIndex()
	{
		return ( $this->pageno - 1 ) * $this->onpage;
	}
	
	function GetEndIndex()
	{
		$maxno = $this->pageno * $this->onpage;
		if( $maxno >= $this->count )
			$maxno = $this->count;
		return $maxno;
	}
	
	function SetTarget( $target )
	{
		$this->target = $target;
	}
	
	function PrevLink()
	{
		if( $this->pageno == 1 )
			return "<font class=\"BodyText02\">&lt;&lt;</font>";
		else
		{
			$prev = $this->pageno - 1;
			return "<a href=\"$this->target?pg=$prev$this->passthrough\" class=\"menu02\">&lt;&lt;</a>";
		}
	}
	
	function NextLink()
	{
		if( $this->pageno == $this->pages )
			return "<font class=\"BodyText02\">&gt;&gt;</font>";
		else
		{
			$next = $this->pageno + 1;
			return "<a href=\"$this->target?pg=$next$this->passthrough\" class=\"menu02\">&gt;&gt;</a>";
		}
	}
	
	function PageLink( $no )
	{
		if( $no == $this->pageno )
			return "<font class=\"BodyText02\">" . $no . "</font>";
		else
			return "<a href=\"$this->target?pg=$no$this->passthrough\" class=\"menu02\">$no</a>";
	}
	
	function SetPassthroughParam( $name, $value )
	{
		$this->passthrough .= "&$name=$value";
	}
	
	function Generate( $tag_data = array() )
	{
		if( count( $tag_data ) != 0 )
			if( $tag_data[1] == 'pages' )
				return $this->pages;
			elseif( $tag_data[1] == 'target' )
				return $this->target;
			elseif( $tag_data[1] == 'pageno' )
				return $this->pageno;
			elseif( $tag_data[1] == 'count' )
				return $this->count;
			elseif( $tag_data[1] == 'page' )
				return $this->GetPage( $tag_data[2] );
			elseif( $tag_data[1] == 'prev' )
				return $this->PrevLink();
			elseif( $tag_data[1] == 'next' )
				return $this->NextLink();
				
		if( $this->pages == 1 )
			return '&nbsp;';

		$rc = $this->PrevLink();
		$rc .= '&nbsp;';
		
		$start_no = max( 1 , $this->pageno - 10 );
		$end_no = min( $this->pages , $this->pageno + 10 );
		for( $i = $start_no; $i <= $end_no; $i++ )
		{
			$rc .= $this->PageLink( $i );
			$rc .= '&nbsp;';
		}
		
		$rc .= $this->NextLink();
		
		return $rc;
	}
}

//-------------------------------------------------------------------------------
// Template processor class
//-------------------------------------------------------------------------------

class Template {

	var $template_contents;
	var $param;
	var $tags = array();
	var $max_recursive_includes = 10;
	var $inc_dir;

	function fatal($msg)
	{
		echo "[Template] Fatal error: $msg";
		exit;
	}

	function Template($filename, $is_file = true) {
		$param = array();
		if( $is_file )
		{
			$fh = @fopen($filename,"rb");
			if( !$fh )
				Template::fatal("Failed opening file '".$filename."'");
			$this->template_contents = fread ($fh, filesize ($filename));
			@fclose ($fh);
			$this->inc_dir = dirname($filename).'/';
		}
		else
		{
			$this->template_contents = $filename;
			$this->inc_dir = './';
		}
	}

	function param($key,$val) {
		$this->param[$key] = $val;
		return true;
	}
	
	function tag( $tag )
	{
		$this->tags[ $tag->name ] = $tag;
	}
	
	function parse_tags( $tc, $param = false )
	{
		$template_tag = '<%tag ';
		
		$template_tag_len = strlen($template_tag);
		
		$tmpl = '';
		while( strlen($tc) > 0 ) {
			$str = stristr($tc,$template_tag);
			if( $str==false )
		 		break;
		 	
			$pos = strpos($str,'%>');
			if( $pos===false )
				break;
		 	
			$tmpl .= substr($tc,0,strlen($tc)-strlen($str));
			$tag_str = trim(substr($str,$template_tag_len,$pos-$template_tag_len));
			$tag_str = trim($tag_str);			
			$tag_data = preg_split( "/[\s,]+/", $tag_str );
			if( count( $tag_data ) == 0 )
			{
				$tmpl .= "<b>???Tag name is not defined.???</b>";
				$tc = substr($str,2+$pos);
				continue;
			}
			
			$tag_name = $tag_data[0];
			unset( $tag_data[0] );

			if( is_array( $param ) && isset( $param[$tag_name] ) )
				$tag = $param[$tag_name];
			if( !isset( $tag ) && isset( $this->tags ) && isset( $this->tags[$tag_name] ) )
				$tag = $this->tags[$tag_name];
				
			if( !isset( $tag ) )
				$tmpl .= "<b>???Tag $tag_name not found???</b>";
			elseif( !is_object( $tag ) )
				$tmpl .= "<b>???Tag $tag_name is not an object???</b>";
			elseif( !is_subclass_of( $tag, 'TemplateTag' ) )
				$tmpl .= "<b>???Tag $tag_name is not a subclass of <code>TemplateTag</code>???</b>";
			else
				$tmpl .= $tag->Generate( $tag_data );
				
			unset( $tag );

			$tc = substr($str,2+$pos);
		}		
		$tmpl .= $tc;
		return $tmpl;
	}

	function parse_vars($tc,$param=false) {
		if( !$param ) {
		 $param = $this->param;
		 $template_tag = '<%tv ';
		} else {
		 $template_tag = '<%tlv ';
		}

		$template_tag_len = strlen($template_tag);

		$tmpl = '';
		while( strlen($tc) > 0 ) {
		 $str = stristr($tc,$template_tag);
		 if( $str==false ) break;
		 $pos = strpos($str,'%>');
		 if( $pos===false ) break;
		 $tmpl .= substr($tc,0,strlen($tc)-strlen($str));
		 $var_name = trim(substr($str,$template_tag_len,$pos-$template_tag_len));

		 if( eregi( "([[:alnum:]_]+)\[([[:alnum:]_]+)\]", $var_name, $matches ) > 0 )
		 {
		 	$arr_param = $param[ $matches[1] ];
		 	$tmpl .= $arr_param[ $matches[2] ];
		 }
		 else
		 	$tmpl .= $param[$var_name];

		 $tc = substr($str,2+$pos);
		}
		$tmpl .= $tc;
		return $tmpl;
	}

	function parse_html_vars($tc,$param=false) {
		if( !$param ) {
		 $param = $this->param;
		 $template_tag = '<%tvh ';
		} else {
		 $template_tag = '<%tlvh ';
		}

		$template_tag_len = strlen($template_tag);

		$tmpl = '';
		while( strlen($tc) > 0 ) {
		 $str = stristr($tc,$template_tag);
		 if( $str==false ) break;
		 $pos = strpos($str,'%>');
		 if( $pos===false ) break;
		 $tmpl .= substr($tc,0,strlen($tc)-strlen($str));
		 $var_name = trim(substr($str,$template_tag_len,$pos-$template_tag_len));
		 
		 if( eregi( "([[:alnum:]_]+)\[([[:alnum:]_]+)\]", $var_name, $matches ) > 0 )
		 {
		 	$arr_param = $param[ $matches[1] ];
		 	$var_value = $arr_param[ $matches[2] ];
		 }
		 else
		 	$var_value = $param[$var_name];

		 $tmpl .= htmlspecialchars( $var_value );

		 $tc = substr($str,2+$pos);
		}
		$tmpl .= $tc;
		return $tmpl;
	}

	function parse_endif($tc,$if_var_name,$param,$flag) {
		$template_tag = '<%tendif ';
		$template_tag_len = strlen($template_tag);

		$str = stristr($tc,$template_tag);
		if( $str==false )
			$this->fatal("Cannot find endif tag for '$if_var_name'");

		$pos = strpos($str,'%>');
		if( $pos===false )
			$this->fatal("Cannot find endif tag for '$if_var_name'");

		$tmpl = '';
		$if_var_name2 = trim(substr($str,$template_tag_len,$pos-$template_tag_len));
		if( (strcasecmp($if_var_name,$if_var_name2)==0) ) {
		 if( $param[$if_var_name]==$flag )
//			$tmpl = $this->parse_loop(substr($tc,0,strlen($tc)-strlen($str)),$param);
			$tmpl = substr($tc,0,strlen($tc)-strlen($str));
		} else {
			$this->fatal("Cannot find endif tag for '$if_var_name'");
		}
		$tmpl .= substr($tc,strlen($tc)-strlen($str)+2+$pos);

		return $tmpl;
	}

	function parse_if($tc,$param=false) {
		if( !$param ) $param=$this->param;

		$template_tag = '<%tif ';
		$template_tag_len = strlen($template_tag);

		$str = stristr($tc,$template_tag);
		if( $str==false ) return $tc;
		$pos = strpos($str,'%>');
		if( $pos===false ) return $tc;

		$tmpl = substr($tc,0,strlen($tc)-strlen($str));
		$if_var_name = trim(substr($str,$template_tag_len,$pos-$template_tag_len));

		if( ord($if_var_name[0]) != ord('!') )
			$tmpl .= $this->parse_endif($this->parse_if(substr($str,$pos+2),$param),$if_var_name,$param,true);
		else
			$tmpl .= $this->parse_endif($this->parse_if(substr($str,$pos+2),$param),trim(substr($if_var_name,1)),$param,false);
		return $tmpl;
	}

	function parse_include($tc,$iter=0) {
		if( $iter > $this->max_recursive_includes )
		 $this->fatal("Maximum number (".$this->max_recursive_includes.
			") of recursive inclusions reached");

		$template_tag = '<%tinclude ';
		$template_tag_len = strlen($template_tag);

		$tmpl = '';
		while( strlen($tc) > 0 ) {
		 $str = stristr($tc,$template_tag);
		 if( $str==false ) break;
		 $pos = strpos($str,'%>');
		 if( $pos===false ) break;
		 $tmpl .= substr($tc,0,strlen($tc)-strlen($str));
		 $inc_filename = trim(substr($str,$template_tag_len,$pos-$template_tag_len));

		 $fc = '';
		 $inc_filename = $this->inc_dir.$inc_filename;
		 $fh = @fopen($inc_filename,"rb");
		 if( $fh ) {
			$fc = fread ($fh, filesize ($inc_filename));
			@fclose ($fh);
			$tmpl .= $this->parse_include($fc,$iter+2);
		 } else {
			$this->fatal("Failed opening '".$inc_filename."' for inclusion");
		 }
		 $tc = substr($str,2+$pos);
		}
		$tmpl .= $tc;
		return $tmpl;
	}


	function parse_endloop($tc,$loop_var_name,$param) {
		$template_tag = '<%tendloop ';
		$template_tag_len = strlen($template_tag);
		$btemplate_tag = '<%tloop ';
		$btemplate_tag_len = strlen($btemplate_tag);

		$inner = 1;
		$spos = 0;
		while( $inner>0 ) {
		 $str = stristr(substr($tc,$spos),$template_tag);
		 if( $str==false )
			$this->fatal("'$loop_var_name' loop not closed");

		 $pos = strpos($str,'%>');
		 if( $pos===false )
			$this->fatal("'$loop_var_name' loop not closed");

		 $bstr = stristr(substr($tc,$spos),$btemplate_tag);
		 if( $bstr!=false ) {
			$bpos = strpos($bstr,'%>');
			if( ($bpos!==false) &&
			    (strlen($bstr) > strlen($str)) ) {
				// loop tag found before endloop
				$inner++;
				$spos = (strlen($tc)-strlen($bstr))+$bpos+2;
			} else {
				$inner--;
				$spos = (strlen($tc)-strlen($str))+$pos+2;
			}
		 } else {
			$inner--;
			$spos = (strlen($tc)-strlen($str))+$pos+2;
		 }
		}

		$tmpl = '';
		$loop_var_name2 = trim(substr($str,$template_tag_len,$pos-$template_tag_len));
		if( (strcasecmp($loop_var_name,$loop_var_name2)==0) ) {
		 if( is_array($param[$loop_var_name]) ) {
		  $src = substr($tc,0,strlen($tc)-strlen($str));
		  $tmpll = '';
		  foreach( $param[$loop_var_name] as $item ) {
			$tmpl = $this->parse_loop($src,$item);
			$tmpl = $this->parse_if($tmpl,$item);
			$tmpl = $this->parse_include($tmpl);
			$tmpl = $this->parse_vars($tmpl,$item);
			$tmpll .= $this->parse_html_vars($tmpl,$item);
		  }
		  $tmpl = $tmpll;
		 }
		} else {
			$this->fatal("'$loop_var_name' loop not closed");
		}

		$tmpl .= substr($tc,strlen($tc)-strlen($str)+2+$pos);

		return $tmpl;
	}

	function parse_loop($tc,$param=false) {
		if( !$param ) $param=$this->param;

		$template_tag = '<%tloop ';
		$template_tag_len = strlen($template_tag);
		while( true ) {
			$str = stristr($tc,$template_tag);
			if( $str==false ) return $tc;
			$pos = strpos($str,'%>');
			if( $pos===false ) return $tc;

			$tmpl = substr($tc,0,strlen($tc)-strlen($str));
			$loop_var_name = trim(substr($str,$template_tag_len,$pos-$template_tag_len));
			$tmpl .= $this->parse_endloop( substr($str,$pos+2),
							$loop_var_name,$param );

			$tc = $tmpl;
		}

		return $tmpl;
	}

	//--------------------------------------
	// Parse template
	// Input: file contents as string
	// Output: parsed template as string
	//--------------------------------------
	function parse() {
		$tmpl = $this->parse_include($this->template_contents);
		$tmpl = $this->parse_loop($tmpl);
		$tmpl = $this->parse_if($tmpl);
		$tmpl = $this->parse_vars($tmpl);
		$tmpl = $this->parse_html_vars($tmpl);
		$tmpl = $this->parse_tags($tmpl);

		return $tmpl;
	}
}

?>
