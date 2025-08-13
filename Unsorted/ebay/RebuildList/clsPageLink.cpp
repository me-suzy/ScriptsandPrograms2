/*	$Id: clsPageLink.cpp,v 1.3 1999/02/21 02:24:00 josh Exp $	*/
//	File:	clsPageLink.cpp
//
//	Class:	clsPageLink
//
//	Author:	Wen Wen
//
//	Function:
//			Print the the link to other item pages
//
// Modifications:
//				- 08/19/97	Wen - Created
//
#include "clsRebuildListApp.h"
#include "clsFileName.h"
#include "clsPageLink.h"

clsPageLink::clsPageLink(clsCategory*		pCategory,
						 int				NumberOfItems,
						 TimeCriterion		TimeStamp,
						 clsFileName*		pFileName,
						 int				ItemsPerPage
						 )
{
	mpCategory   = pCategory;
	mNumberOfItems = NumberOfItems;
	mTimeStamp   = TimeStamp;
	mpFileName   = pFileName;
	mItemsPerPage= ItemsPerPage;

	mBuildDay = ((clsRebuildListApp*) GetApp())->GetBuildDay();
	if (TimeStamp == COMPLETED)
	{
		mpPreviousDayString = ((clsRebuildListApp*) GetApp())->GetPreviousDayString();
		mpNextDayString = ((clsRebuildListApp*) GetApp())->GetNextDayString();
		mPrevDay = ((clsRebuildListApp*) GetApp())->GetPrevDay();
		mNextDay = ((clsRebuildListApp*) GetApp())->GetNextDay();
	}
}


void clsPageLink::Print(ostream* pOutputFile, int CurrentPage)
{
	const char*	pNextFileName;
	char		PageNumber[10];
	int			i;
	int		NumberOfPages;
	int		Limit = 5;

	*pOutputFile << "<p><font size=-1><center>For more items in this category, click on the following pages:<br>\n";

	// determind whether to print for next date
	if (mTimeStamp == COMPLETED && mpNextDayString)
	{
		pNextFileName = mpFileName->GetRelativeLinkName(mpCategory, mTimeStamp, 1, mNextDay);

		*pOutputFile << "<a href=\""
				<< pNextFileName
				<< "\">"
				<< mpNextDayString
				<< "</a>&nbsp;&nbsp;\n";
	}
		
	if (mNumberOfItems > mItemsPerPage)
	{

	NumberOfPages = (mNumberOfItems-1)/mItemsPerPage + 1;

	// determind whether to print the previous
	if (CurrentPage != 1)
	{
		pNextFileName = mpFileName->GetRelativeLinkName(mpCategory, mTimeStamp, CurrentPage-1, mBuildDay);

		// link to next page
		*pOutputFile << "<a href=\""
				 << pNextFileName
				 << "\">&lt;&lt;</a>&nbsp;&nbsp;\n";
	}

	if (NumberOfPages < 25)
	{
		for (i = 1; i <= NumberOfPages; i++)
		{
			if (i != CurrentPage)
			{
				pNextFileName = mpFileName->GetRelativeLinkName(mpCategory, mTimeStamp, i, mBuildDay);

				// link to next page
				*pOutputFile << "<a href=\""
						 << pNextFileName
						 << "\">";
				sprintf(PageNumber, "[%d]</a>\n", i);
			}
			else
			{
				sprintf(PageNumber, " = %d = ", i);
			}
			*pOutputFile << PageNumber;

		}
	}
	else
	{
		for (i = 1; i <= NumberOfPages; i++)
		{
			if (i != CurrentPage)
			{
				if (abs(CurrentPage - i) <= Limit || i % 10 == 0 || i == 1 || i == NumberOfPages)
				{
					pNextFileName = mpFileName->GetRelativeLinkName(mpCategory, mTimeStamp, i, mBuildDay);

					// link to next page
					*pOutputFile << "<a href=\""
							<< pNextFileName
							<< "\">";
					sprintf(PageNumber, "[%d]</a>\n", i);
					*pOutputFile << PageNumber;

					if ((CurrentPage - i) > Limit+1 || 
						((i - CurrentPage) >= Limit && (NumberOfPages - i) > 1) && (10 - i % 10) > 1)
					{
						*pOutputFile << "...";
					}
				}
			}
			else
			{
				sprintf(PageNumber, " = %d = ", i);
				*pOutputFile << "<b>"
					     << PageNumber
					     << "</b>";
			}
		}
	}

	// determind whether to print the next
	if (CurrentPage != NumberOfPages)
	{
		pNextFileName = mpFileName->GetRelativeLinkName(mpCategory, mTimeStamp, CurrentPage+1, mBuildDay);

		// link to next page
		*pOutputFile << "&nbsp;&nbsp;<a href=\""
				 << pNextFileName
				 << "\">&gt;&gt;</a>\n";
	}

	} // end 

	// determind whether to print for previous date
	if (mTimeStamp == COMPLETED && mpPreviousDayString)
	{
		pNextFileName = mpFileName->GetRelativeLinkName(mpCategory, mTimeStamp, 1, mPrevDay);

		*pOutputFile << "<a href=\""
				<< pNextFileName
				<< "\">"
				<< mpPreviousDayString
				<< "</a>&nbsp;&nbsp;\n";
	}

	*pOutputFile << "</center></font><p>\n";
}

