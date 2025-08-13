/*	$Id: clseBayAppAdminScreenItem.cpp,v 1.2.2.2 1999/05/21 06:24:29 poon Exp $	*/
//
//	File:		clseBayAppAdminScreenItem.cpp
//
//	Class:		clseBayApp
//
//	Author:		Mila Bird (mila@ebay.com)
//
//	Function:
//				Screen an item based on category-specific criteria,
//				and report blocking/flagging of item to appropriate
//				folks via eNotes and/or email
//
//
//	Modifications:
//				- 04/18/99 mila		- Created
//

#include "ebihdr.h"
#include "vector.h"
#include "eBayTypes.h"
#include "clsItemValidator.h"
#include "clsNote.h"
#include "clsNotes.h"

// template for new item by flagged seller
static const char eNoteNewItemByFlaggedSellerTemplate[] =
"eBay user %s has been previously flagged for selling "
"items which were blocked, and has just listed, or attempted to "
"list, item %s (%s).\n";

// template for item info update by flagged seller
static const char eNoteItemUpdateInfoByFlaggedSellerTemplate[] =
"eBay user %s has been previously flagged for selling "
"items which were blocked, and has just attempted to "
"update item %s (%s) with information including these "
"words/phrases: \n"
"%s\n";

// template for item add to description by flagged seller
static const char eNoteItemAddToDescByFlaggedSellerTemplate[] =
"eBay user %s has been previously flagged for selling "
"items which were blocked, and has just attempted to "
"add to the description of item %s (%s) with text including these "
"words/phrases: \n"
"%s\n";

// template for item category change by flagged seller
static const char eNoteItemChangeCategoryByFlaggedSellerTemplate[] =
"eBay user %s has been previously flagged for selling "
"items which were blocked, and has just attempted to "
"change the category for item %s (%s) from %s to %s\n";

// template for flagged suspicious item
static const char eNoteSuspiciousItemListedTemplate[] =
"eBay user %s has just listed item %s (%s), which contains "
"these words/phrases in the title and/or description: \n"
"%s\n";

// template for blocked suspicious item
static const char eNoteSuspiciousItemBlockedTemplate[] =
"eBay user %s has just attempted to list item %s (%s), which contains "
"these words/phrases in the title and/or description: \n"
"%s\n";

// template for suspicious item update info
static const char eNoteSuspiciousItemUpdateInfoTemplate[] =
"eBay user %s has just attempted to update information on item %s (%s), "
"which would have contained these words/phrases in the title and/or "
"description, if allowed: \n"
"%s\n";

// template for suspicious item add to description
static const char eNoteSuspiciousItemAddToDescTemplate[] =
"eBay user %s has just attempted to update information on item %s (%s), "
"which would have contained these words/phrases in the title and/or "
"description, if allowed: \n"
"%s\n";

// template for suspicious item category change
static const char eNoteSuspiciousItemChangeCategoryTemplate[] =
"eBay user %s has just attempted to update information on item %s (%s), "
"which would have contained these words/phrases in the title and/or "
"description, if allowed: \n"
"%s\n";

static const char SuspiciousItemAlert[] =
"Alert:  Suspicious Item!";

static const char FlaggedSellerAlert[] =
"Alert: New Item By Flagged Seller";

static const char SuspiciousItemEmailSubject[] =
"Suspicious item blocked/flagged!";

static const char FlaggedSellerEmailSubject[] =
"Item by flagged seller";

static const char MessageSeparator[] =
"\n\n\n";


void clseBayApp::AdminSendEmail(clsItem *pItem, FilterVector *pvFilters, char *pSubject)
{
	char                    *pFrom;
	char                    *pTo;
	char                    *pMsg;
	clsCategory             *thisCategory;
	CategoryId               thisCategoryId;
	MessageId                messageId;
	clsMessages             *messages;
	clsMessage              *message;
	char                    *messageText;
	char                    *tempMessageText;
	char                    *tempStr;
	char                    *tempStr1;
	FilterVector::iterator   i;
	int                      j;

	// Decide the 'To' list to send to - based on the category of this item.
	thisCategoryId = pItem->GetCategory();
	thisCategory = mpCategories->GetCategory(thisCategoryId, true);

//#ifdef _DEBUG
#if 0	// 0 = production, 1 = test
#pragma message("Warning: SEND LEGAL BUDDIES EMAIL USING DEBUG MODE - THIS NEEDS TO BE CHANGED FOR RELEASE")
	if (thisCategory->BelongsToSubtreeOf(1249 /* "Computers/Software/Games" */))
	{
		pTo = strdup("testuser1@altavista.net");
		// pTo = strdup("anoop@ebay.com");
	}
	else if (thisCategory->BelongsToSubtreeOf(181 /* "Computers/Software" */))
	{
		pTo = strdup("testuser2@altavista.net");
	}
	else if (thisCategory->BelongsToSubtreeOf(266 /* "Books, Movies, Music" */))
	{
		pTo = strdup("testuser3@altavista.net");
	}
	else if ((thisCategory->BelongsToSubtreeOf(319 /* "Miscellaneous/Adult Only" */)) ||
			 (thisCategory->BelongsToSubtreeOf(2037 /* Miscellaneous/Firearms, Adult Only" */)))
	{
		pTo = strdup("testuser4@altavista.net");
	}
	else  /* all other categories */
	{
		pTo = strdup("testuser5@altavista.net");
	}
#else
	if (thisCategory->BelongsToSubtreeOf(1249 /* "Computers/Software/Games" */))
	{
		pTo = strdup("games@ebay.com");
	}
	else if (thisCategory->BelongsToSubtreeOf(181 /* "Computers/Software" */))
	{
		pTo = strdup("comsoft@ebay.com");
	}
	else if (thisCategory->BelongsToSubtreeOf(266 /* "Books, Movies, Music" */))
	{
		pTo = strdup("movmus@ebay.com");
	}
	else if ((thisCategory->BelongsToSubtreeOf(319 /* "Miscellaneous/Adult Only" */)) ||
			 (thisCategory->BelongsToSubtreeOf(2037 /* Miscellaneous/Firearms, Adult Only" */)))
	{
		pTo = strdup("adult@ebay.com");
	}
	else  /* all other categories */
	{
		pTo = strdup("ctywatch@ebay.com");
	}
#endif
	// 'From' is to be kept the same as 'To'.
	pFrom = strdup(pTo);

	// Get the message body for the email.
	tempStr = new char[1024];
	j =     sprintf(tempStr,     "\n------------------------- ");
	j = j + sprintf(tempStr + j, "Item Number = %d", pItem->GetId());
	j = j + sprintf(tempStr + j, " -------------------------");
	j = j + sprintf(tempStr + j, "\n\n\n");
	pMsg = strdup(tempStr);
	delete [] tempStr;

	messages = mpMarketPlace->GetMessages();
	
	for (i = pvFilters->begin(); i != pvFilters->end(); ++i)
	{
		if (((messageId = (*i)->GetFilteringMessageId()) != 0) ||
			((messageId = (*i)->GetBlockingMessageId())  != 0) ||
			((messageId = (*i)->GetFlaggingMessageId())  != 0))
		{
			message  = messages->GetMessage(messageId, true);
			messageText = strdup(message->GetText());
		}
		else
		{
			tempStr1 = strdup((*i)->GetExpression());
			tempMessageText = new char[strlen("Item flagged for using offending pattern \"\".") + 
									   strlen(tempStr1) + 16];
			sprintf(tempMessageText, "Item flagged for using offending pattern \"%s\".", 
				                     tempStr1);
			messageText = strdup(tempMessageText);
			free(tempStr1);
			delete [] tempMessageText;
		}
		tempStr = new char[strlen(pMsg) + strlen(messageText) + strlen(MessageSeparator) + 1];
		strcpy(tempStr, pMsg);
		strcat(tempStr, messageText);
		strcat(tempStr, MessageSeparator);
		free(pMsg);
		free(messageText);
		pMsg = strdup(tempStr);
		delete [] tempStr;
	}

	clsUtilities::SendEmail(pTo, pFrom, pSubject, pMsg);

	free(pFrom);
	free(pTo);
	free(pMsg);
}  /* clseBayApp::AdminSendEmail */


void clseBayApp::AdminReportScreenedItem(clsItem *pItem,
										 clsUser *pUser,
										 FilterVector *pvFilters,
										 ActionType action,
										 ScreenItemType when)
{
	clsNotes *		pNotes = NULL;
	clsNote *		pNote = NULL;

	int				supportId;
	eNoteTypeEnum	type = eNoteTypeUnknown;

	char *			pTemplate = NULL;
	char *			pText = NULL;
	char *			pKeywordsString = NULL;
	char			cItemId[EBAY_MAX_ITEM_SIZE + 1];

	time_t			nowTime = time(0);

	bool			blockListing;
	bool			flagListing;

	FilterVector::iterator		i;
	vector<char *>				vBadWords;
	vector<char *>::iterator	ii;
	vector<char *> *			pvBadWords;
	char *						pExpression;
	char *						pString;

	if (pItem == NULL || action == ActionTypeDoNothing)
		return;

	// Set some variables
	blockListing = ((unsigned int)action & ActionTypeBlockListing) != 0;
	flagListing = ((unsigned int)action & ActionTypeFlagListing) != 0;

	// Figure out which template we need for eNote text
	switch (when)
	{
		case ScreenItemOnListing:
			if (blockListing)
			{
				pTemplate = (char *)eNoteSuspiciousItemBlockedTemplate;
				type = eNoteTypeItemBlockedUponListing;
			}
			else
			{
				pTemplate = (char *)eNoteSuspiciousItemListedTemplate;
				type = eNoteTypeItemFlaggedUponListing;
			}
			break;

		case ScreenItemOnUpdateInfo:
			pTemplate = (char *)eNoteSuspiciousItemUpdateInfoTemplate;
			type = eNoteTypeItemFlaggedUpdateItemInfo;
			break;

		case ScreenItemOnAddToDesc:
			pTemplate = (char *)eNoteSuspiciousItemAddToDescTemplate;
			type = eNoteTypeItemFlaggedAddToDescr;
			break;

		case ScreenItemOnChangeCategory:
			pTemplate = (char *)eNoteSuspiciousItemChangeCategoryTemplate;
			type = eNoteTypeItemFlaggedChangeCategory;
			break;

		default:
			pTemplate = NULL;
			break;
	}

	// Return if the template is invalid
	if (pTemplate == NULL)
		return;

	sprintf(cItemId, "%d", pItem->GetId());

	for (i = pvFilters->begin(); i != pvFilters->end(); ++i)
	{

		// Get filter name since the expression could be very long for eNotes
		pExpression = (*i)->GetName();
		if (pExpression != NULL)
		{
			pString = new char[strlen(pExpression) + 1];
			strcpy(pString, pExpression);
			vBadWords.push_back(pString);
		}
	}
	
	pvBadWords = &vBadWords;
	// Convert vector of keywords into single string
	pKeywordsString = clsUtilities::StringVectorToString(pvBadWords, "\n");

	// Construct the eNote text using the template
	if (blockListing)
	{
		pText = new char[strlen(pTemplate)
						 + EBAY_MAX_USERID_SIZE
						 + strlen(cItemId)
						 + EBAY_MAX_TITLE_SIZE
						 + EBAY_MAX_KEYWORD_SIZE * pvBadWords->size()
						 + 1];	// don't forget the NULL terminator!!!
		sprintf(pText, pTemplate,
				pUser->GetUserId(), cItemId, pItem->GetTitle(), pKeywordsString);
	}
	else
	{
		pText = new char[strlen(pTemplate)
						 + EBAY_MAX_USERID_SIZE
						 + strlen(cItemId)
						 + EBAY_MAX_TITLE_SIZE
						 + EBAY_MAX_KEYWORD_SIZE * pvBadWords->size()
						 + 1];	// don't forget the NULL terminator!!!
		sprintf(pText, pTemplate,
				pUser->GetUserId(), cItemId, pItem->GetTitle(), pKeywordsString);
	}

	pNotes = mpMarketPlace->GetNotes();

	supportId = pNotes->GetSupportUser()->GetId();

	// file an eNote, and attach it to both the item and the seller
	pNote = new clsNote(supportId,
						supportId,
						0,
						pItem->GetId(),
						pItem->GetSeller(),
						eClsNoteFromTypeAutoAdminPost,
						type,
						eClsNoteVisibleSupportOnly,
						nowTime,
						(time_t)0,
						(char *)SuspiciousItemAlert,
						pText);

	pNotes->AddNote(pNote);

	AdminSendEmail(pItem, pvFilters, (char *) SuspiciousItemEmailSubject);

	delete [] pKeywordsString;
	delete [] pText;
	delete pNote;

	for (ii = vBadWords.begin(); ii != vBadWords.end(); ii++)
	{
		delete [] (*ii);
	}
	vBadWords.erase(vBadWords.begin(), vBadWords.end());
}


void clseBayApp::AdminReportItemByFlaggedUser(clsItem *pItem, 
											  clsUser *pUser,
											  FilterVector *pvFilters,
											  ScreenItemType when)
{
	clsNotes *	pNotes = NULL;
	clsNote *	pNote = NULL;

	int			supportId;
	int			type = eNoteTypeUnknown;

	char *		pTemplate = NULL;
	char *		pText = NULL;
	char *		pKeywordsString = NULL;
	char		cItemId[EBAY_MAX_ITEM_SIZE + 1];

	time_t		nowTime = time(0);

	FilterVector::iterator		i;
	vector<char *>				vBadWords;
	vector<char *>::iterator	ii;
	vector<char *> *			pvBadWords;
	char *						pExpression;
	char *						pString;

	// Figure out which template we need for eNote text
	switch (when)
	{
		case ScreenItemOnListing:
			pTemplate = (char *)eNoteNewItemByFlaggedSellerTemplate;
			type = eNoteTypeFlaggedSellerListNewItem;
			break;

		case ScreenItemOnUpdateInfo:
			pTemplate = (char *)eNoteItemUpdateInfoByFlaggedSellerTemplate;
			type = eNoteTypeFlaggedSellerUpdateItemInfo;
			break;

		case ScreenItemOnAddToDesc:
			pTemplate = (char *)eNoteItemAddToDescByFlaggedSellerTemplate;
			type = eNoteTypeFlaggedSellerAddToItemDescription;
			break;

		case ScreenItemOnChangeCategory:
			pTemplate = (char *)eNoteItemChangeCategoryByFlaggedSellerTemplate;
			type = eNoteTypeFlaggedSellerChangeCategory;
			break;

		default:
			pTemplate = NULL;
			break;
	}

	// Return if the template is invalid
	if (pTemplate == NULL)
		return;

	sprintf(cItemId, "%d", pItem->GetId());

	// Get bad words out of the filters.
	for (i = pvFilters->begin(); i != pvFilters->end(); ++i)
	{
		// copy the offending keywords into a vector so we can pass them
		// back to the caller
		pExpression = (*i)->GetExpression();
		if (pExpression != NULL)
		{
			pString = new char[strlen(pExpression) + 1];
			strcpy(pString, pExpression);
			vBadWords.push_back(pString);
		}
	}
	
	pvBadWords = &vBadWords;

	// Convert vector of keywords into single string
	pKeywordsString = clsUtilities::StringVectorToString(pvBadWords, "\n");

	// file an eNote, and attach it to both the item and the seller
	pText = new char[strlen(pTemplate)
					 + EBAY_MAX_USERID_SIZE
					 + strlen(cItemId)
					 + EBAY_MAX_TITLE_SIZE
					 + EBAY_MAX_KEYWORD_SIZE * pvBadWords->size()
					 + 1];	// don't forget the NULL terminator!!!
	sprintf(pText, eNoteNewItemByFlaggedSellerTemplate,
			pUser->GetUserId(), cItemId, pItem->GetTitle(), pKeywordsString);

	pNotes = mpMarketPlace->GetNotes();

	supportId = pNotes->GetSupportUser()->GetId();

	pNote = new clsNote(supportId,
						supportId,
						0,
						pItem->GetId(),
						pItem->GetSeller(),
						eClsNoteFromTypeAutoAdminPost,
						type,
						eClsNoteVisibleSupportOnly,
						nowTime,
						(time_t)0,
						(char *)FlaggedSellerAlert,
						pText);

	pNotes->AddNote(pNote);

	AdminSendEmail(pItem, pvFilters, (char *) FlaggedSellerEmailSubject);

	delete [] pKeywordsString;
	delete [] pText;
	delete pNote;

	for (ii = vBadWords.begin(); ii != vBadWords.end(); ii++)
	{
		delete [] (*ii);
	}
	vBadWords.erase(vBadWords.begin(), vBadWords.end());
}


ActionType clseBayApp::AdminScreenItem(clsItem *pItem,
									   clsUser *pUser,
									   FilterVector *pvFilters,
									   ScreenItemType when,
									   ostream *pStream)
{
	char					cEmailSubject[64];
	ActionType				action = ActionTypeDoNothing;
	bool					blockListing;
	bool					flagListing;

	// Check all our input pointers first...
	if (pItem == NULL || pUser == NULL || pvFilters == NULL || pStream == NULL)
		return ActionTypeDoNothing;

	// Instantiate an item validator object
	clsItemValidator validator(pItem);

	// Screen the item, and get back the caught filters for the category
	action = validator.Validate(pvFilters);

	// Set some variables
	blockListing = ((unsigned int)action & ActionTypeBlockListing) != 0;
	flagListing = ((unsigned int)action & ActionTypeFlagListing) != 0;

	memset(cEmailSubject, 0, 64);
	if (blockListing && when == ScreenItemOnListing)
		sprintf(cEmailSubject, "Item %d blocked from listing", pItem->GetId());

	if (blockListing || flagListing)
	{
		AdminReportScreenedItem(pItem, pUser, pvFilters, action, when);
	}

/*
	//LL: remove this code - requested by support on 5/12/99
	//		It was filling up the eNote Q fast.
	if (pUser->HasABlockedItem())
	{
		if (flagListing)
		{
			//Only send message if we are flagging the listing
			AdminReportItemByFlaggedUser(pItem, pUser, pvFilters, when);
		}
	}
	else
	{
		if (blockListing)
		{
			// Flag the seller so we know to keep an eye on future auctions
			// by this person
			pUser->SetHasABlockedItem(true);
		}
	}
*/
	return action;
}

