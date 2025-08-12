<?php
/*
goal:
classes that allow programmer to turn regular html
website with includes into a somewhat fancy CMS'd 
site with stored old versions of content, and file
uploads.

data types:

Text - text that gets formatted
HTML - raw html, but maybe shows an FCKEditor?
Form - form data that's transformed into html (php serialized is the storage format)
Multi - uploaded file, part of form. a collection of one or more attachments

user integration:
method to display a form to edit
method to list past versions
method to save form data into file
method to transform form data into html, and save it
method to manage attachments to forms

fixes:
need to fix the path issue
the attachment paths should have been absolute
*/

class Text {
	var $path;
	var $filename;
	var $fullpath;

	/**
	 * $path must exist
	 */
	function Text( $path, $filename )
	{
		$this->path = $path;
		$this->filename = $filename;
		$this->fullpath = realpath($path).'/'.$filename;
	}
	function currentVersion()
	{
		return date('m-d-y');
	}
	function editForm( $data )
	{
		$data = htmlentities( $data );
		$f = '<form method=post><textarea cols=80 rows=20 name=text>'.$data.'</textarea><br />
			<input type=submit value=save name=state /></form>';
		return $f;
	}
	function load( $version=Null )
	{
		if ($version) $vfilename = $this->filename . '.' . $version;
		else $vfilename = $this->filename;
		$fh = fopen( $this->path.'/'.$vfilename, 'r' );
		$data = fread( $fh, 20000 );
		fclose( $fh );
		return $data;
	}
	function save( $data )
	{
		$version = $this->currentVersion();
		$vfilename = $this->filename . '.' . $version;
		$fh = fopen( $this->path.'/'.$vfilename, 'w' );
		fwrite( $fh, $data );
		fclose( $fh );
		return $version;
	}
	function export( $version=Null )
	{
		if (!$version) die("export requires a version string");
		$vfilename = $this->filename . '.' . $version;
		$vf = $this->fullpath.'/'.$vfilename;
		$lf = $this->path.'/'.$this->filename;
		@unlink($lf);
		symlink( $vf, $lf );
	}
}

class HTML extends Text {
}

class Form extends Text {
	var $attribs;
	var $formPHP;
	function Form( $path, $filename, $formPHP )
	{
		parent::Text( $path, $filename );
		$this->fields = $fields;
		$this->formPHP = $formPHP;
	}
	function editForm()
	{
		foreach($this->attribs as $k=>$v)
		{
			$GLOBALS[$k] = $v;
			global ${$k};
		}
		$code = 'return "'.addslashes($this->formPHP).'";';
		return eval( $code );
	}
	function save( &$formdata )
	{
		$this->attribs = $formdata;
		return parent::save(serialize( $this->attribs ));
	}
	function &load()
	{
		$this->attribs = unserialize(parent::load());
		return $this->attribs;
	}
}

class Formattted extends Form {
}

/**
 * Multi manages "multimedia".  Multimedia is managed
 * via directories.  A version of a Multi is an entire 
 * directory, not just a single file.  Within the parent 
 * directory, the file "Multi" holds metadata about the Multi.
 *
 * In each versioned directory, the file "form" holds the
 * form data for the Multi.
 *
 * Each versioned directory has its own "form", but does not
 * necessarily contain copies of the attachments.  This saves
 * space.  The Multi file keeps track of the attachments.
 */
class Multi extends Form {
	var $attachments;
	var $urlRoot;
	
	function Multi( $path, $filename, $formPHP, $urlRoot )
	{
		$this->urlRoot = $urlRoot;
		return parent::Form( $path, $filename, $formPHP );
	}
	function editForm()
	{
		foreach($this->attribs as $k=>$v)
		{
			$GLOBALS[$k] = $v;
			global ${$k};
		}
		$code = 'return "'.addslashes($this->formPHP).'";';
		return eval( $code );
	}
	function &load( $version=Null )
	{
		// read in the attachments index
		$atf = $this->fullpath.'/Multi';
		$this->attachments = unserialize(@file_get_contents($atf));
		// read in the form
		$vf = $this->fullpath.'/form';
		$this->attribs = unserialize(@file_get_contents($vf));
		// add attributes for the attachments
		if ($this->attachments)
		{
			$this->attribs['attachments'] = $this->attachments;
		}
		return $this->attribs;
	}
	function save( &$formdata )
	{
		$this->load(); //load old data (for attachments)
		
		$this->attribs = $formdata;
		$version = $this->currentVersion();
		$vfd = $this->fullpath."/$version/";
		
		// if there are files, attach them
		//print_r($_FILES);
		foreach( $_FILES as $field=>$props )
		{
			assureDir($vfd);
			if ($props['name'])
			{
				$destination = $vfd.$props['name'];
				move_uploaded_file( $props['tmp_name'], $destination );
				chmod( $destination, 0666 );
				$this->attachments[$field] = $destination;
				unset( $this->attribs[ $field ] );
			}
		}
		
		// save the attachments in a Multi file
		$this->_saveMulti();
		$this->_saveForm( $vfd );
		
	}
	function _saveForm( $versionedFileDirectory )
	{
		// save the form
		$vfd = $versionedFileDirectory;
		assureDir($vfd);
		@unlink($vfd.'form');
		$fh = fopen( $vfd.'form', 'w' );
		fwrite( $fh, serialize( $this->attribs ) );
		fclose( $fh );
	}
	function _saveMulti()
	{
		$atf = $this->fullpath . '/Multi';
		$fh = fopen( $atf, 'w' );
		fwrite( $fh, serialize( $this->attachments ) );
		fclose( $fh );
	}
	function generateFile( $template, $filename )
	{
		// apply the locals to the template
		foreach($this->attribs as $k=>$v)
		{
			$GLOBALS[$k] = $v;
			global ${$k};
		}
		// attachments paths are turned into absolute urls
		foreach($this->attachments as $k=>$v)
		{
			/*
			$v = dirname($_SERVER['SCRIPT_FILENAME']).'/'.$v;
			*/
			while (preg_match('#/(\\w+)?/\.\./#', $v))
			{
				$v = preg_replace('#/(\\w+)?/\.\./#','/',$v);
			}
			while (preg_match('#/\./#', $v))
			{
				$v = preg_replace('#/\./#','/',$v);
			}
			while (preg_match('/\\/\\//', $v))
			{
				$v = preg_replace('/\\/\\//','/',$v);
			}
			$v = join('',split($_SERVER['DOCUMENT_ROOT'],$v));
			$v = $this->urlRoot . $v;
			$GLOBALS[$k] = $v;
			global ${$k};
		}
		$code = 'return "'.addslashes($template).'";';
		$data = eval( $code );
				
		// generate it into current version
		$version = $this->currentVersion();
		$vfd = $this->fullpath."/$version/";
		$fh = fopen( $vfd.$filename, 'w' );
		fwrite( $fh, $data );
		fclose( $fh );

		// add it to the attachments
		$this->attachments[$filename] = $vfd.'/'.$filename;
		$this->_saveMulti();		
	}
	/**
	 * Symlinks the current version to the root dir.
	 */
	function export()
	{
		// fixme - add code to delete broken symlinks
		// link all the attachments
		foreach( $this->attachments as $k=>$path )
		{
			$destination = $this->fullpath .'/'. basename($path);
			@unlink( $destination );
			symlink( realpath($path), $destination );
		}
		// link the current form file
		$destination = $this->fullpath . '/form';
		@unlink( $destination );
		$origin = $this->fullpath .'/'. $this->currentVersion() .'/form';
		symlink( $origin, $destination );
	}
}

/** 
 * A Photo is like a Multi that makes thumbnails.
 * Remember that a Multi is a Form, and that requires an HTML form for data entry.
 * The photo is named "photo" and the thumbnail is named "thumb" in the form.
 * These names must be used.
 * Thumbnails are made by cropping the image to the proportions of
 * $thumbWidth to $thumbHeight, then scaling.
 */
class Photo extends Multi {
	var $thumbWidth;
	var $thumbHeight;
	function Photo( $path, $filename, $formPHP, $urlRoot, $thumbWidth, $thumbHeight )
	{
		$this->thumbWidth = $thumbWidth;
		$this->thumbHeight = $thumbHeight;
		return parent::Multi( $path, $filename, $formPHP, $urlRoot );
	}
	function save( &$formdata )
	{
		$version = $this->currentVersion();
		parent::save( &$formdata ); // let the Multi save the file
		// if there's a thumbnail, delete it
		if ($this->attachments['thumb'])
		{
			unlink($this->attachments['thumb']);
			unset($this->attachments['thumb']);
		}
		// create a thumbnail and attach it
		// GD library must be installed
		// Photo must be a JPEG!
		if ( $photoPath = $this->attachments['photo'] )
		{
			$im = imagecreatefromjpeg( $photoPath );
			$width = imagesx( $im );
			$height = imagesy( $im );
			
			$newim = imagecreatetruecolor( $this->thumbWidth, $this->thumbHeight );
			if(!$newim) die("can't create new image");
			imagecopyresampled( $newim, $im, 0, 0, 0, 0, 
				$this->thumbWidth, $this->thumbHeight, 
				$width, $height
				)
				or die("problem resampling"); 
			imagejpeg( $newim, $thumbpath = stripExt($photoPath).'-thumb.jpg' );

			$this->attachments['thumb'] = $thumbpath;
		}
		
		$this->_saveMulti();
		$this->_saveForm( $this->fullpath."/$version/" );
	}
}

function refresh()
{
	header("Location: $_SERVER[PHP_SELF]");
	exit;
}

function stripExt( $path )
{
	preg_match('/^(.+)?[.]([a-z]+)$/i', $path, $matches );
	return $matches[1];
}

function editButton()
{
	return "<form method=get><input name=state value='edit' type=submit></form>";
}

function addBr( $text )
{
	return preg_replace( '/\n/s', "<br />\n", $text );
}

function propsAsTable( &$ar )
{
	$o = "<table border=1>";
	foreach( $ar as $k=>$v )
	{
		if ($k=='attachments') $v = attachmentsAsHTML( $v );
		$o .= "<tr><td>$k</td><td>$v</td></tr>";
	}
	$o .= "</table>";
	return $o;
}

function attachmentsAsHTML( &$ar )
{
	$out = '<table border=1>';
	foreach( $ar as $name=>$link )
	{
		$out .= '<tr><td>';
		$link = join('',split($_SERVER['DOCUMENT_ROOT'],$link));
		if (preg_match('/(jpg|gif|png)$/i', $link))
			$link = "<img src=$link />";
		else
			$link="<a href=$link>click to view</a>";
		$out .= "<td>$name</td><td>$link\n";
		$out .= "</td></tr>";
	}
	$out .= '</table>';
	return $out;
}

function assureDir( $dirname )
{
	if (!file_exists($dirname)) mkdir($dirname);
	chmod( $dirname, 0777 );
}
