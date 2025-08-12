<?php
//============================================================+
// File name   : tce_pdf.php                                    
// Begin       : 2004-06-11                                    
// Last Update : 2005-01-03                                   
//                                                             
// Description : Configuration file for pdf documents.
//                                                             
//                                                             
// Author: Nicola Asuni                                        
//                                                             
// (c) Copyright:                                              
//               Tecnick.com S.r.l.                            
//               Via Ugo Foscolo n.19                          
//               09045 Quartu Sant'Elena (CA)                  
//               ITALY                                         
//               www.tecnick.com                               
//               info@tecnick.com                              
//============================================================+

/**
 * Configuration file for pdf documents.
 * @author Nicola Asuni
 * @copyright Copyright &copy; 2004, Tecnick.com S.r.l. - Via Ugo Foscolo n.19 - 09045 Quartu Sant'Elena (CA) - ITALY - www.tecnick.com - info@tecnick.com
 * @link www.tecnick.com
 * @since 2004-06-11
 */


define ("PDF_PAGE_FORMAT", "A4"); // page format
define ("PDF_PAGE_ORIENTATION", "P"); // page orientation (P=portrait, L=landscape)
define ("PDF_AUTHOR", "TCExam 2.0"); // document author

define ("PDF_HEADER_TITLE", "University name"); // header title
define ("PDF_HEADER_STRING", "first row\nsecond row\nthird row"); // header description string
define ("PDF_HEADER_LOGO", "logo_example.png"); // image logo
define ("PDF_HEADER_LOGO_WIDTH", 20); // header logo image width [mm]

define ("PDF_UNIT", "mm"); // document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch]
define ("PDF_MARGIN_HEADER", 5); // header margin
define ("PDF_MARGIN_FOOTER", 10); // footer margin
define ("PDF_MARGIN_TOP", 27); // top margin
define ("PDF_MARGIN_BOTTOM", 30); // bottom margin
define ("PDF_MARGIN_LEFT", 15); // left margin
define ("PDF_MARGIN_RIGHT", 15); // right margin

define ("PDF_FONT_NAME_MAIN", "verase"); // main font name
define ("PDF_FONT_SIZE_MAIN", 10); // main font size
define ("PDF_FONT_NAME_DATA", "vera"); // data font name
define ("PDF_FONT_SIZE_DATA", 8); // data font size

define ("PDF_TEXTANSWER_HEIGHT", 40); // height of area for offline user answer

define ("PDF_IMAGE_SCALE_RATIO", 4); // scale factor for images (number of points in user unit)

//============================================================+
// END OF FILE                                                 
//============================================================+
?>
