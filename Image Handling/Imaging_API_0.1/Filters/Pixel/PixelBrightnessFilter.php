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

class PixelBrightnessFilter implements IPixelFilter
{
	private $brightness;

	public function __construct($brightness)
	{
		if($brightness < -100 || $brightness > 100)
			throw new InvalidArgumentException();

		$this->brightness = $brightness / 100;
	}

	public function filter(Color $color)
	{
		$color->red = ($this->brightness * $color->red);
		$color->green = ($this->brightness * $color->green);
		$color->blue = ($this->brightness * $color->blue);

		return $color;
	}
}
?>