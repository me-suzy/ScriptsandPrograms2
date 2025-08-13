/*	$Id: clseBayAppAdminAddScreeningCriteriaShow.cpp,v 1.2 1999/05/19 02:34:20 josh Exp $	*/
//
//	File:		clseBayAppAddScreeningCriteriaShow.cpp
//
//	Class:		clseBayApp
//
//	Author:		Lou Leonardo (lou@ebay.com)
//
//	Function:	clseBayApp::clseBayAppAddScreeningCriteriaShow
//
//
//	Modifications:
//				- 04/11/99 lou - Created

//	For use with Legal Buddies and Bottom Feeder.


#include "ebihdr.h"

static const int nAddAction = 0;
static const int nModifyAction = 1;
static const int nDeleteAction = 2;

// Define title string
static const char *TitleText[] =
{
"Save Screening Criteria",
"Modify Screening Criteria",
"Remove Screening Criteria"
};

static const char *BottomText[] =
{
"The selected item was added to the Screening Criteria.",
"NO MODIFY OPTION",
"The selected item was removed from the Screening Criteria."
};


void clseBayApp::AdminAddScreeningCriteriaShow(CEBayISAPIExtension *pThis,
												CategoryId categoryid,
												FilterId filterid,
												MessageId messageid,
												int action,
												eBayISAPIAuthEnum authLevel) 
{
	clsCategory				*pRealCategory = NULL;

	clsCategoryFilter		*pCatFilter = NULL;
	clsCategoryMessage		*pCatMessage = NULL;
	bool					error = false;

	// Setup
	SetUp();	

	// Title
	EmitHeader(TitleText[action]);

	// Let's see if we're allowed to do this
	if (!CheckAuthorization(authLevel))
	{
		CleanUp();
		return;
	}

	// Header
	*mpStream	<<	"\n<h2>"
				<<	TitleText[action]
				<<	"</h2>\n";

	// Make sure we have a valid category, get from cache so we don't need to delete
	if (categoryid)
		pRealCategory	= mpCategories->GetCategory(categoryid, true);

	if (!categoryid || !pRealCategory)
	{
		*mpStream <<	"<p><b>Category Missing:</b><br>"
						"Please make sure that a category is selected.\n";

		error	= true;
	}

	// Check to see what action we are taking
	switch (action)
	{
		case nAddAction:
			// Make sure we are adding something
			if (!(filterid || messageid))
			{
				*mpStream <<	"<p><b>No Filter or Message Selected:</b><br>"
								"Please select a Filter or Message to add "
								"to the selected category criteria.\n";
				error	= true;
			}

			// Only allow 1 selection from filter and category
			if (filterid && messageid)
			{
				*mpStream <<	"<p><b>Filter and Message Selected:</b><br>"
								"You can only select 1 filter OR message at a time.\n";
				error	= true;
			}

			// See if filter selected
			if (filterid && !error)
			{
				//See if filter is already attached to this category or ancestor category
				if (mpMarketPlace->GetFilters()->IsThisOrParentCategoryFilter(categoryid, filterid))
				{
					// Filter already exists, let user know
					*mpStream	<<	"<p><b>Duplicate Filter:</b><br>\n"
								<<	"The filter selected is already being used by this category.</b>\n";

					// Clean up memory
					error = true;
				}
				else
				{
					// Create new category filter
					if (!mpCategories->GetCategoryFilters()->AddCategoryFilter(categoryid, filterid))
					{
						*mpStream	<<	"<p><b>The category filter was not able to be stored.</b>\n";
	
						error = true;
					}
				}
			}

			// See if message selected
			if (messageid && !error)
			{
				//See if message is already attached to this category or ancestor category
				if (mpMarketPlace->GetMessages()->IsThisOrParentCategoryMessage(categoryid, messageid))
				{
					// Message already exists, let user know
					*mpStream	<<	"<p><b>Duplicate Message:</b><br>\n"
								<<	"The message selected is already being used by this category.</b>\n";

					// Set error flag
					error = true;
				}
				else
				{
					// Create new category filter
					if (!mpCategories->GetCategoryMessages()->AddCategoryMessage(categoryid, messageid))
					{
						*mpStream	<<	"<p><b>The category message was not able to be stored.</b>\n";

						error = true;
					}
				}
			}
			break;

		case nDeleteAction:
			// Make sure we have something selected
			if (!filterid && !messageid)
			{
				*mpStream <<	"<p><b>No Filter or Message Selected:</b><br>"
								"Please select a Filter or Message to remove "
								"from the selected category.\n";
				error	= true;
			}
			else
			{
				if (filterid)
				{
					// Try and get the filter for this category
					pCatFilter = mpCategories->GetCategoryFilters()->GetCategoryFilter(categoryid, filterid);
					if (pCatFilter)
					{
						// Delete Category Filter
						mpCategories->GetCategoryFilters()->DeleteCategoryFilter(categoryid, filterid);

						// Clean up memory
						delete pCatFilter;
						pCatFilter = NULL;
					}
					else
					{
						// Filter not attached to selected category, 
						//	Check to see if it's attached to an ancestor
						if (mpMarketPlace->GetFilters()->
										IsThisOrParentCategoryFilter(categoryid, filterid))
						{
							// Filter exists, let user know to remove from ancestor
							*mpStream	<<	"<p><b>Filter Selected by Parent Category :</b><br>\n"
										<<	"The filter selected is being used by a parent category. "
										<<	"You must remove this filter from the parent category.\n";
							error = true;
						}
						else
						{
							// Filter exists, let user know to remove from ancestor
							*mpStream	<<	"<p><b>Unknown Filter Error:</b><br>\n"
										<<	"The filter selected can not be found. "
										<<	"This is a internal error and should be reported.\n";
							error = true;
						}
					}
				}
				else if	(messageid)
				{
					// Try to get the message for this category
					pCatMessage = mpCategories->GetCategoryMessages()->GetCategoryMessage(categoryid, messageid);
					if (pCatMessage)
					{
						// Delete Category Message
						mpCategories->GetCategoryMessages()->DeleteCategoryMessage(categoryid, messageid);

						// Clean up memory
						delete pCatMessage;
						pCatMessage = NULL;
					}
					else
					{
						// Filter not attached to selected category, 
						//	Check to see if it's attached to an ancestor
						if (mpMarketPlace->GetMessages()->
										IsThisOrParentCategoryMessage(categoryid, messageid))
						{
							// Filter exists, let user know to remove from ancestor
							*mpStream	<<	"<p><b>Message Selected by Parent Category :</b><br>\n"
										<<	"The message selected is being used by a parent category. "
										<<	"You must remove this message from the parent category.\n";
							error = true;
						}
						else
						{
							// Filter exists, let user know to remove from ancestor
							*mpStream	<<	"<p><b>Unknown Message Error:</b><br>\n"
										<<	"The message selected can not be found. "
										<<	"This is a internal error and should be reported.\n";
							error = true;
						}
					}
				}
			}
			break;

		default:
			error = true;
			break;
	}

	// Let user know if it went ok
	if (!error)
	{
		*mpStream	<<	"<p><strong>"
					<<	BottomText[action]
					<<	"</strong><br>\n";
	}
	else
	{
		*mpStream	<<	"<p><b>Please go back and try again.</b>\n";
	}

	// Have link to View Category Screening Criteria
	*mpStream	<<	"<p><a href=\""
				<<	mpMarketPlace->GetCGIPath(PageAdminViewScreeningCriteriaShow)
				<<	"eBayISAPI.dll?AdminViewScreeningCriteriaShow&categoryid="
				<<	categoryid
				<<	"\">"
				<<	"View current Filters and Messages for this Category.</a>\n";

	// Footer
	*mpStream	<<	"<p>"
				<<	mpMarketPlace->GetFooter()
				<<	flush;

	CleanUp();

	return;
}

