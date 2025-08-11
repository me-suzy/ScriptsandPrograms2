                
<?php
                /*
Zircote Web Management
Copyright (C) 1999  Robert D. Allen <allenb@home-networking.org>

This library is free software; you can redistribute it and/or
modify it under the terms of the GNU Lesser General Public
License as published by the Free Software Foundation; either
version 2 of the License, or (at your option) any later version.

This library is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public
License along with this library; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

email: allenb@home-networking.org
*/

                //New DataBase Class Instance
                $logcln_sql = new slashDB;
                
                // Date Variables
                $today = (date( "Y-m-d"));
                if (!isset($day))$day = $today;
                $month = (date( "Y-m"));
                $year = (date( "Y"));
                $yes =date( "Y-m-");
                $yes .= date("d",(mktime(0,0,-1)));
                
                function percent_it ($dat1,$dat2)
                {
		$test = 0;
		if ($dat1 & $dat2) $test =$dat1 / $dat2;
                ereg("0\.([0-9]{2})",$test,$match);
                if ($match[1] == "") $match[1]=0;
                return($match[1]. " %");
                unset($match);
                }
                
                function rowhead ($cat,$res)
                {
                echo"<tr>\n<th>$cat</th>\n<th>$res</th>\n</tr>\n";
                }
                function rowit ($cat,$res)
                {
                echo"<tr>\n<td>$cat</td>\n<th>$res</th>\n</tr>\n";
                }
                
                function close_table ()
                {
                echo"</table>\n</td>";
                }
                function sect_open ()
                {
                echo "<table align=\"center\"><Tr valign=\"middle\"><TD><TABLE  border=1 cellPadding=1 cellSpacing=0  bordercolor=\"#5B69A6\" style=\"font-size: xx-small;\" width=300>\n";
                }
                function seper ()
                {
                echo "</td><td width=100></td>";
                }
                function sect_close ()
                {
                echo "</tr></table>"; 
                }
                
                function avghitcnt ()
                {
                global $logcln_sql;
                for($index=0; $index < 7; $index++)
                {
                $date=(date("Y-m-d",(mktime(0,0,-1))));
                $sear= "SELECT * FROM activity_log where time like '$date%' and page not like '404'"; // ; here
                $logcln_sql->query($sear);
                $res =$logcln_sql->num_rows() ;
                $total = $total+$res;
                }
                $avghits = ($total/7);
                print ("$avghits");
                }
                
                function avgsescnt ()
                {
                global $logcln_sql;
                for($index=0; $index < 7; $index++)
                {
                $date=(date("Y-m-d",(mktime(0,0,-1))));
                $sear= "SELECT * FROM activity_log where time like '$date%' and page not like '404' group by sid"; 
                $logcln_sql->query($sear);
                $res =$logcln_sql->num_rows() ;
                $total = $total+$res;
                }
                $avgses = ($total/7);
                print ("$avgses");
                }
                
                function avguniusr ()
                {
                global $logcln_sql;
                for($index=0; $index < 7; $index++)
                {
                $date=(date("Y-m-d",(mktime(0,0,-1))));
                $sear= "SELECT * FROM activity_log where time like '$date%' group by remote_addr"; 
                $logcln_sql->query($sear);
                $res =$logcln_sql->num_rows() ;
                $total = $total+$res;
                }
                $avgunius = ($total/7);
                print ("$avgunius");
                }
                
                function avgsing ()
                {
                global $logcln_sql;
                $sear= "SELECT * FROM activity_log  group by remote_addr"; 
                $logcln_sql->query($sear);
                $res =$logcln_sql->num_rows() ;
                $total = $total+$res;
                $avgunius = $res;
                print ("$avgunius");
                }
                
                function avgmul ()
                {
                global $logcln_sql;
                $sear1= "SELECT * FROM activity_log group by sid"; 
                $logcln_sql->query($sear1);
                $res1 =$logcln_sql->num_rows() ;
                $sear= "SELECT * FROM activity_log group by remote_addr"; 
                $logcln_sql->query($sear);
                $res =$logcln_sql->num_rows() ;
                $avgmul=$res1-$res;
                print ("$avgmul");
                }
                /* Variable Inits Prevents Blank Cells in table if returns 0 */
                $qntswin32m = 0;
                $qntwin32 = 0;
                $qntwinnt = 0;
                $qntwinntm = 0;
                $qntwin98 = 0;
                $qntwin98m = 0;
                $qntwin95 = 0;
                $qntwin95m = 0;
                $qntlin = 0;
                $qntlinm = 0;
                $qntsun = 0;
                $qntsunm = 0;
                $qntmac = 0;
                $qntmacm = 0;
                $qntbsd = 0;
                $qntbsdm = 0;
                
                
                // Various Reporting Queries
                $quniqeses= "SELECT * FROM activity_log  where time like '$today%' group by sid "; 
                $logcln_sql->query($quniqeses);
                $qntuniqeses =$logcln_sql->num_rows() ;
                
                $qtotses= "SELECT * FROM activity_log  group by sid "; 
                $logcln_sql->query($quniqeses);
                $qnttotses =$logcln_sql->num_rows() ;
                
                $qt= "SELECT * FROM activity_log where page not like '404'"; 
                $logcln_sql->query($qt);
                $qtotal =$logcln_sql->num_rows() ;     
                
                $qth= "SELECT * FROM activity_log where page like 'home' and page not like '404'"; 
                $logcln_sql->query($qth);
                $qtotalh =$logcln_sql->num_rows() ;   
                
                $quniqe= "SELECT * FROM activity_log  where time like '$today%' group by remote_host "; 
                $logcln_sql->query($quniqe);
                $qntuniqe =$logcln_sql->num_rows() ;
                
                $quniqem= "SELECT * FROM activity_log  where time like '$month%' group by remote_host "; 
                $logcln_sql->query($quniqem);
                $qntuniqem =$logcln_sql->num_rows() ;
                
                $qy= "SELECT * FROM activity_log  where time like '$year%' "; 
                $logcln_sql->query($qy);
                $qnty =$logcln_sql->num_rows() ;
                
                $qm= "SELECT * FROM activity_log  where time like '$month%' "; 
                $logcln_sql->query($qm);
                $qntm =$logcln_sql->num_rows() ;
                
                $qn= "SELECT * FROM activity_log  where time like '$today%' "; 
                $logcln_sql->query($qn);
                $qntd =$logcln_sql->num_rows() ;
                
                $qyst= "SELECT * FROM activity_log  where time like '$yes%' "; 
                $logcln_sql->query($qyst);
                $qntys =$logcln_sql->num_rows() ;
                
                //MSIE Queries 
                $qie= "SELECT * FROM activity_log  where browser like 'MSIE%' and time like '$today%'"; 
                $logcln_sql->query($qie);
                $qntie =$logcln_sql->num_rows() ;
                
                $qiem= "SELECT * FROM activity_log  where browser like 'MSIE%' and time like '$month%'"; 
                $logcln_sql->query($qiem);
                $qntiem =$logcln_sql->num_rows() ;
                
                $qie2= "SELECT * FROM activity_log  where browser like 'MSIE%2%' and time like '$today%'"; 
                $logcln_sql->query($qie2);
                $qntie2 =$logcln_sql->num_rows() ;
                
                $qie2m= "SELECT * FROM activity_log  where browser like 'MSIE%2%' and time like '$month%'"; 
                $logcln_sql->query($qie2m);
                $qntie2m =$logcln_sql->num_rows() ;
                
                $qie3= "SELECT * FROM activity_log  where browser like 'MSIE%3%' and time like '$today%'"; 
                $logcln_sql->query($qie3);
                $qntie3 =$logcln_sql->num_rows() ;
                
                $qie3m= "SELECT * FROM activity_log  where browser like 'MSIE%3%' and time like '$month%'"; 
                $logcln_sql->query($qie3m);
                $qntie3m =$logcln_sql->num_rows() ;
                
                $qie4= "SELECT * FROM activity_log  where browser like 'MSIE 4%' and time like '$today%'"; 
                $logcln_sql->query($qie4);
                $qntie4 =$logcln_sql->num_rows() ;
                
                $qie4m= "SELECT * FROM activity_log  where browser like 'MSIE 4%' and time like '$month%'"; 
                $logcln_sql->query($qie4m);
                $qntie4m =$logcln_sql->num_rows() ;
                
                $qie5= "SELECT * FROM activity_log  where browser like 'MSIE 5%' and time like '$today%'"; 
                $logcln_sql->query($qie5);
                $qntie5 =$logcln_sql->num_rows() ;
                
                $qie5m= "SELECT * FROM activity_log  where browser like 'MSIE 5%' and time like '$month%'"; 
                $logcln_sql->query($qie5m);
                $qntie5m =$logcln_sql->num_rows() ;
                //END MSIE Queries
                
                //NAV Queries
                $qnav= "SELECT * FROM activity_log  where browser like 'nav%' and time like '$today%'"; 
                $logcln_sql->query($qnav);
                $qntnav =$logcln_sql->num_rows() ;
                
                $qnavm= "SELECT * FROM activity_log  where browser like 'nav%' and time like '$month%'"; 
                $logcln_sql->query($qnavm);
                $qntnavm =$logcln_sql->num_rows() ;
                
                $qnav5= "SELECT * FROM activity_log  where browser like 'nav%5%' and time like '$today%'"; 
                $logcln_sql->query($qnav5);
                $qntnav5 =$logcln_sql->num_rows() ;
                
                $qnav5m= "SELECT * FROM activity_log  where browser like 'nav%5%' and time like '$month%'"; 
                $logcln_sql->query($qnav5m);
                $qntnav5m =$logcln_sql->num_rows() ;
                
                $qnav4= "SELECT * FROM activity_log  where browser like 'nav%4%' and time like '$today%'"; 
                $logcln_sql->query($qnav4);
                $qntnav4 =$logcln_sql->num_rows() ;
                
                $qnav4m= "SELECT * FROM activity_log  where browser like 'nav%4%' and time like '$month%'"; 
                $logcln_sql->query($qnav4m);
                $qntnav4m =$logcln_sql->num_rows() ;
                
                $qnav3= "SELECT * FROM activity_log  where browser like 'nav%3%' and time like '$today%'"; 
                $logcln_sql->query($qnav3);
                $qntnav3 =$logcln_sql->num_rows() ;
                
                $qnav3m= "SELECT * FROM activity_log  where browser like 'nav%3%' and time like '$month%'"; 
                $logcln_sql->query($qnav3m);
                $qntnav3m =$logcln_sql->num_rows() ;
                
                $qnav2= "SELECT * FROM activity_log  where browser like 'nav%2%' and time like '$today%'"; 
                $logcln_sql->query($qnav2);
                $qntnav2 =$logcln_sql->num_rows() ;
                
                $qnav2m= "SELECT * FROM activity_log  where browser like 'nav%2%' and time like '$month%'"; 
                $logcln_sql->query($qnav2m);
                $qntnav2m =$logcln_sql->num_rows() ;
                
                //END NAV Queries
                //START OPERA
                $qoper= "SELECT * FROM activity_log  where browser like 'opera%' and time like '$today%'"; 
                $logcln_sql->query($qoper);
                $qntoper=$logcln_sql->num_rows() ;
                
                $qoperm= "SELECT * FROM activity_log  where browser like 'opera%' and time like '$month%'"; 
                $logcln_sql->query($qoperm);
                $qntoperm =$logcln_sql->num_rows() ;
                
                //Undefined Browser Data Numbers
                $qntdefm = ($qntiem+$qntnavm+$qntoperm);
                $qntudefm = ($qntm - $qntdefm);
                $qntdef = ($qntie + $qntnav + $qntoper);
                $qntudef = ($qnty-$qntdef);
                
                //Start Total WinDows
                $qoswin= "SELECT * FROM activity_log  where os like 'win%' and time like '$today%'"; 
                $logcln_sql->query($qoswin);
                $qntwin =$logcln_sql->num_rows() ;
                
                $qoswinm= "SELECT * FROM activity_log  where os like 'win%' and time like '$month%'"; 
                $logcln_sql->query($qoswinm);
                $qntwinm =$logcln_sql->num_rows() ;
                //End Total Windows
                
                //Start Total Linux
                $qoslin= "SELECT * FROM activity_log  where os like 'linux%' and time like '$today%'"; 
                $logcln_sql->query($qoslin);
                $qntlin =$logcln_sql->num_rows() ;
                
                $qoslinm= "SELECT * FROM activity_log  where os like 'linux%' and time like '$month%'"; 
                $logcln_sql->query($qoslinm);
                $qntlinm =$logcln_sql->num_rows() ;
                //End Total Linux
                
                //Start Total SunOS
                $qossun= "SELECT * FROM activity_log  where os like 'sun%' and time like '$today%'"; 
                $logcln_sql->query($qossun);
                $qntsun =$logcln_sql->num_rows() ;
                
                $qossunm= "SELECT * FROM activity_log  where os like 'sun%' and time like '$month%'"; 
                $logcln_sql->query($qossunm);
                $qntsunm =$logcln_sql->num_rows() ;
                //End Total SunOS
                
                //Start Total SunOS
                $qosbsd= "SELECT * FROM activity_log  where os like 'bsd%' and time like '$today%'"; 
                $logcln_sql->query($qosbsd);
                $qntbsd =$logcln_sql->num_rows() ;
                
                $qosbsdm= "SELECT * FROM activity_log  where os like 'bsd%' and time like '$month%'"; 
                $logcln_sql->query($qosbsdm);
                $qntbsdm =$logcln_sql->num_rows() ;
                //End Total SunOS
                
                //Start Windows Specific
                //Start Win 95
                $qoswin95= "SELECT * FROM activity_log  where os like 'win%95%' and time like '$today%'"; 
                $logcln_sql->query($qoswin95);
                $qntwin95 =$logcln_sql->num_rows() ;
                
                $qoswin95m= "SELECT * FROM activity_log  where os like 'win%95%' and time like '$month%'"; 
                $logcln_sql->query($qoswin95m);
                $qntwin95m =$logcln_sql->num_rows() ;
                //End Total Windows95
                
                //Start Win 98
                $qoswin98= "SELECT * FROM activity_log  where os like 'win%98%' and time like '$today%'"; 
                $logcln_sql->query($qoswin98);
                $qntwin98 =$logcln_sql->num_rows() ;
                
                $qoswin98m= "SELECT * FROM activity_log  where os like 'win%98%' and time like '$month%'"; 
                $logcln_sql->query($qoswin98m);
                $qntwin98m =$logcln_sql->num_rows() ;
                //End Total Windows98
                
                //Start Win nt
                $qoswinnt= "SELECT * FROM activity_log  where os like 'win%nt%' and time like '$today%'"; 
                $logcln_sql->query($qoswinnt);
                $qntwinnt =$logcln_sql->num_rows() ;
                
                $qoswinntm= "SELECT * FROM activity_log  where os like 'win%nt%' and time like '$month%'"; 
                $logcln_sql->query($qoswinntm);
                $qntwinntm =$logcln_sql->num_rows() ;
                //End Total Windows nt
                
                //Start Win 32
                $qoswin32= "SELECT * FROM activity_log  where os like 'win%32%' and time like '$today%'"; 
                $logcln_sql->query($qoswin32);
                $qntwin32 =$logcln_sql->num_rows() ;
                
                $qoswin32m= "SELECT * FROM activity_log  where os like 'win%32%' and time like '$month%'"; 
                $logcln_sql->query($qoswin32m);
                $qntwin32m =$logcln_sql->num_rows() ;
                
                //Start Win 3.X
                $qoswin3x= "SELECT * FROM activity_log  where os like 'win%3.%' and time like '$today%'"; 
                $logcln_sql->query($qoswin3x);
                $qntwin3x =$logcln_sql->num_rows() ;
                
                $qoswin3xm= "SELECT * FROM activity_log  where os like 'win%3.%' and time like '$month%'"; 
                $logcln_sql->query($qoswin3xm);
                $qntwin3xm =$logcln_sql->num_rows() ;
                //End Total Windows 3.X
                
                //Start Total Linux
                $qoslin= "SELECT * FROM activity_log  where os like 'linux%' and time like '$today%'"; 
                $logcln_sql->query($qoslin);
                $qntlin =$logcln_sql->num_rows() ;
                
                $qoslinm= "SELECT * FROM activity_log  where os like 'linux%' and time like '$month%'"; 
                $logcln_sql->query($qoslinm);
                $qntlinm =$logcln_sql->num_rows() ;
                //End Total Linux
                
                //Start Total Mac
                $qosmac= "SELECT * FROM activity_log  where os like 'mac%' and time like '$today%'"; 
                $logcln_sql->query($qosmac);
                $qntmac =$logcln_sql->num_rows() ;
                
                $qosmacm= "SELECT * FROM activity_log  where os like 'mac%' and time like '$month%'"; 
                $logcln_sql->query($qosmacm);
                $qntmacm =$logcln_sql->num_rows() ;
                //End Total SunOS
                
                $qyahoo= "SELECT * FROM activity_log  where referer like '%yahoo.com%' and time like '$month%'"; 
                $logcln_sql->query($qyahoo);
                $qntyahoo =$logcln_sql->num_rows() ;
                
                $qexcite= "SELECT * FROM activity_log  where referer like '%excite.com%' and time like '$month%'"; 
                $logcln_sql->query($qexcite);
                $qntexcite =$logcln_sql->num_rows() ;       
                
                $qwebcrawler= "SELECT * FROM activity_log  where referer like '%webcrawler.com%' and time like '$month%'"; 
                $logcln_sql->query($qwebcrawler);
                $qntwebcrawler =$logcln_sql->num_rows() ;      
                
                $qinfoseek= "SELECT * FROM activity_log  where referer like '%infoseek%' and time like '$month%'"; 
                $logcln_sql->query($qinfoseek);
                $qntinfoseek =$logcln_sql->num_rows() ;      
                
                $qsnap= "SELECT * FROM activity_log  where referer like '%snap.com%' and time like '$month%'"; 
                $logcln_sql->query($qsnap);
                $qntsnap=$logcln_sql->num_rows() ;           
                
                $qmsn= "SELECT * FROM activity_log  where referer like '%search.msn%' and time like '$month%'"; 
                $logcln_sql->query($qmsn);
                $qntmsn=$logcln_sql->num_rows() ;    
                
                $qgo2= "SELECT * FROM activity_log  where referer like '%go2net.com%' and time like '$month%'"; 
                $logcln_sql->query($qgo2);
                $qntgo2=$logcln_sql->num_rows() ;                     
                //End Total SunOS
                
                ?>
