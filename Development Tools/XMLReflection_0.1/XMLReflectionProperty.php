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

class XMLReflectionProperty extends ReflectionProperty implements ToDom
{
	public function __construct ($Class, $Name)
	{
		echo $Class, $Name, ' ';
		parent::__construct($Class, $Name);
		
	}

	public function __tostring()
	{
		return $this->getDom()->saveXml();
	}

	public function getDom()
	{
		$Dom = new DOMDocument();
		$Property = $Dom->createElement('Property');

		$Property->SetAttribute('Name',		$this->name);
		$Property->SetAttribute('Access',	XMLReflection::getAccess(&$this));
		$Property->SetAttribute('Static',	XMLReflection::b2s($this->isStatic()));

		$Dom->appendChild($Property);
		return $Dom;
	}

	/**
	 * <Description>
	 *  Get the class that declares this property
	 * </Description>
	 * <Return Type="XMLReflectionClass"/>
	 */
	public function getDeclaringClass()
	{
		$Class = parent::getDeclaringClass();
		return new XMLReflectionClass($Class->getName());
	}
}
?>