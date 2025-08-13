<?php

//-------------------------------------
//	Stylesheet template
//-------------------------------------

function deft_stylesheet()
{
ob_start();
?>


<?php
$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
//	Weblog template
//-------------------------------------

function deft_weblog()
{
ob_start();
?>


<?php
$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
//	Archives template
//-------------------------------------

function deft_archives()
{
ob_start();
?>


<?php
$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
//	Category archives template
//-------------------------------------

function deft_cetegory_archives()
{
ob_start();
?>


<?php
$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END



//-------------------------------------
//	Comments
//-------------------------------------

function deft_comments()
{
ob_start();
?>


<?php
$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
//	Comment preview
//-------------------------------------

function deft_comment_preview()
{
ob_start();
?>


<?php
$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
// Trackbacks
//-------------------------------------

function deft_trackbacks()
{
ob_start();
?>


<?php
$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END



//-------------------------------------
//	Default referrers
//-------------------------------------

function deft_referrers()
{
ob_start();
?>


<?php
$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END


//-------------------------------------
//	Smileys
//-------------------------------------

function deft_smileys()
{
ob_start();
?>


<?php
$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;
}
// END



//-------------------------------------
//	RSS 1.0
//-------------------------------------

function deft_rss_1()
{

ob_start();

echo "{exp:rss:feed weblog=\"weblog1\"}\n\n";

echo '<?xml version="1.0" encoding="{encoding}"?>'."\n";

?>
<rdf:RDF
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:cc="http://web.resource.org/cc/"
    xmlns="http://purl.org/rss/1.0/">

<channel rdf:about="{weblog_url}">

    <title>{weblog_name}</title>
    <link>{weblog_url}</link>
    <description>{weblog_description}</description>
    <dc:language>{weblog_language}</dc:language>
    <dc:creator>{email}</dc:creator>
    <dc:date>{date format="%Y-%m-%dT%H:%i:%s%Q"}</dc:date>
    <admin:generatorAgent rdf:resource="" />
    
    <items>
      <rdf:Seq>{exp:weblog:entries weblog="weblog1" limit="10"}
      <rdf:li rdf:resource="{title_permalink=weblog/index}" />
      {/exp:weblog:entries}</rdf:Seq>
    </items>

</channel>

{exp:weblog:entries weblog="weblog1" limit="10" rdf="off"}
    <item rdf:about="{title_permalink=weblog/index}">
      <title>{exp:xml_encode}{title}{/exp:xml_encode}</title>
      <link>{title_permalink=weblog/index}</link>
      <description>{exp:xml_encode}{summary}{/exp:xml_encode}</description>
      <dc:subject>{categories backspace="1"}{category_name}, {/categories}</dc:subject>
      <dc:creator>{exp:xml_encode}{author}{/exp:xml_encode}</dc:creator>
      <dc:date>{entry_date format="%Y-%m-%dT%H:%i:%s%Q"}</dc:date>
    </item>
{/exp:weblog:entries}

</rdf:RDF>

{/exp:rss:feed}
<?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;

}
// END



//-------------------------------------
//	RSS 2.0
//-------------------------------------

function deft_rss_2()
{

ob_start();

echo "{exp:rss:feed weblog=\"weblog1\"}\n\n";

echo '<?xml version="1.0" encoding="{encoding}"?>'."\n";

?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/">

    <channel>
    
    <title>{weblog_name}</title>
    <link>{weblog_url}</link>
    <description>{weblog_description}</description>
    <dc:language>{weblog_language}</dc:language>
    <dc:creator>{email}</dc:creator>
    <dc:rights>Copyright {date format="%Y"}</dc:rights>
    <dc:date>{date format="%Y-%m-%dT%H:%i:%s%Q"}</dc:date>
    <admin:generatorAgent rdf:resource="" />
    
{exp:weblog:entries weblog="weblog1" limit="10" rdf="off"}
    <item>
      <title>{exp:xml_encode}{title}{/exp:xml_encode}</title>
      <link>{exp:xml_encode}{title_permalink=weblog/index}{/exp:xml_encode}</link>
      <description>{exp:xml_encode}{summary}{/exp:xml_encode}</description>
      <dc:subject>{categories backspace="1"}{category_name}, {/categories}</dc:subject>
      <content:encoded><![CDATA[{body}]]></content:encoded>
      <dc:date>{entry_date format="%Y-%m-%dT%H:%i:%s%Q"}</dc:date>
    </item>
{/exp:weblog:entries}
    
    </channel>
</rss>

{/exp:rss:feed}
<?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;

}
// END



//-------------------------------------
//	Atom
//-------------------------------------

function deft_rss_atom()
{

ob_start();

echo "{exp:rss:feed weblog=\"weblog1\"}\n\n";

echo '<?xml version="1.0" encoding="{encoding}"?>'."\n";

?>
<feed version="0.2"
    xmlns="http://purl.org/atom/ns#"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xml:lang="{weblog_language}">

    <title>{weblog_name}</title>
    <link>{weblog_url}</link>
    <modified>{edit_date format='%Y%m%d%H%i%s'}</modified>
    
    <author>
      <name>{author}</name>
      <homepage>{url}</homepage>
      <email>{email}</email>
    </author>
    
    <tagline>{weblog_description}</tagline>
    <id>tag:{weblog_url}, {date format="%Y"}:{weblog_id}</id>
    <generator name="ExpressionEngine">http://www.pmachine.nul/</generator>
    <copyright>Copyright (c) {date format="%Y"}, {author}</copyright>

{exp:weblog:entries weblog="weblog1" limit="10" rdf="off"}
    <entry>
      <title>{exp:xml_encode}{title}{/exp:xml_encode}</title>
      <link>{title_permalink=weblog/index}</link> <id>tag:{url}, {date format="%Y"}:{weblog_id}.{entry_id}</id>
      <issued>{entry_date format="%Y-%m-%dT%H:%i:%s%Q"}</issued>
      <modified>{edit_date format='%Y%m%d%H%i%s'}</modified>
      <summary>{exp:xml_encode}{summary}{/exp:xml_encode}</summary>
      <created>{entry_date format='%Y%m%d%H%m%s'}</created>
      <dc:subject>{categories backspace="1"}{category_name}, {/categories}</dc:subject>
      <content type="text/html" mode="escaped" xml:lang="en-US"><![CDATA[{body}]]></content>
    </entry>
{/exp:weblog:entries}

</feed>

{/exp:rss:feed}
<?php

$buffer = ob_get_contents();
ob_end_clean(); 
return $buffer;

}
// END






//-------------------------------------
//	Member index page
//-------------------------------------

function member_index()
{
return <<<EOF
{exp:member:manager}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{lang}">
<head>
<title>{page_title}</title>

<meta http-equiv='content-type' content='text/html; charset={charset}' />

{stylesheet}

</head>
<body>

<div id="content">
<div class='header'><h1>{heading}</h1></div>

{breadcrumb}
{content}
{copyright}

</div>

</body>
</html>
{/exp:member:manager}
EOF;
}
// END



?>