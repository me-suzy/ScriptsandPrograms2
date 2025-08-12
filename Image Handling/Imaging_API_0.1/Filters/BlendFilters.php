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

/*
 * Note: BlendFilters requires 2 images of the same size!
 */
class BlendFilters implements IAction
{
	private $filters = array();
	private $imageLeft;

	public function __construct(Image $left)
	{
		$this->imageLeft = $left;
	}

	public function addFilter(IBlendFilter $f)
	{
		$this->addFilters($f,$f,$f);
	}

	public function addFilters(IBlendFilter $r, IBlendFilter $g, IBlendFilter $b)
	{
		$this->filters[] = array('r' => $r, 'g' => $g, 'b' => $b);
	}

	public function executeActions(Image $image)
	{
		$w = $image->getWidth();
		$h = $image->getHeight();

		if($this->left->getWidth() != $w || $this->left->getHeight() != $h)
		{
			throw new InvalidImageException('Images should have the same size');
		}

		for($x = 0; $x < $w; $x++)
		{
			for($y = 0; $y < $h; $y++)
			{
				$colorLeft	= $this->imageLeft->getColors($x, $y);
				$colorRight = $image->getColors($x, $y);

				foreach($this->filters as $filter)
				{
					$colorLeft->red		= $filter['r']->filter($colorLeft->red,		$colorRight->red);
					$colorLeft->green	= $filter['g']->filter($colorLeft->green,	$colorRight->green);
					$colorLeft->blue	= $filter['b']->filter($colorLeft->blue,	$colorRight->blue);
				}

				$image->setPixel($x, $y, $colorLeft);
			}
		}
	}
}
?>