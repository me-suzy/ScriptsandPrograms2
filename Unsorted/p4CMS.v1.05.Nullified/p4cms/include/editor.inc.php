<?
$FCKeditorBasePath = "editor/" ;

class p4cmsEditor
{
	var $ToolbarSet ;
	var $Value ;
	var $CanUpload ;
	var $CanBrowse ;

	function p4cmsEditor($val)
	{
		$this->ToolbarSet = '' ;
		$this->Value = $val ;
		$this->CanUpload = 'none' ;
		$this->CanBrowse = 'none' ;
	}
	
	function CreateFCKeditor($instanceName, $width, $height)
	{
		global $d4sess;
		if ( $this->IsCompatible() )
		{
			global $FCKeditorBasePath ;
			$sLink = $FCKeditorBasePath . "p4cmsEditor.html?FieldName=$instanceName" ;

			if ( $this->ToolbarSet != '' )
				$sLink = $sLink . "&Toolbar=$this->ToolbarSet" ;

			if ( $this->CanUpload != 'none' )
			{
				if ($this->CanUpload == true)
					$sLink = $sLink . "&Upload=true" ;
				else
					$sLink = $sLink . "&Upload=false" ;
			}

			if ( $this->CanBrowse != 'none' )
			{
				if ($this->CanBrowse == true)
					$sLink = $sLink . "&Browse=true" ;
				else
					$sLink = $sLink . "&Browse=false" ;
			}

		/*	echo "<IFRAME name=\"fr$instanceName\" src=\"$sLink\" width=\"$width\" height=\"$height\" frameborder=\"no\" scrolling=\"no\"></IFRAME>" ;
			echo "<INPUT type=\"hidden\" name=\"$instanceName\" value=\"" . htmlentities( $this->Value ) . "\">" ;
		*/
			echo "<TEXTAREA name=\"$instanceName\" rows=\"4\" cols=\"40\" style=\"WIDTH: $width; HEIGHT: $height\" wrap=\"virtual\">" . htmlentities( $this->Value ) . "</TEXTAREA>" ;
			?>
<input class="button" type="button" value="WYSIWYG - MODUS" onClick="edipop('<?=$instanceName;?>');">
			<?
		} else {
			echo "<TEXTAREA name=\"$instanceName\" rows=\"4\" cols=\"40\" style=\"WIDTH: $width; HEIGHT: $height\" wrap=\"virtual\">" . htmlentities( $this->Value ) . "</TEXTAREA>" ;
		}
		?>
		<input type="hidden" name="edsid" value="<?=$d4sess;?>">
		<?
		/*
		?>
		<textarea style="width:<?=$width;?>;height:<?=$height;?>" name="<?=$instanceName;?>" id="<?=$instanceName;?>"><?=$this->Value;?></textarea> 
<input name="Button" type="button" class="button" onClick="edipop('<?=$instanceName;?>');" value="im WYSIWYG - Modus bearbeiten">
		<?
		*/
	}
	
	function GetFCKeditor($instanceName, $width, $height)
	{
		if ( $this->IsCompatible() )
		{
			global $FCKeditorBasePath ;
			$sLink = $FCKeditorBasePath . "p4cmsEditor.html?FieldName=$instanceName" ;

			if ( $this->ToolbarSet != '' )
				$sLink = $sLink . "&Toolbar=$this->ToolbarSet" ;

			if ( $this->CanUpload != 'none' )
			{
				if ($this->CanUpload == true)
					$sLink = $sLink . "&Upload=true" ;
				else
					$sLink = $sLink . "&Upload=false" ;
			}

			if ( $this->CanBrowse != 'none' )
			{
				if ($this->CanBrowse == true)
					$sLink = $sLink . "&Browse=true" ;
				else
					$sLink = $sLink . "&Browse=false" ;
			}

			$res = "<IFRAME name=\"fr$instanceName\" src=\"$sLink\" width=\"$width\" height=\"$height\" frameborder=\"no\" scrolling=\"no\"></IFRAME>" ;
			$res .= "<INPUT type=\"hidden\" name=\"$instanceName\" value=\"" . htmlentities( $this->Value ) . "\">" ;
			return $res;
		} else {
			$res = "<TEXTAREA name=\"$instanceName\" rows=\"4\" cols=\"40\" style=\"WIDTH: $width; HEIGHT: $height\" wrap=\"virtual\">" . htmlentities( $this->Value ) . "</TEXTAREA>" ;
			return $res;
		}
	}
	
	function IsCompatible()
	{
		$sAgent = $_SERVER['HTTP_USER_AGENT'] ;

		if ( is_integer( strpos($sAgent, 'MSIE') ) && is_integer( strpos($sAgent, 'Windows') ) && !is_integer( strpos($sAgent, 'Opera') ) )
		{
			$iVersion = (int)substr($sAgent, strpos($sAgent, 'MSIE') + 5, 1) ;
			return ($iVersion >= 5) ;
		} else {
			return FALSE ;
		}
	}
}
?>
