MVW Counter
===========

By Gary Kertopermono

=======
Content

1. How to use
2. HTML-only
3. Making your own event
4. Disclaimer

=============
1. How to use
=============

I'd prefer you use the countdown.func.php. You can just include this file in your PHP
file. The following commands can be used:

===

$variable = countdown_text($seconds,$minutes,$hours,$days,$months,$years)

Usage:

Every variable speaks for themselves. The time you set here is the time when the event
starts, so when the counter stops.

Returns:

An integer.

Function:

It returns the total of seconds that are between now and the given time.

===

$variable = countdown_text_file([$file])

Usage:

$file = Optional. The event file you want to load. Default "".

Returns:

An integer.

Function:

It returns the total of seconds that are between now and the given time. If the file
does not exists, or the file hasn't been specified, it returns 0.

===

$result = countdown_getdays($seconds)
$result = countdown_gethours($seconds)
$result = countdown_getminutes($seconds)

Usage:

$seconds = The seconds to convert the given type of timespan from.

Returns:

An integer.

Function:

This is to break the seconds up in the days, hours and minutes, by combining the four
elements.

===

$result = countdown_flash($file,$width,$height,[$isevent=false,$event=""])

Usage:

$file = The SWF file to load.

$width = The width of the file.

$height = The height of the file.

$isevent = Optional. Checks if there is an event to load. Default false.

$event = Optional. The event to load. Default "".

Returns:

A string.

Function:

This automatically makes the HTML for your Flash counter.

============
2. HTML-only
============

If you don't have PHP you can always use HTML. However, only the Flash-countdown works.

Add this to your file:

 codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0"
 WIDTH="200" HEIGHT="100" id="countdown" ALIGN="">
 <PARAM NAME=movie VALUE="countdown.swf"> <PARAM NAME=quality VALUE=high> <PARAM NAME=bgcolor VALUE=#FFFFFF> <EMBED src="countdown.swf" quality=high bgcolor=#FFFFFF  WIDTH="200" HEIGHT="100" NAME="countdown" ALIGN=""
 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/go/getflashplayer"></EMBED>
</OBJECT>

Replace countdown.swf for your own Flash-file, or add ?event=<your event> to load the
certain event. Replace <your event> with the event name.

========================
3. Making your own event
========================

I'm sure you want to make your own event. That's why this section. It's actually
self-explaining. Here is how a file should look like:

&event_name=<name of event>
&tosec=<seconds>
&tomin=<minutes>
&tohour=<hours>
&today=<days>
&tomonth=<months>
&toyear=<years>
&vloaded=1

The &vloaded=1 may not be changed or else the Flash countdown does not work well. Also,
the &-character must be in front of every value. An example would be:

&event_name=Advent Children DVD release (US)
&tosec=0
&tomin=0
&tohour=0
&today=13
&tomonth=9
&toyear=2005
&vloaded=1

The file has to be plain text. You can save in any extension, but beware that the event
name also has the extension. For example, if your file is called event.txt, the even
name is also event.txt.

=============
4. Disclaimer
=============

When distributing the files, this file must be included. You may not sell this counter
or use it for commercial reasons. The files are served free and remain free. If you
ever sell this file, I will cut your nuts off and hang them on a stick.

Thank you.

Gary Kertopermono.