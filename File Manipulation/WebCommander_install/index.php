<?
$now = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: '.$now);
header('Last-Modified: '.$now);
header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0');
header('Pragma: no-cache');
$AdresarWCMD="http://webcommander.unas.cz";

/*------------------------------------REGISTER GLOBALS----------------------------------*/
if(!isset($variables_order)) $variables_order="gpc";
$variables_order=strtolower($variables_order);



 for($i=0;$i<strlen($variables_order);$i++)
  {
   if($variables_order[$i]=="g")
    {
	reset ($_GET);
	while (list ($key, $val) = each ($_GET)) {
	eval("\$$key=\$val;");
	};
    };
   if($variables_order[$i]=="p")
    {
	reset ($_POST);
	while (list ($key, $val) = each ($_POST)) {
	eval("\$$key=\$val;");
	};
    };
   if($variables_order[$i]=="c")
    {
	reset ($_COOKIE);
	while (list ($key, $val) = each ($_COOKIE)) {
	eval("\$$key=\$val;");
	};
    };
  };
/*-------------------------------END--REGISTER GLOBALS----------------------------------*/

if($Akce=="install")
{

$thisfile=basename($a);

// $data=stripslashes($data);
 $data=str_replace("#!char0!#",chr(0),$data);
 $data=str_replace("#!char13!#",chr(13),$data);
 $data=str_replace("#!char10!#",chr(10),$data);
//$data=str_replace(chr(0x0d).chr(0x0a),chr(0x0a),$data);
// $data=str_replace(chr(0x0a).chr(0x0e),chr(0x0e),$data);


$f=fopen("temp.zip","wb");
fwrite($f,stripslashes($data));
fclose($f);

unzip("temp.zip","./");
unlink($thisfile);
rename("wcmd.ins",$thisfile);
unlink("temp.zip");
header("Location: $a");
}
else
{
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<BODY>
<SCRIPT Language=Javascript Src="<?echo $AdresarWCMD."/update/install2.js?test=".time()?>">
</SCRIPT>
</BODY>
</HTML>
<?
};





function Unzip($myzipname,$folder)
  {
   global $fzip,$zipname,$zipftp;
   $zipftp=0;
    $zipname=$myzipname;
    privExtractByRule($folder);
  }

function UnzipToFTP($myzipname,$ftpid,$folder)
  {
   global $fzip,$zipname,$zipftp;
   $zipftp=$ftpid;
    $zipname=$myzipname;
    privExtractByRule($folder);
  }

function privExtractByRule($myfolder)
  {
  global $fzip,$zipname;
  $p_file_lis=array();
  $p_options = array();
  $p_path=$myfolder;
  $p_remove_path="";
  $p_remove_all_path=false;
    $v_result=1;

    if (($p_path == "") || ((substr($p_path, 0, 1) != "/") && (substr($p_path, 0, 3) != "../") && (substr($p_path,1,2)!=":/")))
      $p_path = "./".$p_path;
    if (($p_path != "./") && ($p_path != "/"))
    {
      while (substr($p_path, -1) == "/")
      {
        $p_path = substr($p_path, 0, strlen($p_path)-1);
      }
    }

    $fzip = fopen($zipname, 'rb');


    $v_central_dir = array();
    privReadEndCentralDir($v_central_dir);

    $v_pos_entry = $v_central_dir['offset'];


    $j_start = 0;
    for ($i=0, $v_nb_extracted=0; $i<$v_central_dir['entries']; $i++)
    {

      rewind($fzip);
      fseek($fzip, $v_pos_entry);

      $v_header = array();
      privReadCentralFileHeader($v_header);


      $v_header['index'] = $i;


      $v_pos_entry = ftell($fzip);

       $v_extract = true;

      if ($v_extract)
      {

          rewind($fzip);
       fseek($fzip, $v_header['offset']);

          privExtractFile($v_header,$p_path, $p_remove_path,$p_remove_all_path,$p_options);

          privConvertHeader2FileInfo($v_header, $p_file_list[$v_nb_extracted++]);

          if ($v_result1 == 2) {
                  break;
          }

      }
    }

    fclose($fzip);
       return $v_result;
  }

function privReadEndCentralDir(&$p_central_dir)
  {
  global $fzip,$zipname;
    $zipsize = filesize($zipname);
    fseek($fzip, $zipsize);
    $v_size=$zipsize;

     $v_found = 0;
    if ($zipsize > 26) {
       fseek($fzip, $zipsize-22);
      $v_binary_data = fread($fzip, 4);
      $v_data = unpack('Vid', $v_binary_data);
      if ($v_data['id'] == 0x06054b50) {
        $v_found = 1;
      }
      $v_pos = ftell($fzip);
    }
        if (!$v_found) {
      $v_maximum_size = 65557; // 0xFFFF + 22;
      if ($v_maximum_size > $v_size)
        $v_maximum_size = $v_size;
      fseek($fzip, $v_size-$v_maximum_size);
         $v_pos = ftell($fzip);
      $v_bytes = 0x00000000;
      while ($v_pos < $v_size)
      {

        $v_byte = fread($fzip, 1);

        $v_bytes = ($v_bytes << 8) | Ord($v_byte);

        if ($v_bytes == 0x504b0506)
        {
          $v_pos++;
          break;
        }

        $v_pos++;
      }

    }


    $v_binary_data = fread($fzip, 18);


    if(!@$v_data = unpack('vdisk/vdisk_start/vdisk_entries/ventries/Vsize/Voffset/vcomment_size', $v_binary_data))
     {
      JSError(Translate("Some files cannot be unzipped!"));
      die;
     };

    if ($v_data['comment_size'] != 0)
      $p_central_dir['comment'] = fread($fzip, $v_data['comment_size']);
    else
      $p_central_dir['comment'] = '';

    $p_central_dir['entries'] = $v_data['entries'];
    $p_central_dir['disk_entries'] = $v_data['disk_entries'];
    $p_central_dir['offset'] = $v_data['offset'];
    $p_central_dir['size'] = $v_data['size'];
    $p_central_dir['disk'] = $v_data['disk'];
    $p_central_dir['disk_start'] = $v_data['disk_start'];
  }
function privReadCentralFileHeader(&$p_header)
  {
  global $fzip;
      $v_result=1;
    $v_binary_data = fread($fzip, 4);
    $v_data = unpack('Vid', $v_binary_data);
    $v_binary_data = fread($fzip, 42);
    $p_header = unpack('vversion/vversion_extracted/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len/vcomment_len/vdisk/vinternal/Vexternal/Voffset', $v_binary_data);

     if ($p_header['filename_len'] != 0)
      $p_header['filename'] = fread($fzip, $p_header['filename_len']);
    else
      $p_header['filename'] = '';
     if ($p_header['extra_len'] != 0)
      $p_header['extra'] = fread($fzip, $p_header['extra_len']);
    else
      $p_header['extra'] = '';
     if ($p_header['comment_len'] != 0)
      $p_header['comment'] = fread($fzip, $p_header['comment_len']);
    else
      $p_header['comment'] = '';
    if ($p_header['mdate'] && $p_header['mtime'])
    {
      $v_hour = ($p_header['mtime'] & 0xF800) >> 11;
      $v_minute = ($p_header['mtime'] & 0x07E0) >> 5;
      $v_seconde = ($p_header['mtime'] & 0x001F)*2;
      $v_year = (($p_header['mdate'] & 0xFE00) >> 9) + 1980;
      $v_month = ($p_header['mdate'] & 0x01E0) >> 5;
      $v_day = $p_header['mdate'] & 0x001F;
      $p_header['mtime'] = mktime($v_hour, $v_minute, $v_seconde, $v_month, $v_day, $v_year);

    }
    else
    {
      $p_header['mtime'] = time();
    }
    $p_header['stored_filename'] = $p_header['filename'];
    $p_header['status'] = 'ok';
      if (substr($p_header['filename'], -1) == '/')
    {
      $p_header['external'] = 0x41FF0010;
     }
  };








function privExtractFile(&$p_entry, $p_path, $p_remove_path, $p_remove_all_path, &$p_options)
  {
  global $fzip,$zipftp;
    privReadFileHeader($v_header);
    if ($p_path != '')
    {
      $p_entry['filename'] = $p_path."/".$p_entry['filename'];
    }
    if ($p_entry['status'] == 'ok') {
    if (file_exists($p_entry['filename']))
    {
      if (is_dir($p_entry['filename']))
      {
        $p_entry['status'] = "already_a_directory";

      }
      else if (!is_writeable($p_entry['filename']))
      {
        $p_entry['status'] = "write_protected";

      }

      else if (filemtime($p_entry['filename']) > $p_entry['mtime'])
      {
        $p_entry['status'] = "newer_exist";
      }
    }

     else {
      if ((($p_entry['external']&0x00000010)==0x00000010) || (substr($p_entry['filename'], -1) == '/'))
        $v_dir_to_check = $p_entry['filename'];
      else if (!strstr($p_entry['filename'], "/"))
        $v_dir_to_check = "";
      else
        $v_dir_to_check = dirname($p_entry['filename']);

      privDirCheck($v_dir_to_check, (($p_entry['external']&0x00000010)==0x00000010));
    }
    }
    if ($p_entry['status'] == 'ok') {
      if (!(($p_entry['external']&0x00000010)==0x00000010))
      {
        if ($p_entry['compressed_size'] == $p_entry['size'])
        {
          $MyFName=$p_entry['filename'];
          $v_dest_file = fopen("temp.dat", 'wb');
          $v_size = $p_entry['compressed_size'];
          while ($v_size != 0)
          {
            $v_read_size = ($v_size < 2048 ? $v_size : 2048);
            $v_buffer = fread($fzip, $v_read_size);
            $v_binary_data = pack('a'.$v_read_size, $v_buffer);
            fwrite($v_dest_file, $v_binary_data, $v_read_size);
            $v_size -= $v_read_size;
          }
          fclose($v_dest_file);
          touch("temp.dat", $p_entry['mtime']);
          if($zipftp==0)
           {
            rename("temp.dat",$MyFName);
           }
           else
           {
            ftp_put($zipftp,$MyFName,"temp.dat",FTP_BINARY);
           };
        }
        else
        {
          $MyFName=$p_entry['filename'];
          $v_dest_file = fopen("temp.dat", 'wb');
          $v_buffer = fread($fzip, $p_entry['compressed_size']);
          $v_file_content = gzinflate($v_buffer);
          unset($v_buffer);
          fwrite($v_dest_file, $v_file_content, $p_entry['size']);
          unset($v_file_content);
          fclose($v_dest_file);
          touch("temp.dat", $p_entry['mtime']);
           rename("temp.dat",$MyFName);
        }
      }
    }
        if ($p_entry['status'] == "aborted") {
      $p_entry['status'] = "skipped";
        }
  }


function privDirCheck($p_dir, $p_is_dir=false)
  {
  global $zipftp,$CreatedDirs;
    if (($p_is_dir) && (substr($p_dir, -1)=='/'))
    {
      $p_dir = substr($p_dir, 0, strlen($p_dir)-1);
    }
    $p_parent_dir = dirname($p_dir);
    if ($p_parent_dir != $p_dir)
    {
      if ($p_parent_dir != "")
      {
        privDirCheck($p_parent_dir);
      }
    }
    @mkdir($p_dir, 0777);
  }




function privReadFileHeader(&$p_header)
  {
  global $fzip;
    $v_binary_data = fread($fzip, 4);
       $v_data = unpack('Vid', $v_binary_data);
    $v_binary_data = fread($fzip, 26);
     $v_data = unpack('vversion/vflag/vcompression/vmtime/vmdate/Vcrc/Vcompressed_size/Vsize/vfilename_len/vextra_len', $v_binary_data);
    $p_header['filename'] = fread($fzip, $v_data['filename_len']);
   if ($v_data['extra_len'] != 0) {
      $p_header['extra'] = fread($fzip, $v_data['extra_len']);
    }
    else {
      $p_header['extra'] = '';
    }
    $p_header['compression'] = $v_data['compression'];
     $p_header['size'] = $v_data['size'];
    $p_header['compressed_size'] = $v_data['compressed_size'];
    $p_header['crc'] = $v_data['crc'];
    $p_header['flag'] = $v_data['flag'];
    $p_header['mdate'] = $v_data['mdate'];
    $p_header['mtime'] = $v_data['mtime'];
    if ($p_header['mdate'] && $p_header['mtime'])
    {
      $v_hour = ($p_header['mtime'] & 0xF800) >> 11;
      $v_minute = ($p_header['mtime'] & 0x07E0) >> 5;
      $v_seconde = ($p_header['mtime'] & 0x001F)*2;
      $v_year = (($p_header['mdate'] & 0xFE00) >> 9) + 1980;
      $v_month = ($p_header['mdate'] & 0x01E0) >> 5;
      $v_day = $p_header['mdate'] & 0x001F;
      $p_header['mtime'] = mktime($v_hour, $v_minute, $v_seconde, $v_month, $v_day, $v_year);
    }
    else
    {
      $p_header['mtime'] = time();
     }
    $p_header['stored_filename'] = $p_header['filename'];
    $p_header['status'] = "ok";
  }


  function privConvertHeader2FileInfo($p_header, &$p_info)
  {
    $p_info['filename'] = $p_header['filename'];
    $p_info['stored_filename'] = $p_header['stored_filename'];
    $p_info['size'] = $p_header['size'];
    $p_info['compressed_size'] = $p_header['compressed_size'];
    $p_info['mtime'] = $p_header['mtime'];
    $p_info['comment'] = $p_header['comment'];
    $p_info['folder'] = (($p_header['external']&0x00000010)==0x00000010);
    $p_info['index'] = $p_header['index'];
    $p_info['status'] = $p_header['status'];
  }

//----------END unzip.php-------------------------------------------------------


?>
