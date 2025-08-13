/*	$Id: clsSpammerControl.cpp,v 1.2 1998/06/23 04:31:43 josh Exp $	*/
// clsSpammerControl.cpp: implementation of the clsSpammerControl class.
//
//////////////////////////////////////////////////////////////////////

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
#include "clsSpammerControl.h"

//////////////////////////////////////////////////////////////////////
// Construction/Destruction
//////////////////////////////////////////////////////////////////////

clsSpammerControl::clsSpammerControl()
{

}

clsSpammerControl::~clsSpammerControl()
{

}


// read the spammer control file into an array
bool clsSpammerControl::readTemplateFile()
{
	short		item, subItem, i;
	ifstream	catNewsStreamIn;
	char		tempBuffer[1000], *tempBufferPtr, *lastPosPtr;
	streampos	pos, lastpos;
	char *subpos, *lastsubpos;
	CategoryToNewsTransformIn catTransform;
	char tempInt[6];

	catNewsStreamIn.open("template/news_category", ios::in);
	if(catNewsStreamIn.is_open()) {

		// j = sizeof(catStringIn) / sizeof(CategoryToNewsTransformIn);
	
		tempBufferPtr = (char *)&tempBuffer;
		lastpos = pos = 0;
		
		while(catNewsStreamIn.eof() != 1) {
			// copy the item into an array and parse it
			
			lastpos += pos;
			catNewsStreamIn.getline(tempBufferPtr, 1000, '\n');
			if(tempBuffer[0] == 0)
			  break;

			pos = catNewsStreamIn.gcount();

			// check for a line of comments
			if(strchr(tempBufferPtr, '#') == 0) {

				// reset the stream pointer and look for tabs
				catNewsStreamIn.seekg(lastpos, ios::beg);
				catNewsStreamIn.getline((char *)&catTransform.newsgroup, sizeof(catTransform.newsgroup), '\t');
				// read in categories
				catNewsStreamIn.getline(tempBufferPtr, 500, '\t');

				i = 0;				
				lastsubpos = tempBufferPtr;
				while((long)lastsubpos < (long)tempBufferPtr + strlen(tempBufferPtr)) {				
				  // parse by ',', if any

				  
				  subpos = strchr(lastsubpos, ',');

				  if(subpos != 0) {
				    // copy this value into temporary string
				    memcpy(&tempInt, lastsubpos, (long)subpos-(long)lastsubpos);
				  
				    catTransform.categories[i] = atoi(tempInt);

				    lastsubpos = subpos + 1;
				  } else {
				    // copy what's left
				    memcpy(&tempInt, lastsubpos, (long)tempBufferPtr + strlen(tempBufferPtr) - (long)lastsubpos);
				    catTransform.categories[i] = atoi(tempInt);
				    lastsubpos = (char *)((long)tempBufferPtr + (long)strlen(tempBufferPtr)); 
				  }
				  i++;
				}
				
				// terminate last category
				catTransform.categories[i] = -1;
	
				// read in hot items
				catNewsStreamIn.getline(tempBufferPtr, 500, '\t');
				catTransform.hotItems = atoi(tempBufferPtr);

				// read in recent items
				catNewsStreamIn.getline(tempBufferPtr, 500, '\t');
				catTransform.recentItems = atoi(tempBufferPtr);




				// read in individual items (same as categories, comma sep.)
				catNewsStreamIn.getline(tempBufferPtr, 500, '\t');

				i = 0;				
				lastsubpos = tempBufferPtr;
				while((long)lastsubpos < (long)tempBufferPtr + strlen(tempBufferPtr)) {				
				  // parse by ',', if any

				  
				  subpos = strchr(lastsubpos, ',');

				  if(subpos != 0) {
				    // copy this value into temporary string
				    memcpy(&tempInt, lastsubpos, (long)subpos-(long)lastsubpos);
				    // terminate string
				    tempInt[(long)subpos - (long)lastsubpos] = '\0';
				  
				    catTransform.individualItems[i] = atoi(tempInt);

				    lastsubpos = subpos + 1;
				  } else {
				    // copy what's left
				    memcpy(&tempInt, lastsubpos, (long)tempBufferPtr + strlen(tempBufferPtr) - (long)lastsubpos);
				    // terminate string
				    tempInt[(long)tempBufferPtr + strlen(tempBufferPtr) - (long)lastsubpos] = '\0';

				    catTransform.individualItems[i] = atoi(tempInt);
				    lastsubpos = (char *)((long)tempBufferPtr + (long)strlen(tempBufferPtr)); 
				  }
				  i++;
				}
				
				// terminate last category
				catTransform.individualItems[i] = -1;

				// read in individual items (same as categories, comma sep.)
				//				catNewsStreamIn.getline(tempBufferPtr, 500, '\t');
				//				catTransform.individualItems = atoi(tempBufferPtr);






				// read in Yahoo headline format
				catNewsStreamIn.getline(tempBufferPtr, 500, '\t');
				catTransform.id = atoi(tempBufferPtr);


			// Look for 


			;
			
//			item = 0;
			
//			for(i = 0 ;i < j; i++)
				
//			catNewsStreamIn >> catStringIn[i].newsgroup;
//			catNewsStreamIn >> catStringIn[i].id;
			}	
		}

			
/*
	char	newsgroup[50][10];
	short	category[50];
	short	hotItems;
	short	recentItems;
	short	individualItems[50];
	short	id;
*/	
	
	catNewsStreamIn.close();
		
		return true;
	}

	return false;
}
