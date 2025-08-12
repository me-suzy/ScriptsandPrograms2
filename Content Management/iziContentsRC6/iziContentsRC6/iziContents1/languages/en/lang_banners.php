<?php

//  Form Title
$GLOBALS["tFormTitle"] = 'maintain banners';

//  List Headings
$GLOBALS["tURL"] = 'URL';
$GLOBALS["tImpressions"] = 'Impressions';
$GLOBALS["tClicks"] = 'Clicks';

//  List Functions
$GLOBALS["tAddNewBanner"] = 'Add new banner';
$GLOBALS["tViewBanner"] = 'View banner';
$GLOBALS["tEditBanner"] = 'Edit banner';
$GLOBALS["tDeleteBanner"] = 'Delete banner';

//  Form Block Titles
$GLOBALS["thDetails"] = 'Banner Display Details';
$GLOBALS["thStatus"] = 'Status';
$GLOBALS["thLog"] = 'Logging';

//  Form Field Headings
$GLOBALS["tTarget"] = 'Remote URL';
$GLOBALS["tAltText"] = 'Image alt text';
$GLOBALS["tPublishDate"] = 'Publication Date';
$GLOBALS["tExpireDate"] = 'Expiry Date';
$GLOBALS["tBannerImage"] = 'Banner Image';
$GLOBALS["tBannerHTML"] = 'Banner HTML';

//  Other Form Headings
$GLOBALS["tShowBanner"] = 'Show banner';

//  Form Detail Comments and Help Texts
$GLOBALS["tDetails"] = 'This form lets you maintain the banners that can appear on your page.';
$GLOBALS["hTarget"] = 'The target url after clicking on the banner.<br />(Always include the \"http:{groups';
$GLOBALS["hAltText"] = 'Alt text for the banner image.';
$GLOBALS["hPublishDate"] = 'Publication date of the banner. It will not be selected for display prior to this date.';
$GLOBALS["hExpireDate"] = 'Expiry date of the banner. It will not be selected for display once this date has passed.';
$GLOBALS["hEnabled"] = 'If this is set to \"No\" the banner will never be selected.';
$GLOBALS["hImpressions"] = 'The number of times that this banner has been displayed. Use this field to reset the value.';
$GLOBALS["hClicks"] = 'The number of times that someone has clicked on the banner. Use this field to reset the figure.';
$GLOBALS["hBannerImage"] = 'Specify the filename for the banner image here.<br />Alternatively, you can specify HTML code in the Banner HTML field to display the banner; but not both.';
$GLOBALS["hBannerHTML"] = 'Here you can specify the html to a location or image on the web instead an uploaded image. e.g.<br />&lt;A HREF=&quot;http:{groups';

//  Error Messages
$GLOBALS["eNoURL"] = 'Target URL can not be left empty.';
$GLOBALS["eImpressionsNum"] = 'Impressions value must be numeric.';
$GLOBALS["eClicksNum"] = 'Clicks value must be numeric.';
$GLOBALS["eNoImage"] = 'An image or image URL must be specified.';
$GLOBALS["eBothImageHTML"] = 'Specify just the image or the banner html, not both.';

?>
