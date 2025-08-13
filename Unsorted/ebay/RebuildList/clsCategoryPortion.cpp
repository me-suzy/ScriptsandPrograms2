/*	$Id: clsCategoryPortion.cpp,v 1.4 1999/02/21 02:23:48 josh Exp $	*/
//
//	File:	clsCategoryPortion.cpp
//
//	Class:	clsCategoryPortion
//
//	Author:	Wen Wen
//
//	Function:
//			Listing the child categories for the current category
//
// Modifications:
//				- 07/07/97	Wen - Created
//
#include "clsRebuildListApp.h"
#include "clsDatabase.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsCategoryPortion.h"
#include "clsMarketPlace.h"
#include "clsFileName.h"

#define COLUMN 3

clsCategoryPortion::clsCategoryPortion(clsCategory*		pCurrentCategory,
									   CategoryVector*	pCategories,
									   TimeCriterion	TimeStamp)
{
	mpCategory = pCurrentCategory;
	mpChildren = pCategories;
	mTimeStamp = TimeStamp;

	// get the app pointer
	mpApp = (clsRebuildListApp*) gApp;

	// get file name
	mpFileName = mpApp->GetFileName();

	// Get categoires
	mpCategories = mpApp->GetCategories();

	// Get Items
	mpItems = mpApp->GetItems();
}

clsCategoryPortion::~clsCategoryPortion()
{
}

// retrieve information of the children categories
void clsCategoryPortion::Initialize()
{
}


// Print the children categories
void clsCategoryPortion::Print(ostream* pOutputFile)
{
	if (mpCategory)
	{
		if (mpChildren && mpChildren->size())
		{
			mNextCategoryLevel = mpCategory->catLevel() + 1;

			// start output
			*pOutputFile << "<p><table width=\"100%\">\n<tr><td width=\"33%\" align=left valign=top>\n";

			// Print the category
			mCategoryCount = mpCategory->GetNumberOfDescendants();
			mCurrentColumn = 1;
			mLineNumber = 0;

			// recursivly print categories
			PrintCategory(pOutputFile, mpChildren, true);

			// end output
			*pOutputFile << "</td>";

			// fill up missing column
			for (; mCurrentColumn < COLUMN; mCurrentColumn++)
			{
				*pOutputFile << "<td width=\"33%\"></td>";
			}

			*pOutputFile << "</tr></table>";
		}
		*pOutputFile << "<a name=\"eBayListings\">&nbsp;</a>";
	}
	else
	{
		mNextCategoryLevel = 1;

		// start output
		*pOutputFile << "<p><table width=\"100%\">\n<tr><td width=\"33%\" align=left valign=top>\n";

		// Print the category
		mCategoryCount = mpCategories->GetFirstTwoLevelCategoryCount();
		mCurrentColumn = 1;
		mLineNumber = 0;
		PrintCategory(pOutputFile, mpChildren, false);

		// end output
		*pOutputFile << "</td>";

		// fill up missing column
		for (; mCurrentColumn < COLUMN; mCurrentColumn++)
		{
			*pOutputFile << "<td width=\"33%\"></td>";
		}

		*pOutputFile << "</tr></table>";

		if (mTimeStamp == GOING)
		{
			*pOutputFile << "<a name=\"eBayListings\">&nbsp;</a>";
		}
	}

	if (mTimeStamp == COMPLETED)
	{
		*pOutputFile << "<p><font size=-1><center>For more items in other days, please click the following links<br>\n";

		if (mpApp->GetNextDayString())
		{
                	*pOutputFile << "<a href=\""
                                << mpFileName->GetRelativeLinkName(mpCategory, mTimeStamp, 1, mpApp->GetNextDay())
                                << "\">"
                                << mpApp->GetNextDayString()
                                << "</a>&nbsp;&nbsp;\n";
		}

		if (mpApp->GetPreviousDayString())
		{
                	*pOutputFile << "<a href=\""
                                << mpFileName->GetRelativeLinkName(mpCategory, mTimeStamp, 1, mpApp->GetPrevDay())
                                << "\">"
                                << mpApp->GetPreviousDayString()
                                << "</a>&nbsp;&nbsp;\n";
		}
		*pOutputFile << "</center></font>\n";
	}

	*pOutputFile << "<p><font size=\"1\"><center>All trademarks rights are owned by their respective holders</center></font></p>";

}

void clsCategoryPortion::PrintCategory(ostream* pOutStream, 
										CategoryVector* pCategories,
										bool Deep)
{
	CategoryVector::iterator	iCategory;
	CategoryVector				ChildCategories;
	int		i;
	int		Level;

	// reserve space
	ChildCategories.reserve(20);

	// print each category
	for (iCategory = pCategories->begin(); iCategory != pCategories->end(); iCategory++)
	{
		Level = (*iCategory)->catLevel();
		if (Level == mNextCategoryLevel)
		{
			// It is the top level category, try to determine whether
			// to start a new column
			if ((mLineNumber >= mCategoryCount / COLUMN) && mCurrentColumn < COLUMN)
			{
				*pOutStream << "</td><td width=\"33%\" align=left valign=top>\n";
				mCurrentColumn++;
				mLineNumber = 0;
			}
			else
			{
				if (mLineNumber != 0 && !(*iCategory)->isLeaf())
				{
					*pOutStream << "<p>";
				}
			}
			*pOutStream << "<strong>"
						<<GetCategoryLink(*iCategory)
						<< "</strong><br>\n";
		}
		else
		{
			for (i = 0; i < (Level - mNextCategoryLevel) * 4; i++)
			{
				*pOutStream << "&nbsp;";
			}

			*pOutStream << "<font size=2>"
						<< GetCategoryLink(*iCategory)
						<< "</font><br>\n";
		}

		if (!(*iCategory)->isLeaf() && (Deep || (!Deep && Level < 2)))
		{
			mpCategories->Children(&ChildCategories, *iCategory);
			PrintCategory(pOutStream, &ChildCategories, Deep);
			clsRebuildListApp::CleanUpVector(&ChildCategories);

			if (Level == mNextCategoryLevel)
			{
				*pOutStream << "<p>";
			}
		}

		mLineNumber++;
	}
	*pOutStream << "\n";
}

//
// Print the categories in going, going, gone section
void clsCategoryPortion::PrintTopGoingPage(ostream* pOutputFile)
{
	CategoryVector::iterator	iCategory;
	int		Column;

	if (mpChildren == NULL)
	{
		return;
	}

	// print the the top categories
	Column = 0;
	*pOutputFile << "<p><table width=\"100%\"><tr>";
	for (iCategory = mpChildren->begin(); iCategory != mpChildren->end(); iCategory++)
 	{
		*pOutputFile << "<td width=\"33%\" valign=top><strong>";
		*pOutputFile << GetCategoryLink(*iCategory)
					 << "</strong></td>\n";

		Column++;
		if (Column == 3 && (iCategory+1) < mpChildren->end())
		{
			*pOutputFile << "</tr></table><table width=\"100%\" border=0><tr>\n";
			Column = 0;
		}
	}
	for (; Column && Column < 3; Column++)
	{
		*pOutputFile << "<td>&nbsp;</td>";
	}
	*pOutputFile << "</tr></table><p>\n";

}


char* clsCategoryPortion::GetCategoryAnchor(clsCategory* pCategory)
{
	sprintf(mCategoryAnchor, "cat-%d", pCategory->GetId());

	return mCategoryAnchor;
}


int clsCategoryPortion::GetNumberOfItemsInCategory(clsCategory* pCategory)
{
	int ItemCount;

	switch (mTimeStamp)
	{
	case LISTING:
	case COMPLETED:
		ItemCount = mpItems->GetNumberOfListingItemsInCategory(pCategory->GetId());
		break;

	case NEW_TODAY:
		ItemCount = mpItems->GetNumberOfNewTodayItemsInCategory(pCategory->GetId());
		break;

	case END_TODAY:
		ItemCount = mpItems->GetNumberOfEndingTodayItemsInCategory(pCategory->GetId());
		break;

	case GOING:
		ItemCount = mpItems->GetGoingItemCountInCategory(pCategory->GetId());
		break;
	}

	return ItemCount;
}

char* clsCategoryPortion::GetCategoryLink(clsCategory* pCategory)
{
/*	// check whether it needs BIG BOOK for the adult auction
	if (pCategory->isAdult() && (!mpCategory || (mpCategory && !mpCategory->isAdult())))
	{
		return GetBigBookLink(pCategory);
	}
	else
*/	{
		sprintf(mCategoryLink, "<a href=\"%s\">%s (%d)</a>", 
			mpFileName->GetLinkName(pCategory, mTimeStamp, 1, mpApp->GetBuildDay()),
			pCategory->GetName(), 
			GetNumberOfItemsInCategory(pCategory));
	}

	return mCategoryLink;
}

char* clsCategoryPortion::GetBigBookLink(clsCategory* pCategory)
{
    sprintf(mCategoryLink, "<a href=\"%s\">%s (%d)</a>\n",
            mpFileName->GetAdultLinkName(pCategory, mTimeStamp),
			pCategory->GetName(), 
			GetNumberOfItemsInCategory(pCategory));

	CreateBigBook(pCategory);

	return mCategoryLink;
}

void clsCategoryPortion::CreateBigBook(clsCategory* pCategory)
{
	FILE*	pIStream;
	char	Buffer[1000];
	size_t	SizeRead;
	char*	pBigBookFileName;
	ofstream	OutStream;

	// get the big book file name
	pBigBookFileName = mpFileName->GetAdultFileName(pCategory, mTimeStamp);
	OutStream.open(pBigBookFileName, ios::out);

	// Use the default header
	OutStream << "<html><head><title>eBay Adult Only Information</title></head>\n";
	//OutStream << mpApp->GetMarketPlace()->GetRelativeHeader();
	OutStream << mpApp->GetMarketPlace()->GetHeader();

	// get the big book
	pIStream = fopen("templates/adult", "r");
	Buffer[sizeof(Buffer)-1] = 0;
	while ((SizeRead = fread(Buffer, sizeof(char), sizeof(Buffer)-1, pIStream)) == sizeof(Buffer)-1)
	{
		OutStream << Buffer;
	}
	Buffer[SizeRead] = '\0';
	OutStream << Buffer;
	fclose(pIStream);

	// output the link to the adult category page
	OutStream << "View <a href=\""
			  << mpFileName->GetLinkName(pCategory, mTimeStamp, 1, mpApp->GetBuildDay())
			  << "\">"
			  << pCategory->GetName()
			  << "</a> listings\n";

	if (mpCategory)
	{
		strcpy(Buffer, mpCategory->GetName());
	}
	else
	{
		strcpy(Buffer, "top category");
	}

	// offer link back to current cateogory
	OutStream << "<p><a href=\""
			  << mpFileName->GetLinkName(mpCategory, mTimeStamp, 1, mpApp->GetBuildDay())
			  << "\">Go back to "
			  << Buffer
			  << "</a>\n";

	// Use the default footer
	//OutStream << mpApp->GetMarketPlace()->GetRelativeFooter();
	OutStream << mpApp->GetMarketPlace()->GetFooter();

	// done! close the stream
	OutStream.close();
}
