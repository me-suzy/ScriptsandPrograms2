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

class XMLReflectionParameter extends ReflectionParameter implements ToDom
{
	/**
	 * <Notice>
	 *  The output in PHP 5.1.0 and up will have an extra 'Optional' attribute
	 * </Notice>
	 */
	public function __tostring()
	{
		return $this->getDom()->saveXml();
	}

	public function getDom()
	{
		$Dom			= new DOMDocument();
		$Parameter		= $Dom->createElement('Parameter');

		$Parameter->SetAttribute('Name', $this->name);

		$TypeHint = $this->getClass();

		if (!empty ($TypeHint))
		{
			$TypeHint = $TypeHint->getName();
		}
		else
		{
			$TypeHint = '';
		}

		$Parameter->SetAttribute('TypeHint', $TypeHint);
		$Parameter->SetAttribute('ByRef', XMLReflection::b2s($this->isPassedByReference()));
		
		//isOptional() was added in PHP 5.1.0.
		if (version_compare(phpversion(), '5.1.0', '>='))
		{
			$Parameter->SetAttribute('Optional', XMLReflection::b2s($this->isOptional()));
		}
		else
		{
			$Parameter->SetAttribute('Optional','unknown');
		}

		$Parameter->SetAttribute('AllowsNull', XMLReflection::b2s($this->allowsNull()));

		$Dom->appendChild($Parameter);
		return $Dom;
	}

	/**
	 * <Description>
	 *  Get the TypeHint class of this parameter
	 * </Description>
	 * <Return Type="XMLReflectionClass"/>
	 */
	public function getClass ()
	{
		$Class = parent::getClass();

		if ($Class !== null)
		{
			return new XMLReflectionClass($Class->getName());
		}
	}
}
?>