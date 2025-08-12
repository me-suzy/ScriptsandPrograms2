<?php
/**
 *	(c)2005 http://Lauri.Kasvandik.com
 */

class tpl {

	function parse($filename, $path="templates/")
	{
		global $tpl;
		if(is_file($path . $filename))
		{
			ob_start();
			require $path . $filename;
			$c = ob_get_contents();
			ob_end_clean();
			return $c;
		}
		else
		{
			return 'Error: cant find ' . $path . $filename ;
		}
	}

	function out($filename, $path="templates/")
	{
		echo tpl::parse($filename, $path);
	}

	function bbcode($str)
	{
		$s = array("]\n", '[code]', '[/code]');
		$r = array("]", '<code>',	"</code>\n");
		$str = str_replace($s, $r, $str);

		$patterns = array(
			'`\[b\](.+?)\[/b\]`is',
			'`\[i\](.+?)\[/i\]`is',
			'`\[u\](.+?)\[/u\]`is',
			'`\[strike\](.+?)\[/strike\]`is',
			'`\[color=#([0-9]{6})\](.+?)\[/color\]`is',
			'`\[email\](.+?)\[/email\]`is',
			'`\[img\](.+?)\[/img\]`is',
			'`\[url=([a-z0-9]+://)([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\](.*?)\[/url\]`si',
			'`\[url\]([a-z0-9]+?://){1}([\w\-]+\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*)?)\[/url\]`si',
			'`\[url\]((www|ftp)\.([\w\-]+\.)*[\w]+(:[0-9]+)?(/[^ \"\n\r\t<]*?)?)\[/url\]`si',
			'`\[flash=([0-9]+),([0-9]+)\](.+?)\[/flash\]`is',
			'`\[quote\](.+?)\[/quote\]`is',
			'`\[indent](.+?)\[/indent\]`is',
			'`\[size=([1-6]+)\](.+?)\[/size\]`is'
		);

		$replaces =  array(
			'<strong>\\1</strong>',
			'<em>\\1</em>',
			'<span style="border-bottom: 1px dotted">\\1</span>',
			'<strike>\\1</strike>',
			'<span style="color:#\1;">\2</span>',
			'<a href="mailto:\1">\1</a>',
			'<img src="\1" alt="" style="border:0px;" />',
			'<a href="\1\2">\6</a>',
			'<a href="\1\2">\1\2</a>',
			'<a href="http://\1">\1</a>',
			'<object width="\1" height="\2"><param name="movie" value="\3" /><embed src="\3" width="\1" height="\2"></embed></object>',
			'<strong>Quote:</strong><div style="margin:0px 10px;padding:5px;background-color:#F7F7F7;border:1px dotted #CCCCCC;width:80%;"><em>\1</em></div>',
			'<pre>\\1</pre>',
			'<h\1>\2</h\1>'
		);

		$str = preg_replace($patterns, $replaces , $str);

		$match = array('#\[php\](.*?)\[\/php\]#se');
		// '<div class="code">'..'</div>'
		$replace = <<<html
			highlight_string(html_entity_decode('$1'), true)
html;
	   $replace = array($replace);
		$str = preg_replace($match, $replace, $str);
		$str = str_replace(array('<font ', '</font>'), array('<span ', '</span>'), $str);
		$str = preg_replace('#color="(.*?)"#', 'style="color: \\1"', $str);
		$str = str_replace("\n", '<br/>', $str);
	   return $str;
	}
}

?>