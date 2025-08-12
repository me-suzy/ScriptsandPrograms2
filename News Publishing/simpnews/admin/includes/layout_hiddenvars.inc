<?php
/***************************************************************************
 * (c)2002-2005 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
for($i=0;$i<count($commonvars);$i++)
{
	echo "<input type=\"hidden\" name=\"";
	echo $commonvars[$i];
	echo "\" value=\"";
	echo $$commonvars[$i];
	echo "\">\n";
}
for($i=0;$i<count($layoutvars);$i++)
{
	if(!in_array($layoutvars[$i],$pagevars[$layoutpage]))
	{
		echo "<input type=\"hidden\" name=\"";
		echo $layoutvars[$i];
		echo "\" value=\"";
		echo do_htmlentities(stripslashes($$layoutvars[$i]));
		echo "\">\n";
	}
}
