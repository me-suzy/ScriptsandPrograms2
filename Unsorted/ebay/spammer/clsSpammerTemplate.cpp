/*	$Id: clsSpammerTemplate.cpp,v 1.2.692.1 1999/08/09 18:45:07 nsacco Exp $	*/
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsSpammer.h"
#include "clsEnvironment.h"
#include "clsDatabase.h"
#include "clsMarketPlaces.h"
#include "clsMarketPlace.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsItems.h"
#include "clsItem.h"

#include <string>

bool clsSpammer::FirstPass(ostrstream *theStream)
{
	char *mainbuffer = NULL, *tagsBuffer = NULL;
	char tagName[25], tagNameMatch[25];
	
	ofstream streamOut;
	ifstream streamIn, streamTagsIn;
	char *mainBufferPtr, *newBuffer = NULL, *newBufferPtr;
	char *pos, *lastPos, *tagPos, *tagLastPos;
	char *tagValue, *tagValueLength, *lastTag;
	long lengthTags, lengthBase, tagNameLength;
	bool match, skippedTag = false, streamClosedCorrectly = false;
	long j;	

	// storage
	newBuffer = new char[50*1024];
	mainbuffer = new char[50*1024];
	
	// make sure they're clean. otherwise we could end up with string garbage.
	// it's a problem when using strstr() and the memory is being re-used.
	// this would be nice as an overload to new char[]
	for(j=0; j < (50*1024) ; j++) {
		mainbuffer[j] = '\0';
		newBuffer[j] = '\0';
    }

	// the buffer we are creating the mail into
	// (will be a member stream later)
	newBufferPtr = newBuffer;
	mainBufferPtr = (char *)mainbuffer;

#ifdef WIN32
	streamTagsIn.open("c:\\ebay\\spammer\\template\\spammer_template", ios::in);
#else
	streamTagsIn.open("template/spammer_template", ios::in);
#endif
	
	
	// read in entire file to mainbuffer
	// read in tags file to buffer
	streamTagsIn.seekg(0, ios::end);
	lengthTags = streamTagsIn.tellg();
	if(lengthTags > 0) {
		tagsBuffer = new char[lengthTags];
		streamTagsIn.seekg(0, ios::beg);
		streamTagsIn.read(tagsBuffer, lengthTags);
	}
	// close it up
	streamTagsIn.close();    
	
	
	// ope our base template and get its length
#ifdef WIN32
//	streamIn.open("c:\\ebay\\spammer\\template\\spammer_base", ios::in);
	streamIn.open(baseTemplate, ios::in);
#else
//	streamIn.open("template/spammer_base", ios::in);
	streamIn.open(baseTemplate, ios::in);
#endif

	streamIn.seekg(0, ios::end);
	lengthBase = streamIn.tellg();
	
	// read it into a buffer
	streamIn.seekg(0, ios::beg);
	streamIn.read(mainbuffer, lengthBase);
	
	lastPos = (char *)mainbuffer;
	
	while((long)mainBufferPtr < (long)mainbuffer + (long)lengthBase)
    {
		// find first tag
		lastPos = mainBufferPtr;
		pos = strchr(mainBufferPtr, '<');
		
		// check to see if we're done
		if (pos == 0) {
			// find end of tag
			pos = strchr(mainBufferPtr, '>');

			if ((long)mainbuffer + lengthBase - (long)( mainBufferPtr + 1) > 0 )
				theStream->write(mainBufferPtr, (long)mainbuffer + lengthBase - (long)( mainBufferPtr + 1));
			
			// we be done, close if off
			*theStream << ends;
		
			streamClosedCorrectly = true;
			break;

		}
		
		// get the tag name 
		tagNameLength = (long)strchr(mainBufferPtr, '>');
 		strncpy(tagName, pos+1, tagNameLength - (long)pos - 1);
		tagNameLength = tagNameLength + 1;
		
		// terminate the string
		tagName[tagNameLength - (long)pos - 2] = '\0';
		
		// copy up to the new tag
		if(skippedTag) { 
			// if we couldn't find a tag in our tags file, go ahead and leave it there for
			// another pass
//			memcpy(newBufferPtr, lastTag, (mainBufferPtr - lastTag) + (int)pos - (int)lastPos);
//			newBufferPtr += (mainBufferPtr - lastTag) + (int)pos - (int)lastPos;

//			WriteToStream(&bob, lastTag, (mainBufferPtr - lastTag) + (int)pos - (int)lastPos);
			theStream->write(lastTag, (int)(mainBufferPtr - lastTag) + (int)pos - (int)lastPos);
		}	else {
			// copy the data from the last point up until the new tag
//			memcpy(newBufferPtr, mainBufferPtr, (int)pos - (int)lastPos);
//			newBufferPtr += (int)pos - (int)lastPos;

//			WriteToStream(theStream, mainBufferPtr, (int)pos - (int)lastPos);

			theStream->write(mainBufferPtr, (int)pos - (int)lastPos);
//			}

		}
		// update our position indicators
		lastTag = pos;
		mainBufferPtr = (char*)tagNameLength;
		
		// now we'll loop through the data from tags file and look for a match
		// look for our internal tags first (those which require code in our app)
		match = false;
		if(strcmp(tagName, HOT_ITEMS) == 0) {
			{
				short hot;
				ListingItemVector::iterator iHotItem;
				
				if(hotItems.begin() != hotItems.end())
				{
					*theStream << "=============\nHot items:\n=============\n\n";

					iHotItem = hotItems.begin();
					hot = 0;
					while (iHotItem != hotItems.end() && hot < catTransformPtr->hotItems) {
						ItemToStream(theStream, *iHotItem);
						*theStream << "\n";

						iHotItem++;
						hot++;
					}
				} else {
//				  *theStream << "\nThere are no hot items at this time.\n";
				}
				
				// empty the string
			tagValueLength = (char *)0;
			tagValue = "\n";
			match = true;
			}
		} else if (strcmp(tagName, RECENT_ITEMS) == 0) {
			{
				short recent;
				ListingItemVector::iterator iRecentItem;
				
				if(recentItems.begin() != recentItems.end())
				{
					*theStream << "=============\nRecent items:\n=============\n\n";

					iRecentItem = recentItems.begin();
					recent = 0;
					while (iRecentItem != recentItems.end() && recent < catTransformPtr->recentItems) {
						ItemToStream(theStream, *iRecentItem);
						*theStream << "\n";	

						iRecentItem++;
						recent++;
					}
				} else {
//				  *theStream << "\nThere are no new items at this time.\n";
				}

				// empty the string
			tagValueLength = (char *)0;
			tagValue = "\n";
			match = true;
			}
		} else if (strcmp(tagName, NEWSGROUP) == 0) {
			theStream->write(catTransformPtr->newsgroup, strlen(catTransformPtr->newsgroup));
			tagValueLength = (char *)0;
			match = true;
		} else if (strcmp(tagName, AUCTION_NAME) == 0) {
			theStream->write(mCategoryTitle, strlen(mCategoryTitle));
			tagValueLength = (char *)0;
			match = true;
		} else if (strcmp(tagName, DATE) == 0) {
			GetDate(theStream);

			tagValueLength = (char *)0;
			match = true;
		} else if (strcmp(tagName, BASE_URL) == 0) {
			*theStream << LISTINGS_URL;

			tagValueLength = (char *)0;
			match = true;
		} else if (strcmp(tagName, CATEGORY_URL) == 0) {
		        // build the URL to get to this category
		        
		        {
			  char categoryURL[512];

			  // NOTE: we only handle one category at the moment!
			  // TODO - replace?
			  sprintf(categoryURL, "http://cayman.ebay.com/aw/listings/list/category%d/index.html", catTransformPtr->categories[0]);

			  theStream->write(categoryURL, strlen(categoryURL));
			  tagValueLength = (char *)0;
			  match = true;
			}

		} else if (strcmp(tagName, CATEGORY_COUNT) == 0) {

			{
			  char tempStr[16];

			  //			  clsCategory *pCategory = mpCategories->GetCategory(catTransformPtr->categories[0]);
			  //			  sprintf(tempStr, "%d", pCategory->GetItemCount(mTime));
			  sprintf(tempStr, "%d", totalCategoryCount);
			  theStream->write(tempStr, strlen(tempStr));
			  tagValueLength = (char *)0;
			  match = true;
			  //			  delete pCategory;
			}
		} else if (strcmp(tagName, YAHOO_HEADLINES) == 0) {

			CreateYahooHeadlines(theStream);

			tagValueLength = (char *)0;
			match = true;
		} else {		
			// search the tags file for a match
			tagLastPos = tagPos = tagsBuffer;
			
			skippedTag = match = false;
			while(tagPos < (tagsBuffer + lengthTags)) {
				// tagLastPos = tagPos;
				tagPos = strchr(tagPos, '<');
				if(tagPos == 0)
					break;
				
				// find the end of the tag
				tagValue = strchr(tagPos, '>');
				strncpy(tagNameMatch, tagPos+1, tagValue - tagPos - 1);
				tagValue += 1;
				tagNameMatch[(long)tagValue - (long)tagPos - 2] = '\0';
				
				// find the length of the tag name
				tagValueLength = strchr(tagPos + 1, '<');
				tagValueLength = (char *)(tagValueLength - tagValue);
				
				// test for a match
				if(strcmp(tagName, tagNameMatch) == 0) {
					match = true;
					break;
				}
				
				// reset the position to the next tag
				tagPos = (char *)((long)tagValue + (long)tagValueLength + 1);
			}
		}
		
		// check to see if this tag is it
		if (match) {
			// if so, copy the data into the new buffer
//			memcpy(newBufferPtr, tagValue, (int)tagValueLength);
//			newBufferPtr = (char *)((long)newBufferPtr + (long)tagValueLength);

			if(tagValueLength > 0)
				theStream->write(tagValue, (int)tagValueLength);

//			*theStream << "\n";

			//			WriteToStream(&bob, tagValue, (int)tagValueLength);

		} else {
			// if we don't find a match just skip it for now
			skippedTag = true;
		}		
	}
	
	// delete buffers
	delete [] newBuffer;
	delete [] mainbuffer;
	delete [] tagsBuffer;
	
	// if we end up here it's because we had a problem somewhere with the stream or the buffers
	if (streamClosedCorrectly == false) {
	  cout << "oops...";
	  return false;
	} else
	  return true;
}


void clsSpammer::PadString(char *str, short length)
{
  while(strlen(str) < length) 
    {
      strcat(str, " ");
    }
}

void clsSpammer::PadNumber(char *str, short length)
{
	char *tempStr;
	
	if(strlen(str) > 0 && (length > strlen(str))) {
		tempStr = new char[length+strlen(str)+1];
		tempStr[0] = '\0';

		while(strlen(tempStr) < (length-1)) 
		{
			strcat(tempStr, "0");
		}
		
		strcat(tempStr, str);
		strcpy(str, tempStr);

		delete [] tempStr;
	}
}

void clsSpammer::ItemToStream(ostrstream *theStream, clsListingItem *pItem)
{
	char		PriceString[50];
	struct tm*	pEndTime;
	char		TimeString[50];
	time_t		EndingTime;
	
	*theStream << (*pItem).GetTitle();

	*theStream << "\n";
	*theStream << "\t";
	
	// Print the current item price
	if ((*pItem).GetBidCount() == 0)
	{
		// start price
		sprintf(PriceString, "Starts at $%.2f", (*pItem).GetPrice()); // GetStartPrice
	}
	else
	{
		// bid price
		sprintf(PriceString, "Bid at $%.2f", (*pItem).GetPrice());
	}
	//	*pOutputFile << "<td width=19% align=left valign=top>"
	
	PadString(PriceString, 20);
	*theStream << PriceString;
	
	//	theStream << "\t";
	
	// Print ending time
	EndingTime = (*pItem).GetEndTime();
	pEndTime = localtime(&EndingTime);
	sprintf(TimeString, "%2.2d/%2.2d, %2.2d:%2.2d %s", 
		pEndTime->tm_mon+1, 
		pEndTime->tm_mday, 
		pEndTime->tm_hour, 
		pEndTime->tm_min, 
		pEndTime->tm_isdst ? "PDT" : "PST");
	
	*theStream << "\t";
	
	//	*pOutputFile << "<td width=25% align=left valign=top>Ends: "
	*theStream << "Ends: "
		<< TimeString;
	
	// bid count
	if(pItem->GetBidCount() > 0) 
	  {
	    *theStream << "\n\tBids: ";
	    *theStream << pItem->GetBidCount();
	  }
	
	// URL
	*theStream << "\n\t";
	*theStream << mpMarketPlace->GetCGIPath(PageViewItem)
		       << "eBayISAPI.dll?ViewItem&item=";
	*theStream << pItem->GetId();

	*theStream << "\n";
}



//
// get the current UNIX style time (secs since 1970) and place in stream
//
void clsSpammer::TimeStamp(ostrstream * theStream)
{
	char *theTime;
	//	std::string test;

	theTime = new char[10];
	time_t ltime;

    /* Get UNIX-style time and display as number and string. */
    time( &ltime );
	
#ifdef WIN32
	ltoa(ltime, theTime, 0);
#else
	lltostr(ltime, theTime);
#endif

	*theStream << theTime;
	*theStream << "\n";

	delete [] theTime;	
}


//
// output date in MMDDYY format
//
void clsSpammer::GetDate(ostrstream * theStream)
{
	tm	*theTime;
	time_t secs;
	char tempStr[5];
	char *str;

	// get time
	time(&secs);

	// convert to tm struct
	theTime = localtime(&secs);

	// spit out to stream
#ifdef WIN32
	_ltoa(theTime->tm_year, tempStr, 10);
#else
	sprintf(tempStr, "%d", theTime->tm_year);
#endif
	PadNumber(tempStr, 2);
	*theStream << tempStr;
#ifdef WIN32
	_ltoa(theTime->tm_mon + 1, tempStr, 10); // month is from 0 (i.e. Jan = 0)
#else
	sprintf(tempStr, "%d", theTime->tm_mon + 1);
#endif
	PadNumber(tempStr, 2);
	*theStream << tempStr;

#ifdef WIN32
	_ltoa(theTime->tm_mday, tempStr, 10);
#else
	sprintf(tempStr, "%d", theTime->tm_mday);
#endif

	PadNumber(tempStr, 2);
	*theStream << tempStr;
}


//
// this routine looks at the entire item list for 5-9 entries
// each entry has this format:
//
// <number> incremental for each headline/auction. starting from 1.
// <tab> - separator
// <item> - this is the auction title
// <tab> - separator
// <url> - this is the URL to item
// <tab> - separator
// <price> - this is the current price of the item
//
//
void clsSpammer::CreateYahooHeadlines(ostrstream * theStream)
{
	int i;
	
	for(i = 0; i < YAHOO_HOT_ITEMS; i++)
		yahooHotWidget->Emit1Cell(theStream, i);
}


bool clsSpammer::yahooDoIt()
{
	return true;
}






/*



#if 0
// Get the items from the database and choose a random set of them
bool clsSpammer::yahooDoIt()
{
	clsItems		*pItems;
	clsCategories	*pCategories;
	vector<int>	vItemIds;		
	vector<int> vBlackListItemIds;		
	vector<int> vChosenItemIds;	
	clsItem		*pItem;
	clsCategory *pCategory;
	int			i, x, vectorSize;
	bool		passed;
	time_t		CurrentTime;

	// get the current time
	CurrentTime = time(0);

	// seed the random number generator with the current time
	srand((unsigned int)CurrentTime);

	// get the marketplace's clsItems and clsCategories object
	if (mpCurrMarketPlace)
	{
		pItems = mpCurrMarketPlace->GetItems();
		pCategories = mpCurrMarketPlace->GetCategories();

		// btw, don't need to delete these, because mpCurrMarketPlace's
		//  dtor will delete them
	}
	else
		return false;

	// get the item ids of all active, black-listed auctions,
	//  and stuff the ids into a vector
	pItems->GetBlackListItemIds(&vBlackListItemIds, CurrentTime);

	// get the relevant item ids and stuff the ids into a vector
//	this->GetItemIds(&vItemIds);
   gApp->GetDatabase()->GetItemIdsVector(mpCurrMarketPlace, &vItemIds, mTime, 3);

   // if (mpCurrMarketPlace) pItems = mpCurrMarketPlace->GetItems();
//	if (pItems) pItems->GetHotItemIds(pvItemIds, CurrentTime);

	// get the size of the vector
	vectorSize = vItemIds.size();

	// if there aren't enough items in the vector, then reset mNumItems
	if (mNumItems > vectorSize) mNumItems = vectorSize;

	// randomly choose the items
	for (i=0; i<mNumItems; i++)
	{
		// keep choosing a random item until you get one passes these tests
		//  1) is not already chosen
		//	2) is not adult
		//	3) is not in the black list
		//	4) title isn't vulgar
		do
		{
			// get a random number and make sure it's bigger than vectorSize.
			// if it's too small, then keep adding more randoms to it until
			// it's big enough.
			//  (RAND_MAX is only 32767 in VC++)
			x = rand();
			while (x < vectorSize) x+=rand();
			x = x % vectorSize;

			// get the item without the description
			pItem = pItems->GetItem(vItemIds[x], false);

			// prepare to do the checks
			passed = true;

			// 1) check if item has already chosen been chosen
			if ((find(vChosenItemIds.begin(), vChosenItemIds.end(), vItemIds[x])
				!= vChosenItemIds.end()))
				passed = false;

			// 2) check for an adult category
			if (passed)
			{
				// get and check the category for adult
				pCategory = pCategories->GetCategory(pItem->GetCategory());
				if (pCategory && pCategory->isAdult())
					passed = false;

				// delete the category
				if (pCategory) delete pCategory;
			}

			// 3) check the black list
			if (passed)
			{
				if ((find(vBlackListItemIds.begin(), vBlackListItemIds.end(), vItemIds[x])
					!= vBlackListItemIds.end()))
					passed = false;
			}

			// 4) check for vulgarity
			if (passed)
			{
				if (clsUtilities::TooVulgar(pItem->GetTitle())) passed = false;
			}

			// if the item didn't pass all the tests, then delete it because
			//  we're not going to use it
			if ((passed==false) && (pItem))
				delete pItem;

		} while (passed==false);

		// ok, you got one, so add the item id to the "chosen" vector
		vChosenItemIds.push_back(vItemIds[x]);

		// put the item into mvItems
		mvItems.push_back(pItem);
	}

	// add 1 to mNumItems to accomodate the more... link
	if (mMoreLink) mNumItems++;

	return true;
}

#endif

*/
