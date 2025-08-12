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

class PixelContrastFilter implements IPixelFilter
{
	private $contrast;

	public function __construct ($contrast)
	{
		if($contrast < -100 || $contrast > 100)
		{
			throw new InvalidArgumentException('$contrast ranges from -100 to 100');
		}

		$this->contrast = pow((100 + $contrast) /  100, 2);
	}

	public function filter(Color $color)
	{
		$color->red -= 127.5;
		$color->red *= $this->contrast;
		$color->red += 127.5;

		$color->green -= 127.5;
		$color->green *= $this->contrast;
		$color->green += 127.5;

		$color->blue -= 127.5;
		$color->blue *= $this->contrast;
		$color->blue += 127.5;

		$color->red = min(255, max(0, $color->red));
		$color->green = min(255, max(0, $color->green));
		$color->blue = min(255, max(0, $color->blue));

		return $color;
	}
}
?>