<?
##############################################################################
#                                                                            #
#                              common.php                                    #
#                                                                            #
##############################################################################
# PROGRAM : E-MatchMaker                                                     #
# VERSION : 1.51                                                             #
#                                                                            #
# NOTES   : site using default site layout and graphics                      #
##############################################################################
# All source code, images, programs, files included in this distribution     #
# Copyright (c) 2001-2002                                                    #
# Supplied by          : CyKuH [WTN]                                         #
# Nullified by         : CyKuH [WTN]                                         #
# Distribution:        : via WebForum and xCGI Forums File Dumps             #
##############################################################################
#                                                                            #
#    While we distribute the source code for our scripts and you are         #
#    allowed to edit them to better suit your needs, we do not               #
#    support modified code.  Please see the license prior to changing        #
#    anything. You must agree to the license terms before using this         #
#    software package or any code contained herein.                          #
#                                                                            #
#    Any redistribution without permission of MatchMakerSoftware             #
#    is strictly forbidden.                                                  #
#                                                                            #
##############################################################################
?>
<?


function CheckHash($hash, $checksum) {

$newvar = chunk_split($hash, 1, ':');
$array = explode(':', $newvar);

foreach($array as $val)  
  if(is_numeric($val))
     $sum += $val;   

if($sum == $checksum)
  return 1;
else
  return 0;

}     

function ReplacePA($str,$art)
{
      if($nr1=strpos($str,'['))
      {
        $tr=substr($str,0,$nr1);
        $nr2=strpos($str,']',$nr1);
        $tr.=$art[substr($str,$nr1+1,$nr2-$nr1-1)];
        $tr.=substr($str,$nr2+1);
        return ReplacePA($tr,$art);
      }
      else
      {
        $OUT = explode("76PULL*PULL76", $str); 
        return $OUT[1];
      }
}

function ReplacePCH($str,$art)
{
      if($nr1=strpos($str,'~'))
      {
        $tr=substr($str,0,$nr1);
        $nr2=strpos($str,'~',$nr1);
        $tr.=$art[substr($str,$nr1+1,$nr2-$nr1-1)];
        $tr.=substr($str,$nr2+1);
        return ReplacePCH($tr,$art);
      }
      else
      {
        return $str;
      }
}



?>

