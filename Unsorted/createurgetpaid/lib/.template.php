<?

	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	// This script is copyrighted to CreateYourGetPaid©       \\
	// Duplication, selling, or transferring of this script   \\
	// is a violation of the copyright and purchase agreement.\\
	// Alteration of this script in any way voids any         \\
	// responsibility CreateYourGetPaid© has towards the      \\
	// functioning of the script. Altering the script in an   \\
	// attempt to unlock other functions of the program that  \\
	// have not been purchased is a violation of the          \\
	// purchase agreement and forbidden by CreateYourGetPaid© \\
	//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~\\
	
	/*********************************************************************
	      TemplateParser - (C)2001 Yepz - www.yepz.nl - info@yepz.nl
	**********************************************************************
	 - This script is delivered as is and without any warranty.
	 - The use of this script is at your own risk and the author of this
	 script can't be held reliable for any damage caused by the use or
	 misuse of this script.
	 - You can use this script for everything but commercial uses unless
	 you have written permission from the author of this script wich is
	 Yepz.
	*********************************************************************/
	
	class Template
	{
	
		var	$content,
			$path,
			$extension,
			$registeredVars,
			$registeredLoops;
		
		var	$buffer								= "";
		
		function Template ( $templatePath = "", $extension = "tml" )
		{
			if($templatePath == "")
				$templatePath	= _TEMPLATE_PATH . _SITE_LANGUAGE . "/";
			
			$this->setPath ( $templatePath );
			$this->setExtension ( $extension );
			$this->clear ();
		}

		function clear ()
		{
			$this->content							= "";

			$this->registeredVars					= Array ();
			$this->registeredLoops					= Array ();
		}

		function setPath ( $templatePath )
		{
			if ( substr ( $templatePath, strlen ( $templatePath ) - 1, 1 ) != '/' )
				$templatePath .= '/'; 
			
			$this->templatePath						= $templatePath;
		}
	
		function setExtension ( $extension )
		{
			$this->extension						= $extension;
		}
			
		function is_number ( $value )
		{
			$number									= false;
			
			if ( preg_match ( "/^\d+$/s", $value ) )
				$number									= true;
				
			return $number;
		}
			
		function parseVariable ( $variableName, $eX, $quoteStringResult = false )
		{
			if ( $this->registeredVars[$variableName] )
			{
				if ( $quoteStringResult && !$this->is_number ( $this->registeredVarsValue[$variableName] ) )
					return  '"' . $this->registeredVarsValue[$variableName] . '"' . $eX;
				else
					return  $this->registeredVarsValue[$variableName] . $eX;
			}
			else
				return "#" . $variableName . $eX;
		}
		
		function parseLoopVariable ( $loopName, $loopIndex, $variableName, $matchContent )
		{
			if ( isset ( $this->registeredLoops[$loopName][$loopIndex][$variableName] ) )
				return $this->registeredLoops[$loopName][$loopIndex][$variableName] . "$matchContent";
			else
				return "#$loopName::$variableName$matchContent";
		}
		
		function parseLoop ( $loopName, $loopContent )
		{
			if ( isset ( $this->registeredLoops[$loopName] ) )
			{
				$newContent								= "";
				
				reset ( $this->registeredLoops[$loopName] );
				while ( list ( $loopIndex, $varArray ) = each ( $this->registeredLoops[$loopName] ) )
				{
					$content								= $loopContent;

					$content								= preg_replace ( "/(#)($loopName)(::)(\w+?)(\W|$|\s+)/me", "\$this->parseLoopVariable(\"$loopName\", \"$loopIndex\", \"\\4\", \"\\5\" );", $content );

					$content								= preg_replace ( "/(#LOOPPOS\(#)($loopName)(\))/", $loopIndex + 1, $content );
					$content								= preg_replace ( "/(#LOOPCOUNT\(#)($loopName)(\))/", count ( $this->registeredLoops[$loopName] ), $content );

					$newContent								.= $content;
				}
				
				return $newContent;
			}
			else
				return "";
		}

		function parseIfStatement ( $condition, $content, $elseContent = "" )
		{
			$condition											= trim ( $condition );

			$condition											= preg_replace ( "/(#)(\w+?)($|\s|\W)/me", "\$this->parseVariable ( \"\\2\", \"\\3\", 1 );", $condition );
			
			eval ( "if ( $condition ) { \$condition = 1; } else { \$condition = 0; }" );

			if ( $condition )
				return stripslashes ( $content ) . $dbg;
			else
				return stripslashes ( $elseContent ) . $dbg;
		}
		
		function parseIfStage ( $forWhat )
		{
			if ( !isset ( $this->ifStage ) )
				$this->ifStage = 0;
				
			if ( !isset ( $this->endIfStage ) )
				$this->endIfStage = 0;
				
			switch ( $forWhat )
			{
				
				case '#IF':
						$this->highestStage++;
						$this->ifStage							= $this->highestStage;
						$this->endIfStage						= $this->highestStage;
						$stage									= $this->ifStage;
					break;
				
				case '#ELSE':
						$stage									= $this->ifStage;
					break;
				
				case '#ENDIF':
						$stage									= $this->endIfStage;
						--$this->endIfStage;
					break;
				
			}
				
			return "$forWhat$stage";
		}
		
		function parseIsLoop ( $loopName )
		{
			if ( $this->registeredLoops[$loopName] )
				return "1";
			else
				return null;
		}
		
		function parse ( $notToBuffer = 0, $allowHTML = 0, $skipBanner = 0 )
		{
			GLOBAL $banners;
			
		 	$this->content	= preg_replace ( "/(#ISLOOP)(\()(#)(\w+?)(\))/se", "\$this->parseIsLoop ( \"\\4\" );", $this->content );
			$this->content	= preg_replace ( "/(#LOOP)(\()(#)(\w+?)(\))(.+?)(#ENDLOOP)/se", "\$this->parseLoop ( \"\\4\", \"\\6\" );", $this->content );
			$this->content	= preg_replace ( "/(#IF|#ELSE|#ENDIF)/me", "\$this->parseIfStage ( \"\\1\" );", $this->content );
			
			for ( $stage=$this->highestStage; $stage>0; $stage-- )
			{
				$this->content	= preg_replace ( "/(#IF$stage)(\(|\s+\()(\))(.+?)(#ELSE$stage)(.+?)(#ENDIF$stage)/s", "", $this->content );
				$this->content	= preg_replace ( "/(#IF$stage)(\(|\s+\()(\))(.+?)(#ENDIF$stage)/s", "", $this->content );

				$this->content	= preg_replace ( "/(#IF$stage)(\(|\s+\()(.+?)(\))(.+?)(#ELSE$stage)(.+?)(#ENDIF$stage)/se", "\$this->parseIfStatement ( \"\\3\", \"\\7\", \"\\5\" );", $this->content );
				$this->content	= preg_replace ( "/(#IF$stage)(\(|\s+\()(.+?)(\))(.+?)(#ENDIF$stage)/se", "\$this->parseIfStatement ( \"\\3\", \"\\5\" );", $this->content );
			}
			
			$this->content	= preg_replace ( "/(#)(\w+?)($|\s|\W)/me", "\$this->parseVariable ( \"\\2\", \"\\3\" );", $this->content );
			
			if($skipBanner == 0)
				$this->content	= preg_replace ( "/#BANNER\(\)/se", "\$banners->RegisterBannerVars();", $this->content );

			$this->content	= preg_replace ( "/\[lquote\]/s", "\"", $this->content );
			$this->content	= preg_replace ( "/\[squote\]/s", "'", $this->content );

			if ( $notToBuffer )
				return $this->GetParsedContent ();
			else
				$this->buffer	.= $this->GetParsedContent ();
		}
		
		function output ()
		{
			global $db, $user, $session;
			
			if($_GET["debug"] == "on" && $user->IsOperator())
				$db->Debug();
			
			$session->Save();
			
			echo $this->buffer;
			
			$this->buffer	= "";
		}
	
		function GetParsedContent ()
		{
			return $this->content;
		}
		
		function setContent ( $content )
		{
			$this->content	= $content;
		}
		
		function loadFromFile ( $fileName )
		{
			global $error, $main;

			$this->content							= "";

			if ( $this->extension )
				$ex = "." . $this->extension;
				
			$fileName								= $this->templatePath . $fileName . $ex;
				
			if ( !file_exists ( $fileName ) )
				$error->fatal ( "class template", "Can't open template '$fileName'" );
				
			$fileHandle								= fopen ( $fileName, "r" );
			while ( !feof ( $fileHandle ) )
			{
				$this->content							.= stripslashes ( fgets ( $fileHandle, 4096 ) );
			}
			fclose ( $fileHandle );
			
			$this->content							= preg_replace ( '/\'/s',	"[squote]", $this->content );
		}
		
		function registerVar ( $name, $value )
		{
			$value									= preg_replace ( "/\\$/s",		"&#036;", $value );
			$value									= preg_replace ( "/\"/s",		"[lquote]", $value );
			$value									= preg_replace ( '/\'/s',		"[squote]", $value );
			$this->registeredVars[$name]			= true;
			$this->registeredVarsValue[$name]		= $value;
		}
		
		function registerVarArray ( $variableArray )
		{
			while ( list ( $variableName, $variableValue ) = each ( $variableArray ) )
			{
				if ( is_array ( $variableValue ) )
				{
					while ( list ( $k, $v ) = each ( $variableValue ) )
						$this->registerLoop ( $variableName, $k, $v );
				}
				else
					$this->registerVar ( $variableName, $variableValue );
			}
		}
		
		function getVarArray ()
		{
			return $this->registeredVarsValue;
		}
		
		function registerLoop ( $loopName, $loopIndex, $variableArray )
		{
			while ( list ( $variableName, $variableValue ) = each ( $variableArray ) )
				$this->registeredLoops[$loopName][$loopIndex][$variableName] = $variableValue;
		}
		
	}
	
	$tml	= new Template(_TEMPLATE_PATH . $session->Get("language") . "/");
	
?>