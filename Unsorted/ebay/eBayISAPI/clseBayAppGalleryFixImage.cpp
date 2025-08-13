/* $Id: clseBayAppGalleryFixImage.cpp,v 1.2.390.1 1999/08/01 03:01:14 barry Exp $ */
/*
 *	File:		clseBayAppGalleryFixImage.cpp
 *
 *	Class:		clseBayApp
 *
 *	Author:		Barry Boone (barry@ebay.com)
 *
 *	Function:	Guide a user through fixing a Gallery image.
 *
 *	Modifications:
 *				- 01/18/99 barry - Created

 */
//
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"

// ISAPI function to display the initial UI. This is not a static page so that
// we can prefill some fields (item number and seller's User Id) if we know
// them in context.
void clseBayApp::DisplayGalleryImagePage(CEBayISAPIExtension *pServer,
									     int item)
{

	SetUp();

	if (item != 0)
	{
		// Let's try and get the item
		if (!GetAndCheckItem(item))
		{
			CleanUp();
			return;
		}	
	}

	// Heading, etc
	*mpStream << "<HTML>"
				 "<HEAD>"
				 "<title>"
			  << mpMarketPlace->GetCurrentPartnerName()
			  << " Fix Your Gallery Image"
				 "</title>"
				 "</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>Fix Your Gallery Image </h2>\n";


	*mpStream	<<	"<font size=\"3\">" 
					"If the image you supplied when you listed an item "
					"is not appearing in the Gallery, use this form to "
					"enter a link to a new Gallery image."
					"</font>"; 


	*mpStream	<< "<font size=\"3\">" 
				<< "For your protection, you need to supply your item number, your registered User ID and password.  "
				<< " <I>Thank you!</I>";

	// form
	*mpStream	<<	"<form method=\"POST\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageEnterNewGalleryImage)
				<<	"eBayISAPI.dll\">\n"
					"<input TYPE=HIDDEN NAME=\"MfcISAPICommand\" "
					"VALUE=\"EnterNewGalleryImage\">\n";

	*mpStream	<<	"<table><tr><td>\n"
					"Your Item No: "
					"</td>"
					"<td><input type=\"text\" name=\"item\" size=40 ";

	// Prefill the item number if we can.
	if (item != 0)
	{
		*mpStream	<<	"value=\""
					<<	item
					<<	"\"\n ";
	}

	*mpStream	<<	"</td></tr>\n"
					"<tr><td>\n"
					"Your  "
				<<	mpMarketPlace->GetLoginPrompt()
				<<	": </td>"
				<<  "<td><input type=\"text\" name=\"userid\" size=40 ";
	
	// Determine whether there is requested user id
	if (item != 0)
	{
		// Let's try and get the item
		if (!GetAndCheckItem(item))
		{
			CleanUp();
			return;
		}	 

		*mpStream	<<	"value=\""
					<<	mpItem->GetSellerUserId()
					<<	"\"\n";
	}

	*mpStream	<<	"></td></tr>\n"
					"<tr><td>Your "
				<<	mpMarketPlace->GetPasswordPrompt()
				<<	":</td>\n"
					"<td><input type=\"password\" name=\"password\" size=40></td></tr>\n";

	// close the table
	*mpStream	<<	"</table>\n";


	// add submit button and finish the form
	*mpStream	<<	"<p><input type=\"submit\" value=\"Fix Your Gallery Image\"></p>\n"
					"</form>\n";


	// the footer
	*mpStream << mpMarketPlace->GetFooter()
				 << flush;

	CleanUp();
	return;
}

// The form that initiates fixing a Gallery image URL invokes this function via ISAPI.
void clseBayApp::EnterNewGalleryImage(CEBayISAPIExtension *pServer,
									  char *pUserId,
									  char *pPassword,
									  int item)
{
	clsItem *pItem = NULL;
	int      galleryType;

    SetUp();

	// Heading, etc
	*mpStream << "<HTML>"
				 "<HEAD>"
				 "<title>"
			  << mpMarketPlace->GetCurrentPartnerName()
			  << " Supply a New Gallery Image"
				 "</title>"
				 "</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>Change Your Gallery Image </h2>\n";

	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPassword, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Display the editing page, with the seller's Gallery URL filled in
	// (if they have one!), along with other item information.


	pItem = mpItems->GetItem(item, false); // Get item without description here, since we won't be updating


	// Make sure this item is valid.
	if (!pItem)
	{
		*mpStream <<    "Sorry, the item number you entered, "
			      <<    item
				  <<    ", is not valid. Please enter a new item number and "
				  <<    "try again.";

		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Make sure the user has put this into the Gallery in the first place.
	galleryType = pItem->GetGalleryType();

	if (galleryType == NoneGallery)
	{
		*mpStream <<    "Sorry, the item number you entered, "
			      <<    item
				  <<    ", was not entered into the Gallery at the time you listed "
				  <<    "the item.";

		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		delete pItem;
		CleanUp();
		return;
	}

	*mpStream <<
		   "<form method=\"post\" action=\""
		<< mpMarketPlace->GetCGIPath(PageEnterNewGalleryImage)
		<< "eBayISAPI.dll\">"
		   "<input type=hidden name=\"MfcISAPICommand\" value=\"FixGalleryImage\"> \n"
		   "<input type=hidden name=\"userid\" value=\""
		<< pUserId
		<< "\">"
		   "<input type=hidden name=\"password\" value=\""
		<< pPassword
		<< "\">"
		   "<input type=hidden name=\"item\" value=\""
		<< item
		<< "\"> \n";		   

	// Theoretically this is not possible...
	if (!pItem->GetGalleryURL())
	{
		*mpStream << "\n You have not provided a URL for your Gallery image. \n";
	}

	*mpStream <<
		"<table border=1 width=\"75%\">"
		"<tr>"
		"<td bgcolor=\"99cccccc\"><b>The URL for your Gallery image:</b></td>"
		"<td>"
		"<input type=\"text\" name=\"url\" size=40 maxlength=255 ";
		
	if (pItem->GetGalleryURL())	
	{
		*mpStream << "value=\""
				  << pItem->GetGalleryURL()
			      << "\"";
	}

	*mpStream <<
		"> </td>"
		"</tr>"
		"<tr>"
		"<td bgcolor=\"99cccccc\"><b>Gallery status:</b></td>"
		"<td>";
	
	*mpStream << pItem->GetGalleryStateMessage();
	
	*mpStream <<
		"</td>"
		"</tr>"
		"</table> \n";

	if (pItem->GetGalleryURL())
	{
		*mpStream << "<p>This image is displayed below:</p>"
					 "<br><img src=\""
				  << pItem->GetGalleryURL()
				  << "\""
					 "<br>"
					 "<ul>"
					 "<li>If your image appears as a \'broken image\', it probably means you typed "
					 "the URL for your image incorrectly. "
					 "<li>If your browser eventually displays a message "
					 "that it could not connect with a server, it probably means the server where your "
					 "picture lives is not responding, and we probably could not retrieve your image "
					 "to create a thumbnail image of it."
					 "<li>If you can see your image just fine on this page, but it does not appear correctly (or at all) "
					 "in the Gallery, click \'update my image\' below and we will attempt to "
					 "retrieve it again."
					 "</ul>";
	}
    *mpStream <<
		"<p>To change your Gallery image, enter a new URL above, then click "
		"<input type=\"submit\" value=\"update my image\">"
		"</p>"
		"</form> \n";

    *mpStream << mpMarketPlace->GetFooter();

	if (pItem)
		delete pItem;

    CleanUp();
}

// The final page that actually updates the Gallery image URL.
void clseBayApp::FixGalleryImage(CEBayISAPIExtension *pServer,
								 char *pUserId,
								 char *pPassword,
								 int item,
								 char *pURL)
{
	clsItem *pItem = NULL;
	int galleryType;

	SetUp();

	// Heading, etc
	*mpStream << "<HTML>"
				 "<HEAD>"
				 "<title>"
			  << mpMarketPlace->GetCurrentPartnerName()
			  << " Your New Gallery Image"
				 "</title>"
				 "</head>"
			  << mpMarketPlace->GetHeader()
			  << "\n"
			  << flush;

	// header
	*mpStream	<<	"<h2>Your New Gallery Image </h2>\n";

	mpUser = mpUsers->GetAndCheckUserAndPassword(pUserId, pPassword, mpStream);
	if (!mpUser)
	{
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}


	pItem = mpItems->GetItem(item, true); // Get item with description here, since we will be updating


	// Make sure this item is valid.
	if (!pItem)
	{
		*mpStream <<    "Sorry, the item number you entered, "
			      <<    item
				  <<    ", is not valid. Please enter a new item number and "
				  <<    "try again.";

		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// Make sure the user has put this into the Gallery in the first place.
	galleryType = pItem->GetGalleryType();

	if (galleryType == NoneGallery)
	{
		*mpStream <<    "Sorry, the item number you entered, "
			      <<    item
				  <<    ", was not entered into the Gallery at the time you listed "
				  <<    "the item.";

		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();

		delete pItem;
		CleanUp();
		return;
	}

	// There is some basic checking we can do, even though we can't do any verification
	// on the type of file they entered.
	if (!pURL || strlen(pURL) < 7 || strncmp(pURL, "http://", 7))
	{
		*mpStream << "<p>The URL you entered for your Gallery image does not seem to be valid. Make sure "
			         "your URL starts with \'http://\' "
					 "<p>Please go back to the previous page and enter the URL for your Gallery image again.";

		delete pItem;
		CleanUp();
		return;
	}


	// Now update the Gallery URL in the item.
	pItem->SetGalleryURL(pURL);
	pItem->UpdateItem();


	// Note: We still want to process the image, even if the user
	// did not update it at all, because the error might have been
	// that the Web server serving the image was down and we could
	// not get it the first time. This triggers us to try to get it
	// now.

	// Update the image.
	
	clsGalleryChangedItem objItem;
		
	objItem.mID          = pItem->GetId();
	objItem.mSequenceID  = gApp->GetDatabase()->GetNextGallerySequence();
	strcpy(objItem.mURL, pURL);
	objItem.mState       = 0;
	objItem.mStartTime   = pItem->GetStartTime();
	objItem.mEndTime     = pItem->GetEndTime();
	objItem.mAttempts    = -1;
	objItem.mLastAttempt = time(NULL);

	// Called program uses a reference...
	bool appendResult = gApp->GetDatabase()->AppendGalleryChangedItem(objItem);

	// Display a page where they know they are done and what 
	// will happen next.
	*mpStream <<
		"<p>You have changed your Gallery URL to:</p>"
		"<p><b>"
	 << pURL
	 << "</b></p>"
	 << "<p>This image is displayed below:</p>"
	    "<br><img src=\""
	 << pURL
	 << "\""
	    "<br>";

	*mpStream <<
		"<p>We will now work to retrieve this image, create a thumbnail, and add it to "
		"the Gallery. (It may take a few hours to update the Gallery image.)</p> "
		"<p>You might want to <a href=\""
	 << mpMarketPlace->GetHTMLPath()
	 << "gallery.html\">go to the Gallery </a>or return to the <a href=\""
	 << mpMarketPlace->GetCGIPath(PageDisplayGalleryImagePage)
	 << "eBayISAPI.dll?DisplayGalleryImagePage&item="
	 << item	 
	 << "\">image editing page</a>.</p>";

    *mpStream << mpMarketPlace->GetFooter();

	delete pItem;
    CleanUp();
}

