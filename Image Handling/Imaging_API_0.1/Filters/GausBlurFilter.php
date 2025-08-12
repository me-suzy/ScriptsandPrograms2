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

class GausBlurFilter implements IAction
{
	private $blurWidth;
	private $blurHeight;

	public function __construct($blurWidth, $blurHeight)
	{
		$this->blurWidth = $blurWidth;
		$this->blurHeight = $blurHeight;
	}

	private function Gaus($x, $middle, $width)
	{
		if ($width == 0)
			return 1;

		$temp = - (1 / $width) * pow($middle - $x, 2);
		return pow(1.5, $temp);
	}

	public function executeActions(Image $image)
	{
		$weights = array();

		for($i = 0; $i < $this->blurWidth * 2; $i++)
		{
			$weights[$i] = $this->Gaus(- $this->blurWidth + $i, 0, $this->blurWidth);;
		}


		for($row = 0; $row < $image->getHeight(); $row++)
		{
			for($col = 0; $col < $image->getWidth(); $col++)
			{
				$tempColor = new Color(0,0,0);

				$weightsum = 0;

				for($i = 0; $i < $this->blurWidth * 2; $i++)
				{
					$x = $col - $this->blurWidth + $i;

					if($x < 0)
					{
						$i += -$x;
						$x = 0;
					}

					if($x >= $image->getWidth() - 1)
					{
						break;
					}

					$color = $image->getColors($x, $row);

					$tempColor->red		+= $color->red * $weights[$i];
					$tempColor->green	+= $color->green * $weights[$i];
					$tempColor->blue	+= $color->blue * $weights[$i];

					$weightsum += $weights[$i];
				}

				$tempColor->red		/= $weightsum;
				$tempColor->green	/= $weightsum;
				$tempColor->blue	/= $weightsum;

				if($tempColor->red		> 255)	$tempColor->red		= 255;
				if($tempColor->green	> 255)	$tempColor->green	= 255;
				if($tempColor->blue		> 255)	$tempColor->blue	= 255;

				$image->setPixel($col, $row, $tempColor);
			}
		}

		for($i = 0; $i < $image->getHeight() * 2; $i++)
		{
			$weights[$i] = $this->Gaus(- $this->blurHeight + $i, 0, $this->blurHeight);
		}

		for($row = 0; $row < $image->getHeight(); $row++)
		{
			for($col = 0; $col < $image->getWidth(); $col++)
			{
				$tempColor = new Color(0,0,0);

				$weightsum = 0;

				for($i = 0; $i < $this->blurHeight * 2; $i++)
				{
					$y = $row - $this->blurHeight + $i;

					if($y < 0)
					{
						$i += -$y;
						$y = 0;
					}

					if($y >= $image->getHeight() - 1)
					{
						break;
					}

					$color = $image->getColors($col, $y);

					$tempColor->red		+= $color->red * $weights[$i];
					$tempColor->green	+= $color->green * $weights[$i];
					$tempColor->blue	+= $color->blue * $weights[$i];

					$weightsum += $weights[$i];
				}

				$tempColor->red		/= $weightsum;
				$tempColor->green	/= $weightsum;
				$tempColor->blue	/= $weightsum;

				if($tempColor->red		> 255)	$tempColor->red		= 255;
				if($tempColor->green	> 255)	$tempColor->green	= 255;
				if($tempColor->blue		> 255)	$tempColor->blue	= 255;

				$image->setPixel($col, $row, $tempColor);
			}
		}
	}
}
?>