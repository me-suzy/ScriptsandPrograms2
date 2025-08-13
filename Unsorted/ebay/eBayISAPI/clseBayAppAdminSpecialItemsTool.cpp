/*	$Id: clseBayAppAdminSpecialItemsTool.cpp,v 1.1.6.6 1999/06/07 22:45:22 jpearson Exp $	*/
//
//	File:		clseBayAppAdminSpecialItemsTool.cpp
//
//	Class:		clseBayApp
//
//	Author:		Jennifer Pearson (jen@ebay.com)
//
//	Function:	clseBayApp::clseBayAppAdminSpecialItemsTool
//
//
//	Modifications:
//				- 05/21/99 jennifer - Created

//	Allow admin to view, add, delete items to ebay_special_items talbe.
//  This table contains black listed items, staff picks items, and approved
//  gallery items


#include "ebihdr.h"

#define		STAFF_PICKS		1
#define		BLACK_LISTED	2
#define		GALLERY			3

void clseBayApp::AdminSpecialItemsTool(CEBayISAPIExtension *pThis,
									   eBayISAPIAuthEnum authLevel)
{
	clsMessage				*pMessage = NULL;
	bool					error = false;
	bool					bNote = false;

	clsItem					*pItem = NULL;

	time_t					endTime;
	struct	tm				*pEndTime;
	char					cEndTime[32];
	char					*pCategory;
	int						i, vectorSize, kind;

	vector<int>				vItemIds;


	// Setup
	SetUp();	

	// Title
	EmitHeader("Admin Tool - Pick Out Special Items");

	// Title using black on darkgrey table
	*mpStream <<	"<p><center>"
					"<table border cellspacing=0 "
					"width=100% bgcolor=#99CCCC>"
					"<tr>"
					"<td align=center width=100%>"
					"<font size=4 face=Arial color=#000000>"
					"<strong>"
					"Update the Approved Gallery Pool, or "
					"Black Listed, or Staff Picks"
					"</strong></font></td>\n"
					"</tr>"
					"</table>"
					"</center>\n";


	// Spacer
	*mpStream <<	"<br>\n";



	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// Display the use of this page

	*mpStream	<<	"<b>This tool allows Marketing/Customer Support "
					"to:</b>\n"
					"<ul><li>\n"
					"Mark items as Approved Gallery (if an item is marked as this "
					"kind it will be randomly selected to be displayed on the "
					"home page);</li>\n"
					"<li> Mark items as Black Listed; </li>"
					"<li> Mark items as Staff Picks. </li> </ul>\n";

	// Add an item
	*mpStream	<<	"<font color=\"#A52A2A\"><b>Add an Item:</b></font>";

	// Use table for formating
	*mpStream	<<	"<table><tr><td>Enter item number</td>\n"
					"<td>Select kind </td><td></td><td></td></tr>\n";

	// Start Add form
	*mpStream	<<	"<form method=\"get\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminSpecialItemAdd)
				<<	"eBayISAPI.dll\">\n"
				<<  "<input type=\"hidden\" name=\"MfcISAPICommand\" "
				<<	"value=\"AdminSpecialItemAdd\">\n";

	// Get the item number
	*mpStream	<<	"<tr>"
					"<td><input type=\"text\" name=\"itemno\" size=\"24\" "
					"maxlength=\"20\"</td>\n";

	// Get the kind
	*mpStream	<<	"<td><select name=\"kind\" size=1>\n"
					"<option value=3 selected>Approved Gallery\n"
					"<option value=1>Staff Picks\n"
					"<option value=2>Black Listed\n"
					"</selected></td>\n"
					"<td></td>\n"
					"<td><input type=\"submit\" value=\"Add\"></td></tr>\n"
					"</table></form>\n";
			
	// Delete an item
	*mpStream	<<	"<font color=\"#A52A2A\"><b>Delete an Item:</b></font>\n";

	// Use table for formating
	*mpStream	<<	"<table><tr><td>Enter item number</td>\n"
					"<td></td><td></td><td></td><td></td><td></td>"
					"<td></td><td></td></tr>\n";

	// Start Delete form
	*mpStream	<<	"<form method=\"get\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminSpecialItemDelete)
				<<	"eBayISAPI.dll\">\n"
				<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" "
				<<	"value=\"AdminSpecialItemDelete\">\n";

	// Get the item number
	*mpStream	<<	"<tr>"
					"<td><input type=\"text\" name=\"itemno\" size=\"24\" "
					"maxlength=\"20\"</td>\n";

	// Delete button
	*mpStream	<<	"<td></td>\n"
					"<td><input type=\"submit\" value=\"Delete\"></td>"
					"<td></td><td></td><td></td><td></td>\n"
					"</form>\n";

	// Start Flush form
	*mpStream	<<	"<form method=\"get\" action=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminSpecialItemFlush)
				<<	"eBayISAPI.dll\">\n"
				<<	"<input type=\"hidden\" name=\"MfcISAPICommand\" "
				<<	"value=\"AdminSpecialItemFlush\">\n";

	// Flush button
	*mpStream	<<	"<td><input type=\"submit\" value=\"Remove all Ended Auction Items\"></td>"
					"</tr>\n"
					"</table></form>\n";

	// use table form for formating the list
	*mpStream	<<	"<p><font color=\"#A52A2A\">"
				<<	"<b>View Items in the Special Pools:</b></font><br>\n"
				<<	"(note: the lists are sorted by add date, "
				<<	"the newest addition is on the top.)<p>\n";


	// table for the list of items

	for (kind = GALLERY; kind >= STAFF_PICKS; kind--)
	{

		*mpStream	<<	"<table border=1>\n";

		switch (kind)
		{
			case STAFF_PICKS:
				mpItems->GetStaffPicksItemIds(&vItemIds, 0);
				*mpStream	<<	"<tr><td colspan=4 align=center>"
							<<	"<b>Staff Picks</b> ("
							<<	vItemIds.size()
							<<	" items) </td></tr>\n"
							<<	"<tr><td align=center>Item</td>"
							<<	"<td align=center>Category</td>"
							<<	"<td align=center>Title</td>"
							<<	"<td align=center>End Date</td></tr>\n";
				break;

			case BLACK_LISTED:
				mpItems->GetBlackListItemIds(&vItemIds, 0);
				*mpStream	<<	"<tr><td colspan=4 align=center>"
							<<	"<b>Black Listed</b> ("
							<<	vItemIds.size()
							<<	" items) </td></tr>\n"
							<<	"<tr><td align=center>Item</td>"
							<<	"<td align=center>Category</td>"
							<<	"<td align=center>Title</td>"
							<<	"<td align=center>End Date</td></tr>\n";
				break;

			case GALLERY:
				mpItems->GetGalleryListItemIds(&vItemIds, 0);
				*mpStream	<<	"<tr><td colspan=5 align=center>"
							<<	"<b>Approved Gallery </b>("
							<<	vItemIds.size()
							<<	" items) </td></tr>\n"
							<<	"<tr><td align=center>Item</td>"
							<<	"<td align=center>Category</td>"
							<<	"<td align=center>Title</td>"
							<<	"<td align=center>Picture</td>"
							<<	"<td align=center>End Date</td></tr>\n";
				break;
		}

		vectorSize = vItemIds.size();

		// iterate over the items
		for ( i = 0; i < vectorSize; i++)
		{
			pItem = NULL;
			// set to true, in order to get category name
			pItem = mpItems->GetItem(vItemIds[i], true);

			*mpStream	<<	"<tr><td><a href=\""
						<<	mpMarketPlace->GetCGIPath(PageViewItem)
						<<	"ebayISAPI.dll?ViewItem&item="
						<<	pItem->GetId()
						<<	"\""
						<<	">"
						<<	pItem->GetId()
						<<	"</a></td>";

			// Get the category
			// The Category could contain a number of leading
			// ':' characters, which is a side affect of the query
			// used to retrieve it. Let's skip past them.
			pCategory	= pItem->GetCategoryName();

			if (pCategory)
			{
				for (;
					*pCategory == ':';
					pCategory++)
				{
					;
				}
			}
			else
				pCategory	= "";

			// Emit the category
			*mpStream	<<	"<td>"
						<<	"<font face=\"Arial, Helvetica\" size=\"2\">"
						<<	"<a href=\""
						<<	mpMarketPlace->GetCategories()->GetLinkPath(pItem->GetCategory())
						<<	"\""
						<<	">"
						<<	pCategory
						<<	"</a></font></td>"
						<<	"<td>"
						<<	pItem->GetTitle()
						<<	"</td>";

			// if it is gallery item, show the thumnail 
			if (kind == GALLERY)
			{
				*mpStream	<<	"<td><a href=\""
							<<	mpMarketPlace->GetCGIPath(PageViewItem)
							<<	"ebayISAPI.dll?ViewItem&item="
							<<	pItem->GetId()
							<<	"\""
							<<	">"
							<<	"<img src=\"http://thumbs.ebay.com/pict/"
							<<	pItem->GetId()
							<<	".jpg"
							<<	"\""
							<<	"border=0 alt=\"\" width=30 height=30>"
							<<	"</a></td>";
			}

			// format the time
			endTime = pItem->GetEndTime();
			pEndTime = localtime(&endTime);
			strftime(cEndTime, sizeof(cEndTime), "%m/%d/%y %H:%M:%S", pEndTime);

			if (endTime > time(0))
			{
				*mpStream	<<	"<td>"
							<<	cEndTime
							<<	"</td></tr>\n";
			}
			// if auction has ended, use red
			else
			{
				*mpStream	<<	"<td><font color=\"#FF0000\">"
							<<	cEndTime
							<<	"</font></td></tr>\n";
				bNote = true;
			}

			delete pItem;
		} // end for

		*mpStream	<<	"</table><p>\n";

		vItemIds.erase(vItemIds.begin(), vItemIds.end());

	} // end for kind 

	if (bNote)
	{
		*mpStream	<<	"<br><font color=\"#FF0000\">red </font>"
					<<	"indicates the auction has ended.<br>\n";
	}

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	// Memory cleanup
	if (pMessage)
	{
		delete pMessage;
	}

	return;
}

// add item into the ebay_special_items
void clseBayApp::AdminSpecialItemAdd(CEBayISAPIExtension *pThis,
									 char *pItemNo, int kind,
									 eBayISAPIAuthEnum authLevel)
{
	clsMessage	*pMessage = NULL;
	bool		error = false;
	bool		bItemFound = false;
	clsItem		*pItem = NULL;
	int			itemid;
	vector<int>	vItemIds;

	// Setup
	SetUp();	

	// Let's try and get the item
	if (!GetAndCheckItem(pItemNo))
	{
		CleanUp();
		return;
	}

	// Let's see if the auction's ended
	if (mpItem->GetEndTime() < time(0))
	{

		// Title
		EmitHeader("eBay Admin Tool - Auction Ended");

		*mpStream	<<	"<h2>Bidding already closed</h2>"
						"The bidding on the item # "
					<<	pItemNo
					<<	" has ended. "
						"Please go Back and re-enter the item number and kind.\n";
					
		*mpStream	<<	mpMarketPlace->GetFooter()
					<<	flush;	

		CleanUp();
		return;
	}

	// Title
	EmitHeader("eBay Admin Tool - Special Item Added");

	// Heading
	*mpStream <<	"<p><h2><center>Add Item</center></h2><br>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// convert the pItemNo 
	itemid = atoi(pItemNo);

	// check if the item has already in the table
	switch (kind)
	{
		case STAFF_PICKS:
			mpItems->GetStaffPicksItemIds(&vItemIds, 0);
			break;

		case BLACK_LISTED:
			mpItems->GetBlackListItemIds(&vItemIds, 0);
			break;

		case GALLERY:
			mpItems->GetGalleryListItemIds(&vItemIds, 0);
			break;
	}

	if ((find(vItemIds.begin(), vItemIds.end(), itemid)) != vItemIds.end())
	{
		*mpStream	<<	"<b> Unsuccessful: </b><br>\n"
					<<	"Item # "
					<<	pItemNo
					<<	" already exists. "
						"Please go Back and re-enter the item number and kind.\n";
	}
	// Check if the item is a Gallery item
	else if ( (kind == GALLERY) && (! (mpItem->IsGallery() || mpItem->IsFeaturedGallery())) )
	{
		*mpStream	<<	"<b> Unsuccessful:</b><br>\n"
					<<	"Item # "
					<<	pItemNo
					<<	" is not a Gallery item. "
						"Please go Back and re-enter the item number and kind.\n";
	}
	else
	{
		// Get item id
		pItem = mpItems->GetItem(itemid);

		// Add the item into the ebay_special_items table
		mpItems->AddSpecialItem(itemid, kind, pItem->GetEndTime());

		// Display successfully added message
		*mpStream	<<	"Item # "
					<<	pItemNo
					<<	" has been successfully added in the ";

		switch (kind)
		{
			case STAFF_PICKS:
				*mpStream	<<	"staff picks list.\n";
				break;

			case BLACK_LISTED:
				*mpStream	<<	"black listed list.\n";
				break;

			case GALLERY:
				*mpStream	<<	"approved gallery list.\n";
				break;
		}
		// spacer
		*mpStream	<<	"<br>";
    }
	vItemIds.erase(vItemIds.begin(), vItemIds.end());

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	// Memory cleanup
	if (pMessage)
	{
		delete pMessage;
	}

	if (pItem)
	{
		delete pItem;
	}

	return;
}

// delete an item from the ebay_special_items
void clseBayApp::AdminSpecialItemDelete(CEBayISAPIExtension *pThis,
										char *pItemNo,
									    eBayISAPIAuthEnum authLevel)
{
	clsMessage	*pMessage = NULL;
	bool		error = false;
	bool		bItemFound1 = false;
	bool		bItemFound2 = false;
	bool		bItemFound3 = false;
	int			itemid;
	clsItem		*pItem = NULL;
	vector<int>	vStaffPicksItemIds;
	vector<int>	vBlackListItemIds;
	vector<int>	vGalleryItemIds;

	// Setup
	SetUp();	

	// Title
	EmitHeader("eBay Admin Tool - Item Deleted");

	// Heading
	*mpStream <<	"<p><h2><center>Delete Item</center></h2><br>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// Get item id
	itemid = atoi(pItemNo);

	// get all the items
	mpItems->GetStaffPicksItemIds(&vStaffPicksItemIds, 0);
	mpItems->GetBlackListItemIds(&vBlackListItemIds, 0);
	mpItems->GetGalleryListItemIds(&vGalleryItemIds, 0);

	// check if the item exists
	if ( (find(vStaffPicksItemIds.begin(), vStaffPicksItemIds.end(), itemid)
		  != vStaffPicksItemIds.end()) ||
		 (find(vBlackListItemIds.begin(), vBlackListItemIds.end(), itemid)
		  != vBlackListItemIds.end()) ||
		 (find(vGalleryItemIds.begin(), vGalleryItemIds.end(), itemid)
		  != vGalleryItemIds.end()) )
	{
		// Delete the item from the ebay_special_items table
		mpItems->DeleteSpecialItem(itemid);

		// Display successfully added message
		*mpStream	<<	"Item # "
					<<	pItemNo
					<<	" has been successfully deleted.";
	}
	else
	{
		*mpStream	<<	"<b> Unsuccessful: </b><br>\n"
					<<	"Item # "
					<<	pItemNo
					<<	" does not exist. "
						"Please go Back and re-enter the item number.\n";
	}

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	// Memory cleanup
	if (pMessage)
	{
		delete pMessage;
	}

	if (pItem)
	{
		delete pItem;
	}
	vStaffPicksItemIds.erase(vStaffPicksItemIds.begin(), vStaffPicksItemIds.end());
	vBlackListItemIds.erase(vBlackListItemIds.begin(), vBlackListItemIds.end());
	vGalleryItemIds.erase(vGalleryItemIds.begin(), vGalleryItemIds.end());

	return;
}

// flush ended auction from ebay_special_items
void clseBayApp::AdminSpecialItemFlush(CEBayISAPIExtension *pThis,
									   eBayISAPIAuthEnum authLevel)
{
	clsMessage	*pMessage = NULL;
	bool		error = false;

	// Setup
	SetUp();	

	// Title
	EmitHeader("eBay Admin Tool - Update");

	// Heading
	*mpStream <<	"<p><h2><center>Flush Item</center></h2><br>";

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// Delete the items which auction has ended from the ebay_special_items table
	mpItems->FlushSpecialItem();

	// Display successfully added message
	*mpStream	<<	"All ended auction items has been successfully deleted.";

	// spacer
	*mpStream	<<	"<br>";

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	// Memory cleanup
	if (pMessage)
	{
		delete pMessage;
	}

	return;
}

