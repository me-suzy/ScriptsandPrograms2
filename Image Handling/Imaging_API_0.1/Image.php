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

class Image
{
	const PNG = 0x01;
	const GIF = 0x02;
	const JPG = 0x03;

	private $image;
	private $actions = array();

	public function __construct(IImageLoad $imageLoader)
	{
		$this->image = $imageLoader->getImage();
	}

	public static function checkType($type)
	{
		return $type == Image::PNG || $type == Image::GIF || $type == Image::JPG;
	}

	public function getWidth()
		{
		return imageSX($this->image);
	}

	public function getHeight()
	{
		return imageSY($this->image);
	}

	public function getHandle()
	{
		return $this->image;
	}

	public function render()
	{
		header('Content-type: image/png');
		imagepng($this->image);
	}

	public function addAction(IAction $a)
	{
		$this->actions[] = $a;
	}

	public function execute()
	{
		foreach($this->actions as $a)
		{
			$a->executeActions(&$this);
		}
	}

	public function getColors($x, $y)
	{
		return Color::fromGd(imageColorAt($this->image, $x, $y));
	}

	public function setPixel($x, $y, Color $color)
	{
		imageSetPixel($this->image, $x, $y, 
			imageColorAllocate($this->image, $color->red, $color->green, $color->blue));
	}
}

class InvalidImageException extends Exception {};
class InvalidArgumentException extends Exception {};
class InvalidImageTypeException extends Exception {};
?>