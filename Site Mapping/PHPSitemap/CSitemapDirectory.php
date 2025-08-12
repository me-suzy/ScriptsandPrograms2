<?php

define('SM_CONFIG_FILENAME', 'sm.conf');

$smIndexFilePriority[] = 'index.html';
$smIndexFilePriority[] = 'index.htm';
$smIndexFilePriority[] = 'index.php';

$smFileReaders['html'] = 'SitemapHTMLReader';
$smFileReaders['htm'] = 'SitemapHTMLReader';
$smFileReaders['php'] = 'SitemapHTMLReader';
$smFileReaders['pdf'] = 'SitemapPDFReader';

include 'common.php';
include 'CSimpleConfigFile.php';
include 'CSitemapSaver.php';
include 'SitemapReaders.php';

class CSitemapConfigFile extends CSimpleConfigFile
{
	function onCreate()
	{
		// if a config file is found, adjust any specified file paths relative to its directory
		if ($this->arrays['SM_EXCLUDE_ENTRIES'])
		{
			foreach ($this->arrays['SM_EXCLUDE_ENTRIES'] as $i => $entry)
			{
				$this->arrays['SM_EXCLUDE_ENTRIES'][$i] = $this->path.'/'.$entry;
			}
		}
	}
}

class CSitemapDirectory
{
	function CSitemapDirectory()
	{
		$args = func_get_args();
		if (func_num_args() == 1) $this->CSitemapDirectory1($args[0]);
		else  $this->CSitemapDirectory2($args[0], $args[1]);
	}
	function CSitemapDirectory1($dir)
	{
		$this->dir = $this->filename = $dir;
		$this->setConfigFile();
		$this->findEntries();
		$this->markIndexFile();
		$this->title = $this->files[$this->indexFileIndex]->title;
	}
	function CSitemapDirectory2($dir, &$parentConfigFile)
	{
		$this->parentConfigFile =& $parentConfigFile;
		$this->CSitemapDirectory1($dir);
	}
	function setConfigFile()
	{
		$this->configFile = new CSitemapConfigFile($this->dir.'/'.SM_CONFIG_FILENAME, &$this->parentConfigFile);
	}
	function markIndexFile()
	{
		global $smIndexFilePriority;
		if (!is_array($this->files)) return;
		foreach ($this->files as $i => $file)
		{
			if (in_array($file->filename, $smIndexFilePriority))
			{
				$this->indexFileIndex = $i;
			}
		}
	}
	function isIndexFile($fileIndex)
	{
		return $this->indexFileIndex == $fileIndex;
	}
	function isExcluded($path)
	{
		$excludeEntries = $this->configFile->getArray('SM_EXCLUDE_ENTRIES');
		return is_array($excludeEntries) && in_array($path, $excludeEntries);
	}
	function processFileType($ext)
	{
		$fileTypes = $this->configFile->getArray('SM_PROCESS_FILE_TYPES');
		return is_array($fileTypes) && in_array($ext, $fileTypes);
	}
	function findEntries()
	{
		global $smFileReaders;
		$dirid = opendir($this->dir); 
		while ($file = readdir($dirid))
		{
			$path = "$this->dir/$file";
			if ($file != '.' && $file != '..' && !$this->isExcluded($path))
			{
				if ($this->configFile->getVariable('SM_INDEX_SUBDIRS') && is_dir($path))
				{
					$this->dirs[] = new CSitemapDirectory($path, &$this->configFile);
				}
				else
				{
					$ext = substr($file, (1 + strpos($file, '.')));
					if ($this->processFileType($ext))
					{
						$this->files[] = new CSitemapFile($path, $smFileReaders[$ext]);
					}
				}
			}
		}
	}
	function save(&$saver)
	{
		$saver->open();
		$this->saveEntries($saver);
		$saver->close();
	}
	function saveEntries(&$saver)
	{
		if ((is_array($this->files) || is_array($this->dirs)) && isset($this->indexFileIndex))
		{
			$saver->dirHead($this->files[$this->indexFileIndex]);
			if ($this->configFile->getVariable('SM_DIR_PLACEMENT') == 'top')
			{
				$this->saveDirs($saver);
				$this->saveFiles($saver);
			}
			else
			{
				$this->saveFiles($saver);
				$this->saveDirs($saver);
			}
			$saver->dirFoot();
		}
	}
	function saveFiles(&$saver)
	{
		$cmp = 'cmp_'.$this->configFile->getVariable('SM_SORT_METHOD').'_'.$this->configFile->getVariable('SM_SORT_ORDER');
		if (is_array($this->files))
		{
			uasort($this->files, $cmp);
			foreach ($this->files as $i => $file)
			{
				if ($file->title && !$this->isIndexFile($i)) $saver->fileLine($file);
			}
		}
	}
	function saveDirs(&$saver)
	{
		$cmp = 'cmp_'.$this->configFile->getVariable('SM_SORT_METHOD').'_'.$this->configFile->getVariable('SM_SORT_ORDER');
		if (is_array($this->dirs))
		{
			uasort($this->dirs, $cmp);
			foreach ($this->dirs as $dir)
			{
				$dir->saveEntries($saver);
			}
		}
	}
}

function cmp_title_asc($a, $b) {return strcmp($a->title, $b->title);}
function cmp_title_desc($a, $b) {return -strcmp($a->title, $b->title);}
function cmp_filename_asc($a, $b) {return strnatcmp($a->filename, $b->filename);}
function cmp_filename_desc($a, $b) {return -strnatcmp($a->filename, $b->filename);}
	
class CSitemapFile
{
	function CSitemapFile($path, $reader)
	{
		$this->path = $path;
		$this->filename = basename($path);
		$this->title = $reader($path);
	}
	function getTitle()
	{
		return $this->title;
	}
	function getURL()
	{
		return str_replace($GLOBALS['DOCUMENT_ROOT'], '', str_replace('\\', '/', $this->path));
	}
}

?>