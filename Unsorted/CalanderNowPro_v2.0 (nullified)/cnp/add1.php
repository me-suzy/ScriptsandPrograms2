<html>

<head>

<title>CalendarNow</title>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">

</head>



<body bgcolor="#FFFFFF" text="#000000">
<div align="left"><font size="4" face="Arial, Helvetica, sans-serif"><strong>Add 
  Event </strong></font></div>
            <form name="" method="POST" action="main.php" ONSUBMIT="copyValue(this);">
  <table width="375" border="0" cellspacing="0" cellpadding="0">
    <tr>
      <td width="60"><font size="2" face="Arial, Helvetica, sans-serif">Date</font></td>
      <td><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <select size=1 name=month>
            <option value="01" selected>January</option>
            <option value="02">February</option>
            <option value="03">March</option>
            <option value="04">April</option>
            <option value="05">May</option>
            <option value="06">June</option>
            <option value="07">July</option>
            <option value="08">August</option>
            <option value="09">September</option>
            <option value="10">October</option>
            <option value="11">November</option>
            <option value="12">December</option>
          </select>
          <select size=1 name=movo>
            <option 

                    value=01 selected>1</option>
            <option value=02>2</option>
            <option value=03>3</option>
            <option value=04>4</option>
            <option value=05>5</option>
            <option value=06>6</option>
            <option value=07>7</option>
            <option value=08>8</option>
            <option value=09>9</option>
            <option value=10>10</option>
            <option value=11>11</option>
            <option value=12>12</option>
            <option value=13>13</option>
            <option value=14>14</option>
            <option value=15>15</option>
            <option value=16>16</option>
            <option value=17>17</option>
            <option value=18>18</option>
            <option value=19>19</option>
            <option value=20>20</option>
            <option value=21>21</option>
            <option value=22>22</option>
            <option value=23>23</option>
            <option value=24>24</option>
            <option value=25>25</option>
            <option value=26>26</option>
            <option value=27>27</option>
            <option value=28>28</option>
            <option value=29>29</option>
            <option value=30>30</option>
            <option value=31>31</option>
          </select>
          <select size=1 name=year>
            <option value="2002">2002</option>
            <option value="2003" selected>2003</option>
            <option value="2004">2004</option>
            <option value="2005">2005</option>
            <option value="2006">2006</option>
            <option value="2007">2007</option>
            <option value="2008">2008</option>
          </select>
          </font></div></td>
    </tr>
    <tr>
      <td width="60"><font size="2" face="Arial, Helvetica, sans-serif">Time 
        </font></td>
      <td><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input name="time" type="text" id="time" size="10">
          <font color="#999999">(Optional) </font></font></div></td>
    </tr>
    <tr>
      <td width="60"><font size="2" face="Arial, Helvetica, sans-serif">Header</font></td>
      <td><div align="left"><font size="2" face="Arial, Helvetica, sans-serif"> 
          <input type="text" name="header">
          </font></div></td>
    </tr>
  </table>
  <p><font size="2" face="Arial, Helvetica, sans-serif">Information<br>
    <? 
//////////////////////////////////////////////////////////////////////////////                      
//                                                                          //
//  Program Name         : Calander Now Pro                                 //
//  Program version      : 2.0                                              //
//  Program Author       : Jason VandeBoom                                  //
//  Supplied by          : drew010                                          //
//  Nullified by         : CyKuH [WTN]                                      //
//  Distribution         : via WebForum, ForumRU and associated file dumps  //
//                                                                          //
//////////////////////////////////////////////////////////////////////////////                      
	$srcloc = BYPASS;
	include("editor.php"); 
	?>
    </font></p>
              <p><font size="2" face="Arial, Helvetica, sans-serif"> 
			  			  <input name="page" type="hidden" value="add">

			  <input name="nl" type="hidden" value="<? print $nl; ?>">
                <input type="submit" name="Submit" value="Submit">
                </font></p>
            </form>
  
  
</body>

</html>

