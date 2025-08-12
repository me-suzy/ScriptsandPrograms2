<?php

// Copyright (C) 2004-2005 Jasper Bekkers
//
// This library is free software; you can redistribute it and/or
// modify it under the terms of the GNU Lesser General Public
// License as published by the Free Software Foundation; either
// version 2.1 of the License, or (at your option) any later version.
//
// This library is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
// Lesser General Public License for more details.
//
// You should have received a copy of the GNU Lesser General Public
// License along with this library; if not, write to the Free Software
// Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

class XMLReflection
{
	/**
	 * <Description>
	 *  Generate a simple node like this:
	 *  &lt;$NodeName Name='$Name' &gt;
	 *		$Value
	 *  &lt;/$NodeName&gt;
	 * </Description>
	 * <Return Type="void"/>
	 */
	public static function name_value_node(DomDocument &$Dom, $NodeName, DomElement &$Root, &$Name, &$Value)
	{
		$Node = $Dom->createElement($NodeName);
		$Node->SetAttribute('Name', $Name);
		
		$NodeValue = $Dom->createTextNode($Value);

		$Node->appendChild($NodeValue);
		$Root->appendChild($Node);
	}

	/**
	 * <Description>Generate &lt;Comment&gt; tag</Description>
	 */
	public static function toCommentTag($Comment, &$Dom)
	{
		$Comment = DomDocument::loadXML("<Comment>" . XMLReflection::Strip($Comment) . '</Comment>');

		$Selection = new DOMXPath($Comment);
		$NodeList = $Selection->Query("/Comment");

		return $Dom->importNode($NodeList->item(0), true);
	}

	/**
	 * <Description>
	 *  Merge $String into $Dom using $XPathQuery to filter out the right tags
	 * </Description>
	 * <Return Type='Dom'>
	 *	A DOMDocument with $String as XML tags
	 * </Return>
	 */
	public static function Merge ($String, $XPathQuery, DOMDocument &$Dom)
	{
		$Method = new DomDocument();
		$Method->loadXML($String);
		$Selection = new DOMXPath($Method);
		$NodeList = $Selection->Query($XPathQuery);
		return $Dom->importNode($NodeList->item(0), true);
	}
	
	/**
	 * <Description>
	 *  Define a global getConstant function for XMLReflectionClass and XMLReflectionExtension
	 * </Description>
	 * <Return Type='Void'/>
	 */
	public static function getConstants (&$Object, &$Node, $XPathQuery, DOMDocument &$Dom)
	{
		foreach ($Object->getConstants() as $Name => $Value)
		{
			 XMLReflection::name_value_node(&$Dom, 'Constant', &$Node, &$Name, &$Value);
		}
	}

	/**
	 * <Description>
	 * Clear all comment markup stuff like /*, *, or /******* etc and closing comments
	 * Strips whitespace
	 * </Description>
	 */
	public static function Strip($Comment)
	{
		//Split on newlines
		$Lines = preg_split (';\r|\n|\r\n;', $Comment);

		//Replace starting *, /*, */ or /*** etc, strip starting whitespace
		$Regexp[0] = ';(.*)(\s*[*]/)$;U';	//Match */
		$Regexp[1] = ';^\s*\*+(.*)$;';		//Match beginning *
		$Regexp[2] = ';^\s*/[\*]+(.*)$;';	//Match /*

		foreach ($Lines as &$Line)
		{
			$Line = preg_replace($Regexp,'$1',$Line);
			$Line = trim($Line);

			//$Line = preg_replace (';^(\*/)$;', 'a', $Line);

			if (!empty ($Line))
			{
				$NewLines[] = $Line;
			}
		}

		if(isset($NewLines))
		{
			return implode("\n", $NewLines);
		}
	}

	/**
	 * <Description>Determine the accessor of the function</Description>
	 * <Return Type='String'>
	 *	Either public, private or protected
	 * </Return>
	 */
	public static function getAccess(Reflector $Reflector)
	{
		return ucfirst(($Reflector->isPublic() ? 'public' : ($Reflector->isPrivate() ? 'private' : 'protected')));
	}

	/**
	 * <Description>Convert a boolean to a string ('true' or 'false')</Description>
	 * <Returns Type='String'>
	 *	Either 'true' or 'false' depending on the boolean value
	 * </Returns>
	 */  
	public static function b2s ($Bool)
	{
		if ($Bool === true)
		{
			return 'true';
		}
		return 'false';
	}
}
?>