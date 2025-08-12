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

class PixelGrayScaleFilter implements IPixelFilter
{
	public function filter(Color $color)
	{
		// 0.299, 0.587 and 0.114 are corrections made for the human eye
		$scale = $color->red * 0.299 + $color->green * 0.587 + $color->blue * 0.114;
		$scale = min(255, max(0, $scale));

		$color->red		= $scale;
		$color->green	= $scale;
		$color->blue	= $scale;

		return $color;
	}
}
?>