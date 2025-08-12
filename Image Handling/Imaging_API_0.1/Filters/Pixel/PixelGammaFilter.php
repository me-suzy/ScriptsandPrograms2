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

class PixelGammaFilter implements IPixelFilter
{
	private $gammaRed = array();
	private $gammaGreen = array();
	private $gammaBlue = array();

	public function __construct($red, $green, $blue)
	{
		if($red < .2 || $red > 5 || $green < .2 || $green > 5 || $blue < .2 || $blue > 5)
		{
			throw new InvalidArgumentException();
		}

		for($i = 0; $i < 0xFF; $i++)
		{
			$this->gammaRed[$i]		= min(255, (255 * pow($i / 255, 1 / $red))   + .5);
			$this->gammaGreen[$i]	= min(255, (255 * pow($i / 255, 1 / $green)) + .5);
			$this->gammaBlue[$i]	= min(255, (255 * pow($i / 255, 1 / $blue))  + .5);
		}
	}

	public function filter(Color $color)
	{
		$color->red = $this->gammaRed[$color->red];
		$color->green = $this->gammaGreen[$color->green];
		$color->blue = $this->gammaBlue[$color->blue];

		return $color;
	}
}
?>