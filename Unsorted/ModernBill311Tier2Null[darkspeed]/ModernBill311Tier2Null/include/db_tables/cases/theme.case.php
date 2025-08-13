<?
/* THIS FILE IS NOT IS USE
** ModernBill [TM] (Copyright::2001)
** Questions? webmaster@modernbill.com
**
**
**          Always save a backup before your upgrade!
**          Proceed with caution. You have been warned.
*/
########################################
######### THEME_DEFAULT CONFIG ########
########################################
      $args = array(array("column"         => "config_type",
                           "required"      => 0,
                           "title"         => ID,
                           "type"          => "HIDDEN"),

# Result's Table Display Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Result's Table Display Configuration"),

                    array("column"         => "config_4",
                           "required"      => 0,
                           "title"         => "Set Table Spacing [cellspacing]",
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 2),

                    array("column"         => "config_5",
                           "required"      => 0,
                           "title"         => "Set Table Padding [cellpadding]",
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 2),

                    array("column"         => "config_1",
                           "required"      => 0,
                           "title"         => "Set Alternating Background Color 1",
                           "type"          => "TEXT",
                           "size"          => 8,
                           "maxlength"     => 25,
                           "swatch"        => 1),
                    array("column"         => "config_2",
                           "required"      => 0,
                           "title"         => "Set Alternating Background Color 2",
                           "type"          => "TEXT",
                           "size"          => 8,
                           "maxlength"     => 25,
                           "swatch"        => 1),

                    array("column"         => "config_6",
                           "required"      => 0,
                           "title"         => "Set Table Width [Admin Section]",
                           "type"          => "TEXT",
                           "size"          => 4,
                           "maxlength"     => 4,
                           "append"        => "<br>Pixel Width of the Outside Table.
                                               <br><br>"),

                    array("column"         => "config_7",
                           "required"      => 0,
                           "title"         => "Set Table Width [User Section]",
                           "type"          => "TEXT",
                           "size"          => 4,
                           "maxlength"     => 4,
                           "append"        => "<br>Pixel Width of the Outside Table
                                               <br><br>"),

# Result's Table Display Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Header Tile Config"),

                    array("column"         => "config_3",
                           "required"      => 0,
                           "title"         => "Set Font Color [Top Header Text]",
                           "type"          => "TEXT",
                           "size"          => 8,
                           "maxlength"     => 255,
                           "swatch"        => 1,
                           "append"        => "This is the font color for the text above the navigation table.
                                               <br><br>"),

                    array("column"         => "config_23",
                           "required"      => 0,
                           "title"         => "Set Font Color [Heading Text]",
                           "type"          => "TEXT",
                           "size"          => 8,
                           "maxlength"     => 255,
                           "swatch"        => 1,
                           "append"        => "This is the font color of the section headings.
                                               <br><br>"),

                    array("column"         => "config_8",
                           "required"      => 0,
                           "title"         => "Set Tile Background Color [Default Cell]",
                           "type"          => "TEXT",
                           "size"          => 8,
                           "maxlength"     => 255,
                           "swatch"        => 1),

                    array("column"         => "config_9",
                           "required"      => 0,
                           "title"         => "Set Tile Background Color [Active Cell]",
                           "type"          => "TEXT",
                           "size"          => 8,
                           "maxlength"     => 255,
                           "swatch"        => 1),

                    array("column"         => "config_22",
                           "required"      => 0,
                           "title"         => "Set Box Title Row Background Color",
                           "type"          => "TEXT",
                           "size"          => 8,
                           "maxlength"     => 255,
                           "swatch"        => 1),

# Image Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Image Configuration"),

                    array("column"         => "config_12",
                           "required"      => 0,
                           "title"         => "Set Image [Your Main Logo]",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the relative path to your logo image.
                                               <br>The width should be no wider than 157 pixels.
                                               <br><br>"),

                    array("column"         => "config_14",
                           "required"      => 0,
                           "title"         => "Set Image [Delete Icon]",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the relative path to your delete icon.
                                               <br>The width should be no wider than 16 pixels.
                                               <br><br>"),

                    array("column"         => "config_15",
                           "required"      => 0,
                           "title"         => "Set Image [Edit Icon]",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the relative path to your edit icon.
                                               <br>The width should be no wider than 16 pixels.
                                               <br><br>"),

                    array("column"         => "config_16",
                           "required"      => 0,
                           "title"         => "Set Image [Descending Icon]",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the relative path to your descending icon.
                                               <br>The width should be no wider than 9 pixels.
                                               <br><br>"),

                    array("column"         => "config_17",
                           "required"      => 0,
                           "title"         => "Set Image [Ascending Icon]",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the relative path to your ascending icon.
                                               <br>The width should be no wider than 9 pixels.
                                               <br><br>"),

                    array("column"         => "config_25",
                           "required"      => 0,
                           "title"         => "Set Image [Background Graphic]",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255,
                           "append"        => "<br>Enter the relative path to your background image.
                                               <br>Leave blank if you do not wish to have a background image.
                                               <br><br>"),

# Font Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "Font Configuration"),

                    array("column"         => "config_18",
                           "required"      => 0,
                           "title"         => "Set Default Font Face",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255),

                    array("column"         => "config_19",
                           "required"      => 0,
                           "title"         => "Set Font Size [Large]",
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 2),

                    array("column"         => "config_21",
                           "required"      => 0,
                           "title"         => "Set Font Size [Medium]",
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 2),

                    array("column"         => "config_20",
                           "required"      => 0,
                           "title"         => "Set Font Size [Small]",
                           "type"          => "TEXT",
                           "size"          => 2,
                           "maxlength"     => 2),

                    array("column"         => "config_24",
                           "required"      => 0,
                           "title"         => "Set Sub-Heading Font Color",
                           "type"          => "TEXT",
                           "size"          => 7,
                           "swatch"        => 1,
                           "maxlength"     => 255,
                           "append"        => "This is the Sub-Heading text that divides each section.
                                               <br><br>"),

# HTML & Style Config #######################################################################################
                    array("type"           => "HEADERROW",
                           "title"         => "HTML & Style Configuration"),

                    array("column"         => "config_10",
                           "required"      => 0,
                           "title"         => "Set Title Bar Text",
                           "type"          => "TEXT",
                           "size"          => 40,
                           "maxlength"     => 255),

                    array("column"         => "config_11",
                           "required"      => 0,
                           "title"         => "Set Body Background Color",
                           "type"          => "TEXT",
                           "size"          => 8,
                           "maxlength"     => 255,
                           "swatch"        => 1),

                    array("column"         => "config_50",
                           "required"      => 0,
                           "title"         => "Set Style Sheet Definitions",
                           "type"          => "TEXTAREA",
                           "rows"          => 20,
                           "cols"          => 80,
                           "wrap"          => $textarea_wrap));

?>