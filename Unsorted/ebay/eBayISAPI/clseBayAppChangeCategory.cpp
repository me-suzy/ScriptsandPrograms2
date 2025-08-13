/*	$Id: clseBayAppChangeCategory.cpp,v 1.11.22.5.14.1 1999/08/01 03:01:08 barry Exp $	*/
//
//	File:		clsItemChangeCategory.cpp
//
//	Class:		clseBayApp
//
//	Author:		Michael Wilson (michael@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 06/14/97 michael	- Created
//				- 07/31/97 inna		- allow users with special 
//									password chage category of closed item
//				- 04/15/99 kaz		- Check for Police Badge T&C
//
//				- 04/07/99 kaz		- Check for Police Badge T&C
//				- 04/07/99 kaz		- Changed PB Date to 4/16/99
//				- 07/19/99 nsacco	- fixed title

#include "ebihdr.h"

static const char eBayBlockedItemAppealEmailAddress[] = "itemapl@ebay.com";


void clseBayApp::EmitChangeCategoryDenied(clsItem *pItem,
										  FilterVector *pvFilters,
										  ostream *pStream)
{
#if 1
		// display blocked message to seller!!!!!
	// be sure to tell them how to appeal!
	*pStream	<< "<h2>Category Change Denied</h2>"
				<< "Item "
				<< pItem->GetId()
				<< " ("
				<< pItem->GetTitle()
				<< ") cannot be moved to the specified category.";
#else
	FilterVector::iterator	i;
	char *					pString = NULL;

	// display blocked message to seller!!!!!
	// be sure to tell them how to appeal!
	*pStream	<< "<h2>Category Change Denied</h2>"
				<< "Item "
				<< pItem->GetId()
				<< " ("
				<< pItem->GetTitle()
				<< ") cannot be moved to the specified category because "
				<< "the title and/or description contain(s) the following "
				<< "word(s) or phrase(s):<p>";
	
	for (i = pvFilters->begin(); i != pvFilters->end(); i++)
	{
		pString = (*i)->GetExpression();
		if (pString != NULL)
		{
			*pStream	<< "<li>"
						<< (*i)->GetExpression()
						<< "</li>";
		}
	}
#endif

//	*pStream	<< "<p>"
//				<< "If you wish to appeal, please send an email which includes "
//				<< "your user ID, the item number, and the item title to "
//				<< "<a href=\"mailto:"
//				<< eBayBlockedItemAppealEmailAddress
//				<< "\">"
//				<< eBayBlockedItemAppealEmailAddress
//				<< "</a>.";
}

	
void clseBayApp::ChangeCategory(CEBayISAPIExtension *pServer,
								char *pUserId,
								char *pPass,
								int item,
								int newCategory)
{
	char		cItem[EBAY_MAX_ITEM_SIZE + 1];
	char		cOldCategory[256];
	clsCategory	*pCategory;
	clsAccount	*pSellerAccount;
	bool priceChange =false;

	FilterVector			vFilters;

	// Setup
	SetUp();

	// Prepare the stream
	mpStream->setf(ios::fixed, ios::floatfield);
	mpStream->setf(ios::showpoint, 1);
	mpStream->precision(2);

	// Usual Title and Header
	*mpStream <<	"<HTML>"
					"<HEAD>"
					"<TITLE>"
					// nsacco 07/19/99
			  <<	mpMarketPlace->GetCurrentPartnerName()
			  <<	" Change Category"
			  <<	"</TITLE>"
					"</HEAD>"
			  <<	flush;

	// And, now the item thang
	sprintf(cItem, "%d", item);
	GetAndCheckItem(cItem);

	if (!mpItem)
	{
		*mpStream <<	"<p>";

		CleanUp();
		return;
	}

	*mpStream <<	mpMarketPlace->GetHeader();

	// Do the User Thang foist
	mpUser	= mpUsers->GetAndCheckUserAndPassword(pUserId, pPass, mpStream);

	if (!mpUser)
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;

	}

	// See if the user owns the item
	if (mpUser->GetId() != mpItem->GetSeller())
	{
		*mpStream <<	"<p>"
						"<H2>"
				  <<	pUserId
				  <<	" is not the seller for item "
				  <<	item
				  <<	"</H2>"
						"<p>"
						"Only the seller is allowed to change an item\'s category. "
						"If you are the seller, please go back, "
						"correct the "
				  <<	mpMarketPlace->GetLoginPrompt()
				  <<	", and try again. "
				  <<	mpMarketPlace->GetFooter()
				  <<	flush;
		CleanUp();
		return;
	}
	
	//See if the item is ended?
	if (mpItem->GetEndTime() < time(0))
	{
		//inna, if not special password, NO change allowed!
		if (strcmp(pPass, mpMarketPlace->GetSpecialPassword()) != 0)
		{
			*mpStream <<	"<p>"
							"<H2>"
					  <<	item
					  <<	" is closed "
					  <<	"</H2>"
						    "<p>"
							"Only the current item is allowed to change an item\'s category. "
							"If you are the seller, please go back "
					  <<	", and make sure the auction is not closed. "
					  <<	mpMarketPlace->GetFooter()
					 <<	flush;
			CleanUp();
			return;
		}
		
	}

	// 02/24/99 Alex Poon
	// Block firearms listing beginning on Sat 2/27/99
	if (CheckForFirearmsListing(newCategory))
	{
		*mpStream <<	"<p>"
				  <<	mpMarketPlace->GetFooter();

		CleanUp();
		return;
	}

	// kaz: 4/15/99: Support for Police Badge T&C
	if (newCategory == kPoliceBadgeCatID)
		if (clsUtilities::CompareTimeToGivenDate(time(0),4,16,99,0,0,0) >= 0) 
			if (! mpUser->AcceptedPoliceBadgeAgreement())
	{
		PoliceBadgeAgreementForSelling(pUserId,pPass);
		*mpStream <<	"<br>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}	//

	// And the Category Thang
	pCategory	= mpCategories->GetCategory(newCategory, true);

	if (!pCategory)
	{
		*mpStream <<	"<h2>New Category Invalid</h2>"
						"The new category does not exist. Please go  "
						"back and ensure that you are using the "
						"<A HREF="
						"\""
				  <<	mpMarketPlace->GetCGIPath(PageChangeCategoryShow)
				  <<	"eBayISAPI.dll?ChangeCategoryShow&item="
				  <<	item
				  <<	"\""
						">"
						"change category form"
						"</a>"
						" properly."
						"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
	else if (!pCategory->isLeaf())
	{
		*mpStream <<	"<h2>Category does not allow listings</h2>"
						"The new category is not a \'leaf\' "
						"category, and items cannot be listed in it. "
						"Please go  "
						"back and ensure that you are using the "
						"<A HREF="
						"\""
				  <<	mpMarketPlace->GetCGIPath(PageChangeCategoryShow)
				  <<	"eBayISAPI.dll?ChangeCategoryShow&item="
				  <<	item
				  <<	"\""
						">"
						"change category form"
						"</a>"
						" properly."
						"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
	else if (pCategory->GetIsExpired())
	{
		*mpStream <<	"<h2>Category expired</h2>"
						"The new category is being phased out, and items "
						"cannot be listed in it. "
						"Please go  "
						"back and ensure that you are using the "
						"<A HREF="
						"\""
				  <<	mpMarketPlace->GetCGIPath(PageChangeCategoryShow)
				  <<	"eBayISAPI.dll?ChangeCategoryShow&item="
				  <<	item
				  <<	"\""
						">"
						"change category form"
						"</a>"
						" properly."
						"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
	//if move into Cars Or Real Estate - it can not be Dutch
	else if ((mpItem->CheckForAutomotiveListing(newCategory) && mpItem->GetQuantity()>1)
		     || (mpItem->CheckForRealEstateListing(newCategory) && mpItem->GetQuantity()>1))
	{
		*mpStream <<	"<h2>Move Invalid</h2>"
						"Dutch Auctions are not allowed in this category, because of "
						"the fixed fee structure that applies to items listed there. "
						 "For details, see the <a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"help/sellerguide/fixed.html\">"
						"list</a>."
						"of these categories.  For this reason, you may not move this"
						"Dutch Auction listing into this category.  If you "
						"wish to list individual items which you are selling via a Dutch Auction "
                         "within this category, you must <a href=\""
					<<	mpMarketPlace->GetHTMLPath()
					<<	"end-auction.html\">"
					     "end the auction</a> "
						 "and relist the individual item in the desired category. "
						 "We regret the inconvenience."
						"<p>"
				  <<	mpMarketPlace->GetFooter();
		CleanUp();
		return;
	}
	// Wellll, looks like everything's cool. Update the item and tell the 
	// user they're wonderful.

	// First, let's save the old category name
	strcpy(cOldCategory, mpItem->GetCategoryName());

	//if change in or out of cars, we need some accounting
 	if (( (mpItem->CheckForAutomotiveListing() 
			&&  !mpItem->CheckForAutomotiveListing(newCategory))) 
		|| ((!mpItem->CheckForAutomotiveListing() 
			&& mpItem->CheckForAutomotiveListing(newCategory)))
		|| ((mpItem->CheckForRealEstateListing() 
			&& !mpItem->CheckForRealEstateListing(newCategory))) 
		|| ((!mpItem->CheckForRealEstateListing()
			&& mpItem->CheckForRealEstateListing(newCategory))))
	{
		priceChange=true;
		//credit first, pItem still has old category
		pSellerAccount	= mpUser->GetAccount();
		pSellerAccount->ApplyInsertionFeeCredit(mpItem,NULL);
		mpItem->SetCategory(newCategory);
		pSellerAccount->ChargeInsertionFee(mpItem);
		delete pSellerAccount;

	}
	else
	{

		mpItem->SetCategory(newCategory);
    }

	// We have to update the category in the item object, cuz the item
	// validator needs to know which category to get the screening
	// criteria from
	mpItem->SetCategory(newCategory);

	// Do we need to screen items in this category?
	if (pCategory->GetScreenItems())
	{
		// Display category-specific messages to seller for new category
		EmitSellerMessages(newCategory, mpStream);

		// Screen the item against filters for new category
		ActionType action = AdminScreenItem(mpItem,
											mpUser,
											&vFilters,
											ScreenItemOnChangeCategory,
											mpStream);


		// Does the listing need to be blocked?
		if (action == ActionTypeBlockListing)
		{
			// Display a message to the seller about the category change
			// being denied
			EmitChangeCategoryDenied(mpItem, &vFilters, mpStream);

			*mpStream	<< "<p>"
						<< mpMarketPlace->GetFooter();

			// Clear vector
			vFilters.erase(vFilters.begin(), vFilters.end());

			// Clean up and return cuz we're done
			CleanUp();
			return;
		}

#if 0   // don't display message to seller by default

		// BUG FIX: 2174 S.Forgaard.  Allow item to be moved to a catagory that would cause it to be flagged.
		else if (action == ActionTypeFlagListing)
		{
			*mpStream	<< "Item #"
						<<	mpItem->GetId()
						<< " ("
						<< mpItem->GetTitle()
						<< ") has been flagged for review.";
		}
#endif
	}
	

	// Change got past screening, so let's continue...

	// Wellll, looks like everything's cool. Update the item and tell the 
	// user they're wonderful.

	mpItem->UpdateItem();
	delete mpItem;
	mpItem	= NULL;

	GetAndCheckItem(cItem);

	*mpStream <<	"<h2>Category Changed</h2>"
					"Item #"
			  <<	item
			  <<	" ("
			  <<	mpItem->GetTitle()
			  <<	") has been moved from \'"
			  <<	cOldCategory
			  <<	"\' to \'"
			  <<	mpItem->GetCategoryName()
			  <<	" \'.";

	if 	(priceChange)
	{
		*mpStream <<	"<p>"
						"These two categories have different fee structures.  "
						"Your previous listing fee will be credited back to you and "
						"the listing fee for the new category will apply. "
						" Please see "
						"<a href=\""
					 <<	mpMarketPlace->GetHTMLPath()
					 <<	"help/sellerguide/selling-fees.html\">"
					    " eBay fees</A>"
						"  for details." ;
	}

	*mpStream <<	"<p>"
					"<b>Note</b>: Your item will not show up in the "
					"listings in its new category until the listing "
					"pages are updated, typically in an hour or so from "
					"now."
			  <<	"<p>"
			  <<	mpMarketPlace->GetFooter();

	CleanUp();

	return;

}

