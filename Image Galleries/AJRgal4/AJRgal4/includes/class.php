<?php
// +----------------------------------------------------------------------+
// |                        AJRgal version 4.1                            |
// +----------------------------------------------------------------------+
// |     This program is free software; you can redistribute it and/or    |
// |      modify it under the terms of the GNU General Public License     |
// |    as published by the Free Software Foundation; either version 2    |
// |        of the License, or (at your option) any later version.        |
// |                                                                      |
// |   This program is distributed in the hope that it will be useful,    |
// |   but WITHOUT ANY WARRANTY; without even the implied warranty of     |
// |    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the     |
// |          GNU General Public License for more details.                |
// |                                                                      |
// | You should have received a copy of the GNU General Public License    |
// |    along with this program; if not, write to the Free Software       |
// |     Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA        |
// |                          02111-1307, USA.                            |
// |                                                                      |
// +----------------------------------------------------------------------+
// | Author:   Andrew John Reading - A.J.Reading <andrew@cbitsonline.com> |
// | Website:  http://www.cbitsonline.com                                 |
// +----------------------------------------------------------------------+
// | File:             class.php                                          |
// | Description:      Used to power the inner workings of AJRgal         |
// | Last Update:      22/11/2005                                         |
// +----------------------------------------------------------------------+

class ajrgal
{
    var $m_count;
    var $m_images = Array();
    var $m_cols;
    var $m_rows;
    var $m_pages;
    var $m_page;
    var $m_perpage;
    
    function ajrgal($dir, $rows, $cols, $page)
    {
        $this->readdir($dir);
        $this->m_page = $page;
        $this->m_cols = $cols;
        $this->m_rows = $rows;
        $this->m_perpage = $this->m_rows * $this->m_cols;
        $this->m_pages = ceil($this->m_count / $this->m_perpage);
        if($this->m_page > $this->m_pages) { $this->m_page = 1;}
    }
    
    function display($tdClass, $linkTarget)
    {
        if(empty($linkTarget)) { $linkTarget = "_blank"; }
        if($this->m_page == 1)
        {
            if($this->m_count < $this->m_perpage)
            {
                $this->m_perpage = $this->m_count;
            }
            $col_count = 0;
            for( $i=0; $i<$this->m_perpage; $i++ )
            {
                if($col_count == $this->m_cols)
                {
                    echo "  </tr>\n  <tr>\n";
                    $col_count = 0;
                }
                echo "   <td class=\"".$tdClass."\"><a href=\"".$this->m_images[$i]."\" target=\"".$linkTarget."\"><img alt=\"\" border=\"0\" src=\"show.php?file=".$this->m_images[$i]."\"></a></td>\n";
                $col_count++;
            }
        }
        else
        {
            $col_count = 0;
            $range = $this->m_perpage * $this->m_page;
            $range_start = $range - $this->m_perpage;
            for( $i=$range_start; $i<$range; $i++ )
            {
                if($col_count == $this->m_cols)
                {
                    echo "  </tr>\n  <tr>\n";
                    $col_count = 0;
                }
                if( !empty($this->m_images[$i]) )
                {
                    echo "   <td class=\"".$tdClass."\"><a href=\"".$this->m_images[$i]."\" target=\"".$linkTarget."\"><img alt=\"\" border=\"0\" src=\"show.php?file=".$this->m_images[$i]."\"></a></td>\n";
                }
                else
                {
                    echo "   <td class=\"".$tdClass."\"></td>\n";
                }
                $col_count++;
            }
        }
    }

    function readdir($dirname)
    {
        $dir = opendir($dirname);
        $this->m_count = 0;

        while( false != ($file = readdir($dir) ) )
        {
            if( ($file != ".") && ( $file != ".." ) )
            {
                if($this->checkImg($file))
                {
                    $this->m_images[$this->m_count] = $file;
                    $this->m_count++;
                }
            }
        }
    }
    
    function checkImg($filename)
    {
        $format = strtolower(substr(strrchr($filename,"."),1));
        switch($format)
        {
         case 'gif' :
             return true;
             break;
         case 'png' :
             return true;
             break;
         case 'jpg' :
             return true;
             break;
         case 'jpeg' :
             return true;
             break;
         default :
             return false;
             break;
         }
    }
    
    function pageNext($class)
    {
        echo "<span class=\"".$class."\">";
        if($this->m_page == $this->m_pages)
        {
            echo "Next";
        }
        else
        {
            $page = $this->m_page + 1;
            echo '<a href="index.php?page='.$page.'">Next</a>';
        }
        echo "</span>";
    }
    
    function pagePrev($class)
    {
        echo "<span class=\"".$class."\">";
        if($this->m_page == 1)
        {
            echo 'Previous';
        }
        else
        {
            $temp = $this->m_page - 1;
            echo '<a href="index.php?page='.$temp.'">Previous</a>';
        }
        echo "</span>";
    }
    
    function pageNumbers($class,$class2)
    {
        echo "<span class=\"".$class."\">";
        $i=1;
        while( $i<$this->m_pages + 1 )
        {
            if($this->m_page == $i)
            {
                echo "</span><span class=\"".$class2."\"> ".$i." </span><span class=\"".$class."\">";
            }
            else
            {
                echo '<a href="index.php?page='.$i.'"> '.$i.' </a> ';
            }
        $i++;
        }
        echo "</span>";
    }
}
?>
