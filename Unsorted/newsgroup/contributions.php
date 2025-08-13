TO BE ADDED

# i18n bug solution 

I found that there is a serious bug in headerDecode
function in lib/standard.lib.php

The origanal code look like this:

function headerDecode($value) {
if (eregi('=\?.*\?Q\?.*\?=',$value)) {

$result=eregi_replace('(.*)=\?.*\?Q\?(.*)\?=(.*)','\1\2\3',$value);
if ($value != $result) $result=headerDecode($result);
$result=str_replace("_","
",quoted_printable_decode($result));
return($result);
}
if (eregi('=\?.*\?B\?.*\?=',$value)) {

$result=eregi_replace('(.*)=\?.*\?B\?(.*)\?=(.*)','\1\2\3',$value);
if ($value != $result) $result=headerDecode($result);
$result=str_replace("_"," ",base64_decode($result));
return($result);
}

return($value);
}

However, if $value is a combination of ASCII text and
base64/quoted printable text, the returned value will
go wrong.

For example, if the passed argument $value is a string
"Re: =?Big5?B?pKSw6qRI?=" (which offen occur when
replying messages by Netscape6/Mozilla) , clearly, it's
base64 text, but what will be passed to base64_decode
function is "Re: pKSw6qRI" instead of "pKSw6qRI", the
result will be totally wrong (sometimes output
unreadable characters and sometimes output an empty
string!)

This problem is very annoying for foreign language
users. Especially for CJKV users. Since they can't even
read some replies due to the empty subjects.

My solution is:

if (eregi('=\?.*\?B\?.*\?=',$value)) {


$header=eregi_replace('(.*)=\?.*\?B\?(.*)\?=(.*)','\1',$value);

$base64=eregi_replace('(.*)=\?.*\?B\?(.*)\?=(.*)','\2',$value);

$footer=eregi_replace('(.*)=\?.*\?B\?(.*)\?=(.*)','\3',$value);
// Make sure if there is no more base64/quoted
printable text
$header=headerDecode($header);
$footer=headerDecode($footer);

$result=$header.str_replace("_","
",base64_decode($base64)).$footer;
return($result);
}

Just for your reference :-)

Regards,
Kam Tik

------------------------------------------

In post.php, the code:

$t->set_var("subject",htmlentities(stripslashes($subject)));

will make some double byte characters unreadable (at
least it's true for Big5 characters)

After I changed it to:

$t->set_var("subject",$subject);

Everything works fine. 

-----------------------------------------------------
# Great MyNewsGroups :) description...!!

Hi Cliff,

MyNewsGroups purpose is actually to mirror public 
newsgroups so that you don't need a newsreader - 
just your internet browser.
MyNewsGroups copies all messages from subcribed 
public newsgroups into a database and adds extra 
features such as: saving articler per user, statistics 
on users, search functions and so on.

What you need is a dedicated newsserver like for 
example "innd" with that one you can greate your 
own newsgroups and only allow your friends to 
read and post articles.

MyNewsGroups is aiming to people (so far I have 
understood) that have a website from where they 
want people to have access to newsgroups of 
same subject. 
-----------------------------------------------

