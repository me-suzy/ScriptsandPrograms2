<?php
/*  
 * FileManager.php	
 * Copyright (C) 2003-2004, Alejandro Espinoza Esparza.
 *
 * Description :
 *   This class manages all the I/O services.The reason this file is named FileManager is becuase it is intended to manage all
 * methods regarding IO, even for directories.
 *
 * Author(s):
 *   Alejandro Espinoza <aespinoza@structum.com.mx>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as
 * published by the Free Software Foundation; either version 2.1 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307, USA.
 *
 */

import("moebius2.base.Object");

/* --- Constants --- */
// I/O Errors
define("IOERR_NO_ERR", 0);
define("IOERR_FILE_DOESNT_EXIST", 1);
define("IOERR_FILE_COULDNT_BE_OPENED", 2);

/**
  * This class manages all the I/O services. The reason this file is named FileManager is becuase it is intended to manage all
  * methods regarding IO, even for directories.
  * @class		FileManager
  * @package	moebius2.base
  * @author		Alejandro Espinoza &lt; <a href="mailto:aespinoza@structum.com.mx">aespinoza@structum.com.mx</a> &gt;
  * @version	1.2
  * @extends	Object
  * @requires	Object
  */
class FileManager extends Object
{
	/* --- Attributes --- */
	var $filePointer;
	var $fileName;
	var $fileContentArray;
	var $fileIsOpen;
	var $fileOpenType;

	var $lineCount;
	var $updateContent;	

	var $errNum;
			
	/* --- Methods --- */
	/**
	  * Constructor, initializes the default options and opens the file.
	  * @method		File
	  * @param		optional string fileName
	  * @returns	none
	  */	
	function FileManager($fileName="", $openType="a+")
	{
		Object::Object("moebius2.base","FileManager");
		
		// Init Variables
		$this->fileIsOpen = false;
		$this->fileContentArray = array();
		$this->fileOpenType = $openType;
		
		$this->lineCount = 0;
		$this->updateContent = true;		
		$this->errNum = IOERR_NO_ERR;			   

		if(!empty($fileName)) {
			   $this->Open($fileName);
		   }
	}
	
	/**
	  * Opens a file and updates the content array if set. This method can be debugged.
	  * @method		Open
	  * @param		string fileName	  
	  * @returns	true if the file is open, or false otherwise.
	  */	
	function Open($fileName)
	{
		$this->DebugWriteLine("***** BEGIN DEBUG: Open ***** ");

		// Check for File Existance
 		if(!file_exists($fileName)) {
			$this->errNum = IOERR_FILE_DOESNT_EXIST;
			$this->DebugWriteLine("* File doesn't exist");			
		}
		
		// Close a file if opened and clean all buffers.
		if($this->IsOpen()) {
			$this->Destroy();
			$this->DebugWriteLine("* Destroying old file's contents from object.");
		}
		
		$this->fileName = $fileName;
		$this->filePointer = fopen($fileName, $this->fileOpenType);

		// Test file pointer		
		if(!$this->filePointer) {			
			$this->fileIsOpen = false;
			$this->Destroy();
			$this->errNum = IOERR_FILE_COULDNT_BE_OPENED;			
			$this->DebugWriteLine("* File pointer test failed. File is not opened. Filename : '".$this->fileName."'");
		} else {
			$this->lineCount = count($this->fileContentArray);
			$this->UpdateContent();
			$this->fileIsOpen = true;
			$this->DebugWriteLine("* File pointer test passed. File is opened. Filename : '".$this->fileName."'");
		}

		$this->DebugWriteLine("***** END DEBUG: Open  *****");			
		
		return $this->fileIsOpen;
	}

	/**
	  * Closes the file in use.
	  * @method		Close
	  * @returns	true if the file is closed, or false otherwise.
	  */	
	function Close()
	{
		$success = false;

		// Check if File is in use.
		if($this->IsOpen()) {
			// Close the file and hope success.
			if(fclose($this->filePointer)) {
				$this->fileIsOpen = false;
				$success = true;
			}
		}
		
		return $success;
	}
	
	/**
	  * Inserts a line into the array content; if the line number that is to be inserted exists
	  * the default action is not to replace it, but to move it to the next line. That can be
	  * changed by setting the replaceLine variable to 'true'. This method can be debugged.
	  * @method		InsertLine
	  * @param		int lineNumber
	  * @param		string lineContent
	  * @param		optional bool replaceLine
	  * @returns	none
	  */	
	function InsertLine($lineNumber, $lineContent, $replaceLine = false)
	{
		$this->DebugWriteLine("***** BEGIN DEBUG: InsertLine  *****");
		$this->DebugWriteLine("* Params : lineNumber = ".$lineNumber." - lineContent = ".$lineContent);		
		
		if(!$this->IsOpen()) {
			// Establish Connection
			$this->Open($this->fileName);
			$this->DebugWriteLine("* File opened. Filename : '".$this->fileName."'");
		}
		
		// Destroy File Contents and base all on the buffered copy in the array.
		$this->ResetFileContent();
		$this->DebugWriteLine("* Truncating file on disk.");
		
		$count = count($this->fileContentArray);

		// If the item goes beyond the array or if the array is empty.
		if($count <= $lineNumber) {
			$this->DebugWriteLine("* The item is not in between the array.");
			
			// LineNumber-1 is applied because LineNumberStarts in 0, and Count in 1.
			$diff = ($lineNumber - 1) - $count;

			// If no Difference, then the Item goes into the first position of the array.
			if($diff == 0) {				
				// if the array is not empty, then write the whole array.
				if($count != 0) {
					
					// Insert the whole array.
					while (list ($lineNum, $lineString) = each ($this->fileContentArray)) {
						fputs($this->filePointer, $lineString,  strlen($lineString));
						$this->DebugWriteLine("* Inserting line #".$lineNum." - lineString = ".$lineString);
					}
				}
			} else {  // To setup the difference between the arrayCount and the LineNumber where the insertion is wanted.
				// If array is not empty
				if($count!=0)
				{
					// Insert the whole array.
					while (list ($lineNum, $lineString) = each ($this->fileContentArray))
					{
						$this->DebugWriteLine("* Inserting the array");
						fputs($this->filePointer, $lineString,  strlen($lineString));
						$this->DebugWriteLine("* Inserting line #".$lineNum." - lineString = ".$lineString);
					}
				}
				
				// Fill up the difference with spaces.
				for($i = 0 ; $i <= $diff; $i++) {
					fputs($this->filePointer, "\n",  strlen("\n"));
				}
			}

			// Write the wanted item.
			fputs($this->filePointer, $lineContent,  strlen($lineContent));
			$this->DebugWriteLine("* Inserting wanted line #".$lineNumber." - lineString = ".$lineContent);			
			
		} else { // If the Item goes in between the array.
			$this->DebugWriteLine("* The item goes in between the array.");
			
			while (list ($lineNum, $lineString) = each ($this->fileContentArray)) {
				// Init Variable to ignore lines.
				$ignore = false;
				
				// If the LineNumber is equal to the actual line, insert the item.
				if($lineNum == $lineNumber) {
					fputs($this->filePointer, $lineContent."\n",  strlen($lineContent."\n"));
					$this->DebugWriteLine("* Inserting wanted line #".$lineNumber." - lineString = ".$lineContent);					

					// if the replace option is set, then the corresponding line will be ignored.
					if($replace) {
					     $ignore = true;
					}
				}

				if(!$ignore) {
					fputs($this->filePointer, $lineString,  strlen($lineString));
					$this->DebugWriteLine("* Inserting line #".$lineNum." - lineString = ".$lineString);					
				}
			}
		}
		$this->Close();
		$this->UpdateContent();
		$this->DebugWriteLine("***** END DEBUG: InsertLine  *****");		
	}

	/**
	  * Returns the value of the desired line from the file. By default it retrieves the line 0 fmr the file if the
	  * line number is not set.
	  * @method		GetLine
	  * @param		optional int lineNumber
	  * @returns	string retrieved from the file's selected line.
	  */	
	function GetLine($lineNumber = 0)
	{
		// Update Buffer
		$this->UpdateContent();

		// Obtains Line from buffer
		$lineString = $this->fileContentArray[$lineNumber];

		if(empty($lineString)) {
			$lineString = "(Empty Line)";
		}
		
		return $lineString;
	}
	
	/**
	  * Deletes a line from the file.
	  * @method		DeleteLine
	  * @param		int lineNumber
	  * @returns	none
	  */	
	function DeleteLine($lineNumber)
	{
		// Backup the whole File on Memory.
		$this->UpdateContent();

		// Establish connection with the file
		$this->Open($this->fileName);

		// Reset File on Disk.
		$this->ResetFileContent();

		// Write the Lines on disk except the one to Delete.
		while (list ($lineNum, $lineString) = each ($this->fileContentArray)) {
			// If the LineNumber is equal to the actual line, don't write the item.
			if($lineNum != $lineNumber) {
				fputs($this->filePointer, $lineString,  strlen($lineString));
			}
		}

		// Close Connection
		$this->Close();

		// Update Buffer array.
		$this->UpdateContent();
	}

	/**
	  * Updates the file content array from disk.
	  * @method		UpdateContent
	  * @returns	none
	  */	
	function UpdateContent()
	{
		if($this->updateContent) {
			$this->fileContentArray = array();
			$this->fileContentArray = file($this->fileName);

			if($this->IsFileContentEmpty()) {
				$this->lineCount = 0;
			} else {
				$this->lineCount = count($this->fileContentArray);
			}
		}
	}

	/**
	  * Returns the file object variables' values, formated into a string.
	  * @method		GetCoreDump
	  * @returns	string formated variables dump.
	  */	
	function GetCoreDump()
	{		
		if($this->fileIsOpen) {
			$isOpen = "true";
		} else {
			$isOpen = "false";
		}
		
		$dump  = "File Pointer = ".$this->filePointer."\n";
		$dump .= "File Name = ".$this->fileName."\n";
		$dump .= "File Open = ".$isOpen."\n";
		$dump .= "Last Error#".$this->GetLastErrorNumber()." = ".$this->GetLastError()."\n";

		return $dump;
	}

	/**
	  * Returns the last error number reported.
	  * @method		GetLastErrorNumber
	  * @returns	last error number reported.
	  */	
	function GetLastErrorNumber()
	{
		return $this->errNum;
	}

	/**
	  * Returns the last error reported.
	  * @method		GetLastError
	  * @returns	last error string reported.
	  */	
	function GetLastError()
	{
		$errorString = "(None)";
		
		switch($this->errNum)
		{
		case IO:
			$errorString = "File (".$this->fileName.") Does not Exist!!!! Trying to Create it";
			break;
		case 2:
			$errorString = "File (".$this->fileName .") Couldn't be Opened";
			break;
		default:
			break;
		}
		
		return $errorString;
	}

	/**
	  * Returns true if the file content array is empty.
	  * @method		IsFileContentEmpty
	  * @returns	true if the content array is empty, false otherwise.
	  */	
	function IsFileContentEmpty()
	{
		$isEmpty = false;
		
		if(empty($this->fileContentArray)) {
			$isEmpty = true;
		}

		return $isEmpty;
	}
	
	/**
	  * Returns the content array into a formated string.
	  * @method		GetContents
	  * @returns	string formated of the content array
	  */	
	function GetContents()
	{
		$lineNum = 0;
		$lineString = "";
		$contentString = "";
		$contentArray = $this->fileContentArray;

		if($this->IsFileContentEmpty()) {
			$this->UpdateContent();
		}

		while (list($lineNum, $lineString) = each($contentArray)) {
			$contentString .= $lineString;
		}

		return $contentString;
	}
	
	/**
	  * Returns true if the file is in use, false otherwise.
	  * @method		IsOpen
	  * @returns	true if the file is opened, false otherwise
	  */	
	function IsOpen()
	{
		return $this->fileIsOpen;
	}

	/**
	  * Returns the last time the file was accessed.
	  * @method		GetLastAccessTime
	  * @returns	string of the file's last access.
	  */	
	function GetLastAccessTime()
	{
		return fileatime($this->fileName);
	}

	/**
	  * Returns the last time the file's inode changed.
	  * @method		GetLastInodeChangeTime
	  * @returns	string of the last file's inode change.
	  */	
	function GetLastInodeChangeTime()
	{
		return filectime($this->fileName);
	}

	/**
	  * Returns the las time the file was updated.
	  * @method		GetLastUpdateTime
	  * @returns	string of the last time the file's was updated.
	  */	
	function GetLastUpdateTime()
	{
		return filemtime($this->fileName);
	}
	
	/**
	  * Truncates ONLY the file on disk.
	  * @method		ResetFileContents.
	  * @returns	none
	  */	
	function ResetFileContent()
	{
		// If the connection is not open, then open it.
		if(!$this->fileIsOpen) {
			if($this->Open($this->fileName)) {
			   ftruncate($this->filePointer, 0);
			   $this->Close();
			}
		} else {
			ftruncate($this->filePointer, 0);
		}

		$this->lineCount = 0;
	}

	/**
	  * Resets all buffers to initial condition.
	  * @method		ResetBuffers
	  * @returns	none
	  */	
	function ResetBuffers()
	{
		$this->fileIsOpen = false;
		$this->fileContentArray = array();
		$this->fileOpenType = "a+";

		$this->lineCount = 0;
		$this->updateContent = true;		
		$this->errNum = IOERR_NO_ERR;		
	}
	
	/**
	  * Resets all buffers to initial condition and trucantes the file on disk.
	  * @method		ResetAll
	  * @returns	none
	  */	
	function ResetAll()
	{
		$this->ResetFileContent();
		$this->ResetBuffers();
	}

	/**
	  * Destroy the objects reseting the buffers to initial condition.
	  * @method		Destroy
	  * @returns	none
	  */	
	function Destroy()
	{
		// Check if file is in use.
		if($this->IsOpen()) {
			$this->Close();
		}

		// Clean the objects garbage.
		$this->ResetBuffers();
	}

	/**
	  * Locates a string in a file.
	  * @method		FindString
	  * @param		string String
	  * @returns	line number where the string was found, -1 otherwise.
	  */	
	function FindString($string)
	{
		$count = 0;
		$lineNumber = -1;
		$contentArray = $this->fileContentArray;

		$count = count($contentArray);
		
		// Clean String
		$key = trim($string);

		// Update Contents Array
		$this->UpdateContent();

		// Search the Contents for the String
		for($i = 0; $i < $count; $i++) {
			$lineString = $contentarray[$i];
			$pos = strpos($lineString, $key);

			// Check if it was found. NOTE: Three equal signs NOT a typo.
			if($pos === false) {
				$lineNumber = -1;
			} else {
				$lineNumber = $i;
				$i = $count;
			}
		}
		return $lineNumber;
	}

	
	/**
	  * Strips the file returning either the filename or the extension.
	  * Types :
	  * 0 - filename. (Default)
	  * 1 - extension (size 3)
	  * 2 - extension (any size)
	  * @method		StripFile
	  * @param		optional int type
	  * @returns	string containing either the filename or the extension
	  */
	function StripFile($type=0)
	{
		$pos = 0;
		$len = 0;
		$fileName = basename($this->fileName);

		switch($type)
		{
		case 0:
			/* Separate the Filename from the extension, divided by the '.' (dot)  */
			$pos = strpos($fileName, ".");
			$temp = substr($fileName, 0, $pos);
			break;
		case 1:
			/* Strip Extension (size=3) */
			$temp  = substr($fileName, -3);
			break;
			/* Strip Extension */
		case 2:
			$pos  = strpos($fileName, ".");
			$len  = strlen($fileName);
			$temp = substr($fileName, $pos + 1, $len - ($pos + 1));			
			break;
		default:
			$temp = "";
		}
		return $temp;
	}

	/**
	  * Returns an array with all files from the defined directory.
	  * @method		GetAllFilesInDir
	  * @param		string defDir
	  * @returns	an array containing all the files in the directory.
	  */	
	function GetAllFilesInDir($defDir)
	{
		$files = array();
		$count = 0;
		$dir = dir($defDir);

		while($fileName = $dir->read())	{
			if(strpos($fileName,".")!=0) {
				$files[$count] = $fileName;
				$this->DebugWriteLine("* File #".$count." with file name ".$fileName." added.");
				$count++;
			}
		}
		$dir->close;

		return $files;		
	}		
}

?>
