<?

//---------------------------------------------------------------
//This program is free software; you can redistribute it and/or
//modify it under the terms of the GNU General Public License
//as published by the Free Software Foundation; either version 2
//of the License, or (at your option) any later version.
//
//This program is distributed in the hope that it will be useful,
//but WITHOUT ANY WARRANTY; without even the implied warranty of
//MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//GNU General Public License for more details.
//
//Meezerk's Advanced CowCounter - An Advanced Website Counter.
//Copyright (C) 2004  Daniel Foster  dan_software@meezerk.com
//---------------------------------------------------------------

//session check

session_start();
include("config.php");

if (!(($_SESSION['ip'] == $_SERVER['REMOTE_ADDR']) && ($_SESSION['pass'] == $adminpass) && ($_SESSION['access'] == "granted"))) {
  //session info bad
  header("Location: http://" . $_SERVER['HTTP_HOST'] . str_replace( "\\", "/", dirname($_SERVER['PHP_SELF'])) . "/logout.php?session=bad");

} else {
  //session info good

  include("header.php");

  ?>

<FORM>
<H2>Help - Table Of Contents</H2>

<UL>
  <LI><A HREF="#THeadings">Table Headings</A>
  <UL>
    <LI><A HREF="#THeadings_CID">CID (Counter ID)</A>
    <LI><A HREF="#THeadings_Order">Order</A>
    <LI><A HREF="#THeadings_Name">Name</A>
    <LI><A HREF="#THeadings_NumHits">Number of Hits</A>
    <LI><A HREF="#THeadings_LastViewed">Last Viewed / Reset 'Last
    Viewed'</A>
    <LI><A HREF="#THeadings_Type">Type</A>
    <LI><A HREF="#THeadings_MinDigits">Minimum Digits</A>
    <LI><A HREF="#THeadings_Started">Started</A>
    <LI><A HREF="#THeadings_HTML">HTML (button)</A>
    <LI><A HREF="#THeadings_Reset">Reset (button)</A>
    <LI><A HREF="#THeadings_Edit">Edit (button)</A>
    <LI><A HREF="#THeadings_Delete">Delete (button)</A><BR>
  </UL>
  <LI><A HREF="#AddEditCounters">Add/Edit Counters</A>
  <UL>
    <LI><A HREF="#AddEditCounters_New">Create a New Counter</A>
    <UL>
      <LI><A HREF="#AddEditCounters_New_CID">CID (Counter ID)</A>
      <LI><A HREF="#AddEditCounters_New_Started">Started</A>
      <LI><A HREF="#AddEditCounters_New_Name">Name</A>
      <LI><A HREF="#AddEditCounters_New_Type">Type</A>
      <LI><A HREF="#AddEditCounters_New_Dest">Destination</A>
      <LI><A HREF="#AddEditCounters_New_MinDigits">Minimum Digits</A>
      <LI><A HREF="#AddEditCounters_New_StartCount">Starting Count</A>
    </UL>
    <LI><A HREF="#AddEditCounters_Sep">Create a Counter Seperator</A><BR>
  </UL>
  <LI><A HREF="#Statistics">Statistics</A>
  <UL>
    <LI><A HREF="#Stats_TotalHits">Total Hits</A>
    <LI><A HREF="#Stats_YearlyHits">Yearly Hits</A>
    <LI><A HREF="#Stats_MonthlyHitsOverAYear">Monthly Hits Over A Year</A>
    <LI><A HREF="#Stats_WeeklyHitsOverAYear">Weekly Hits Over A Year</A>
    <LI><A HREF="#Stats_DailyHitsOverAMonth">Daily Hits Over A Month</A>
    <LI><A HREF="#Stats_DailyHitsOverAWeek">Daily Hits Over A Week</A>
    <LI><A HREF="#Stats_CounterTable">Statistical Daily Counter Table</A>
  </UL>
  <LI><A HREF="#EditIgnoreIP">Add/Edit Ignoring IPs</A>
  <LI><A HREF="#EditConfig">Edit Configuration</A>
</UL>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P><FONT SIZE="+1"><HR ALIGN=LEFT></FONT></P>

<H2><A NAME="THeadings"></A>Table Headings (Counter Stats / Add/Edit
Counters)</H2>

<H3><A NAME="THeadings_CID"></A>CID (Counter ID)</H3>

<P>This number is unique for each counter created. This number
is also how you identify the counter when calling it. This number
is set when the counter is created, cannot be changed, and is
lost when deleted. Once used, the number is never used again.
These numbers are created in sequence, however, remember that
the seperators also take a number.</P>

<P>&nbsp;</P>

<H3><A NAME="THeadings_Order"></A>Order</H3>

<P>The buttons <INPUT NAME="name" TYPE="submit" VALUE="/\"> and
<INPUT NAME="name" TYPE="submit" VALUE="\/"> are used to move
the counters up and down in the list. If you click on any button
in this column and the sort order is in another view other then
&quot;Order&quot;, then the page is re-sorted to reflect the current
&quot;Order&quot; sequence and you are sent back to the main page
if you are on the &quot;Add/Edit Counters&quot; page. To move
a counter (or seperator) up in the list, click the <INPUT NAME="name"
TYPE="submit" VALUE="/\"> button for the counter. To move a counter
down in the list, click the <INPUT NAME="name" TYPE="submit" 
VALUE="\/"> button. Listing the counters by in this order is the
default and is the only sort type where you will see the seperators.</P>

<P>&nbsp;</P>

<H3><A NAME="THeadings_Name"></A>Name</H3>

<P>This is an arbitrary name given to a counter to identify the
counter to you. This piece of text has no acutal meaning to Advanced
CowCounter.</P>

<P>&nbsp;</P>

<H3><A NAME="THeadings_NumHits"></A># of Hits (Number of Hits)</H3>

<P>This column gives you a quick count of the total number of
hits for each counter. This number is only viewed on the main
Counter Stats page.</P>

<P>&nbsp;</P>

<H3><A NAME="THeadings_LastViewed"></A>Last Viewed / Reset 'Last
Viewed'</H3>

<P>This number takes the # of hits for each counter when you click
the &quot;Reset 'Last Viewed' to current counter numbers&quot;
link at the bottom of the main Counter Stats page. Clicking this
link also changes the &quot;Time of Last Reset&quot; to the current
server time. This allows for a quick hit difference over the time
that it was last reset. This number is only viewed on the Counter
Stats page.</P>

<P>&nbsp;</P>

<H3><A NAME="THeadings_Type"></A>Type</H3>

<P>There are three types of counters avalible in Advanced CowCounter,
they are as follows:</P>

<UL>
  <LI>Hit Counter (HC) - A Hit Counter is your standard 'lets tell
  everyone the number of hits we have' type of counter. A Hit Counter
  displays an image of the number of hits that counter has recieved.<BR>
  <LI>Page Accessed Counter (PAC) - A Page Access Counter is similar
  to a Hit Counter in the fact that they are both loaded as images
  (to keep the HTML simple) but instead of displaying a counter
  to the browser, it displays a transparent, 43 byte, 1 pixel x
  1 pixel image so the browser does not see it.<BR>
  <LI>Link Counter (LC) - A Link Counter is an entirely different
  type of counter. A Link Counter is called by putting it as the
  destination of the HREF part of a link. It then uses the destination
  field (set when the counter was created or last edited) to forward
  the browser to the correct address. This way you can track how
  many times a link gets clicked.
</UL>

<P>&nbsp;</P>

<H3><A NAME="THeadings_MinDigits"></A>Minimum Digits</H3>

<P>This column tells you how many digits are the minimum digits set by you. 
The Minimum Digits option allows you to control how many digits you want 
displayed.  For example, say your counter has had 345 hits, and you have
set the Minimum Digits to 10 digits.  Then the image displayed will show
up as 0000000345.  Keep in mind that since the Hit Counter (HC) is the only
counter to actually display an image, this option is only used for that
counter.</P>

<P>&nbsp;</P>

<H3><A NAME="THeadings_Started"></A>Started</H3>

<P>This date just tells you when the counter was started.</P>

<P>&nbsp;</P>

<H3><A NAME="THeadings_HTML"></A>HTML (button)</H3>

<P>This button sends you to where you can get the correct HTML
for calling the counter. This HTML can be modified by the experienced
coder (or even a new one) but is provided for those just starting
out with HTML and with Advanced CowCounter. Once you get used
to how the code works, then you will be able to modify the code
to better suit your own needs (putting it in more PHP, ASP, Perl,
CGI, Java, or more). Located on the Counter Stats page only.</P>

<P>&nbsp;</P>

<H3><A NAME="THeadings_Reset"></A>Reset (button)</H3>

<P>This button is a very quick way to reset that particular counter
back to what the starting count is set to. This button will not
reset the starting count. If you wish to reset a counter back
to zero (0) then you must click on this button AND set the starting
count to zero (assuming you changed in the first place). Located
on the Add/Edit Counters page only.</P>

<P>&nbsp;</P>

<H3><A NAME="THeadings_Edit"></A>Edit (button)</H3>

<P>This button will take you to a page that will allow you to
change the properties that was set during that counters creation.
Note: be carefull when changing the type of the counter, changing
between a Hit Counter and a Page Accessed counter is alright but
changing to or from a Link Counter will require changing HTML
code on your affected web pages. Located on the Add/Edit Counters
page only.</P>

<P>&nbsp;</P>

<H3><A NAME="THeadings_Delete"></A>Delete (button)</H3>

<P>This button does quite litterally what it says it does. It
deletes the counter or seperator. Located on the Add/Edit Counters
page only.</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P><FONT SIZE="+1"><HR ALIGN=LEFT></FONT></P>

<H2><A NAME="AddEditCounters"></A>Add/Edit Counters</H2>

<H3><A NAME="AddEditCounters_New"></A>Create a New Counter</H3>

<P>Creating a new counter is really quite simple. Just enter a
name, type, destination, and if desired, a starting count. A name
is just an arbitrary string of text that is usefull to you and
has a maximum length of 30 characters. Destination and Starting
Count are explained below. As for type, see <A HREF="#AddEditCounters_New_Type">Type</A>. 
Located on the Add/Edit Counters page
only.</P>

<P>&nbsp;</P>

<H3><A NAME="AddEditCounters_New_CID"></A>CID (Counter ID)</H3>

<P>This number is unique for each counter created. This number
is also how you identify the counter when calling it. This number
is set when the counter is created, cannot be changed, and is
lost when deleted. Once used, the number is never used again.
These numbers are created in sequence, however, remember that
the seperators also take a number.</P>

<P>&nbsp;</P>

<H3><A NAME="AddEditCounters_New_Started"></A>Started</H3>

<P>This date just tells you when the counter was started.</P>

<P>&nbsp;</P>

<H3><A NAME="AddEditCounters_New_Name"></A>Name</H3>

<P>This is an arbitrary name given to a counter to identify the
counter to you. This piece of text has no acutal meaning to Advanced
CowCounter.</P>

<P>&nbsp;</P>

<H3><A NAME="AddEditCounters_New_Type"></A>Type</H3>

<P>There are three types of counters avalible in Advanced CowCounter,
they are as follows:</P>

<UL>
  <LI>Hit Counter (HC) - A Hit Counter is your standard 'lets tell
  everyone the number of hits we have' type of counter. A Hit Counter
  displays an image of the number of hits that counter has recieved.<BR>
  <LI>Page Accessed Counter (PAC) - A Page Access Counter is similar
  to a Hit Counter in the fact that they are both loaded as images
  (to keep the HTML simple) but instead of displaying a counter
  to the browser, it displays a transparent, 43 byte, 1 pixel x
  1 pixel image so the browser does not see it.<BR>
  <LI>Link Counter (LC) - A Link Counter is an entirely different
  type of counter. A Link Counter is called by putting it as the
  destination of the HREF part of a link. It then uses the destination
  field (set when the counter was created or last edited) to forward
  the browser to the correct address. This way you can track how
  many times a link gets clicked.
</UL>

<P>&nbsp;</P>

<H3><A NAME="AddEditCounters_New_Dest"></A>Destination</H3>

<P>Destination is where you want the counter to go when clicked
on (Link Counters Only) and has a maximum length of 255 characters.
</P>

<P>&nbsp;</P>

<H3><A NAME="AddEditCounters_New_MinDigits"></A>Minimum Digits</H3>

<P>The Minimum Digits option allows you to control how many digits you want 
displayed.  For example, say your counter has had 345 hits, and you have
set the Minimum Digits to 10 digits.  Then the image displayed will show
up as 0000000345.  Keep in mind that since the Hit Counter (HC) is the only
counter to actually display an image, this option is only used for that
counter.</P>

<P>&nbsp;</P>

<H3><A NAME="AddEditCounters_New_StartCount"></A>Starting Count</H3>

<P>The Starting Count is what you want the count to be started
at (default is zero).</P>

<P>&nbsp;</P>

<H3><A NAME="AddEditCounters_Sep"></A>Create a Counter Seperator</H3>

<P>This button adds a 'counter like' gap to the bottom of the
list on both the main Counter Stats table and the Change Existing
Counters table. Located on the Add/Edit Counters page only.</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P><HR ALIGN=LEFT></P>

<H2><A NAME="Statistics"></A>Statistics</H2>

<P>There are several types of statistics each with their own set of 
abilities. In a nutshell, first you select a statistic, click the button to go to
step 2, select a date restriction (if applicable), select the counters 
you wish to include, and click build statistics.</P>

<P><B>NOTE:</B> The statistics are built from the hits counted in the database only, the 
starting count number for any counter is not used in any statistic. </P>

<P><B>NOTE:</B> The statistics use all of the data in the database, for instance, if you 
are performing the Yearly Hits statistic, and the counter started half way through one 
year, and we are half way though this year, the first and last year will naturally be 
smaller then any year in between (assuming a relatively equal number of hits per year).</P>

<P>&nbsp;</P>

<H3><A NAME="Stats_TotalHits"></A>Total Hits</H3>

<P>The Total Hits Statistic lists every counter that was selected, the number of 
hits each counter has received, and the percentage those hits have counted towards
the total hits of the counters selected.  The total hits is the total hits off all
counters that were selected, not all counters in the database. On this statistic, 
all counters will show up reguardless of whether or not they have received any hits.</P>

<P>&nbsp;</P>

<H3><A NAME="Stats_YearlyHits"></A>Yearly Hits</H3>

<P>The Yearly hits takes every counter that was selected and seperates the hits per year
and shows the number of hits each year as well as the percentage of hits that each year 
contributed to the counters total hits.  Each counter is evaluated seperately from every
other counter.</P>

<P>&nbsp;</P>

<H3><A NAME="Stats_MonthlyHitsOverAYear"></A>Monthly Hits Over A Year</H3>

<P>The Monthly Hits Over A Year statistic allows you to see each month with the number
of hits in each month.  With this counter, you can have an average number of hits for each
month (averaged over the years) or you can select the year and get an absolute number of 
hits for each month.  Any months that do not have any hits will simply not show up.</P>
<P><B>Note:</B> This statistic uses every piece of data in the database, and divides by the
total number of 365 day years (rounding up) the counter has been running not by the number of times each
month has occured. This allows for an inconsistancy in the averages depending on when the 
counter was started and what time of the year it is now. This inconsistancy does not exist 
if you specify a year as there is no averaging but only a count of the number of hits in
each month.</P>

<P>&nbsp;</P>

<H3><A NAME="Stats_WeeklyHitsOverAYear"></A>Weekly Hits Over A Year</H3>

<P>The Weekly Hits Over A Year statistic allows you to see each week with the number of 
hits received each week over a year.  With this counter you can have an average number of 
hits for each week (averaged by the number of years) or you can select the year and get an
absolute number of hits for each week. Any weeks with no hits will simply not show up.</P>
<P><B>Note:</B> This statistic uses every piece of data in the database, and divides by the
total number of 365 day years (rounding up) the counter has been running not by the number 
of times each week has occured. This allows for an inconsistancy in the averages depending 
on when the counter was started and what time of the year it is now. This inconsistancy does 
not exist if you specify a year as there is no averaging but only a count of the number of 
hits in each week.</P>

<P>&nbsp;</P>

<H3><A NAME="Stats_DailyHitsOverAMonth"></A>Daily Hits Over A Month</H3>

<P>The Daily Hits Over A Month Statistic allows you to see 31 days over a month with the
hits each day received. With this counter you can have an average number of hits for each
day (averaged by the number of months and years) or you can select a specific year and month. 
You can also select either just the month or just the year.  If you don't specify either the 
year or the month, averaging will take place for each of the 31 days.  Any days with no hits 
will simply not show up.</P>
<P><B>Note:</B> This statistic uses every piece of data in the database, and divides by the
total number of months (rounding up) the counter has been running not by the number 
of times each day has occured. This allows for an inconsistancy in the averages depending 
on when the counter was started and what time of the year it is now. This inconsistancy does 
not exist if you specify both a year and month as there is no averaging but only a count of 
the number of hits in each day.  This inconsistancy also does not exist if you perform the 
statistic while selecting either a fully completed month (specifying month) or a fully 
completed year (specifying year).</P>

<P>&nbsp;</P>

<H3><A NAME="Stats_DailyHitsOverAWeek"></A>Daily Hits Over A Week</H3>

<P>The Daily Hits Over A Week Statistic allows you to see 7 days over a week with the hits
each day received. With this counter you can have an average number of hits for each
day (averaged by the number of weeks and years) or you can select a specific year and week. 
You can also select either just the week or just the year.  If you don't specify either the 
year or the week, averaging will take place for each of the 7 days.  Any days with no hits 
will simply not show up.</P>
<P><B>Note:</B> This statistic uses every piece of data in the database, and divides by the
total number of weeks (rounding up) the counter has been running not by the number 
of times each day has occured. This allows for an inconsistancy in the averages depending 
on when the counter was started and what time of the year it is now. This inconsistancy does 
not exist if you specify both a year and month as there is no averaging but only a count of 
the number of hits in each day.  This inconsistancy also does not exist if you perform the 
statistic while selecting either a fully completed week (specifying week) or a fully 
completed year (specifying year).</P>

<P>&nbsp;</P>

<H3><A NAME="Stats_CounterTable"></A>Statistical Daily Counter Table</H3>

<P>The Statistical Daily Counter Table allows you to see exactly how many hits each day received
for an entire year.  There is no averaging or other calculations performed other then simply adding
up the number of hits for each day.  Because of the possible length of this table, you are required
to specify a year (latest year with hits is the default).  This table allows you to perform your own
calculations and statistics if you wish to.</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P><HR ALIGN=LEFT></P>

<H2><A NAME="EditIgnoreIP"></A>Add/Edit Ignoring IPs</H2>

<P>This page is used to add IPs or ranges of IPs to be ignored.
You can ignore your own IP so that your own hits don't count or
for whatever reason. This page is where you add the IPs to ignore.
To ignore an IP, enter an IP address (not a DNS or Hostname) or
IP range to be ignored. If you need to, you can enter an asterisk
(*) as a wildcard if you want to specify a range. See the examples
below</P>

<UL>
  <LI>192.168.22.174 - Ignore a single IP
  <LI>192.168.45.* - Ignore the range 192.168.45.0 thru 192.168.45.255
  <LI>192.168.* - Ignore the range 192.168.0.0 thru 192.168.255.255
</UL>

<P>To add an address to the list, simply enter the IP address
in the &quot;Add a New IP Address to Ignore&quot; section of the
page under the heading &quot;IP Address&quot;. You can then enter
a description of what the IPs are or something that is useful
to you, this text has no meaning to Advanced CowCounter. Finally,
click the Create button. To delete an entry, simply click the
Delete button beside the entry you wish to delete.</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P><HR ALIGN=LEFT></P>

<H2><A NAME="EditConfig"></A>Edit Configuration</H2>

<P>This page is used to change anything that you originally entered
during the initial setup process, be carefull with this as Advanced
CowCounter may not like not finding the MySQL Database. The table
prefix is used as a beginning to the name of each table that is
created, changing this field will NOT change the names of the
tables themselves. If you change this field, then the table names
must be changed manually. The table prefix is generally used if
you have multiple programs using a single database. The Bot Cracker
Confuser checkbox is used to enable/disable the jumbled text (CAPTCHA) 
on the login page that makes it extremely hard for bots to do dictionary
attacks on the login page to try to break in as they can't interpret
the image displayed.  The Bot Confuser feature is not yet implemented.</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</P>

<P>&nbsp;</FORM>


  <?

  include("footer.php");


};
?>