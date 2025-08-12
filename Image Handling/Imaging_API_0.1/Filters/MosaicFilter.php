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

class MosaicFilter implements IAction
{
	private $pixelSize;

	public function __construct($pixelSize)
	{
		$this->pixelSize = $pixelSize;
	}

	public function executeActions(Image $image)
	{
		for($x = 0; $x < $image->getWidth(); $x += $this->pixelSize)
		{
			for($y = 0; $y < $image->getHeight(); $y += $this->pixelSize)
			{
				imageFilledRectangle($image->getHandle(), 
					$x, 
					$y, 
					$x + $this->pixelSize, 
					$y + $this->pixelSize, 
					imageColorAt($image->getHandle(), $x + 1, $y + 1));
			}
		}
	}
}
?>