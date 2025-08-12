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

class XMLReflectionMethod extends XMLReflectionFunction implements ToDom
{
	private $RMHandle;

	public function __construct ($Class, $Method)
	{
		$this->RMHandle =& new ReflectionMethod ($Class, $Method);
	}

	/**
	 * <Description>
	 *  Map all methods to the standard ReflectionMethod witch are not specificaly defined in this class
	 * </Description>
	 * <Return Type="Mixed">
	 *  Returns whatever $Method returns
	 * </Return>
	 */
	public function __call ($Method, $Args)
	{
		return call_user_func_array(array(&$this->RMHandle, $Method), $Args);
	}

	public function __tostring()
	{
		return $this->getDom()->saveXml();
	}

	public function getDom()
	{
		$Dom = new DOMDocument();
		$Method = $Dom->createElement('Method');
		$Method->SetAttribute ('Name',		$this->RMHandle->getName());
		$Method->SetAttribute ('Access',	XMLReflection::getAccess(&$this));
		$Method->SetAttribute ('Final',		XMLReflection::b2s($this->isFinal()));
		$Method->SetAttribute ('Static',	XMLReflection::b2s($this->isStatic()));
		$Method->SetAttribute ('Abstract',	XMLReflection::b2s($this->isAbstract()));
		$Method->SetAttribute ('Userdefined',XMLReflection::b2s($this->RMHandle->IsUserdefined()));
		$Method->SetAttribute ('File',		$this->RMHandle->getFilename());
		$Method->SetAttribute ('StartLine',	$this->RMHandle->getStartLine());
		$Method->SetAttribute ('EndLine',	$this->RMHandle->getEndLine());

		//Import comments
		$Method->appendChild(XMLReflection::toCommentTag($this->RMHandle->getDocComment(), &$Dom));
		//Static variables
		$this->__toStringStatic($this->getStaticVariables(), &$Method, &$Dom);

		foreach ($this->getParameters() as $Parameter)
		{
			$Method->appendChild(XMLReflection::Merge($Parameter->__toString(), '/Parameter', &$Dom));
		}

		$Dom->appendChild($Method);
		return $Dom;
	}

	/**
	 * <Description>
	 *  Gets only the static variables for userdefined methods, 
	 *  it does not try to get them for internal methods
	 * </Description>
	 * <Return Type="String[]">
	 *  [Variable] &lt;&gt; [Value] relation
	 * </Return>
	 */
	public function getStaticVariables()
	{
		if ($this->RMHandle->isUserDefined())
		{
			return $this->RMHandle->getStaticVariables();
		}
		return array();
	}

	/**
	 * <Description>
	 *  Get the method's parameters
	 * </Description>
	 * <Return Type="XMLReflectionParameter[]">
	 *  Returns a XMLReflectionParameter array, or an empty array if there are no parameters
	 * </Return>
	 */
	public function & getParameters()
	{
		foreach ($this->RMHandle->getParameters() as $Parameter)
		{
			$Parameters[] = new XMLReflectionParameter
				(
					array
					(
						$this->RMHandle->getDeclaringClass()->getName(),
						$this->RMHandle->getName()
					), $Parameter->getName());
		}

		if (!isSet ($Parameters))
		{
			$Parameters = array();
		}

		return $Parameters;
	}

	/**
	 * <Description>
	 *  Get the class that declares this method
	 * </Description>
	 * <Return Type="XMLReflectionClass"/>
	 */
	public function & getDeclaringClass()
	{
		$Class = parent::getDeclaringClass();

		return new XMLReflectionClass($Class->getName());
	}
}
?>