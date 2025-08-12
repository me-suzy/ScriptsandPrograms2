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

class XMLReflectionExtension extends ReflectionExtension implements ToDom
{
	/**
	 * <Description>
	 *  Get the functions declared by this Extension
	 * </Description>
	 * <Return Type="XMLReflectionFunction[]">
	 *  Or an empty array if no functions are defined (witch isn't likely)
	 * </Return>
	 */
	public function getFunctions()
	{
		foreach (parent::getFunctions() as $Function)
		{
			$NewFunctions[] = new XMLReflectionFunction($Function->getName());
		}

		if (!isSet ($NewFunctions))
		{
			$NewFunctions = array();
		}

		return $NewFunctions;
	}

	public function __tostring()
	{
		return $this->getDom()->saveXml();
	}

	public function getDom()
	{
		$Dom = new DomDocument();
		$Extension = $Dom->createElement('Extension');
		$Extension->SetAttribute('Name',			$this->getName());
		$Extension->SetAttribute('Version',			$this->getVersion());

		XMLReflection::getConstants(&$this, &$Extension, '/Constant', &$Dom);

		foreach ($this->getFunctions() as $Function)
		{
			$Extension->appendChild(XMLReflection::Merge($Function->__toString(), '/Function', &$Dom));
		}

		foreach ($this->getINIEntries() as $Name => $Value)
		{
			 XMLReflection::name_value_node(&$Dom, 'INIEntry', &$Extension, &$Name, &$Value);
		}

		foreach ($this->getClassNames() as $Name)
		{
			$ClassName = $Dom->createElement('Class');
			$ClassName->SetAttribute('Name', $Name);
			$Extension->appendChild($ClassName);
		}

		$Dom->appendChild($Extension);
		return $Dom;
	}
}
?>