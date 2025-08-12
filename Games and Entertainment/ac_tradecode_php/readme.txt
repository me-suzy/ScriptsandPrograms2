==========================================
=      Animal Crossing Code Project      =
==========================================
= Original by MooglyGuy / UltraMoogleMan =
=   Ported to PHP by Gary Kertopermono   =
==========================================
The tags and this readme may not be removed
when redistributing. It is not allowed to
sell this script in any way.

CONTENTS

1. Introduction
2. Usage
3. Special notes
4. Installing on your website

===================
= 1. Introduction =
===================

Animal Crossing has a cool feature: the possibility
to trade items using codes. However, Nintendo also
put a special tag in for Universal Codes. A long time
it was impossible to create them, and many people
searched for the answer.

And then came MooglyGuy.

He created the original generators, and as a tribute
I created this PHP version, with some improvements.

Now you can decode codes to see what kind of item
your friend or "friend" gave to you, and you can
create codes with it.

============
= 2. Usage =
============

How do I use it? Simple. For the Decoder, simply copy
and paste the code. You then see some information even
I mostly don't get.

The Generator is also self-explaining. Search the item
number, you can use the item list for that, and fill in
every field. With the Player-To-Player codes you can send
an item to yourself. Just fill in your townname and
playername. The same goes for the NES Contest codes. The
Universal codes are different. The townname is always the
first part of the name from which you'll recieve your present,
the playername the second part. The "Add leading space" option
adds a 0x00 byte after each name, so that it adds a space
between them.

Examples:

Townname: Nintendo
Playername:
Checkbox ticked: No
Result: Nintendo

Townname: Multiver
Playername: seWorks
Checkbox ticked: No
Result: MultiverseWorks

Townname: Narshe
Playername: Kutan
Checkbox ticked: Yes
Result: Narshe Kutan

====================
= 3. Special Notes =
====================

These items cannot be aquired with any of the code types:

Insects
Fish
Legend of Zelda
Super Mario Brothers
Ice Climbers
Mario Brothers
"Glitch" items in the GSCentral code list

These items can only be aquired using the NES Contest code:

Baseball
Clu Clu Land D
Donkey Kong 3
Donkey Kong Jr.
Punchout
Soccer

=================================
= 4. Installing on your website =
=================================

You can just extract every file and upload them all on your
website, or you can just upload some. If you want to intergrate
it in your webdesign, you only need additional.php and tools_include.php
as the base, and the item lists for if you want to identify the
items.

The commands you can use are these:

create_password( $playername, $townname, $itemnum, $codetype, $leadspace, $custom )

$playername = The playername
$townname   = The townname
$itemnum    = The item number, found in the item list
$codetype   = The type of code ("P" for Player-to-Player, "N" for NES Contest,
                                "U" for Universal, "C" for Custom)
$leadspace  = If you want to add a 0x00 at the end of each name
$custom     = (Optional) The custom algorythm if you choose codetype C.

Returns: String, the passcode.

create_password_from_byte( $playername, $townname, $itemnum, $leadspace, $byte1, $byte2 )

$playername = The playername
$townname   = The townname
$itemnum    = The item number, found in the item list
$leadspace  = If you want to add a 0x00 at the end of each name
$byte1      = Byte 1 modifier
$byte2      = Byte 2 modifier

Returns: String, the passcode.

decode_password( $passcode )

$passcode   = The passcode you want to decode

Returns: Array.

"itemnum"    => $itemnum    = (Integer) The item number
"codebyte0"  => $codebyte0  = (Integer) Codebyte 0
"codebyte1"  => $codebyte1  = (Integer) Codebyte 1
"modbyte0"   => $modbyte0   = (Integer) Modbyte 0
"modbyte1"   => $modbyte1   = (Integer) Modbyte 1
"modbyte2"   => $modbyte2   = (Integer) Modbyte 2
"modbyte3"   => $modbyte3   = (Integer) Modbyte 3
"modbyte4"   => $modbyte4   = (Integer) Modbyte 4
"townname"   => $townname   = (String) The townname
"playername" => $playername = (String) The playername
"data"       => $data       = (Array) The hexadecimal data of $outputcode
"outputcode" => $outputcode = (String) The output from which the data was extracted

!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
! NOTE THAT YOU CAN NOT CLAIM THE CODE AS YOURS! !
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

Actually, you can, but that wouldn't be pollite, and sooner or later
people will find out.

If you ever want to put it on your site, credit MooglyGuy first,
then Gary Kertopermono. You will need to give credit to both of us,
however, you are free to choose if you want to put the URL of the
websites in it.

MooglyGuy: http://www.zophar.net/personal/mooglyguy/codegen.html

Gary Kertopermono: http://www.multiverseworks.com

What is required to be put on however is a link to the GPL file,
either the one included in the package or the one on the official
site:

http://www.gnu.org/licenses/gpl.txt