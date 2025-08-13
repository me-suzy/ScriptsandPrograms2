/*	$Id: clseBayAppPaginateFeedback.cpp,v 1.3.204.1 1999/05/21 05:44:52 poon Exp $	*/
//
//	File:	clseBayAppPaginateFeedback.cc
//
//	Class:	clseBayApp
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		Contains the methods used to emit page
//		info and pagination controls.
//
// Modifications:
//				- 08/13/98 mila		- Created
//				- 08/14/98 mila		- bug fixes
//				- 08/15/98 mila		- make methods work with both regular and
//									  personalized feedback profiles; add links
//									  to pagination controls
//				- 08/17/98 mila		- changed calculation of pageCount value
//				- 08/25/98 mila		- changed calculation of current page
//				- 09/22/98 mila		- call GetPasswordNoSalt instead of GetPassword;
//									  changed look of pagination controls to match
//									  listings pages
//

#include "ebihdr.h"

#include "hash_map.h"


// PrintFeedbackPageStats
//
// Output a single-row table which displays which feedback
// items are displayed on the current page, and what the
// total number of feedback items is.
//
void clseBayApp::PrintFeedbackPageStats(ostream *mpStream,
					   						int firstItem,
											int lastItem,
											int totalItems)

{
	// Output current page stats.
	*mpStream <<	"<table WIDTH=\"100%\" BORDER=\"1\">"
			  <<	"  <tr><td>";

	if (firstItem == lastItem)		// only one item per page
	{
		*mpStream <<	"    <p ALIGN=\"center\">Item "
				  <<	firstItem
				  <<	" of "
				  <<	totalItems
				  <<	" total";
	}
	else	// more than one item per page
	{
		*mpStream <<	"    <p ALIGN=\"center\">Items "
				  <<	firstItem
				  <<	"-"
				  <<	lastItem
				  <<	" of "
				  <<	totalItems
				  <<	" total";
	}

	*mpStream <<	"  </td></tr>"
			  <<	"</table>"
			  <<	"\n";

}


// EmitFeedbackPageLink
//
// Emit a single link to a page of a feedback profile.  The
// profile will be either the ViewFeedback profile (read-only)
// or the ViewPersonalizedFeedback profile (respond to comments),
// depending on the page enum passed into the method.
// NOTE:  The only two pages handled by this method at this
// time are PageViewFeedback and PageViewPersonalizedFeedback.
// NOTE:  This method does not emit the link text or the
// terminating </a>.
//
void clseBayApp::EmitFeedbackPageLink(ostream *mpStream,
									  char *pUserId,
									  char *pPass,
									  int pageNum,
									  int itemsPerPage,
									  PageEnum page)
{
	if (page == PageViewFeedback)
	{
		*mpStream <<	"<A HREF=\""
				  <<	mpMarketPlace->GetCGIPath(page)
				  <<	"eBayISAPI.dll?ViewFeedback"
				  <<	"&userid="
				  <<	pUserId
				  <<	"&page="
				  <<	pageNum
				  <<	"&items="
				  <<	itemsPerPage
				  <<	"\">";
	}
	else if (page == PageViewPersonalizedFeedback)
	{
		*mpStream <<	"<A HREF=\""
				  <<	mpMarketPlace->GetCGIPath(page)
				  <<	"eBayISAPI.dll?ViewPersonalizedFeedback"
				  <<	"&userid="
				  <<	pUserId
				  <<	"&pass="
				  <<	pPass
				  <<	"&page="
				  <<	pageNum
				  <<	"&items="
				  <<	itemsPerPage
				  <<	"\">";
	}
}


// PrintFeedbackPagingInfo
//
// Output a single-row table which displays the feedback
// item page numbers. all numbers except the current page
// number are links to jump to those pages of feedback items.
//
void clseBayApp::PrintFeedbackPagingInfo(ostream *mpStream,
											int lastItem,
											int totalItems,
											int itemsPerPage,
											PageEnum pageToView,
											clsUser *pUser)

{
	char *		pUserId;
	char *		pPass;

	int			pageCount;
	int			currentPage;
	int			pageNo;
	const int	pageGap = 20;
	const int	pagesLimit = 5;

	pUserId = pUser->GetUserId();
	pPass = pUser->GetPasswordNoSalt();

	if (pUserId == NULL || pPass == NULL)
	{
		return;
	}

	pageCount = (totalItems + itemsPerPage - 1) / itemsPerPage;
	if (pageCount == 1)
	{
		return;
	}

	currentPage = (lastItem + itemsPerPage - 1) / itemsPerPage;
	if (currentPage < 1 || currentPage > pageCount)
	{
		return;
	}

	// Start table to hold paging info.
	*mpStream <<	"<table WIDTH=\"100%\" BORDER=\"1\">"
			  <<	"  <tr><td>"
			  <<	"    <p ALIGN=\"center\">";

	// Output initial "<<", if necessary.
	if (currentPage > 1)
	{
		// Emit link to previous page.
		EmitFeedbackPageLink(mpStream, pUserId, pPass, currentPage - 1, 
							 itemsPerPage, pageToView);
		*mpStream <<	"(previous page)"
				  <<	"</A>";
	}

	*mpStream <<	" ";

	// If there are less than 25 pages of feedback, we just draw all of
	// the page links.
	if (pageCount < 25)
	{
		for (pageNo = 1; pageNo <= pageCount; pageNo++)
		{
			if (pageNo != currentPage)
			{
				// Emit link to specific page.
				EmitFeedbackPageLink(mpStream, pUserId, pPass, pageNo, itemsPerPage, pageToView);
				*mpStream <<	"["
						  <<	pageNo
						  <<	"]"
						  <<	"</A>";
			}
			else
			{
				// Emit page number as "= n =", where n is the page number.
				*mpStream <<	"= "
						  <<	pageNo
						  <<	" =";
			}

			// Emit space character for spacing page links.
			*mpStream <<	" ";
		}
	}
	else	// Otherwise, just draw some of the page links.
	{
		for (pageNo = 1; pageNo <= pageCount; pageNo++)
		{
			if (pageNo != currentPage)
			{
				if (abs(pageNo - currentPage) <= pagesLimit ||
					(pageNo % pageGap == 0) ||
					(pageNo == 1) ||
					(pageNo == pageCount))		// output page number as "[n]"
				{
					// Emit link to specific page.
					EmitFeedbackPageLink(mpStream, pUserId, pPass, pageNo, itemsPerPage, pageToView);
					*mpStream <<	"["
							  <<	pageNo
							  <<	"]"
							  <<	"</A>";

					// Emit ellipsis if we're skipping over pages.
					if ((currentPage - pageNo) > pagesLimit + 1 ||
						((pageNo - currentPage) >= pagesLimit &&
						 (pageCount - pageNo) > 1) && (pageGap - pageNo % pageGap) > 1)
						*mpStream << " ... ";
				}
			}
			else		// output page number as "= n ="
			{
				*mpStream <<	"= "
						  <<	pageNo
						  <<	" =";
			}
			*mpStream <<	" ";
		}
	}

	// Output final ">>", if necessary.
	if (currentPage < pageCount)
	{
		// Emit link to next page.
		EmitFeedbackPageLink(mpStream, pUserId, pPass, currentPage + 1, 
							 itemsPerPage, pageToView);
		*mpStream <<	"(next page)"
				  <<	"</A>";
	}

	*mpStream <<	"  </td></tr>"
			  <<	"</table>"
			  <<	"\n";
}

void clseBayApp::PrintFeedbackPaginationControl(ostream *mpStream,
					   						int firstItem,
											int lastItem,
											int totalItems,
											int itemsPerPage,
											bool controlOnTop,
											PageEnum pageToView,
											clsUser *pUser)

{
	if (controlOnTop)
	{
		PrintFeedbackPageStats(mpStream, firstItem, lastItem, totalItems);
		if (itemsPerPage > 0)
		{
			PrintFeedbackPagingInfo(mpStream, lastItem, totalItems, itemsPerPage,
									pageToView, pUser);
		}
	}
	else
	{
		if (itemsPerPage > 0)
		{
			PrintFeedbackPagingInfo(mpStream, lastItem, totalItems, itemsPerPage,
									pageToView, pUser);
		}
		PrintFeedbackPageStats(mpStream, firstItem, lastItem, totalItems);
	}
}

