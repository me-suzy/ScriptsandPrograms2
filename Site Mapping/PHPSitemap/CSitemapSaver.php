<?php

class ISitemapSaver
{
	function CSitemapSaver(){}
	function open(){}
	function close(){}
	function dirHead(){}
	function dirFoot(){}
	function fileLine(){}
}

class CSitemapEchoSaver extends ISitemapSaver
{
	function open(){echo '<ul>';}
	function dirHead(&$file) {echo '<li><a href="'.$file->getURL().'">'.$file->getTitle().'</a></li><ul>';}
	function fileLine(&$file) {echo '<li><a href="'.$file->getURL().'">'.$file->getTitle().'</a></li>';}
	function dirFoot() {echo '</ul>';}
	function close() {echo '</ul>';}
}

class CSitemapFileSaver extends ISitemapSaver
{
	function CSitemapFileSaver($path)
	{
		$this->path = $path;
	}
	function open()
	{
	   	if (!$this->fp = fopen($this->path, 'w'))
		{
    	   	echo "Cannot open file ($this->path)";
       		exit;
	   	}
	}
	function close()
	{
		fclose($this->fp);
	}
	function write($line)
	{
		if (($this->bytesWritten += fwrite($this->fp, $line)) === false)
		{
			echo "Cannot write to file ($this->path)";
			exit;
		}
	}
}

class CSitemapFileSaverPHPArray extends CSitemapFileSaver
{
	function open()
	{
		parent::open();
		$this->write("<?php\n");
	}
	function fileLine(&$file)
	{
		$url = $file->getURL();
		$this->write("\$sitemap['$url'] = \"".$file->getTitle()."\";\n");
		echo "Writing \$sitemap['$url'] = \"".$file->getTitle().'";...<br>';
	}
	function close()
	{
		$this->write("\n?>");
		parent::close();
		echo "Total bytes written: $this->bytesWritten";
	}
}

?>