/*	$Id: clseBayMyFavoritesWidget.cpp,v 1.4 1998/12/06 05:23:09 josh Exp $	*/
//
//	File:	clseBayMyFavoritesWidget.cpp
//
//	Class:	clseBayMyFavoritesWidget
//
//	Author:	Charles Manga
//
//	Function:
//			Widget that shows Favorites items for a user.
//
// Modifications:
//				- 11/4/97	Charles - Created
//
#include "widgets.h"
#include "clseBayMyFavoritesWidget.h"

clseBayMyFavoritesWidget::clseBayMyFavoritesWidget(clsMarketPlace *pMarketPlace) :
	clseBayTableWidget(pMarketPlace)
{
	mpUser				= NULL;
	mTotalNumberOfCells = 0;
	mTitleColor[0]		= '\0';
	mPassWord[0]		= '\0';
	mUserId[0]			= '\0';

	// Allocating the data structure ...
	mpNames = new mNames[EBAY_NUMBER_OF_INTERESTS];

	for(int j = 0 ; j < EBAY_NUMBER_OF_INTERESTS ; j++)
	{
		// Allocating the data in the structure
		mpNames[j].pName0 = new char[EBAY_FAVORITES_SIZE_OF_NAME];
		mpNames[j].pName1 = new char[EBAY_FAVORITES_SIZE_OF_NAME];
		mpNames[j].pName2 = new char[EBAY_FAVORITES_SIZE_OF_NAME];
		mpNames[j].pName3 = new char[EBAY_FAVORITES_SIZE_OF_NAME];
		mpNames[j].pName4 = new char[EBAY_FAVORITES_SIZE_OF_NAME];
		mpNames[j].pLink1 = new char[EBAY_FAVORITES_SIZE_OF_LINK];
		mpNames[j].pLink2 = new char[EBAY_FAVORITES_SIZE_OF_LINK];
		mpNames[j].pLink3 = new char[EBAY_FAVORITES_SIZE_OF_LINK];
		mpNames[j].pLink4 = new char[EBAY_FAVORITES_SIZE_OF_LINK];
		mpNames[j].pLink5 = new char[EBAY_FAVORITES_SIZE_OF_LINK];
	}

}

clseBayMyFavoritesWidget::~clseBayMyFavoritesWidget()
{
	// Deallocating the data
	for( int j = 0 ; j < EBAY_NUMBER_OF_INTERESTS; j++ )
	{
		delete [] mpNames[j].pName0; 
		delete [] mpNames[j].pName1; 
		delete [] mpNames[j].pName2; 
		delete [] mpNames[j].pName3; 
		delete [] mpNames[j].pName4; 
		delete [] mpNames[j].pLink1; 
		delete [] mpNames[j].pLink2; 
		delete [] mpNames[j].pLink3; 
		delete [] mpNames[j].pLink4; 
		delete [] mpNames[j].pLink5; 
	}

	// Deallocating the data structure
	delete [] mpNames;
	mpUser = NULL;
}

//
// Initializing the number of cells to display and	
// Loading the Favorites Details
// Retrieve all current feedback items for a user into pvItemFeedback.
//
bool clseBayMyFavoritesWidget::Initialize()
{

	int					 j = 0;
	clsCategories		*pMyCategories; // The categories
	clsCategory			*pCategory;
	CategoryId			 interest_1 = (long) 0;
	CategoryId			 interest_2 = (long) 0;
	CategoryId			 interest_3 = (long) 0;
	CategoryId			 interest_4 = (long) 0;

	pMyCategories		= NULL;
	//
	// safety
	//

	if (!mpMarketPlace) 
		return false;

	pMyCategories = mpMarketPlace->GetCategories();

	// safety
	if (!mpUser)
		return false;
	if (!pMyCategories) 
		return false;

	//
	// Getting the interests of users and the categories of interests
	// and the Items by category
	// Get in USER_INFO the interests_1,_2,_3,_4
	//
	interest_1 = (CategoryId) mpUser->GetInterests_1();
	interest_2 = (CategoryId) mpUser->GetInterests_2();
	interest_3 = (CategoryId) mpUser->GetInterests_3();
	interest_4 = (CategoryId) mpUser->GetInterests_4();

	for( j = 0 ; j < EBAY_NUMBER_OF_INTERESTS ; j++ )
	{
		pCategory  = NULL;
		mpNames[j].pName0[0] = '\0';
		mpNames[j].pName1[0] = '\0';
		mpNames[j].pName2[0] = '\0';
		mpNames[j].pName3[0] = '\0';
		mpNames[j].pName4[0] = '\0';
		mpNames[j].pLink1[0] = '\0';
		mpNames[j].pLink2[0] = '\0';
		mpNames[j].pLink3[0] = '\0';
		mpNames[j].pLink4[0] = '\0';
		mpNames[j].pLink5[0] = '\0';

		switch(j)
		{
			//
			// Get the category for the interests
			//
		case FavoritesInterest_1:
			{
				if(interest_1 > 0)
				{
					// Get the category of the the interests_1
					pCategory = pMyCategories->GetCategory(interest_1, true);
				}
				break;
			}
			
		case FavoritesInterest_2:
			{
				if(interest_2 > 0)
				{
					// Get the category of the the interests_2
					pCategory = pMyCategories->GetCategory(interest_2, true);
				}
				break;
			}
			
		case FavoritesInterest_3:
			{
				if(interest_3 > 0)
				{
					// Get the category of the the interests_3
					pCategory = pMyCategories->GetCategory(interest_3, true);
					
				}
				break;
			}
			
		case FavoritesInterest_4:
			{
				if(interest_4 > 0)
				{
					// Get the category of the the interests_4
					pCategory = pMyCategories->GetCategory(interest_4, true);
				}
				break;
			}
			
		default:
			{
				break;
			}
			
		} // end of the switch(j)
		
		//
		// The interest doesn't return any category
		// We have to go to the next interest !!!
		//
		if(!pCategory) 
		{
			// Reset the correspondant interest
			switch(j)
			{
			case FavoritesInterest_1:
				{
					interest_1 = 0;
					break;
				}
				
			case FavoritesInterest_2:
				{
					interest_2 = 0;
					break;
				}
				
			case FavoritesInterest_3:
				{
					interest_3 = 0;
					break;
				}
				
			case FavoritesInterest_4:
				{
					interest_4 = 0;
					break;
				}
				
			default:
				{
					break;
				}
			}
			
			// Go to the next interest !!!
			continue;
		}
		
		//
		// Get the names of the category
		// and increment the total number of cells
		//
		strcpy(mpNames[j].pName0,pCategory->GetName());
		strcpy(mpNames[j].pName1,pCategory->GetName1());
		strcpy(mpNames[j].pName2,pCategory->GetName2());
		strcpy(mpNames[j].pName3,pCategory->GetName3());
		strcpy(mpNames[j].pName4,pCategory->GetName4());
		
		if( strlen(mpNames[j].pName0) > 0 ||
			strlen(mpNames[j].pName1) > 0 ||
			strlen(mpNames[j].pName2) > 0 ||
			strlen(mpNames[j].pName3) > 0 ||
			strlen(mpNames[j].pName4) > 0  )
		{
			mTotalNumberOfCells++;
		}
		
		/*
		//
		// The category have to be a leaf category !
		//
		if( ! pCategory->isLeaf() )
		{
		delete pCategory;
		
		  return false;
			}*/
		
		//
		// Load the links for the currrent, new today, end today, etc.
		//
		strcpy(mpNames[j].pLink1,(char *) pMyCategories->GetLinkPath(pCategory->GetId(),LISTING));
		strcpy(mpNames[j].pLink2,(char *) pMyCategories->GetLinkPath(pCategory->GetId(),NEW_TODAY));
		strcpy(mpNames[j].pLink3,(char *) pMyCategories->GetLinkPath(pCategory->GetId(),END_TODAY));
		strcpy(mpNames[j].pLink4,(char *) pMyCategories->GetLinkPath(pCategory->GetId(),COMPLETED));
		strcpy(mpNames[j].pLink5,(char *) pMyCategories->GetLinkPath(pCategory->GetId(),GOING));
		
		mTotalNumberOfCells ++;
		
	}  // ( j = 0 ; j < EBAY_NUMBER_OF_INTERESTS ; j++) ...
	
	//
	// Changing the number of cells to display
	// and the number of columns by item
	//
	//SetNumItems(mTotalNumberOfCells);
	SetNumItems(EBAY_FAVORITES_CELLS_PER_CATEGORY*EBAY_NUMBER_OF_INTERESTS);
	SetNumCols(EBAY_NUMBER_OF_FAVORITES_COLUMNS);
	
	// Get the E-mail of the user
	memset(mUserId,0,sizeof(mUserId));
	strcpy(mUserId,mpUser->GetUserId());
	
	
	return true;
		
}


//
// Before the Items, creating a header 
//
bool clseBayMyFavoritesWidget::EmitPreTable(ostream *pStream)
{
	// Open the table
	if (mTitleColor[0] == '\0')
	{
		// emit begin table tag without the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"BORDER=\""
				 <<		1
				 <<		"\" "
				 <<		"CELLPADDING=\""
				 <<		0
				 <<		"\" "
				 <<		"CELLSPACING=\""
				 <<		0
				 <<		"\" "
				 <<		"WIDTH=\""
				 <<		mTableWidth
				 <<		"%\""
				 <<		">"
				 <<		"\n";
	}
	else
	{
		// emit begin table tag with the bgcolor attribute
		*pStream <<		"<TABLE "
				 <<		"BORDER=\""
				 <<		1
				 <<		"\" "
				 <<		"CELLPADDING=\""
				 <<		0
				 <<		"\" "
				 <<		"CELLSPACING=\""
				 <<		0
				 <<		"\" "
				 <<		"WIDTH=\""
				 <<		mTableWidth
				 <<		"%\" "
				 <<		"BGCOLOR=\""
				 <<		mTitleColor
				 <<		"\""
				 <<		">"
				 <<		"\n";
	}

	
	*pStream	<<	"<TR><TH ALIGN=CENTER>"
				<<	"<strong><FONT face=\"arial, helvetica\" size=\"3\">"
				<<	"My Favorites"
				<<	"</FONT></strong>"
				<<	"</TH></TR>"
				<<	"</TABLE>";

	if(mTotalNumberOfCells == 0)
	{
		*pStream	<<	"<P>"
				<<	"<FONT size=\"2\">"
				<<	"Click <A href="
				<<	"\""
				<<	mpMarketPlace->GetCGIPath(PageChangePreferencesShow)
				<<	"ebayISAPI.dll?ChangePreferencesShow"
				<<	"&userid="
				<<	mUserId
				<<	"&pass="
				<<	mPassWord
				<<	"\""
				<<	">"
				<<	"here"
				<<	"</A>"
				<<	" to choose your favorite categories."
				<<	"</FONT>"
				<<	"<BR><BR></P>";
	}

	*pStream	<<	flush;

	return true;
}


//
// After the Items, creating a footer 
//
bool clseBayMyFavoritesWidget::EmitPostTable(ostream *pStream)
{
	// Write a space row 
	if(mTotalNumberOfCells > 0)
	{
		*pStream	<<	"<P>"
			<<	"<FONT size=\"2\">"
			<<	"Click <A href="
			<<	"\""
			<<	mpMarketPlace->GetCGIPath(PageChangePreferencesShow)
			<<	"ebayISAPI.dll?ChangePreferencesShow"
			<<	"&userid="
			<<	mUserId
			<<	"&pass="
			<<	mPassWord
			<<	"\""
			<<	">"
			<<	"here"
			<<	"</A>"
			<<	" to change your favorite categories."
			<<	"</FONT>"
			<<	"<BR><BR></P>";
	}
	
	*pStream	<<	flush;

	return true;
}



// This routine have to be called n = 0..mNumItems-1 times 
bool clseBayMyFavoritesWidget::EmitCell( ostream *pStream, int	n)
{
	
	int j = 0;


	if( n < EBAY_FAVORITES_CELLS_PER_CATEGORY)
	{
		// We are writing the first set of data
		j= 0;
	}
	else
	{
				
		if( (n >=  EBAY_FAVORITES_CELLS_PER_CATEGORY) && 
			(n < 2*EBAY_FAVORITES_CELLS_PER_CATEGORY)  )
		{
			// We are writing the second set of data
			j= 1;
		}
		else
		{

			if( (n >= 2*EBAY_FAVORITES_CELLS_PER_CATEGORY) && 
				(n <  3*EBAY_FAVORITES_CELLS_PER_CATEGORY)  )
			{
				// We are writing the third set of data
				j= 2;
			}
			else
			{
				if( (n >= 3*EBAY_FAVORITES_CELLS_PER_CATEGORY) && 
					(n <  4*EBAY_FAVORITES_CELLS_PER_CATEGORY)  )
				{
					// We are writing the fourth set of data
					j= 3;
				}
			}

		}

	}


	switch(n % EBAY_FAVORITES_CELLS_PER_CATEGORY)
	{
	case FavoritesNamesRow:
		{
			
			if( mpNames[j].pName0[0] == '\0' &&
				mpNames[j].pName1[0] == '\0' &&
				mpNames[j].pName2[0] == '\0' &&
				mpNames[j].pName3[0] == '\0' &&
				mpNames[j].pName4[0] == '\0'  )
			{
				break;
			}
			
			// Open the Cell
			if( mColor[0] == '\0')
			{
				*pStream	<<	"<TD>";
			}
			else
			{
				*pStream	<<	"<TD BGCOLOR=\""
					<<	mColor
					<<	"\""
					<<	">";
			}
			
			*pStream	<<	"<STRONG> <BR>";
			
			if(strlen(mpNames[j].pName4) > 0)
			{
				// This is the first row
				// Writing the name level 4 of the category
				*pStream	<<	mpNames[j].pName4
					<<	":"
					<<	"\n";
			}
			
			if(strlen(mpNames[j].pName3) > 0)
			{
				// This is the first row
				// Writing the name level 3 of the category
				*pStream	<<	mpNames[j].pName3
					<<	":"
					<<	"\n";
			}
			
			
			if(strlen(mpNames[j].pName2) > 0)
			{
				// This is the first row
				// Writing the name level 2 of the category
				*pStream	<<	mpNames[j].pName2
					<<	":"
					<<	"\n";
			}
			
			if(strlen(mpNames[j].pName1) > 0)
			{
				// This is the first row
				// Writing the name level 1 of the category
				*pStream	<<	mpNames[j].pName1
					<<	":"
					<<	"\n";
			}
			
			if(strlen(mpNames[j].pName0) > 0)
			{
				// This is the first row
				// Writing the name level 1 of the category
				*pStream	<<	mpNames[j].pName0
					<<	"\n";
			}
			
			*pStream	<<	"</STRONG>"
				<<	"</TD>"
				<<	"\n";
			break;
		}
		
	case FavoritesCountRow:
		{
			if( mpNames[j].pLink1[0] == '\0' &&
				mpNames[j].pLink2[0] == '\0' &&
				mpNames[j].pLink3[0] == '\0' &&
				mpNames[j].pLink4[0] == '\0' &&
				mpNames[j].pLink5[0] == '\0'  )
			{
				break;
			}
			
			
			if( mColor[0] == '\0')
			{
				*pStream	<<	"<TD>";
			}
			else
			{
				*pStream	<<	"<TD BGCOLOR=\""
					<<	mColor
					<<	"\""
					<<	">";
			}
			
			// This is the second row
			// Writing the current items of the category
			*pStream		<<	"<A HREF="
				<<	"\""
				<<	mpNames[j].pLink1
				<<	"\""
				<<	">"
				<<	"Current"
				<<	"</A>"
				<<	" || ";
			
			// This is the second row
			// Writing the number of items new today of the category
			*pStream	<<	"<A HREF="
				<<	"\""
				<<	mpNames[j].pLink2
				<<	"\""
				<<	">"
				<<	"New Today"
				<<	"</A>"
				<<	" || ";
			
			// This is the second row
			// Writing the number of items end today of the category
			*pStream	<<	"<A HREF="
				<<	"\""
				<<	mpNames[j].pLink3
				<<	"\""
				<<	">"
				<<	"Ending Today"
				<<	"</A>"
				<<	" || ";
			
			// This is the second row
			// Writing the number of items completed of the category
			*pStream	<<	"<A HREF="
				<<	"\""
				<<	mpNames[j].pLink4
				<<	"\""
				<<	">"
				<<	"Completed"
				<<	"</A>"
				<<	" || ";
			
			// This is the second row
			// Writing the number of items going, going, gone of the category
			*pStream	<<	"<A HREF="
				<<	"\""
				<<	mpNames[j].pLink5
				<<	"\""
				<<	">"
				<<	"Going, Going, Gone"
				<<	"</A>";
			
			*pStream	<<	"</TD>"
				<<	"\n";
			
			
			break;
		}
		
		
	default :
		{
			*pStream	<<	flush;
			break;
		}
		
	}

	*pStream	<<	flush;
	return true;

}



