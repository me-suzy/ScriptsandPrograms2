<?PHP
/************************************************************************/
/* BCWB: Business Card Web Builder                                      */
/* ============================================                         */
/*                                                                      */
/* 	The author of this program code:                                    */
/*  Max Baryshnikov (sapid.sf.net),	                    				*/
/*  Dmitry Sheiko (sheiko@cmsdevelopment.com)	                    	*/
/* 	Copyright by Dmitry Sheiko											*/
/* 	http://bcwb.cmsdevelopment.com     			                        */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

 

function create_tnail($file, $w, $h, $path_prefix, $name_prefix, $unlink=false){
if (!function_exists('imagecreatetruecolor')) return false;


									$info=getimagesize($file["tmp_name"]);

                                   switch ($info[2]) {

                                               case 1:

                                               $img=imagecreatefromgif($file["tmp_name"]);

                                               break;

                                               case 2:

                                               $img=imagecreatefromjpeg($file["tmp_name"]);

                                               break;

                                               case 3:

                                               $img=imagecreatefrompng($file["tmp_name"]);

                                               break;

                                   }

 

                                               if(($info[0]/$info[1])<=($w/$h)){

                                                           $new_w=$w;

                                                           $new_h=(int)(($w/$info[0])*$info[1]);

 

                                                          if( !($tmp_im=@imageCreateTrueColor($new_w, $new_h)) ) return false;

                                                           if( !@imagecopyresampled($tmp_im, $img, 0, 0, 0, 0, $new_w, $new_h, $info[0], $info[1]) ) return false;

                                                           imagedestroy($img);

                                                           $im=imageCreateTrueColor($w, $h);

                                                           $offset=(int)(($new_h-$h)/2);

                                                           imagecopy($im, $tmp_im, 0,0,0,$offset,$w, $h);

                                                           

                                                           

                                                           ob_start();

                                                           imagejpeg($im);

                                                           $buffer=ob_get_contents();

                                                           ob_end_clean();

                                                           

                                                           imagedestroy($im);

                                                           imagedestroy($tmp_im);

 

 

                                                           if ($unlink) @unlink($file["tmp_name"]);

                                                           $filename=$path_prefix . $name_prefix . $file["name"];

                                                           $fp=fopen($filename, "w+");

                                                           $res=fwrite($fp, $buffer);

                                                           fclose($fp);

 

                                               }elseif (($info[0]/$info[1])>($w/$h)){

                                                           $new_h=$h;

                                                           $new_w=(int)(($h/$info[1])*$info[0]);

 

                                                           $tmp_im=imageCreateTrueColor($new_w, $new_h);

                                                           imagecopyresampled($tmp_im, $img, 0, 0, 0, 0, $new_w, $new_h, $info[0], $info[1]);

                                                           imagedestroy($img);

                                                           $im=imageCreateTrueColor($w, $h);

                                                           $offset=(int)(($new_w-$w)/2);

                                                           imagecopy($im, $tmp_im, 0,0,$offset,0,$w, $h);

                                                           

                                                           

                                                           ob_start();

                                                           imagejpeg($im);

                                                           $buffer=ob_get_contents();

                                                           ob_end_clean();

                                                           

                                                           imagedestroy($im);

                                                           imagedestroy($tmp_im);

 

 

                                                           if ($unlink) @unlink($file["tmp_name"]);

                                                           $filename=$path_prefix . $name_prefix . $file["name"];

                                                           $fp=fopen($filename, "w+");

                                                           $res=fwrite($fp, $buffer);

                                                           fclose($fp);

 

                                               }

            return array("init_width"=>$info[0], "init_height"=>$info[1]);

}


?>