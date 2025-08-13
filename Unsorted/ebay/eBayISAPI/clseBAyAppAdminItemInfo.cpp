/*	$Id: clseBAyAppAdminItemInfo.cpp,v 1.11.2.1.80.1 1999/08/01 02:51:32 barry Exp $	*/
//
//	File:		clseBayAppAdmItemInfo.cpp
//
//	Class:		clseBayApp
//
//	Author:		Vicki Shu (vicki@ebay.com)
//
//	Function:
//
//
//	Modifications:
//				- 09/08/97	vicki	- Created
//				- 2/2/99	pvh		- added gallery admin

#pragma warning( disable : 4786 )

#include "ebihdr.h"
#include "hash_map.h"
#include <stdlib.h>

// are we using code for one thumbnail server or multiple?
#define GALLERY_MULTIPLE_THUMBNAIL_SERVERS
//#undef GALLERY_MULTIPLE_THUMBNAIL_SERVERS

// list all thumbnail machines here
// NOTE: this should probably be moved to clsMarketplaces at some point in the future!
//
static char *sThumbnailservers[] = {
	"gecko.ebay.com",
	"mangrove.ebay.com"
//	"pete.corp.ebay.com",
};


bool clseBayApp::ItemInfo(CEBayISAPIExtension *pThis,
						     char *pAction,
						     char *pItemNo,
						     char *pTitle,
							 char *pQuantity,
							 char *pcEndTime,
							 char *pcEndTimeHour,
							 char *pcEndTimeMin,
							 char *pcEndTimeSec,
						     int  featured,	
						     int  superfeatured,
							 char *pDescription,
						     int  gallery,	 
						     int  galleryfeatured,	 
							 char *pGiftIcon,
						     char *pRedirectURL)
{

    time_t	endTime;
	struct tm *timeAsTm;

    bool error = false;
	bool		foundNonBlank;
	char		*pI;
	int			nQuantity;
	int         item;
	//need to give credit
	clsAccount					*pAccount=NULL;
	clsAccountDetail			*pAccountDetail;
	clsUser						*pUser=NULL;

	int								mailRc=1;
	bool galleryRedirect = false;
	bool FreeGallery = false;
	time_t	theTime;

	int icontype;
	int newIconType;

	// ** NOTE **
	// Free gallery period ( new feature promotion until 2-21-99)
	// ** NOTE **
	if ((clsUtilities::CompareTimeToGivenDate(theTime, 2, 21, 99, 0, 0, 0) < 0))
		FreeGallery = true;

	SetUp();

	//let's get header first
	*mpStream <<	mpMarketPlace->GetHeader()
			  <<	"<p>";
   
	// Let's get the item
    if (!GetAndCheckItem(pItemNo))
	{
	    *mpStream <<	"ERROR MESSAGE";
	    CleanUp();
	    return false;
	}
   
	if(strcmp(pAction, "ChangeInfo") ==0 ) 
	{
		item	= atoi(pItemNo);
	   
		// title is empty?
	    if (FIELD_OMITTED(pTitle))
		{
		    *mpStream <<	"<h2>"
						"Empty Title"
						"</h2>"
						"You must enter a title for your item. "
						"Please go back and try again.<p>\n ";
			error = true;
		}

	   // title too long?
	    if ( strlen(pTitle) > 45)
		{
		    *mpStream <<	"<h2>"
						"Title too long"
						"</h2>"
						"Listing titles are limited to 45 characters."
						" Please go back and shorten your title<p>\n";

	         error = true;
		}
        // title is All blanks?
	   foundNonBlank	= false;
	        for (pI	= pTitle;
	           *pI != '\0';
		       pI++) {
		       if (*pI != ' ')
			   {
			     foundNonBlank	= true;
			     break;
			   }
			}

	    if (!foundNonBlank)
		{
		    *mpStream <<	"<h2>Title blank</h2>"
						"The title you entered was all blank. You must "
						"enter a title for your item. Please go back and try "
						"again."
						"<p>";

		    error = true;
		}

	  	// Quantity is valid?	 
        if (!FIELD_OMITTED(pQuantity))
		    nQuantity	= atoi(pQuantity);
	    else{
		  *mpStream <<      "<h2>"
						    "Error in quantity"
					    	"</h2>"
						    "The quantity was missing! Please go "
					        "back and enter a valid quantity."
						    "<p>";;
		   nQuantity	= -1;
		   error = true;
		}
	  
       if (!FIELD_OMITTED(pQuantity) && 
		    (nQuantity < 1 || nQuantity > EBAY_MAX_QUANTITY_AMOUNT))
		{
		    *mpStream <<	"<h2>"
						    "Error in quantity"
					    	"</h2>"
						    "The quantity was  missing, invalid, or zero. Please go "
					        "back and enter a valid quantity."
						    "<p>";
        error = true;
		} 

	   	//gift icon
		if (strcmp(mpItem->GetSellerUserId(), "4allkids") && strcmp(pGiftIcon, "2") == 0)
		{
			*mpStream <<	"<h2>"
						    "Only Rosie can use Rosie Icon"
					    	"</h2>"
						    "Please go "
					        "back and enter a valid selection."
						    "<p>";
			error = true;
		}

		if (strcmp(mpItem->GetSellerUserId(), "4allkids") == 0 && strcmp(pGiftIcon, "2") != 0)
	    {
			*mpStream <<	"<h2>"
						    "Only Rosie can use Rosie Icon"
					    	"</h2>"
							"If user is Rosie, you cannot select any other icons. "
						    "Please go "
					        "back and enter a valid selection."
						    "<p>";
			error = true;
		}
// Description
	if (FIELD_OMITTED(pDescription))
	{
		*mpStream << "<h2>"
			         "No description"
				     "</h2>"
				     "There was no description provided. You must provide a description "
					 "for your item. Please go back and try again."
				     "<p>";		
       error = true;
	} 


	// Let's see if the user can do this
	if ((!mpItem->GetFeatured() && featured==1) ||
			(!mpItem->GetSuperFeatured() && superfeatured==1))
	{
		//let's see who is the seller
		pUser= gApp->GetDatabase()->GetUserById(mpItem->GetSeller());
		if (!mpMarketPlace->UserCanFeature(pUser,mpStream))
			error = true;
	}

	if (error)
	{
		*mpStream	<<	"<p>"
					<<	mpMarketPlace->GetFooter();

		CleanUp();

		return false;
	}

		// if featured box has changed or category featured box
		//chaged to unfeature - need account objects
		if ((mpItem->GetFeatured() && featured==0) ||
			(mpItem->GetSuperFeatured() && superfeatured==0) ||
			(!mpItem->GetFeatured() && featured==1) ||
			(!mpItem->GetSuperFeatured() && superfeatured==1) ||
			((mpItem->GetGalleryType() == Gallery) && (gallery==0)) ||
			((mpItem->GetGalleryType() == FeaturedGallery) && (gallery==0)) ||
			((mpItem->GetGalleryType() == FeaturedGallery) && (galleryfeatured==0)) ||
			((mpItem->GetGiftIconType() != GiftIconUnknown) && (strcmp(pGiftIcon, "0") == 0 || strcmp(pGiftIcon, "2") == 0)) ||
			((mpItem->GetGiftIconType() == GiftIconUnknown) && (strcmp(pGiftIcon, "0") != 0 && strcmp(pGiftIcon, "2") != 0)))
		{
			//we need to get stuff out of db
				pUser= gApp->GetDatabase()->GetUserById(mpItem->GetSeller());
				pAccount	= pUser->GetAccount();
		}
		
		// if featured box has changed to Unfeature
		if (mpItem->GetFeatured() && featured==0)
		{
			if (!(mpItem->HasCategoryFeaturedCredit()))
			{
				//give credit,since no credit exist
				if (pAccount)
				{
					//the only way to give crdit if account is there
	
					// Ok, let's make account detail
					pAccountDetail	= new clsAccountDetail();

					// Fill it in with what we know
					pAccountDetail->mTime			= time(0);
					pAccountDetail->mType			= AccountDetailCreditCategoryFeatured;
					pAccountDetail->mpMemo			= NULL;
					pAccountDetail->mAmount			=  // Lena - new featured price
					mpItem->GetCategoryFeaturedFee(mpItem->GetStartTime());
					mpItem->GetCategoryFeaturedFee(mpItem->GetStartTime());

					pAccountDetail->mItemId			= mpItem->GetId();

					//
					// Let's add the raw account detail and the transaction xref
					//
					gApp->GetDatabase()->AddAccountDetail(pUser->GetId(), 
											pAccount->GetTableIndicator(),
										  pAccountDetail);
					gApp->GetDatabase()->AddAccountItemXref(
											pAccountDetail->mTransactionId,
											mpItem->GetId());
					delete	pAccountDetail;
					pAccount->AdjustBalance(mpItem->GetCategoryFeaturedFee(mpItem->GetStartTime()));

					//update flag for billing-credit is given!
					mpItem->SetHasCategoryFeaturedCredit(true);
					//email user
					mailRc = MailUnfeatureNotice(pUser);

				}
			}
			//set listings flag from featured to unfeatured
			//regardless of credit given or not	
			mpItem->SetNewFeatured(false);			
		}
		else
		{
			// if featured box has changed to Feature
			if (!mpItem->GetFeatured() && featured==1)
			{
				//first charge the user:
				pAccountDetail	= new clsAccountDetail();

				// Fill it in with what we know
				pAccountDetail->mTime			= time(0);
				pAccountDetail->mType			= AccountDetailFeeCategoryFeatured;
				pAccountDetail->mpMemo			= NULL;
				pAccountDetail->mAmount			=  // Lena - new featured price
					-1 * (mpItem->GetCategoryFeaturedFee(mpItem->GetStartTime()));
				pAccountDetail->mItemId			= mpItem->GetId();

				//
				// Let's add the raw account detail and the transaction xref
				//
				gApp->GetDatabase()->AddAccountDetail(pUser->GetId(), 
										pAccount->GetTableIndicator(),
										 pAccountDetail);
				gApp->GetDatabase()->AddAccountItemXref(
										pAccountDetail->mTransactionId,
										mpItem->GetId());
				delete	pAccountDetail;
				//now credit them
				pAccountDetail	= new clsAccountDetail();

				// Fill it in with what we know
				pAccountDetail->mTime			= time(0);
				pAccountDetail->mType			= AccountDetailCreditCategoryFeatured;
				pAccountDetail->mpMemo			= NULL;
				pAccountDetail->mAmount			=  // Lena - new featured price
				mpItem->GetCategoryFeaturedFee(mpItem->GetStartTime());
				pAccountDetail->mItemId			= mpItem->GetId();

				//
				// Let's add the raw account detail and the transaction xref
				//
				gApp->GetDatabase()->AddAccountDetail(pUser->GetId(), 
										pAccount->GetTableIndicator(),
										 pAccountDetail);
				gApp->GetDatabase()->AddAccountItemXref(
										pAccountDetail->mTransactionId,
										mpItem->GetId());
				delete	pAccountDetail;
				// no  need to adjust entries balance each other
				//pAccount->AdjustBalance(mpMarketPlace->GetCategoryFeaturedFee(mpItem->GetStartTime()));

				//update flag for billing-credit is given!
				mpItem->SetHasCategoryFeaturedCredit(true);
				
				//just set listings flag, billing flag untouched - no charges here
				mpItem->SetNewFeatured(true);
			}
		}
		//DONE with CATEGORY FEATURED
		// if super featured box has changed to Unfeature
		if (mpItem->GetSuperFeatured() && superfeatured==0)
		{
			if (!(mpItem->HasFeaturedCredit()))
			{
				//give credit,since no credit exist
				if (pAccount)
				{
					//the only way to give crdit if account is there
	
					// Ok, let's make account detail
					pAccountDetail	= new clsAccountDetail();

					// Fill it in with what we know
					pAccountDetail->mTime			= time(0);
					pAccountDetail->mType			= AccountDetailCreditFeatured;
					pAccountDetail->mpMemo			= NULL;
					// Lena - new featured fees
					pAccountDetail->mAmount			= mpItem->GetFeaturedFee(mpItem->GetStartTime());
					pAccountDetail->mItemId			= mpItem->GetId();

					//
					// Let's add the raw account detail and the transaction xref
					//
					gApp->GetDatabase()->AddAccountDetail(pUser->GetId(), 
											pAccount->GetTableIndicator(),
										  pAccountDetail);
					gApp->GetDatabase()->AddAccountItemXref(
											pAccountDetail->mTransactionId,
											mpItem->GetId());
					delete	pAccountDetail;
					pAccount->AdjustBalance(mpItem->GetFeaturedFee(mpItem->GetStartTime()));

					//update flag for billing-credit is given!
					mpItem->SetHasFeaturedCredit(true);
					//email user
					mailRc = MailUnfeatureNotice(pUser);

				}
			}
			//set listings flag from featured to unfeatured
			//regardless of credit given or not
			mpItem->SetNewSuperFeatured(false);			
		}
		else
		{
			// if featured box has changed to Feature
			if (!mpItem->GetSuperFeatured() && superfeatured==1)
			{
					//first charge them 
					pAccountDetail	= new clsAccountDetail();

					// Fill it in with what we know
					pAccountDetail->mTime			= time(0);
					pAccountDetail->mType			= AccountDetailFeeFeatured;
					pAccountDetail->mpMemo			= NULL;
					// Lena - new featured fees
					pAccountDetail->mAmount			= -1 * 
						(mpItem->GetFeaturedFee(mpItem->GetStartTime()));
					pAccountDetail->mItemId			= mpItem->GetId();

					//
					// Let's add the raw account detail and the transaction xref
					// 
					gApp->GetDatabase()->AddAccountDetail(pUser->GetId(), 
											pAccount->GetTableIndicator(),
										  pAccountDetail);
					gApp->GetDatabase()->AddAccountItemXref(
											pAccountDetail->mTransactionId,
											mpItem->GetId());
					delete	pAccountDetail;

					//now credit them them 
					pAccountDetail	= new clsAccountDetail();

					// Fill it in with what we know
					pAccountDetail->mTime			= time(0);
					pAccountDetail->mType			= AccountDetailCreditFeatured;
					pAccountDetail->mpMemo			= NULL;
					// Lena - new featured fees
					pAccountDetail->mAmount			= 
						mpItem->GetFeaturedFee(mpItem->GetStartTime());
					pAccountDetail->mItemId			= mpItem->GetId();

					//
					// Let's add the raw account detail and the transaction xref
					// 
					gApp->GetDatabase()->AddAccountDetail(pUser->GetId(), 
											pAccount->GetTableIndicator(),
										  pAccountDetail);
					gApp->GetDatabase()->AddAccountItemXref(
											pAccountDetail->mTransactionId,
											mpItem->GetId());
					delete	pAccountDetail;

					//no need to adjust - washing  entries
					//pAccount->AdjustBalance(mpMarketPlace->GetFeaturedFee(mpItem->GetStartTime()));

					//update flag for billing-credit is given!
					mpItem->SetHasFeaturedCredit(true);
					//and set correct flag
					mpItem->SetNewSuperFeatured(true);
			}
		}

		//gift icon credit handling
		// chage from no gift icon to a gift icon, but not Rosies 
		
		icontype = mpItem->GetGiftIconType();
		newIconType = atoi(pGiftIcon);
		//need charge or credit
		if ((icontype <= GiftIconUnknown || icontype == 2) && (newIconType > GiftIconUnknown && newIconType != 2))
		{
				mpItem->SetIconFlags(pGiftIcon);
				//we need charge the user
				if (pAccount != NULL)
					pAccount->ChargeGiftIconFee(mpItem);
		}
		else
		{
			//credit user, if they changed icon to unselected
			if ((icontype >= GiftIconUnknown || icontype != 2) && (newIconType <= GiftIconUnknown || newIconType ==2))
			{
				if (!(mpItem->HasGiftIconCredit())) 
				{
					if (pAccount != NULL)
						pAccount->CreditGiftIconFee(mpItem);
					mpItem->SetHasGiftIconCredit(true);
				}
			}
		}
		
		// Gallery credit handling

		// gallery featured
		// if we are making this item an UNFeaturedGallery item
		// this can happen if either the item is cleared of FeaturedGallery or Gallery settings
		if ((mpItem->GetGalleryType() == FeaturedGallery) && (galleryfeatured==0) ||
			(mpItem->GetGalleryType() == FeaturedGallery) && (gallery==0))
		{
			if (!(mpItem->HasFeaturedGalleryCredit())) {
				// ADD CREDITING HERE!

				pAccount->CreditFeaturedGalleryFee(mpItem);
				
				mpItem->SetHasFeaturedGalleryCredit(true);
				
				// ok, if gallery is still set to true, that means they just wanted to turn GalleryFeatured off
				// but leave the image in the Gallery. so now we need to charge them the 0.25 for that here.
				if(gallery == 1) {
					if(!FreeGallery)
						pAccount->ChargeGalleryFee(mpItem);		
				}
			}
			
			// we need to make this gallery pic unfeatured
			mpItem->SetGalleryType(Gallery); //  UnFeaturedGallery
			mpItem->UpdateItem();
			
			// if gallery was set to 0, then we want to change the type to NoneGallery so we don't
			// credit them twice in the next section
			if(gallery == 0) {
				mpItem->SetGalleryType(NoneGallery); //  UnFeaturedGallery
				mpItem->UpdateItem();

#ifndef GALLERY_MULTIPLE_THUMBNAIL_SERVERS
				//				sprintf(pRedirectURL, "http://pete.corp.ebay.com/ed/thumbServe.dll?MakeNoisy&item=%d", mpItem->GetId());
//				sprintf(pRedirectURL, "http://mangrove.ebay.com/ed/thumbServe.dll?MakeNoisy&item=%d", mpItem->GetId());
				sprintf(pRedirectURL, "http://thumbnails.ebay.com/ed/thumbServe.dll?MakeNoisy&item=%d", mpItem->GetId());
#endif
				galleryRedirect = true;			
			}
		} 
		else 
		{
// we're not doing ANY admin for turning gallery images back on for now.
#if 0
			// only if they were gallery featured at one time can we set them back to gallery featured
			if ((mpItem->GetGalleryType() == UnFeaturedGallery) && (galleryfeatured==1)) 
			{
				mpItem->SetGalleryType(FeaturedGallery);
				mpItem->UpdateItem();
			}
#endif
		}

		
		// un-gallery this item
		if ((mpItem->GetGalleryType() == Gallery) && (gallery==0)) //  || (mpItem->GetGalleryType() == UnFeaturedGallery )
		{
			if (!(mpItem->HasGalleryCredit())) {
				// ADD CREDITING HERE!
				if(!FreeGallery)
					pAccount->CreditGalleryFee(mpItem);
				
				mpItem->SetHasGalleryCredit(true);
			}
			
			// we need to make this an un gallery pic
			{
				clsGalleryChangedItem item;
				
				item.mID = mpItem->GetId();
				item.mSequenceID = gApp->GetDatabase()->GetNextGallerySequence();
				strcpy(item.mURL, "nothing");
				item.mState = 0;
				item.mStartTime = 0;	// set both start & end times to 0 to flag that this item should be removed
				item.mEndTime = 0;		// set both start & end times to 0 to flag that this item should be removed
				item.mAttempts = -1;
				item.mLastAttempt = time(NULL);
				
				bool appendResult = gApp->GetDatabase()->AppendGalleryChangedItem(item);
			}
			mpItem->SetGalleryType(NoneGallery); // UnGallery
			mpItem->UpdateItem();
			
#ifndef GALLERY_MULTIPLE_THUMBNAIL_SERVERS
			//				sprintf(pRedirectURL, "http://pete.corp.ebay.com/ed/thumbServe.dll?MakeNoisy&item=%d", mpItem->GetId());
//			sprintf(pRedirectURL, "http://mangrove.ebay.com/ed/thumbServe.dll?MakeNoisy&item=%d", mpItem->GetId());
			sprintf(pRedirectURL, "http://thumbnails.ebay.com/ed/thumbServe.dll?MakeNoisy&item=%d", mpItem->GetId());
#endif
			//			sprintf(pRedirectURL, "http://thumbnails.ebay.com/ed/thumbServe.dll?MakeNoisy&item=%d", mpItem->GetId());
			galleryRedirect = true;
		}

// we're not doing ANY admin for turning gallery images back on for now.
#if 0
		// not officially sanctioned function to add an item back to the gallery
		// no charging is done
		// we can only do this if they are UnFeaturedGallery, or UnGallery: this implies that the item was started with a gallery setting
		if ((mpItem->GetGalleryType() != NoneGallery) && (gallery==1)) {
			// should also have code to make sure the thumb DB is updated as well
			
			// we need to make this gallery pic unfeatured
			mpItem->SetGalleryType(Gallery);
			mpItem->Update();

			// try to make this item show
#ifndef GALLERY_MULTIPLE_THUMBNAIL_SERVERS
//			sprintf(pRedirectURL, "http://pete.corp.ebay.com/ed/thumbServe.dll?MakeUnNoisy&item=%d", mpItem->GetId());
//			sprintf(pRedirectURL, "http://mangrove.ebay.com/ed/thumbServe.dll?MakeUnNoisy&item=%d", mpItem->GetId());
			sprintf(pRedirectURL, "http://thumbnails.ebay.com/ed/thumbServe.dll?MakeUnNoisy&item=%d", mpItem->GetId());
#endif
			// reset Gallery
//			mpItem->SetNewGallery(true);

			galleryRedirect = true;
		}
#endif
		
#ifdef GALLERY_MULTIPLE_THUMBNAIL_SERVERS
		{
			char **thumbmachines = sThumbnailservers;
			int nummachines = sizeof (sThumbnailservers) / sizeof (char *);
			int i = 0;
			int systemReturn, err;
			
			char commandString[1024], tempStr[10];
			
			// call all thumbnail servers we know about with the item # to remove
			if(galleryRedirect) {
				*mpStream << "<h2>Gallery Admin</h2>";
				*mpStream << "You selected an operation that affected Gallery operations. Here are the results for each thumbnail server.<br><br>";
				
				if (i >= nummachines)		// sanity check
					i = 0;
				
				while(i < nummachines) {
					if(thumbmachines[i] != "") {
						strcpy(commandString, "c:\\bin\\wget -t 10 -O dontcare -T 5 http://");
						strcat(commandString, thumbmachines[i]);
						strcat(commandString, "/aw-cgi/thumbServe.dll?MakeNoisy?item=");
						sprintf(tempStr, "%d", mpItem->GetId());
						strcat(commandString, tempStr);
						
						systemReturn = system(commandString);
						err = errno;

						// unfortunately systemReturn & errno will not tell us if the problem was
						// a missing exec or a bad HTTP response. we always get '1'
						if(systemReturn == 0) {
							// success!
							*mpStream << "<strong>";
							*mpStream << thumbmachines[i]
								<< "</strong> accepted MakeNoisy for item: "
								<< mpItem->GetId()
								<< ". The item's picture will no longer be shown by this Gallery server.<br><br>";
//						} else if(systemReturn == 1) {
							// failure!
//							*mpStream << "'wget' application could not be found on this admin machine. It should be in c:\\bin\\"
//								<< "<br>";
						} else if(systemReturn != 0) {
							// failure!
							*mpStream << "<strong>";
							*mpStream << thumbmachines[i]
								<< "</strong> did <strong>NOT</strong> accept MakeNoisy for item: "
								<< mpItem->GetId()
//								<< ". Check to make sure the application \"c:\\bin\\wget\" is present on the Admin machine.<br>";
								<< ". Right now this item will still be shown by this server."
								<< "<br> You can try to force this command directly on the server by using this link: "
								<< "<a href=\"http://";
							*mpStream <<	thumbmachines[i]
								<<	"/ed/thumbserve.dll?MakeNoisy&item="
								<< mpItem->GetId()
								<<	"\">"
								<< thumbmachines[i]
								<<	"</a><br><br>";
						}
					}
					i++;
				}
				
				// flag to disable redirect
				sprintf(pRedirectURL, "-");

				
		*mpStream << "<br><form method=\"POST\" action=\""
			<< mpMarketPlace->GetAdminPath() // PageChangeItemInfo
			<<	"eBayISAPI.dll?\">\n"
			"<input TYPE=\"hidden\" NAME=\"MfcISAPICommand\" VALUE=\"ChangeItemInfo"
			<< "\">\n<input TYPE=\"hidden\" NAME=\"item\" VALUE=\""
			<< mpItem->GetId()
			<< "\">\n"
			"<b> Click "
			"<input type=\"submit\" value=\"here\"> "
			"to return to ChangeAdminInfo page.</b>\n"
			"</form>\n";
				
			*mpStream << "<br>"
						  << mpMarketPlace->GetFooter();
			}
		}
#endif
		

// All done with this user, we don't need their account any more
		if (pAccount)
		{
			delete		pAccount;
			pAccount	= NULL;
		}
		if (pUser)
		delete pUser;

		//now go to all existing updates
	    mpItem = mpItems -> GetItem(item, true);
	    mpItem->SetTitle(pTitle);

		mpItem->SetIconFlags(pGiftIcon);


	    mpItem->SetQuantity(nQuantity);
	    mpItem->UpdateItem();

        mpItem->SetNewDescription(pDescription);

		//update sale_end time, need time convertion
	    endTime = mpItem->GetEndTime();
	    timeAsTm = localtime(&endTime);
	    sscanf(pcEndTime, "%d/%d/%d",  &(timeAsTm->tm_mon), 
	  	                               &(timeAsTm->tm_mday),
	  								   &(timeAsTm->tm_year));
		sscanf(pcEndTimeHour, "%d", &(timeAsTm->tm_hour));
		sscanf(pcEndTimeMin,  "%d", &(timeAsTm->tm_min));
		sscanf(pcEndTimeSec,  "%d", &(timeAsTm->tm_sec));

        timeAsTm->tm_mon--;
	    endTime = mktime(timeAsTm);
	  
	    mpItem->SetNewEndTime(endTime); 
	  	 
	//Redirection for production :
		if(galleryRedirect) {
			// redirect to the thumbserve(r) to add this item to the live "noise photo" list
//	sprintf(pRedirectURL, "http://pete.corp.ebay.com/ed/thumbServe.dll?MakeNoisy&item=%d", mpItem->GetId());
//			pServer->EbayRedirect(pCtxt, newURL);
		} else {
			sprintf(pRedirectURL,
			"%seBayISAPI.dll?ChangeItemInfo&item=%d",
			 mpMarketPlace->GetAdminPath(),
		     mpItem->GetId());
		}
	}      
	CleanUp();
	return true;
}
// 
// Mail Unfeature Auction Notification to the user
//
int clseBayApp::MailUnfeatureNotice(clsUser *pUser)
{
	clsMail		*pMail;
	ostream		*pMStream;
	char		subject[256];
	int			mailRc;

	// We need a mail object
	pMail		= new clsMail;
	pMStream	= pMail->OpenStream();


	// Start Message Text
	*pMStream 
		<<	"Dear eBay User:"
		<<	"\n\n";

	*pMStream	<<	"\n"
				<< "We have recently begun enforcing our existing Featured Auction policy as"
				<<	"\n"
				<<  " stated below (and also on the page where you can feature your auction which"
				<<	"\n"
				<<  " can be reviewed at http://cgi.ebay.com/aw-cgi/eBayISAPI.dll?Featured):"
				<<	"\n"
				<<	"\n"

				<<  "eBay reserves the right to refuse placement of your listing in the Featured"
				<<	"\n"
				<<  " Auction section. The following types of auctions are not eligible for" 
				<<	"\n"
				<<  "placement in the section:"
				<<	"\n"
				<<	"\n"

				<<  "* Listings of an adult nature. " 
				<<	"\n"
				<<  "* Listings for services or for the sale of information." 
				<<	"\n"
				<<	"* Listings that are of a promotional/advertising nature."
				<<	"\n"
				<<	"* Listings that may be illicit, illegal, or immoral."
				<<	"\n"
				<<	"* Listings that do not offer a genuine auction per eBay's Guidelines."
				<<	"\n"
				<<	"\n"


				<<  "Please note that this list is not exhaustive."
				<<	"\n"
				<<	"\n"

				<<  "While we understand that the great majority of items listed in the section" 
				<<	"\n"
				<<  " are worthy of placement in the Featured Auction section, we have" 
				<<	"\n"				
				<<  " unfortunately received numerous complaints from our community members about" 
				<<	"\n"				
				<<  " the quality of some of those items. Because we want to comply with the" 
				<<	"\n"				
				<<  " community's high standards, we feel the need to enforce this policy more" 
				<<	"\n"				
				<<  "rigorously." 
				<<	"\n"
				<<	"\n"

				<<  "Thus, our decisions on placement in the Featured Auction section are final." 
				<<	"\n"				
				<<  " We reserve the right to refuse placement in the Featured Auction section" 
				<<	"\n"				
				<<  " for any auction for any reason without explanation." 
				<<	"\n"
				<<	"\n"

				<<  " If we determine that an item does not meet the Featured Auction standards, "
				<<	"\n"
				<<  "we will:" 
				<<	"\n"
				<<	"\n"

				<<  "* Unfeature the auction," 
				<<	"\n"				
				<<  "* Refund the Featured Auction fee, and" 
				<<	"\n"				
				<<  " * Notify the seller by e-mail of our actions." 
				<<	"\n"
				<<	"\n"


				<<  "Thank you for your understanding in this matter and especially your help in" 
				<<	"\n"				
				<<  " supporting the eBay community. " 
				<<	"\n"
				<<	"\n"

				<<  " eBay Customer Support." 
				<<	"\n"
				<<	"\n";
				;

	*pMStream	<<	flush;


	// Send
	sprintf(subject, "Featured Auction Enforcement ");

	// Inna's note 2nd parm is From User; who should it be? 
	// this should be ccard@ebay.com, it is not a member of class marketplace
	// i can just hard coded it here, or add another static data member to 
	// marketplace. This how it would look if I did chage market place
	   mailRc =	pMail->Send(pUser->GetEmail(), 
					"support@ebay.com",
							subject);



	// All done!
	delete	pMail;

	return mailRc;
}
/** INNA End ***/
