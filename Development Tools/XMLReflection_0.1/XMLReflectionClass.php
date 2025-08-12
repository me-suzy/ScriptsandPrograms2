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

/**
 * <Description>Reflection class with XML output</Description>
 */
class XMLReflectionClass extends ReflectionClass implements ToDom
{
	public function getDom()
	{
		$Dom		= new domDocument();
		$Class		= $Dom->createElement('Class');
		$Class->SetAttribute('Name', $this->getName());
		$Parent		= $this->getParentClass();

		$Class->SetAttribute('Interface',		XMLReflection::b2s($this->isInterface()));
		$Class->SetAttribute('Abstract',		XMLReflection::b2s($this->isAbstract()));
		$Class->SetAttribute('Final',			XMLReflection::b2s($this->isFinal()));
		$Class->SetAttribute('Userdefined',		XMLReflection::b2s($this->IsUserdefined()));

		$Class->SetAttribute('File', $this->getFilename());
		$Class->SetAttribute('StartLine', $this->getStartLine());
		$Class->SetAttribute('EndLine', $this->getEndLine());
		
		if (empty($Parent))
		{
			$Parent = '';
		}
		else
		{
			$Parent = $Parent->getName();
		}

		$Class->SetAttribute('Parent',$Parent);
		
		//Import comments
		$Class->appendChild(XMLReflection::toCommentTag($this->getDocComment(), &$Dom));

		foreach ($this->getInterfaces() as $Interface)
		{
			$InterfaceNode = $Dom->createElement('Interface');
			$InterfaceNode->SetAttribute('Name', $Interface->getName());
			$Class->appendChild($InterfaceNode);
		}

		XMLReflection::getConstants(&$this, &$Node, '/Constant', &$Dom);

		foreach ($this->getProperties() as $Property)
		{
			$Class->appendChild(XMLReflection::Merge($Property->__toString(), '/Property', &$Dom));
		}

		foreach ($this->getMethods() as $Method)
		{
			$Class->appendChild(XMLReflection::Merge($Method->__toString(), '/Method', &$Dom));
		}

		$Dom->appendChild($Class);

		return $Dom;
	}

	/**
	 * <Description>Output / render the XML file</Description>
	 */
	public function __tostring()
	{
		return $this->getDom()->saveXml();
	}

	/**
	 * <Description>
	 *  Get the constructor for &lt;class&gt;
	 * </Description>
	 */
	public function & getConstructor()
	{
		$Constructor = parent::getConstructor();

		return $this->GenMethod(&$Constructor);
	}

	/**
	 * <Description>
	 *  Generat a XMLReflectionMethod from a Reflection Method
	 * </Description>
	 * <Return Type="XMLReflectionMethod">
	 *  Same as ReflectionMethod but with XML rendering
	 * </Return>
	 */
	private function & GenMethod(&$ReflectionMethod)
	{
		if ($ReflectionMethod !== null)
		{
			return new XMLReflectionMethod($ReflectionMethod->class, $ReflectionMethod->name);
		}
	}

	/**
	 * <Description>
	 *  Get the method with $Name
	 * </Description>
	 * <Return Type="XMLReflectionMethod">
	 *  An instance of XMLReflectionMethod with method name $Name
	 * </Return>
	 */
	public function & getMethod($Name)
	{
		$Method = parent::getMethod($Name);
		
		return $this->GenMethod(&$Method);
	}

	/**
	 * <Description>
	 *  Get all methods defined in this class
	 * </Description>
	 * <Return Type="XMLReflectionMethod[]">
	 *  An array with all the methods defined in this class, or an empty array if no methods are defined
	 * </Return>
	 */
	public function & getMethods()
	{
		foreach (parent::getMethods() as $Method)
		{
			$NewMethods[] = new XMLReflectionMethod($Method->class, $Method->name);
		}

		if(!isset($NewMethods))
		{
			$NewMethods = array();
		}

		return $NewMethods;
	}

	/**
	 * <Description>
	 *  Get an XMLReflectionProperty with name $Name
	 * </Description>
	 * <Return Type="XMLReflectionProperty">
	 *  An instance of XMLReflectionProperty
	 * </Return>
	 */
	public function & getProperty($Name)
	{
		$Property = parent::getProperty($Name);

		return new XMLReflectionProperty($Property->class, $Property->name);
	}

	/**
	 * <Description>
	 *  Get all properties defined for this class
	 * </Description>
	 * <Return Type="XMLReflectionProperty[]">
	 *  An array with XMLReflectionProperties, or an emtpy array if no properties are defined
	 * </Return>
	 */
	public function getProperties()
	{
		foreach (parent::getProperties() as $Property)
		{
			
			$NewProperties[] = new XMLReflectionProperty($Property->class, $Property->name);
		}

		if(!isset($NewProperties))
		{
			$NewProperties = array();
		}

		return $NewProperties;
	}

	/**
	 * <Description>
	 *  Get a list of interfaces implemented by this class
	 * </Description>
	 * <Return Type="XMLReflectionClass[]">
	 *  A list of implemented interfaces
	 * </Return>
	 */
	public function & getInterfaces()
	{
		foreach (parent::getInterfaces() as $Interface)
		{
			$Interfaces[] = new XMLReflectionClass($Interface->getName());
		}

		if(!isSet($Interfaces))
		{
			$Interfaces = array();
		}
		
		return $Interfaces;
	}

	/**
	 * <Description>
	 *  Get the parent of this class
	 * </Description>
	 * <Return Type="XMLReflectionClass">
	 *  The parent of this class, if one is defined
	 * </Return>
	 */
	public function getParentClass()
	{
		$Parent = parent::getParentClass();

		if($Parent !== false)
		{
			return new XMLReflectionClass($Parent->getName());
		}
	}
}
?>