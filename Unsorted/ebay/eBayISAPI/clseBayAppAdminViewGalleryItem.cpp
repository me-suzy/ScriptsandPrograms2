//
//	File:		clseBayAppAdminViewGalleryItem.cpp
//
//	Class:		clseBayApp
//
//	Author:		pete helme <pete@ebay.com>
//
//	Function:
//
//
//	Modifications:
//				- 01/14/99 pvh	- Created
//				- 07/19/99 nsacco	- Switched from mpMarketPlace->GetName() to GetCurrentPartnerName()
//
#include "ebihdr.h"


void clseBayApp::AdminGalleryItemView(CEBayISAPIExtension *pServer,
									  int	itemId,
									  eBayISAPIAuthEnum authLevel)
{
	SetUp();
	
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}
	
	// Get the item to see if it;s for real
	GetAndCheckItem(itemId);
	
	if (!mpItem)
		goto leave;
	
	// Heading, etc
	*mpStream <<	"<html><head><title>"
		<<	mpMarketPlace->GetCurrentPartnerName()
		<<	" Administrative Gallery View Item"
		<<	"</title></head>"
		<< mpMarketPlace->GetHeader()
		<< "\n"
		<< flush;
	
	*mpStream <<    "<h2>View Gallery picture for item"	
		<< "</h2>";
	
	// give a little bit of info
/*	*mpStream << "<table border=\"0\" width=\"100%\" height=\"185\">"
		<< "<tr>"
		<< "<td width=\"25%\" height=\"19\">Item number:</td>"
		<< "<td width=\"75%\" height=\"19\">"
		<<	mpItem->GetId()
		<< "</td></tr>"
		// Title
		<< "<tr>"
		<< "<td width=\"25%\" height=\"19\">Title:</td>"
		<< "<td width=\"75%\" height=\"19\">"
		<<	mpItem->GetTitle()
		<< "</td></tr>"
		// User Id
		<< "<tr>"
		<< "<td width=\"25%\" height=\"19\">User Id: </td>"
		<< "<td width=\"75%\" height=\"19\">"
		<<	mpItem->GetSellerUserId()
		<< "</td></tr>"
		// email address
		<< "<tr>"
		<< "<td width=\"25%\" height=\"19\">email address:</td>"
		<< "<td width=\"75%\" height=\"19\">"
		<<	mpItem->GetSellerEmail()
		<< "</td></tr>"
		<< "</table>"		
		<< "<br>";
*/
	*mpStream << "<b>Item number: </b>"
		<< mpItem->GetId()
		<< "<br>";

	*mpStream << "<b>Title: </b>"
		<< mpItem->GetTitle()
		<< "<br>";

	*mpStream << "<b>User Id: </b>"
		<< mpItem->GetSellerUserId()
		<< "<br>";

	*mpStream << "<b>email address: </b>"
		<< mpItem->GetSellerEmail()
		<< "<br><br>";

	// Image
	// now get the thumbnail image and display it
	*mpStream << "<table border=\"0\" width=\"100%\" >"
		<< "<tr>"
		<< "<td align=center>Thumbnail</td><td align=center>Original Image</td>"				
		<< "</tr><tr>"
		<< "<td align=center><img src=\"http://thumbnails.ebay.com/pict/"
		<<	itemId
		<< ".jpg\"></td>";
	
	// get the image form the user's URL
	*mpStream << "<td align=center><img src=\"";
	
	// do we have a gallery image?
	if(mpItem->GetGalleryURL() == NULL)
		*mpStream << "";
	else	
		*mpStream << mpItem->GetGalleryURL();
	
	*mpStream		<< "\"></td>"
		<< "</tr>"
		<< "</table>"		
		<< flush;
	
	// form for deletion
	*mpStream << "<br><form method=\"POST\" action=\""
		<< mpMarketPlace->GetAdminPath() // PageGalleryItemDeleteConfirm
		<<	"eBayISAPI.dll?\">\n"
		"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AdminGalleryItemDeleteConfirm\""
		<< "\">\n<input TYPE=\"hidden\" NAME=\"item\" VALUE=\""
		<< itemId
		<< "\">\n"
		"<b> Click "
		"<input type=\"submit\" value=\"here\"> "
		"to delete the Gallery picture for this item. </b>\n"
		"</form>\n";
	
	*mpStream << "<hr><p>Look at another Gallery item:</p>";
	
	// form for getting a new item
	*mpStream << "<form method=\"POST\" action=\""
		<< mpMarketPlace->GetAdminPath() // PageGalleryItemDelete
		<< "eBayISAPI.dll?\">"
		<< "<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AdminGalleryItemView\""
		<< "\"><input type=\"text\" name=\"item\" size=\"20\">"
		<< "<b> Click "
		<< "<input type=\"submit\" value=\"here\"> "
		<< "to look at this item. </b>\n"
		<< "</form>\n";

	// return to support page
	*mpStream << "<a href=\""
		<< mpMarketPlace->GetAdminPath() 
		<< "support.html\">return to support page</a>";


leave:
	*mpStream <<	"<p>"
		<<	mpMarketPlace->GetFooter()
		<< flush;
	
	CleanUp();
	return;
}

void clseBayApp::AdminGalleryItemDeleteConfirm(CEBayISAPIExtension *pServer,
											   int	itemId,
											   eBayISAPIAuthEnum authLevel)
{
	SetUp();
	
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}
	
	// Get the item to see if it;s for real
	GetAndCheckItem(itemId);
	
	if (!mpItem)
		goto leave;
	
	
	// put up the confirmation message
	// Heading, etc
	*mpStream <<	"<html><head><title>"
		<<	mpMarketPlace->GetCurrentPartnerName()
		<<	" Administrative Gallery Delete picture confirmation"
		<<	"</title></head>"
		<< mpMarketPlace->GetHeader()
		<< "\n"
		<< flush;
	
	*mpStream <<    "<h2>Confirmation to delete Gallery picture item : "	
		<<	itemId
		<< "</h2>";
	
	
	// delete it
	// http://pete.corp.ebay.com/ed/thumbServe.dll?MakeNoisy&item=550
	// http://thumbserve.ebay.com/ed/thumbServe.dll?MakeNoisy&item=550
/*	*mpStream << "<form method=\"POST\" action=\""
		<< "http://pete.corp.ebay.com/ed/thumbServe.dll?\">"
		<< "<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"MakeNoisy"
		<< "\"><input type=\"hidden\" name=\"item\" value=\""
		<< itemId
		<< "\">"
		<< "<b> Click "
		<< "<input type=\"submit\" value=\"here\"> "
		<< "to DELETE this Gallery picture. </b>\n"
		<< "</form>\n";
*/
	*mpStream << "<form method=\"POST\" action=\""
		<< mpMarketPlace->GetAdminPath() // PageGalleryItemDelete
		<< "eBayISAPI.dll?\">"
		<< "<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AdminGalleryItemDelete\""
		<< "\"><input type=\"hidden\" name=\"item\" value=\""
		<< itemId
		<< "\">"
		<< "<b> Click "
		<< "<input type=\"submit\" value=\"here\"> "
		<< "to DELETE this Gallery picture. </b>\n"
		<< "</form>\n";
	
	// go back
	*mpStream << "<form method=\"POST\" action=\""
		<< mpMarketPlace->GetAdminPath() // PageGalleryItemView
		<< "eBayISAPI.dll?\">"
		<< "<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AdminGalleryItemView\""
		<< "\"><input type=\"hidden\" name=\"item\" value=\""
		<< itemId
		<< "\">"
		<< "<b> Click "
		<< "<input type=\"submit\" value=\"here\"> "
		<< "to NOT delete this picture and return to its Gallery view. </b>\n"
		<< "</form>\n";
	
	
leave:
	*mpStream <<	"<p>"
		<<	mpMarketPlace->GetFooter()
		<< flush;
	
	CleanUp();
	return;
}

//
// delete gallery picture form thumb database by signalling GetItems
// signal Thumbserve to not show this item, until it is deleted
//
void clseBayApp::AdminGalleryItemDelete(CEBayISAPIExtension *pServer,
						     	        CHttpServerContext *pCtxt,
										int	itemId,
										eBayISAPIAuthEnum authLevel)
{
	char newURL[255];
	
	SetUp();
	
	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp(); 
		return;
	}
	
	// Get the item to see if it;s for real
	GetAndCheckItem(itemId);
	
	if (!mpItem)
		goto leave;

	// first we'll call AppendGalleryChangedItem to flag this item to be removed on the next update
	{
		clsGalleryChangedItem item;
		
		item.mID = itemId;
		item.mSequenceID = gApp->GetDatabase()->GetNextGallerySequence();
//		strcpy(item.mURL, "");
		item.mState = UnGallery;
		item.mStartTime = 0;	// set both start & end times to 0 to flag that this item should be removed
		item.mEndTime = 0;		// set both start & end times to 0 to flag that this item should be removed
		item.mAttempts = -1;
		item.mLastAttempt = time(NULL);

		bool appendResult = gApp->GetDatabase()->AppendGalleryChangedItem(item);
	}

	// redirect to the thumbserve(r) to add this item to the live "noise photo" list
	sprintf(newURL, "http://pete.corp.ebay.com/ed/thumbServe.dll?MakeNoisy&item=%d", itemId);
	pServer->EbayRedirect(pCtxt, newURL);

/*	
	// put up the confirmation message
	// Heading, etc
	*mpStream <<	"<html><head><title>"
		<<	mpMarketPlace->GetCurrentPartnerName()
		<<	" Administrative Gallery Delete picture"
		<<	"</title></head>"
		<< mpMarketPlace->GetHeader()
		<< "\n"
		<< flush;
	
	*mpStream <<    "<h2>Gallery picture for item: "	
		<<	itemId
		<< " </h2>";
	
	
	// form for getting a new item
	*mpStream << "<form method=\"POST\" action=\""
		<< mpMarketPlace->GetAdminPath() // PageGalleryItemDelete
		<< "eBayISAPI.dll?\">"
		<< "<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"AdminGalleryItemView\""
		<< "\"><input type=\"text\" name=\"item\" size=\"20\">"
		<< "<b> Click "
		<< "<input type=\"submit\" value=\"submit\"> "
		<< "to look at this item. </b>\n"
		<< "</form>\n";
*/	
	
leave:
	*mpStream <<	"<p>"
		<<	mpMarketPlace->GetFooter()
		<< flush;
	
	CleanUp();
	return;
}
