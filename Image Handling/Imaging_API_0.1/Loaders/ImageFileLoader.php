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

class ImageFileLoader implements IImageLoad
{
	private $filename;
	private $type;

	public function __construct($filename, $type)
	{
		$this->filename = $filename;

		if(!Image::checkType($type))
		{
			throw new InvalidArgumentException('$type is not a valid image type');
		}
		else
		{
			$this->type = $type;
		}
	}

	public function & getImage()
	{
		switch($this->type)
		{
			case Image::PNG:
			{
				$createFunc = 'imageCreateFromPng';
			}
			break;
			case Image::GIF:
			{
				$createFunc = 'imageCreateFromGif';
			}
			break;
			case Image::JPG:
			{
				$createFunc = 'imageCreateFromJpeg';
			}
			break;
		}

		
		if(!isSet($createFunc))
		{
			throw new InvalidImageTypeException();
		}
		else
		{
			$image = @$createFunc($this->filename);

			if($image === false)
			{
				throw new InvalidImageException();
			}
			else
			{
				return $image;
			}
		}
	}
}
?>