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

class XMLReflectionFunction extends ReflectionFunction implements ToDom
{
	/**
	 * <Description>
	 *  Get this function's parameters, only if the function is user defined!
	 * </Description>
	 * <Return Type="XMLReflectionParameter">
	 *  Or an empty array if the function doesn't have parameters
	 * </Return>
	 */
	public function & getParameters()
	{
		if ($this->isUserDefined())
		{
			foreach (parent::getParameters() as $Parameter)
			{
				$Parameters[] = new XMLReflectionParameter ($this->getName(), $Parameter->getName());
			}
		}

		if (!isSet ($Parameters))
		{
			$Parameters = array();
		}

		return $Parameters;
	}

	/**
	 * <Description>
	 *  Get the static variables of this function, if it's user defined
	 * </Description>
	 * <Return Type="string[]">
	 *  An array [Variable] &lt;&gt; [Value] or an empty array
	 * </Return>
	 */
	public function getStaticVariables()
	{
		if ($this->isUserDefined())
		{
			return $this->getStaticVariables();
		}
		return array();
	}

	/**
	 * <Description>
	 *  A '__toString' method for the static variables
	 * </Description>
	 * <Return Type='Void'/>
	 */
	protected function __toStringStatic (&$StaticVariables, &$Node, &$Dom)
	{
		foreach ($StaticVariables as $Variable => $Value)
		{
			XMLReflection::name_value_node(&$Dom, 'Static', &$Extension, &$Name, &$Value);
		}
	}

	public function __tostring()
	{
		return $this->getDom()->saveXml();
	}

	public function getDom()
	{
		$Dom			= new DOMDocument();
		$Function		= $Dom->createElement('Function');

		$Function->SetAttribute('Name',			$this->getName());
		$Function->SetAttribute('ByRef',		XMLReflection::b2s($this->returnsReference()));
		$Function->SetAttribute('Userdefined',	XMLReflection::b2s($this->IsUserdefined()));
		$Function->SetAttribute('File',			$this->getFilename());
		$Function->SetAttribute('StartLine',	$this->getStartLine());
		$Function->SetAttribute('EndLine',		$this->getEndLine());

		$this->__toStringStatic($this->getStaticVariables(), &$Function, &$Dom);

		foreach ($this->getParameters() as $Parameter)
		{
			$Function->appendChild(XMLReflection::Merge($Parameter->__toString(), '/Parameter', &$Dom));
		}

		$Dom->appendChild($Function);
		return $Dom;
	}
}
?>