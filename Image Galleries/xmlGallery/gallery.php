<?

////////////////////////////////////////////////////////////
//
// xmlGallery v1.0 - a one-page image gallery
//
////////////////////////////////////////////////////////////
//
// This script allows you to display images in a gallery.
//
// See readme.txt for more information.
//
// Author: Jon Thomas <http://www.fromthedesk.com/code>
// Last Modified: 04/26/2003
//
// You may freely use, modify, and distribute this script.
//
////////////////////////////////////////////////////////////

//
// DEFINE VARIABLES
//

$xmlFile = "images.xml";
$displayOption = 1; // 1 for table; 2 for linear presentation
$imagesPerRow = 2;
$templateFile = "template.txt";

//
// GET XML DATA
//

// get XML data
$data = implode("", file($xmlFile));

// create XML parser
$parser = xml_parser_create();

// set parser options
xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);

// parse XML data into arrays
xml_parse_into_struct($parser, $data, $values, $tags);

// free parser
xml_parser_free($parser);

// set the counter variable for the images array
$imageNo = 0;

// cycle through parsed XML data
for ($i = 0; $i < count($values); $i++) {
	// if a FILENAME tag, put the value in the images array
	if ($values[$i][tag] == "FILENAME") {
		$images[$imageNo]['filename'] = $values[$i][value];
	}

	// if a CAPTION tag, put the value in the images array
	if ($values[$i][tag] == "CAPTION") {
		$images[$imageNo]['caption'] = $values[$i][value];

		// increment the quotes counter variable
		$imageNo++;
	}
}

//
// INCLUDE FILES
// 

// selectText() function
include("selectText.inc");

// replaceArrStrs() function
include("replaceArrStrs.inc");

//
// PRINT GALLERY
//

// get the image template
$imgTemplate = selectText("template.txt", "<!--BEGIN-->", "<!--END-->", 0);

// option 1: display gallery as HTML table
if ($displayOption == 1) {
	// begin table
	$gallery = "<table>\n";

	// set column counter to 0
	$columnNo = 0;

	// for each image
	for ($i = 0; $i < count($images); $i++) {
		// replace template code with data
		$imgCode = preg_replace("/<!--IMAGE-->/", $images[$i]['filename'], $imgTemplate);
		$imgCode = preg_replace("/<!--CAPTION-->/", $images[$i]['caption'], $imgCode);

		// if image in the first column
		if ($columnNo == 0) {
			$imgCode = "<tr>\n\t<td>\n" . $imgCode . "\n\t</td>\n";
			$columnNo++;
		}

		// if image in the last column
		elseif ($columnNo == ($imagesPerRow - 1)) {
			$imgCode = "\t<td>\n" . $imgCode . "\n\t</td>\n</tr>\n";
			$columnNo = 0;
		}

		// if image in middle columns
		else {
			$imgCode = "\t<td>\n" . $imgCode . "\n\t</td>\n";
			$columnNo++;
		}

		// add data to HTML
		$gallery .= $imgCode;
	}

	// close row if last image not in last column
	if ($columnNo != 0) {
		while ($columnNo < $imagesPerRow) {
			$gallery .= "\t<td></td>\n";
			$columnNo++;
		}

		$gallery .= "</tr>\n";
	}

	// close table
	$gallery .= "</table>\n";
}

// option 2: display gallery using linear presentation
if ($displayOption == 2) {
	// for each image
	for ($i = 0; $i < count($images); $i++) {
		// replace template code with data
		$html = preg_replace("/<!--IMAGE-->/", $images[$i]['filename'], $imgTemplate);
		$html = preg_replace("/<!--CAPTION-->/", $images[$i]['caption'], $html);

		// add data to HTML
		$gallery .= $html;
	}
}

// get the page template
$page = file($templateFile);

// replace image template with gallery code
$page = replaceArrStrs($page, "<!--BEGIN-->", "<!--END-->", 1, $gallery);

// print HTML code
echo implode("", $page);

?>