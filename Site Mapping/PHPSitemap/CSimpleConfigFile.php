<?php

class CSimpleConfigFile
{
	function onCreate(){} // override in subclasses
	function CSimpleConfigFile($path, &$parentConfigFile)
	{
		if (!is_null($parentConfigFile)) $this->parentConfigFile =& $parentConfigFile;
		$this->readConfigFile($path);
		$this->path = dirname($path);
		$this->onCreate();
	}
	function readConfigFile($path)
	{
		if (file_exists($path))
		{
			$fd = fopen ($path, 'r');
			while (!feof ($fd)) 
			{
				$buffer = fgets($fd, 4096);
				$buffer = preg_replace('/\s+/', ' ', $buffer); 
				$buffer = str_replace(' ', '', $buffer);
				$this->fileLines[] = $buffer;
			}
			fclose ($fd);
		}
		if (is_array($this->fileLines))
		{
			$this->readVariables();
			$this->readArrays();
		}
	}
	function readVariables()
	{
		foreach ($this->fileLines as $line)
		{
			if (strstr($line, '$'))
			{
				$line = str_replace('$', '', $line);
				list($k, $v) = split('=', $line);
				switch ($v)
				{
					case 'true': $v = true; break;
					case 'false': $v = false;
				}
				$this->variables[$k] = $v;
			}
		}
	}
	function readArrays()
	{
		foreach ($this->fileLines as $line)
		{
			if (empty($line) || strpos($line, '$') === true) $k = '';
			if (!empty($k) && strpos($line, '##') === false) $this->arrays[$k][] = $line;
			if (strstr($line, '[')) $k = str_replace('[', '', str_replace(']', '', $line));
		}
	}
	function getVariable($name)
	{
		if ($this->variables[$name])
		{
			return $this->variables[$name];
		}
		else
		{
			if (!is_null($this->parentConfigFile))
			{
				return $this->parentConfigFile->getVariable($name);
			}
		}
	}
	function getArray($name)
	{
		if ($this->arrays[$name])
		{
			return $this->arrays[$name];
		}
		else
		{
			if (!is_null($this->parentConfigFile))
			{
				return $this->parentConfigFile->getArray($name);
			}
		}
	}
}

?>