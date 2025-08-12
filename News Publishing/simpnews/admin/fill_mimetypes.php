<?php
/***************************************************************************
 * (c)2002-2004 Boesch IT-Consulting (info@boesch-it.de)
 ***************************************************************************/
function fill_mimetypes($tableprefix, $db)
{
	$registered_types=get_file_types();
	while(list($extension, $mimetype) = each($registered_types))
	{
		$sql="select * from ".$tableprefix."_mimetypes where mimetype='$mimetype'";
		if(!$result = mysql_query($sql, $db))
			die("Unable to get data from ".$tableprefix."_mimetypes");
		if(!$myrow=mysql_fetch_array($result))
		{
			$sql = "INSERT INTO ".$tableprefix."_mimetypes (mimetype) ";
			$sql .="values ('$mimetype')";
			if(!$result = mysql_query($sql, $db))
				die("Unable to insert data into ".$tableprefix."_mimetypes");
			$mimetypenr=mysql_insert_id($db);
		}
		else
			$mimetypenr=$myrow["entrynr"];
		$sql = "INSERT INTO ".$tableprefix."_fileextensions (mimetype, extension) ";
		$sql.="values ($mimetypenr, '$extension')";
		if(!$result = mysql_query($sql, $db))
			die("Unable to insert data into ".$tableprefix."_fileextensions");
	}
}
function get_file_types()
{
	$registered_types = array(
		".gz" => "application/x-gzip-compressed",
		".tgz" => "application/x-gzip-compressed",
		".zip" => "application/x-zip-compressed",
		".tar" => "application/x-tar",
		".html" => "text/html",
		".php" => "text/plain",
		".inc" => "text/plain",
		".txt" => "text/plain",
		".asc" => "text/plain",
		".bmp" => "image/bmp",
		".ico" => "image/bmp",
		".gif" => "image/gif",
		".jpg" => "image/jpeg",
		".jpeg" => "image/jpeg",
		".swf" => "application/x-shockwave-flash",
		".doc" => "application/msword",
		".xls" => "application/vnd.ms-excel",
		".pdf" => "application/pdf",
		".aiff" => "audio/aiff",
		".aif" => "audio/aiff",
		".arj" => "application/arj",
		".asx" => "video/x-ms-asf",
		".au" => "audio/basic",
		".avi" => "video/avi",
		".bz" => "application/x-bzip",
		".bz2" => "application/x-bzip2",
		".dvi" => "application/x-dvi",
		".hlp" => "application/x-helpfile",
		".hqx" => "application/mac-binhex",
		".mov" => "video/quicktime",
		".mp3" => "audio/mpeg3",
		".mpeg3" => "audio/mpeg3",
		".mpeg" => "video/mpeg",
		".mpg" => "video/mpeg",
		".mpp" => "application/vnd.ms-project",
		".png" => "image/png",
		".ppt" => "application/vnd.ms-powerpoint",
		".ps" => "application/postscript",
		".rtf" => "application/rtf",
		".sea" => "application/sea",
		".tex" => "application/x-tex",
		".texi" => "application/x-texinfo",
		".tif" => "image/tiff",
		".tiff" => "image/tiff",
		".wmf" => "windows/metafile"
	);
	return $registered_types;
}
?>
