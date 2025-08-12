<?
/*
Copyright 2003 Greg Neustaetter

Redistribution and use in source and binary forms, with or without modification, 
are permitted provided that the following conditions are met:

1.	Redistributions of source code must retain the above copyright notice, this list 
	of conditions and the following disclaimer. 
2.	Redistributions in binary form must reproduce the above copyright notice, this list 
	of conditions and the following disclaimer in the documentation and/or other materials 
	provided with the distribution. 
3.	The name of the author may not be used to endorse or promote products derived from this 
	software without specific prior written permission. 

THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS OR IMPLIED WARRANTIES, 
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR 
A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY DIRECT, 
INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT 
LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR 
BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, 
STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE 
USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/


// pager class requires use of ADODB
class pager
{
	var $row;
	var $pages;
	var $currentpage;
	var $limit;
	var $numrows;
	var $first;
	var $last;
	function pager($conn, $row, $limit=10, $getNumRowsSQL)
	{
		if (!isset($row)) $row = 0;
		$this->row = $row;
		$this->limit = $limit;
		$this->numrows = $conn->GetOne($getNumRowsSQL);
		if (($this->numrows % $this->limit) == 0) $this->pages = ($this->numrows/$this->limit);
		else $this->pages = intval($this->numrows/$this->limit) + 1;
		$this->currentpage = ($this->row/$this->limit) + 1;
		$this->first = ($this->row + 1);
		if (($this->row + $this->limit) >= $this->numrows) $this->last = $this->numrows; else $this->last = ($this->row + $this->limit);
	}
	function getrecords($conn, $sql, $array=false)
	{
		$recordSet = &$conn->SelectLimit($sql,$this->limit,$this->row);
		if (!$array) return $recordSet;
		return $recordSet->getArray(); 
	}
	function showpagernav($backtext = 'Back', $nexttext = 'Next', $otherargs='')
	{
		if ($this->pages > 1)
		{
			echo '<table>';
			// Back Link
			echo '<td width="30">';
			if ($this->row != 0) 
			{
				$backPage = $this->row - $this->limit;  
				echo "<a href=\"".$_SERVER['PHP_SELF']."?row=".$backPage.$otherargs."\">$backtext</a>\n";
			}
			else echo '&nbsp;';
			echo '</td>';
			// Pages Links
			echo '<td>';
			for ($i=1; $i <= $this->pages; $i++) 
			{  
				$ppage = $this->limit*($i - 1);
				if ($ppage == $this->row)
				{
					echo "<b>$i</b>&nbsp;&nbsp; \n";
				}
				else 
				{
					echo "<a href=\"".$_SERVER['PHP_SELF']."?row=".$ppage.$otherargs."\">$i</a>&nbsp;&nbsp; \n";
				}
			}
			echo '</td>';
			// Next Link
			if ($this->currentpage < $this->pages) 
			{ 
				$nextPage = $this->row + $this->limit;
				echo "<td><a href=\"".$_SERVER['PHP_SELF']."?row=".$nextPage.$otherargs."\">$nexttext</a></td>\n";
			}
			echo '</table>';
		}
		else echo "&nbsp;";
	}	
}
?>