<?php
include 'func.php';
        $parser = xml_parser_create();
        $images=null;
        $images=array();
        xml_set_element_handler($parser, 'startElement', 'endElement');
        xml_set_character_data_handler($parser, 'characterData');
        $data = xml_parse($parser, file_get_contents('images.xml'));
        if(!$data) {
                        die(sprintf('XML error: %s at line %d',
                        xml_error_string(xml_get_error_code($parser)),
                        xml_get_current_line_number($parser)));
                   }
        xml_parser_free($parser);
        for($i=0;$i<count($images);$i++)
                {
                 if($images[$i]['url']==$_GET['image'])
                        {
                                $images[$i]['hits']++;
                                $images[$i]['lastDate']=date("m.d.y");
                                echo'<center><img src="' . $_GET['image'] . '"><br>Upload Date:' . $images[$i]['upDate'] . '<br>Last Date Viewed:' . $images[$i]['lastDate'] . '<br>Hits:' . $images[$i]['hits'] . '<br>' . $images[$i]['caption'] . '<br></center>';

                        }
                
                }
        write_xml($images,"images.xml");
?>

