/*	$Id: clsSpammer.cpp,v 1.3.276.1 1999/08/05 18:59:21 nsacco Exp $	*/
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

#include "clsMail.h"
#include "clsSpammerControl.h"
#include "clseBayHotYahooWidget.h"

#include <string>

#ifdef WIN32
#include <process.h>
#else
#include <unistd.h>
#include <sys/types.h>
#include <sys/wait.h>
#endif

//
// run
//
void clsSpammer::Run()
{
	ostrstream *theStream;

	// Initialize

	if(verbose)
	  cout << "initializing database connection...\n";

	Initialize();

	if(verbose)
	  cout << "reading news control file...\n";

	if(readNewsControlFile() == false) {
	  if(verbose)
	    cout << "opening control file failed.";
	  
	  exit(1);
	}

	if(verbose)
	  cout << "reading in ALL the items...\n";
	
	if(yahoo) {
		theStream = new ostrstream();
		
		// only send out news if things go right
		if(FirstPass(theStream)) {			   			
			cout << "sending out mail to yahoo...\n";
			// write to a file and pipe to inews
			SendUnixMail(theStream);
		} else {
			cout << "we had a problem with the output stream\n";
		}
		
		delete theStream->str();
		delete theStream;
		
	} else {
		mpItems->PrepareActiveListingItems(mTime);
		
		{
			short i = 0,j = 0;
			
			for(i=0;i < numOfTransforms;i++) {
				j = 0;
				while(catTransform[i].categories[j] != -1) {
					catTransformPtr = &catTransform[i];
					
					
					if(GetTheItems(catTransform[i].categories[j]) == true) {
						theStream = new ostrstream();
						
						// only send out news if things go right
						if(FirstPass(theStream)) {			   
							
							// now mail it out
							//					SendMail(theStream);
							
							if(!yahoo) {
								cout << "sending out news...\n";
								// write to a file and pipe to inews
						 		SendNews(theStream);
							} 
							
							if (yahoo) {
								cout << "sending out mail to yahoo...\n";
								// write to a file and pipe to inews
								SendMail(theStream);
							}
						} else {
							cout << "we had a problem with the output stream\n";
						}
						
						
						// dump the items
						if(verbose)
							cout << "dumping hot & recent item list for this category...\n";
						
						hotItems.erase(hotItems.begin(), hotItems.end());
						recentItems.erase(recentItems.begin(), recentItems.end());
						
						delete theStream->str();
						delete theStream;
					}
					j++;
				}
			}
		}
		
		if(verbose)
			cout << "freeing entire item list...\n";
		
		mpItems->RemoveListingItems();
	}
}

typedef struct
{
	char	*newsgroup;
	short	id;
} CategoryToNewsTransform;



// leaves only please!
static const CategoryToNewsTransform CatString[] =
{
	"computers.services",		192,
	"rec.antiques.marketplace-textiles",	33,
	"alt.art.marketplace",		46	
};

//
//	Initialization
//
void clsSpammer::Initialize()
{
	mpDatabase              = (clsDatabase *)0;
	mpMarketPlaces          = (clsMarketPlaces *)0;
	mpCurrMarketPlace       = (clsMarketPlace *)0;
	mpCategories            = (clsCategories *)0;
	mpItems                 = (clsItems *) 0;
	mpUsers                 = (clsUsers *) 0;
	
	mpDatabase = GetDatabase();
	mpMarketPlaces = GetMarketPlaces();        
	mpCurrMarketPlace = mpMarketPlaces->GetCurrentMarketPlace();
	mpCategories = mpCurrMarketPlace->GetCategories();
	mpItems = mpCurrMarketPlace->GetItems();
	mpUsers = mpCurrMarketPlace->GetUsers();
	
	// Get the current system time
	//	mTime -= 1209600;	
	time(&mTime);
	
	// set up base template
	if(yahoo) {
		strcpy(baseTemplate, YAHOO_TEMPLATE);
		yahooHotWidget = new clseBayHotYahooWidget(mpCurrMarketPlace);
		yahooHotWidget->Initialize();


	} else
		strcpy(baseTemplate, SPAMMER_TEMPLATE);
}

void separateItems()
{
}

void clsSpammer::ReturnTitle(clsCategory* pCategory, char *buffer)
{
	clsCategory*	pParent;

	pParent = mpCategories->GetCategory(pCategory->GetLevel1());

	if (pParent)
	{
		strcpy(buffer, pParent->GetName());
		strcat(buffer, ":");
	}

	strcat(buffer, pCategory->GetName());
}


bool clsSpammer::GetTheItems(short categoryID)
{
	//	const CategoryToNewsTransformIn *pCurrentTransform;
	short			i;
	clsCategory*	pCategory;
	//	char			buffer[256];
	ListingItemVector* pItems;
	ListingItemVector Items;
	ListingItemVector::iterator	iItem;
	
	
	// #ifdef WIN32
	//	pCategory = GetTheCategory(categoryID);
	// #else
	pCategory = mpCategories->GetCategory(categoryID);
	// #endif
	
	if(verbose)
		cout << "getting the items for category " << categoryID << "...\n";
	
	mpItems->GetListingItemsInCategory(categoryID, &Items);
	
//	clsDatabaseOracle::SortItems(mpItems, sort_items_bidcount);
//				sort(Items.begin(), Items.end(), sort_items_bidcount);
	
	if(verbose)
		cout << "got the items for category " <<  categoryID << ". Now looping through them...\n";
	
	if(Items.begin() == Items.end()) {
		// dump the category
		if(pCategory)
			delete pCategory;
		
		return false;
	}
	
	
	pItems = &Items;
	
	// get the title
	ReturnTitle(pCategory, (char *)&mCategoryTitle);
	
	if(verbose)
		cout << "got the items for " << mCategoryTitle << "...\n";
	
	// Now, we loop through them
	iItem = Items.begin();
	i = 0;
	while (iItem != Items.end()) {
		
		// if hot (more than 30 bids)
		if ((catTransformPtr->hotItems > 0) && getHot && ((*iItem)->GetBidCount() > HOT_COUNT))
		{
			hotItems.push_back(*iItem);			
		}
		else 
		{
			// get items that are less than one day old
			if ((catTransformPtr->recentItems > 0) && getNew && (difftime(mTime, (*iItem)->GetStartTime()) <= 60*60*24))
			{
				recentItems.push_back(*iItem);
			}
			else
			{
				// just erase it
				// pItems->erase(*iItem);
			}
		}
		
		
		// bump up our iterators
		iItem++;
		i++;
	}
	
	// dump the category
	if(pCategory)
		delete pCategory;
	
	totalCategoryCount = i;
	
	return true;
}

clsCategory *clsSpammer::GetTheCategory(CategoryId id)
{
	return(mpDatabase->GetCategoryById(mpCurrMarketPlace->GetId(), id));	
}


clsSpammer::clsSpammer()
{
  return;
}

clsSpammer::~clsSpammer()
{
}

clsFile::clsFile(char *name)
{
    OutputStream.open(name, ios::out);
}

clsFile::~clsFile()
{
    OutputStream.close();
}

void PadString(char *str, short length)
{
  while(strlen(str) < length) 
    {
      strcat(str, " ");
    }
}

void clsFile::Print(clsItem *pItem)
{
	char		PriceString[50];
	struct tm*	pEndTime;
	char		TimeString[50];
	time_t		EndingTime;
	
	OutputStream << (*pItem).GetTitle();

	OutputStream << "\n";
	OutputStream << "\t";
	
	// Print the current item price
	if ((*pItem).GetBidCount() == 0)
	{
		// start price
		FormatMoney(*pItem).GetCurrencyId(), (*pItem).GetStartPrice(), PriceString);
	}
	else
	{
		// bid price
		FormatMoney(*pItem).GetCurrencyId(), (*pItem).GetPrice(), PriceString);
	}
	//	*pOutputFile << "<td width=19% align=left valign=top>"
	
	PadString(PriceString, 20);
	OutputStream << PriceString;
	
	//	OutputStream << "\t";
	
	// Print ending time
	EndingTime = (*pItem).GetEndTime();
	pEndTime = localtime(&EndingTime);
	sprintf(TimeString, "%2.2d/%2.2d, %2.2d:%2.2d %s", 
		pEndTime->tm_mon+1, 
		pEndTime->tm_mday, 
		pEndTime->tm_hour, 
		pEndTime->tm_min, 
		pEndTime->tm_isdst ? "PDT" : "PST");
	
	OutputStream << "\t";
	
	//	*pOutputFile << "<td width=25% align=left valign=top>Ends: "
	OutputStream << "Ends: "
		<< TimeString;
	
	// bid count
	if(pItem->GetBidCount() > 0) 
	  {
	    OutputStream << "\n\tBids: ";
	    OutputStream << pItem->GetBidCount();
	  }
	
	// URL
	OutputStream << "\n\t"
				 << gApp->GetMarketPlaces()->GetCurrentMarketPlace->GetCGIPath(PageViewItem)
				 << "eBayISAPI.dll?ViewItem&item=";
	OutputStream << pItem->GetId();

	OutputStream << "\n\n";
}

void clsFile::WriteIt(char *tempStr)
{
	OutputStream << tempStr;
	OutputStream << "\n";
}

void clsFile::WritePreamble(char *title)
{
	OutputStream << "\n";
	OutputStream << "eBay Current Listings for ";
	OutputStream << title;
	OutputStream << "\n\n";
	OutputStream << "Private individuals and organizations that are not affiliated with eBay Inc. offer these items for sale. To get more information on or to bid online for any of these items, or to start your own auctions, please visit eBay at:\n";
	OutputStream << "\n";
//	OutputStream << "http://www.ebay.com/\n";
// kakiyama 07/18/99
	OutputStream << mpMarketPlace->>GetHTMLPath();
	OutputStream << "\n";

	OutputStream << "\n";
	OutputStream << "Remember, that this service is entirely FREE for buyers! And if you like this, there are thousands of other items available!\n";
	OutputStream << "\n";
	OutputStream << "Please note: bids and listings cannot be processed by e-mail; you must visit the site listed above.\n";
	OutputStream << "\n";
}

void clsFile::WriteHeader(char *title, char *newsgroup)
{
	OutputStream << "From: aw@ebay.com (eBay)\nOrganization: eBay Inc.\n";
	OutputStream << "Newsgroups: ";
	OutputStream <<	 newsgroup;
	OutputStream << "\n";
	OutputStream << "Subject: eBay Listings for ";
	OutputStream <<	 title;
	OutputStream << " [may be long]\n";
}






// read the spammer control file into an array
//
// we should also check to see that each line entry is consistent. we can simply check for the right number of tabs to start
//

bool clsSpammer::readNewsControlFile()
{
	short		i;
	ifstream	catNewsStreamIn;
	char		tempBuffer[2000], *tempBufferPtr;
	streampos	pos, lastpos;
	char *subpos, *lastsubpos;
	char tempInt[6];

	numOfTransforms = 0;

#ifdef WIN32
	catNewsStreamIn.open("c:\\ebay\\spammer\\template\\news_category", ios::in);
#else
	catNewsStreamIn.open("template/news_category", ios::in);
#endif

	if(catNewsStreamIn.is_open()) {

		// j = sizeof(catStringIn) / sizeof(CategoryToNewsTransformIn);
	
		tempBufferPtr = (char *)&tempBuffer;
		lastpos = pos = 0;
		
		while(catNewsStreamIn.eof() != 1) {
			// copy the item into an array and parse it
			

		  //			lastpos += pos;
		  //		        lastpos = catNewsStreamIn.__my_fb._IO_read_ptr - catNewsStreamIn.__my_fb._IO_buf_base;
			lastpos = catNewsStreamIn.tellg();
		        catNewsStreamIn.getline(tempBufferPtr, 2000, '\n');

			if(tempBuffer[0] == 0)
			  break;

			pos = catNewsStreamIn.gcount();

			// check for a line of comments
			if(strchr(tempBufferPtr, '#') == 0) {

				// reset the stream pointer and look for tabs
				catNewsStreamIn.seekg(lastpos, ios::beg);
				catNewsStreamIn.getline((char *)&catTransform[numOfTransforms].newsgroup, sizeof(catTransform[numOfTransforms].newsgroup), '\t');
				// read in categories
				catNewsStreamIn.getline(tempBufferPtr, 500, '\t');

				i = 0;				
				lastsubpos = tempBufferPtr;
				while((long)lastsubpos < (long)tempBufferPtr + strlen(tempBufferPtr)) {				
				  // parse by ',', if any

				  
				  subpos = strchr(lastsubpos, ',');
				  tempInt[0] = '0';

				  if(subpos != 0) {
				    // copy this value into temporary string
				    memcpy(&tempInt, lastsubpos, (long)subpos-(long)lastsubpos);
				  
				    catTransform[numOfTransforms].categories[i] = atoi(tempInt);

				    lastsubpos = subpos + 1;
				  } else {
				    // copy what's left
 				    memcpy(&tempInt, lastsubpos, (long)tempBufferPtr + strlen(tempBufferPtr) - (long)lastsubpos);
				    tempInt[(long)tempBufferPtr + strlen(tempBufferPtr) - (long)lastsubpos] = '\0';

				    catTransform[numOfTransforms].categories[i] = atoi(tempInt);
				    lastsubpos = (char *)((long)tempBufferPtr + (long)strlen(tempBufferPtr)); 
				  }
				  i++;
				}
				
				// terminate last category
				catTransform[numOfTransforms].categories[i] = -1;
	
				// read in hot items
				catNewsStreamIn.getline(tempBufferPtr, 500, '\t');
				catTransform[numOfTransforms].hotItems = atoi(tempBufferPtr);

				// read in recent items
				catNewsStreamIn.getline(tempBufferPtr, 500, '\t');
				catTransform[numOfTransforms].recentItems = atoi(tempBufferPtr);




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
				  
				    catTransform[numOfTransforms].individualItems[i] = atoi(tempInt);

				    lastsubpos = subpos + 1;
				  } else {
				    // copy what's left
				    memcpy(&tempInt, lastsubpos, (long)tempBufferPtr + strlen(tempBufferPtr) - (long)lastsubpos);
				    // terminate string
				    tempInt[(long)tempBufferPtr + strlen(tempBufferPtr) - (long)lastsubpos] = '\0';

				    catTransform[numOfTransforms].individualItems[i] = atoi(tempInt);
				    lastsubpos = (char *)((long)tempBufferPtr + (long)strlen(tempBufferPtr)); 
				  }
				  i++;
				}
				
				// terminate last category
				catTransform[numOfTransforms].individualItems[i] = -1;

				// read in individual items (same as categories, comma sep.)
				//				catNewsStreamIn.getline(tempBufferPtr, 500, '\t');
				//				catTransform.individualItems = atoi(tempBufferPtr);






				// read in Yahoo headline format (until LF)
				catNewsStreamIn.getline(tempBufferPtr, 500, '\n');
				catTransform[numOfTransforms].id = atoi(tempBufferPtr);



				numOfTransforms++;
			
//			item = 0;
			
//			for(i = 0 ;i < j; i++)
				
//			catNewsStreamIn >> catStringIn[i].newsgroup;
//			catNewsStreamIn >> catStringIn[i].id;
			}	
		}
			
		catNewsStreamIn.close();
		
		return true;
	}
	
	return false;
}






// OLD STUFF



void clsSpammer::writeCategories()
{
 		ofstream			catStreamOut;
//		CategoryToNewsTransformIn catstr2[150];

/*
		catStreamOut.open("d:\\temp\\categories1.txt", ios::out);
		for(i = 0 ;i < sizeof(CatString) / sizeof(CategoryToNewsTransform); i++) {
			catStreamOut << CatString[i].newsgroup;
			catStreamOut << "\n";
			catStreamOut << CatString[i].id;
			catStreamOut << "\n";
		}
		catStreamOut.close();
*/

/*
		catStreamIn.open("d:\\temp\\categories.txt", ios::in);
		for(i = 0 ;i < sizeof(catstr2) / sizeof(CategoryToNewsTransformIn); i++) {
			if (catStreamIn.eof() == 1)
				break;
			catStreamIn >> catstr2[i].newsgroup;
			catStreamIn >> catstr2[i].id;
		}
		catStreamIn.close();
*/

}

bool clsSpammer::readCategoriesTemplate()
{
/*
	short	i, j;
	
	//	catStreamIn.open("/oracle04/export/home/pete/ebay/ItemsToNewsApp/template/categories.txt", ios::nocreate);
	catStreamIn.open("template/news_category", ios::in);
	if(catStreamIn.is_open()) {
		j = sizeof(catStringIn) / sizeof(CategoryToNewsTransformIn);
		for(i = 0 ;i < j; i++) {
			if (catStreamIn.eof() == 1)
				break;
			catStreamIn >> catStringIn[i].newsgroup;
			catStreamIn >> catStringIn[i].id;
		}
		catStreamIn.close();
		
		return true;
	}
*/
	return true;

}













/*
print NEWS "From: aw\@ebay.com (AuctionWeb)\nOrganization: eBay Internet's AuctionWeb
Newsgroups: $newsgroup
Subject:	AuctionWeb Listings for $title [may be long]
*/



/*

clsFile::clsFile(char *name)
{
    OutputStream.open(name, ios::out);
}

clsFile::~clsFile()
{
    OutputStream.close();
}

clsCategory *clsSpammer::GetTheCategory(CategoryId id)
{
	return(mpDatabase->GetCategoryById(mpCurrMarketPlace->GetId(), id));	
}


clsSpammer::clsSpammer()
{
	clsApp::clsApp();
}

clsSpammer::~clsSpammer()
{
}


clsFile::clsFile(char *name)
{
    OutputStream.open(name, ios::out);
}

clsFile::~clsFile()
{
    OutputStream.close();
}


void clsFile::Print(clsItem *pItem)
{
	char		PriceString[50];
	struct tm*	pEndTime;
	char		TimeString[50];
	time_t		EndingTime;
	
	OutputStream << (*pItem).GetTitle();

		    iHotItem = hotItems.begin();
		    hot = 0;
		    while (iHotItem != hotItems.end() && hot < 50) {
		      theFile->Print(*iHotItem);
		      
		      iHotItem++;
		      hot++;
		    }
		  }
		
		// now go through all the recent items and display the top 50
		if(getNew)
		  {

		    iRecentItem = recentItems.begin();
		    recent = 0;
		    while (iRecentItem != recentItems.end() && recent < 50) {
		      theFile->Print(*iRecentItem);
		      
		      iRecentItem++;
		      recent++;
		    }
		  }
		
		// nuke all the items
		pItems->erase(pItems->begin(), pItems->end());
		hotItems.erase(hotItems.begin(), hotItems.end());
		recentItems.erase(recentItems.begin(), recentItems.end());

		// dump the category
		if(pCategory)
			delete pCategory;

		// close the file
		delete theFile;
	}

	// hand off to INews
}


clsCategory *clsSpammer::GetTheCategory(CategoryId id)
{
	return(mpDatabase->GetCategoryById(mpCurrMarketPlace->GetId(), id));	
}


clsSpammer::clsSpammer()
{
	clsApp::clsApp();
}

clsSpammer::~clsSpammer()
{
}
*/

void clsSpammer::WriteToStream(ostrstream *theStream, char *buffer, size_t size)
{
	char *tempStr;
	
	if(size > 0) {
		tempStr = new char[size + 1];

		memcpy(tempStr, buffer, size);
		
		// insure length
		if(tempStr[size] != '\0') {
			tempStr[size] = '\0';
		}

		// all the above for this
		*theStream << (char *)tempStr;

		// be nice
		delete tempStr;
	}
}


void clsSpammer::SendMail(ostrstream *theStream)
{
	clsMail		*pMail;
	ostrstream	*pMailStream;
	char		subject[512];

	pMail	= new clsMail();

	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	*pMailStream << theStream->str();

	sprintf(subject, MAIL_SUBJECT);

	pMail->Send((char *)MAIL_TO_ADDRESS,
				(char *)MAIL_TO_ADDRESS,
				subject);

	delete	pMail;
}


void clsSpammer::SendUnixMail(ostrstream *theStream)
{
	clsMail		*pMail;
	ostrstream	*pMailStream;
	char		subject[512];
	FILE *mail;
	char *mailer;
	
	mailer = new char[512] = '\0';

	sprintf(subject, MAIL_SUBJECT);
	strcpy(mailer, "/usr/bin/mail "); // need "-t" option for standard /usr/bin/mail, drop it for mailx
	//	strcat(mailer, MAIL_SUBJECT);
	strcat(mailer, " ");
	strcat(mailer, MAIL_TO_ADDRESS);


	mail = popen(mailer, "w");
	if(mail != NULL)
	  {
	    fprintf(mail, theStream->str());
	    pclose(mail);
	  }
	
	delete [] mailer;

#if 0
	pMailStream	= pMail->OpenStream();

	// Prepare the stream
	pMailStream->setf(ios::fixed, ios::floatfield);
	pMailStream->setf(ios::showpoint, 1);
	pMailStream->precision(2);

	*pMailStream << theStream->str();



	pMail->Send((char *)MAIL_TO_ADDRESS,
				(char *)MAIL_TO_ADDRESS,
				subject);

	delete	pMail;
#endif
}


void clsSpammer::SendNews(ostrstream * theStream)
{
	char fileName[255], call[255];
	clsFile *theFile;
	char *args[4], *prog;
	int pid;
	int status, rc, result;
	
	// create the new output file
	//#ifdef WIN32
	//	sprintf((char *)&fileName, "d:\\temp\\%s", "testNewsFile");
	//#else
	//	sprintf((char *)&fileName, "/tmp/%s", "testNewsFile");
	//#endif

	sprintf((char *)&fileName, TEMP_PATH, TEST_FILE);

	theFile = new clsFile(fileName);
	
	theFile->WriteIt(theStream->str());

	// close it
	delete theFile;

	if(verbose)
	  cout << "waiting for inews...\n";
	    	    
	// eh call system instead
 	sprintf(call, "./inews %s", fileName);
	
	if(verbose) {
	  cout << "executing: ";
	  cout << call;
	  cout << "\n";
	}

#ifndef WIN32
	// call inews
	result = system(call);

	printf("result of system (inews) call: %d\n", result);

	// wait another few seconds to be sure...
	if(verbose)
	  cout << "waiting 5 seconds to be safe...\n";
	sleep(5);
#endif





	/*
	// now fork...
	if(verbose)
	  cout << "forking for inews...\n";

#ifndef WIN32
	switch (pid = fork()) 
	  {
	  case 0: // child
#ifdef WIN32
		  // Arguments for _execv?    
		  prog = "c:\\ebay\\inews\\Release\\inews.exe";
		  args[0] = prog;   
		  args[1] = (char *)&fileName;
		  _execv(prog, args);

		  // we be done (NOTE: some kind of cleanup should happen here
		  _exit(0);
#else
		  // Arguments for _execv?
		  prog = "./inews";
	
		  args[0] = prog;   
		  args[1] = (char *)&fileName;
		  // call inews
		  execv(prog, args);
		  // we be done (NOTE: some kind of cleanup should happen here
		  _exit(0);
#endif
	  default: // parent
	    // wait for the child process to finish
		  rc = waitpid(pid, &status, 0);
	    
	    // pid greater than zero is parent getting the child's pid 
	    if(verbose)
	      cout << "Child's pid is: " << pid << "\n";
	    
	    // give the inews process a bit of time to post (we use the same file name over and over)
	    if(verbose)
	      cout << "waiting for inews...\n";
	    	    
	    // wait another few seconds to be sure...
	    sleep(5);

		return;

	  case -1: // error
	    if(verbose)
	      cout << "something went wrong with the fork()..." << pid << "\n";

	    exit(-1);
	    return;
	  }
#endif


*/

}




#if 0
void clsSpammer::WalkThroughTransforms()
{
	const CategoryToNewsTransformIn *pCurrentTransform;
	ItemVector				Items, recentItems, hotItems;
	ItemVector*				pItems;
	ItemVector::iterator	iItem, iHotItem, iRecentItem;
	short			i, j, numOfTransforms, hot, recent;
	clsCategory*	pCategory;
	char			fileName[256];
	char			buffer[256];
	clsFile *theFile;

	numOfTransforms = sizeof(catStringIn) / sizeof(CategoryToNewsTransformIn);
	for(j=0; j < numOfTransforms; j++) {			
		// get current transform
		pCurrentTransform = (CategoryToNewsTransformIn *)&catStringIn[j];

		if(catStringIn[j].id == 0)
		  break;

		// create the new output file
#ifdef WIN32
		sprintf((char *)&fileName, "d:\\temp\\%d", pCurrentTransform->id);
#else
		sprintf((char *)&fileName, "/tmp/%d", pCurrentTransform->id);
#endif
		theFile = new clsFile(fileName);		
		
#ifdef WIN32
		pCategory = GetTheCategory(pCurrentTransform->id);
		mpDatabase->GetCategoryItems(pCategory->GetMarketPlaceId(), pCategory->GetId(), 1, mTime, &Items);
#else
		pCategory = mpCategories->GetCategory(pCurrentTransform->id);
		pCategory->GetItems(&Items, mTime);
#endif
		
		pItems = &Items;
		
		ReturnTitle(pCategory, (char *)&buffer);
		
		// write file headers & preamble
		theFile->WriteHeader(buffer, (char *)pCurrentTransform->newsgroup);
		theFile->WritePreamble(buffer);

			// Now, we loop through them
		iItem = Items.begin();
		i = 0;
		while (iItem != Items.end()) {
			
/*
			// if featured
			if ((*iItem)->GetFeatured()) 
			{
				theFile->Print(*iItem);
			}
*/

		  // if hot (more than 30 bids)
		  if (getHot && (*iItem)->GetBidCount() > HOT_COUNT)
		    {
		      hotItems.push_back(*iItem);
		      //			    pItems->erase(*iItem);
		      //				theFile->Print(*iItem);
		    }
		  else 
		    {
		      // get items that are less than one day old
		      if (getNew && difftime(mTime, (*iItem)->GetStartTime()) <= 60*60*24)
			{
			  recentItems.push_back(*iItem);
			  // erase the item
			  //				pItems->erase(*iItem);
			}
		      else
			{
			  // just erase it
			  //				pItems->erase(*iItem);
			}
		    }
		  
		  
		  // all others
		  //	theFile->Print(*iItem);
		  
		  // we're done with the item so delete it
		  //				delete *iItem;
		  
		  // bump up our iterators
		  iItem++;
		  i++;
		}
		
		// now go through all the hot items and display the top 50
		if(getHot && (hotItems.begin() != hotItems.end()))
		  {
		    theFile->WriteIt("==========\nHot items:\n==========\n\n");

		    iHotItem = hotItems.begin();
		    hot = 0;
		    while (iHotItem != hotItems.end() && hot < 50) {
		      theFile->Print(*iHotItem);
		      
		      iHotItem++;
		      hot++;
		    }
		  }
		
		// now go through all the recent items and display the top 50
		if(getNew)
		  {
		    theFile->WriteIt("=============\nRecent items:\n=============\n\n");

		    iRecentItem = recentItems.begin();
		    recent = 0;
		    while (iRecentItem != recentItems.end() && recent < 50) {
		      theFile->Print(*iRecentItem);
		      
		      iRecentItem++;
		      recent++;
		    }
		  }
		
		// nuke all the items
		pItems->erase(pItems->begin(), pItems->end());
		hotItems.erase(hotItems.begin(), hotItems.end());
		recentItems.erase(recentItems.begin(), recentItems.end());


		// dump the category
		if(pCategory)
		  delete pCategory;
		
		// close the file
		delete theFile;
	}

	// hand off to INews
}

#endif // #if 0



#if 0

// randomize
void getRandomItem()
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
	if (mpMarketPlace)
	{
		pItems = mpMarketPlace->GetItems();
		pCategories = mpMarketPlace->GetCategories();

		// btw, don't need to delete these, because mpMarketPlace's
		//  dtor will delete them
	}
	else
		return false;

	// get the item ids of all active, black-listed auctions,
	//  and stuff the ids into a vector
	pItems->GetBlackListItemIds(&vBlackListItemIds, CurrentTime);

	// get the relevant item ids and stuff the ids into a vector
	this->GetItemIds(&vItemIds);

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

#endif // #if 0
