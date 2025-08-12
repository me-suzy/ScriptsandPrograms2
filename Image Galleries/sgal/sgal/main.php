<?php
include'http://statc.net/header.php';
        /*
--------------------------------------------------------------
|Sgal 2.0                                                    |
|(c)Adrian Wisernig 2005                                     |
|For help or more scripts go to:                             |
|http://www.statc.net                                        |
--------------------------------------------------------------
*/
        include 'sgal/func.php';
        include 'sgal/config.php';
        $parser = xml_parser_create();
        $images=null;
        $images=array();
        xml_set_element_handler($parser, 'startElement', 'endElement');
        xml_set_character_data_handler($parser, 'characterData');
        $data = xml_parse($parser, file_get_contents('sgal/images.xml'));
        if(!$data) {
                        die(sprintf('XML error: %s at line %d',
                        xml_error_string(xml_get_error_code($parser)),
                        xml_get_current_line_number($parser)));
                   }
        xml_parser_free($parser);
        if($intdis==0)
        {
        echo'<table id="images" background="#CCCCCC"><tr>';
        for($i=0;$i<count($images);$i++)
        {
           $imagesize=getimagesize("sgal/" . $images[$i]['url']);
           if($i%$maximages==0 && $i>1){echo'</tr><tr>';}
           echo'<td align="center"><center><img  alt="' .$images[$i]['imageName'] . '" src="sgal/thumb.php?url=' . $images[$i]['url'] . '"onClick=javascript:window.open("sgal/view.php?image=' . $images[$i]['url'] . '","blank","toolbar=no,width=' . ($imagesize[0]+10) . ',height=' . ($imagesize[1]+100) . ',xdirectories=0,menubar=0") alt="' . $images[$i]['imageName'] . '"></td>';
        }
        echo'</table>';
        }
        else
        {
        echo'<div style="overflow : auto; width:100%; height:170px;">';
        for($i=0;$i<count($images);$i++)
        {
         echo'<a href="main.php?view=' . $images[$i]['url'] . '"><img border="0" src="sgal/thumb.php?url=' . $images[$i]['url'] . '"></a>';
        
        }
              echo'</div>';
              if(!empty($_GET['view'])){
                               echo '<br><center><img src="' . $_GET['view'] . '"><br>';
                                        for($i=0;$i<count($images);$i++)
                               {
                                        if($images[$i]['url']==$_GET['view'])
                                        {
                                                $images[$i]['hits']++;
                                                echo $images[$i]['caption'];
                                                
                                        }
                               }
                               write_xml($images,"sgal/images.xml");
                               }
        
        
        }
?>
<br><font size="-1">Powered by&nbsp;<a title="Statc.net Web Solutions" href="http://statc.net">Sgal</a></font>
<?php include'http://statc.net/footer.php';?>
