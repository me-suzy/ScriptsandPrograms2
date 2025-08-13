/*	$Id: clsItemsToHTMLApp.cpp,v 1.2 1999/02/21 02:22:52 josh Exp $	*/
//
//	File:	clsItemsToHTMLApp.cc
//
//	Class:	clsItemsToHTMLApp
//
//	Author:	pete helme (pete@ebay.com)
//
//	Function:
//
//
// Modifications:
//				- 06/05/97 pete		- added expire item code
//


// conversion method for UNIX seconds since 1970 to VB
// CVDate(UnixTime / 24# / 3600# + #01/01/1970 12:00:00 AM#)


#include "eBayTypes.h"
#include "eBayDebug.h"
#include "clsItemsToHTMLApp.h"
#include "clsEnvironment.h"

// for event logging
#ifdef EVENT_REPORTING
#include "afxwin.h"
#include "clsEventLog.h"
#include "ebayResource.h"
#else
#include "WINDOWS.H"
#endif

#include <time.h>
#include <stdio.h>
#include <DIRECT.h>
#include <io.h>
#include <errno.h>

// #define DEBUG_PETE - now defined  in the Win32Debug project itself



// declare app name based on functionality
// #ifdef EXPIRE_CURRENT
// #define APP_NAME "expireItems"
// #else

// #endif


//
// code starts here
//

#define fgetAndStrip(it)						\
	if (!fgets(buffer, sizeof(buffer), pIn))	\
		quitSoon = true;						\
	it[sizeof (it) - 1] = 0;					\
	strncpy(it, buffer, sizeof(it) - 1);		\
	pIt		= strchr(it, '\n');					\
	if (pIt)									\
		*pIt	= '\0';


#ifdef EVENT_REPORTING


// statics
static clsItemsToHTMLApp *pTestApp = NULL;
static clsItemsToHTML *pItems = NULL;

//
// Create a new Registery source
//
void clsEventLog::CreateRegistrySource()
{
	HKEY	hk;                      // registry key handle
	DWORD	data;
	char	pSubKey[256];
	char	pFilePath[256];

	// create the subkey
	strcpy(pSubKey, "SYSTEM\\CurrentControlSet\\Services\\EventLog\\Application\\");
//	strcat(pSubKey, AfxGetAppName());
	strcat(pSubKey, APP_NAME);

	// Get the path of the current module
//	HMODULE hModule = GetModuleHandle(AfxGetAppName());
	HMODULE hModule = GetModuleHandle(APP_NAME);
	if (GetModuleFileName(hModule, pFilePath, sizeof(pFilePath)))
	{
		char*	p = strrchr(pFilePath, '\\');
		*p = 0;
		strcat(pFilePath, "\\APP_NAME.exe");
	}

	// Create a new key for our application
	RegCreateKey(HKEY_LOCAL_MACHINE, pSubKey, &hk);

	// Add the Event-ID message-file name to the subkey.
	RegSetValueEx(hk,						// subkey handle
				"EventMessageFile",			// value name
				0,							// must be zero
				REG_EXPAND_SZ,				// value type
				(LPBYTE) pFilePath,			// address of value data
				strlen(pFilePath) + 1);		// length of value data

	// Set the supported types flags and addit to the subkey.
	data = EVENTLOG_ERROR_TYPE | EVENTLOG_WARNING_TYPE | EVENTLOG_INFORMATION_TYPE;
	  
	RegSetValueEx(hk,					//subkey handle
				"TypesSupported",       // value name
				0,                      // must be zero
				REG_DWORD,              // value type
				(LPBYTE) &data,         // address of value data
				sizeof(DWORD));         // length of value data

	RegCloseKey(hk);
	return;
}

// 
// Log an event to the event log
//
void clsEventLog::LogAnEvent(unsigned int eventType, 
							 unsigned int category,
							 unsigned int evetId,
							 int numStrings, 
							 char** pStrings)
{
	HANDLE hAppLog;

	// Get a handle to the Application event log
	hAppLog = RegisterEventSource(NULL,				// use local machine
								  APP_NAME);	// source name   AfxGetAppName()   

	if (hAppLog == NULL)
	{
		// Create a key in Registry
		CreateRegistrySource();

		hAppLog = RegisterEventSource(NULL,				// use local machine
									   APP_NAME);	// source name   AfxGetAppName()     
	}

	// Now report the event, which will add this event to the event log
	ReportEvent(hAppLog,				 // event-log handle
				eventType,				 // event type
				category,                // category
				evetId,					 // event ID
				NULL,                    // no user SID
				numStrings,              // number of substitution strings
				0,                       // no binary data
				(LPCSTR*) pStrings,                // string array
				NULL);                   // address of data
	DeregisterEventSource(hAppLog);

	return;
}

// 
// Log an information event to the event log
//
void clsEventLog::LogInformationEvent(char* pStrings)
{
	LPSTR pMsg[] = { 
						pStrings, 
	}; 

	LogAnEvent(EVENTLOG_INFORMATION_TYPE, 0, MSG_INFORMATION, 1, pMsg);
}

// 
// Log a warning event to the event log
//
void clsEventLog::LogWarningEvent(char* pStrings)
{
	LPSTR pMsg[] = { 
						pStrings, 
	}; 

	LogAnEvent(EVENTLOG_WARNING_TYPE, 0, 0, 1, pMsg);
}

// 
// Log an error event to the event log
//
void clsEventLog::LogErrorEvent(char* pStrings)
{
	LPSTR pMsg[] = { 
						pStrings, 
	}; 

	LogAnEvent(EVENTLOG_ERROR_TYPE, 0, 0, 1, pMsg);
}


#endif // EVENT_REPORTING


// looks at end of last entry for extra LFs
//
// The types of events that can be logged.
//
// #define EVENTLOG_SUCCESS                0X0000
// #define EVENTLOG_ERROR_TYPE             0x0001
// #define EVENTLOG_WARNING_TYPE           0x0002
// #define EVENTLOG_INFORMATION_TYPE       0x0004
// #define EVENTLOG_AUDIT_SUCCESS          0x0008
// #define EVENTLOG_AUDIT_FAILURE          0x0010

int clsItemsToHTMLApp::checkForExtraLFs(FILE *file)
{
	char buf[3];
	short err = 0;
	fpos_t	pos;

	// get our current pos in the file and show our progress
	fgetpos(file, &pos);

	// get the next char
	fgets((char *)&buf, 2, file);

	if (buf[0] == '\n') {
		printf("file had extra LF!\n");
			
#ifdef EVENT_REPORTING
/*
		ReportAnEvent(	"[ItemsToHTML]",
							"[ItemsToHTML] file had extra LF!",
							"",
							"",
							EVENTLOG_WARNING_TYPE,
							0);
*/
#endif
	
	} else {
		// reset position to before test
		fsetpos(file, &pos );
	}

	return err;
}

void clsItemsToHTMLApp::parseItemsForSearch(char *searchFile, char *whichDir, int checkForCompleted)
{
	FILE	*pIn;

	char	itemAndDescriptionId[64];
	char	start[16];
	char	end[16];
	char	startPrice[16];
	char	startPriceSort[16];
	char	currentPrice[16];
	char	currentPriceSort[16];
	char	category[8];
	char	reservePrice[16];
	char	quantity[16];
	char	bidCount[16];
	char	bidCountSort[16];
	char	flags[8];
	char	seller[64];
	char	highBidder[16*1024]; // possible high count of bidders for dutch auctions
	char	location[128];
	char	title[128];
	char	junk[128];
	char	buffer[16*1024]; // 8192
	char	tmpbuffer[16];
	
	long	pricesize, bidsize;
	double	currentpriced;
	double	startpriced;
	long	i;

	char	*pIt;
	char	*pColon;

	// Time conversions
	time_t		startTime;
	time_t		endTime;
	struct tm	*pTheTime;

	char		cStartTime[32];
	char		cEndTime[32];
	char		cStartDate[32];
	char		cEndDate[32];

	// HTML filename
	char	HTMLFileName[128], tmpFileName[128];
	FILE	*pOut;

	int			count	= 0;
	fpos_t		totalLength, pos;
	double		ratio;

	char dirname[128], currentDirName[128];
	int quitSoon = false;


	// Let's open the input file
//	pIn		= fopen("E:item-info.dump.mw", "r");
//	pIn		= fopen("D:item-info.dump.pvh", "r");

	printf("%s\n", searchFile);

	pIn		 = fopen(searchFile, "r");

	if (!pIn)
	{
		printf("couldn't open %s!", searchFile);
		return;
	}
	
	// seek to end to get length
	fseek(pIn, 0, SEEK_END);
	// get length
	fgetpos(pIn, &totalLength );
	// seek to beginning to start
	fseek(pIn, 0, SEEK_SET); 

	// 
	// Let's read the file. Each item consistst of
	// multiple lines as follows:
	//	item-id:description-id
	//	Auction Start
	//	Auction End
	//	Starting price
	//	Ending price
	//	Reserve
	//	Category
	//	Quantity
	//	Bid Count
	//	Flags (?)
	//	(don't know)
	//	Seller
	//	High Bidder
	//	Location
	//	Title
	//	(newline)
	do
	{
		if(feof(pIn)) {
			printf("end of input file reached...");
			goto leave;
		}

		fgetAndStrip(itemAndDescriptionId);
		fgetAndStrip(start);
		fgetAndStrip(end);
		fgetAndStrip(startPrice);
		fgetAndStrip(currentPrice);
		fgetAndStrip(reservePrice);
		fgetAndStrip(category);
		fgetAndStrip(quantity);
		fgetAndStrip(bidCount);
		fgetAndStrip(flags);
		fgetAndStrip(junk);	// unused long int
		fgetAndStrip(seller);
		fgetAndStrip(highBidder);
		fgetAndStrip(location);
		fgetAndStrip(title);
		fgetAndStrip(junk); // location2
		
		// get our current pos in the file and show our progress
		fgetpos(pIn, &pos);
		ratio = (double)pos/(double)totalLength;
		printf("progress: %.2f%%  : %s\n", ratio*100, itemAndDescriptionId);
		
		// look for those occasional extra LFs that have snuck their way in
		this->checkForExtraLFs(pIn);

		// check to see if we have any adult items. we don't want to add those to the index
		if(atol(category) != ADULT_CATEGORY) {
			if(itemAndDescriptionId[0] == '\0') {
				printf("ouch!\n");
				printf("dump file corrupted near %s or %s", start, end);
				goto leave;
			}
			
			
			// ack something broke
			if(feof(pIn)) {
				printf("end of input file reached...");
				goto leave;
			}
			
			// Ok, let's null terminate the item
			pColon	= strchr(itemAndDescriptionId, ':');
			if (pColon)
				*pColon	= '\0';
			
			// check to see if this item exists in the "current" directory, if it does delete it.
			// this is only used for matching against the completed file search list
			if(checkForCompleted) {
				// create the path to the file
				sprintf(currentDirName, currentAuctionPath, itemAndDescriptionId[0]);		
				sprintf(tmpFileName, 
					"%s\\%s.html",
					currentDirName,
					itemAndDescriptionId);
				
				// remove it
				if(remove(tmpFileName) == 0) {
					printf("deleted (moved to completed): %s\n", itemAndDescriptionId);
				}
			}
			
			
			// Convert the times
			startTime	= atol(start);
			pTheTime	= localtime(&startTime);
			//		strftime(cStartTime, sizeof(cStartTime),
			//				 "%m/%d/%Y %H:%M:%S",
			//				 pTheTime);
			strftime(cStartTime, sizeof(cStartTime),
				"%H:%M",	// :%S
				pTheTime);
			strftime(cStartDate, sizeof(cStartDate),
				"%m/%d/%Y",
				pTheTime);
			
			endTime	= atol(end);
			pTheTime	= localtime(&endTime);
			//		strftime(cEndTime, sizeof(cEndTime),
			//				 "%m/%d/%Y %H:%M:%S",
			//				 pTheTime);
			strftime(cEndTime, sizeof(cEndTime),
				"%H:%M",	// :%S
				pTheTime);
			strftime(cEndDate, sizeof(cEndDate),
				"%m/%d/%Y",
				pTheTime);
			
			
			// convert the prices to a sortable format, up to 100 billion 00000000000.00 (13 chars)
			// starting price
			startPriceSort[0] = 0;
			pricesize = strlen(startPrice);
			
			// build up a bunch of 0's in front of the price
			for(i=15;i > pricesize;i--) {
				// prepend 0
				strcat(startPriceSort, "0");
			}
			startpriced = atof(startPrice);
			startpriced = startpriced * 100;
			_ltoa((long)startpriced, tmpbuffer, 10);
			strcat(startPriceSort, tmpbuffer);
			
			// current price
			currentPriceSort[0] = 0;
			pricesize = strlen(currentPrice);
			
			// build up a bunch of 0's in front of the price
			for(i=15;i > pricesize;i--) {
				// prepend 0
				strcat(currentPriceSort, "0");
			}
			currentpriced = atof(currentPrice);
			currentpriced = currentpriced * 100;
			_ltoa(currentpriced, tmpbuffer, 10);
			strcat(currentPriceSort, tmpbuffer);
			
			// current bid count
			bidCountSort[0] = 0;
			bidsize = strlen(bidCount);
			 
			// build up a bunch of 0's in front of the price
			for(i=15;i > bidsize;i--) {
				// prepend 0
				strcat(bidCountSort, "0");
			}
			strcat(bidCountSort, bidCount);
			
			// get the first letter of the item, we need this to build the directory it lives in
			//		sprintf(dirname, "D:\\InetPub\\eBay Products\\%c", itemAndDescriptionId[0]);
			sprintf(dirname, whichDir, itemAndDescriptionId[0]);
			
			// make it
			mkdir(dirname);
			
			// Okay, let's make some HTML in the right directory
			sprintf(HTMLFileName, 
			/*				"E:\\INetPub\\eBay Products\\%s.html",
			itemAndDescriptionId);		sprintf(HTMLFileName, */
			"%s\\%s.html",
			dirname,
			itemAndDescriptionId);
			pOut	= fopen(HTMLFileName, "w");
			if (!pOut) {
				printf("couldn't open %s", HTMLFileName);
				goto leave;
			} else {
				
				fputs("<HTML>\n", pOut);
				fprintf(pOut,"<HEAD><TITLE>%s</TITLE></HEAD>\n",
					title);
//				fprintf(pOut,"<META NAME=\"auctiontitle\" CONTENT=\"%s\">\n",
//					title);
				fprintf(pOut, "<META NAME=\"item\" CONTENT=\"%s\">\n",
					itemAndDescriptionId);
				fprintf(pOut, "<META NAME=\"startdate\" CONTENT=\"%s\">\n",
					cStartDate);
				fprintf(pOut, "<META NAME=\"starttime\" CONTENT=\"%s\">\n",
					cStartTime);
				fprintf(pOut, "<META NAME=\"startsort\" CONTENT=\"%s\">\n",
					start);
				fprintf(pOut, "<META NAME=\"endtime\" CONTENT=\"%s\">\n",
					cEndTime);
				fprintf(pOut, "<META NAME=\"enddate\" CONTENT=\"%s\">\n",
					cEndDate);
				fprintf(pOut, "<META NAME=\"endsort\" CONTENT=\"%s\">\n",
					end);
				fprintf(pOut, "<META NAME=\"startprice\" CONTENT=\"%s\">\n",
					startPrice);
				fprintf(pOut, "<META NAME=\"startpricesort\" CONTENT=\"%s\">\n",
					startPriceSort);
				fprintf(pOut, "<META NAME=\"currentprice\" CONTENT=\"%s\">\n",
					currentPrice);
				fprintf(pOut, "<META NAME=\"currentpricesort\" CONTENT=\"%s\">\n",
					currentPriceSort);
				fprintf(pOut, "<META NAME=\"category\" CONTENT=\"%s\">\n",
					category);
				fprintf(pOut, "<META NAME=\"bidcount\" CONTENT=\"%s\">\n",
					bidCount);
				fprintf(pOut, "<META NAME=\"bidcountsort\" CONTENT=\"%s\">\n",
					bidCountSort);
				fprintf(pOut, "<META NAME=\"seller\" CONTENT=\"%s\">\n",
					seller);
				fprintf(pOut, "<META NAME=\"highbidder\" CONTENT=\"%s\">\n",
					highBidder);
				fprintf(pOut, "<META NAME=\"location\" CONTENT=\"%s\">\n",
					location);
//				fputs("<BODY></BODY>\n", pOut);
				fprintf(pOut,"<BODY>%s</BODY>\n",
					title);				
				fputs("</HTML>", pOut);
				
				fclose(pOut);
			}
		} else {
			// tell the console we're skipping this item because its nasty
			printf("skipping: '%s' because it's in category '%s'\n", itemAndDescriptionId, category);		
		}

		count++;

	} while (!quitSoon);

	leave:
		fclose(pIn);

	return;
}

void clsItemsToHTMLApp::readMoveOrExpire(FILE *stream, _finddata_t *html_file, int howLongAgo, 
										 int moveOrDelete, char *parentDir)
{
	int		pos, numread, i, readSize = 17*1024, result;   
	char	strBuf[17*1024];	// large buffer
	char	*pdest, *pdest2;
	char	str[] = "endsort";
	char	quote = '"';
	char	endSortDate[16];
	time_t	timer;	
	time_t		endTime;
	struct tm	*pTheTime;
	char		cEndTime[32];
	char	newFileName[256], oldFileName[256];
	
	numread = fread( &strBuf, sizeof( char ), readSize, stream );
	if(numread == 0) {
		printf( "error reading %s\n", html_file->name);
	} else {					
		// find occurrence of endsort
		pdest = strstr(strBuf,  str);
		
		// point the buffer to the value (past "endsort" CONTENT =")
		pdest += 18;
		
		// get the length of the string
		pdest2 = strchr(pdest, quote);
		pos = pdest2 - pdest;
		
		// copy the string and terminate it
		for(i=0;i<pos;i++) {
			endSortDate[i] = pdest[i];
		}
		endSortDate[pos+1] = '\0';
		
		// compare endtime of item to (current time - passed in value)
		time(&timer);
		timer -= howLongAgo; 
		if(atol(endSortDate) < timer) {
			endTime	= atol(endSortDate);
			pTheTime	= localtime(&endTime);
			strftime(cEndTime, sizeof(cEndTime), "%H:%M %m/%d/%Y", pTheTime);
			printf("%s is old (ended on %s). nuke it!\n", html_file->name, cEndTime);
			
			// check whether or not we should move the item or just delete it
			if(moveOrDelete == MOVE_IT) { // 
				fclose(stream);
				
				oldFileName[0] = 0;
				strcat(oldFileName, currentAuctionPathNoChar);
				strcat(oldFileName, parentDir);
				strcat(oldFileName, "\\");
				strcat(oldFileName, html_file->name);
				
				newFileName[0] = 0;
				strcat(newFileName, completedAuctionPathNoChar);
				strcat(newFileName, parentDir);
				strcat(newFileName, "\\");
				strcat(newFileName, html_file->name);
				
				result = rename(oldFileName, newFileName );   
				if( result != 0 ) {

					printf( "Could not rename '%s'. deleting it.\n", html_file->name );   	

					// just delete it
					fclose(stream);
					result = remove(html_file->name);
					if( result != 0 ) {
						printf( "Could not remove '%s'\n", html_file->name );   	
					} else { 
						printf( "'%s' was deleted!\n", html_file->name );   	
					}	
					
/*			
					printf( "Could not rename '%s'. moving to a holding directory\n", html_file->name );   	
					// move to a holding directory
					newFileName[0] = 0;
					strcat(newFileName, holdingPenPathNoChar);
					strcat(newFileName, parentDir);
					strcat(newFileName, "\\");
					strcat(newFileName, html_file->name);
					
					result = rename(oldFileName, newFileName );  
					if( result != 0 ) {
						// just delete it
						fclose(stream);
						result = remove(html_file->name);
						if( result != 0 ) {
							printf( "Could not remove '%s'\n", html_file->name );   	
						} else { 
							printf( "'%s' was deleted!\n", html_file->name );   	
						}					
					}

					// check to see if the rename failed (it will if the same filename already exists)
					if( result == EACCES ) {
						// remove the old holding file
						result = remove(newFileName);

						// and rename again
						result = rename(oldFileName, newFileName ); 
						if(result == 0) {
							printf( "successfully moved '%s' to holding after removing old file first\n", html_file->name ); 
						} else {
							printf( "Tried remove() and still could not move '%s' to holding directory (%s to %s)\n", html_file->name, oldFileName, newFileName);
						}
					} else if(result != 0) {
						printf( "Error: %i. Could not move '%s' to holding directory (%s to %s)\n", result, html_file->name, oldFileName, newFileName);
					} else if(result == 0) {
						printf( "successfully moved '%s' to holding\n", html_file->name ); 
					}
*/
				} else {
					printf( "successfully moved '%s' to completed\n", html_file->name );   	
				}
			} else { 
				// just delete it
				fclose(stream);
				result = remove(html_file->name);
				if( result != 0 ) {
					printf( "Could not remove '%s'\n", html_file->name );   	
				} else { 
					printf( "'%s' was deleted!\n", html_file->name );   	
				}
			}
			
		} else {
			printf("%s\n", html_file->name);
		}
	}
}


// remove items from their directory if too old
// whichDir = the directory to start searching from
// howLongAgo = how much time from currentTime to set the expiration date to
// moveOrDelete = whether to remove the file completely (completed items) or move it to completed (current)

void clsItemsToHTMLApp::expireItems(char *whichDir, int howLongAgo, int moveOrDelete)
{
    struct  _finddata_t html_file;    
	long	hFile;
	FILE	*stream;
	int		count = 0, i = 0;
	char	subDirArray[26] = {'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'};
	char	subDir[256];
	char	parentDir[3];

	parentDir[1] = '\0';
	
	// loop through all the sub folders [a-z]
	for(i=0;i<sizeof(subDirArray);i++) {
		subDir[0] = 0;
		strcat(subDir, whichDir);
		parentDir[0] = subDirArray[i];
		strcat(subDir, parentDir);

		// set the current working directory
		if(_chdir(subDir) == -1) {
			printf("couldn't change directory to '%s'\n", subDir);
		} else {
			/* Find first .html file in current directory */
			if( (hFile = _findfirst( "*.html", &html_file )) == -1L ) {
				printf( "No *.html files in current directory!\n" );
			} else   {            
				if( (stream  = fopen(html_file.name, "r" )) != NULL ) {				
					this->readMoveOrExpire(stream, &html_file, howLongAgo, moveOrDelete, parentDir);
					// close up the file
					fclose(stream);
				}
				count++;
			}
			
			/* Find the rest of the .html files */
			while( (hFile != -1L) && _findnext( hFile, &html_file ) == 0 )            {
				if( (stream  = fopen(html_file.name, "r" )) != NULL ) {				
					this->readMoveOrExpire(stream, &html_file, howLongAgo, moveOrDelete, parentDir);
					// close up the file
					fclose(stream);
				}
				count++;
			}       
			_findclose( hFile );  
		}	
	}
}


void clsItemsToHTMLApp::strstrtest( void )
{  
	char str[] =    "lazy";
	char string[] = "The quick brown dog jumps over the lazy fox";
	char fmt1[] =   "         1         2         3         4         5";
	char fmt2[] =   "12345678901234567890123456789012345678901234567890";
	char *pdest;   
	int  result;
	
	printf( "String to be searched:\n\t%s\n", string );
	printf( "\t%s\n\t%s\n\n", fmt1, fmt2 );   
	pdest = strstr( string, str );
	result = pdest - string + 1;   
	if( pdest != NULL )
		printf( "%s found at position %d\n\n", str, result );   
	else
		printf( "%s not found\n", str );
	
}

void clsItemsToHTMLApp::cleanOutCategoryFromIndex(long categoryID)
{
	FILE	*pIn;
	char	itemAndDescriptionId[64], tmpFileName[64], currentDirName[64];
	char	buffer[16*1024];
	char	*pIt;
	int		count = 0;
	int		quitSoon = false;
	
	pIn		 = fopen("D:\expire64.txt", "r"); 
	
	if(pIn != NULL) {
		do
		{
			if(feof(pIn)) {
				printf("end of input file reached...");
				quitSoon = true;
				break;
			}
			
			fgetAndStrip(itemAndDescriptionId);
			
			sprintf(currentDirName, currentAuctionPath, itemAndDescriptionId[0]);		
			sprintf(tmpFileName, 
				"%s\\%s.html",
				currentDirName,
				itemAndDescriptionId);
			
			// remove it
			if(remove(tmpFileName) == 0) {
				printf("deleted: %s\n", itemAndDescriptionId);
			} else {
				printf("remove of: '%s' failed...\n", itemAndDescriptionId);
			}
			
			count++;
		} while(1 == 1);
		
leave:
		fclose(pIn);
	}
}

clsItemsToHTMLApp::~clsItemsToHTMLApp()
{
	return;
}

clsItemsToHTMLApp::clsItemsToHTMLApp()
{
	return;
}


void clsItemsToHTMLApp::Run()
{
	//
	// parse through the db dump file for items to add to the index
	//
	if(newRoutines) {
		if(parseInc) {
			LogEvent("itemsToHTML starting to parse incremental items");
			
			printf("handling incremental items...\n");
			
			// set up default paths for this kind of parse
//			pItems->auctionsFile = modifiedAuctionsNew;
			strcpy(pItems->auctionsFile, disk);
			strcat(pItems->auctionsFile, modifiedAuctionsNew);

			pItems->auctionsFilePath = modifiedAuctionPathNew;
			pItems->auctionsFilePathNoChar = modifiedAuctionPathNoCharNew;
			
			// do it
			pItems->parseItemsForSearch((char *)&pItems->auctionsFile, modifiedAuctionPathNew, 1);
			
			LogEvent("itemsToHTML finished parsing incremental items");
		}
		
		if(parseAllCurr) {
			LogEvent("itemsToHTML starting to parse all current items");
			
			printf("handling current items...\n");
			
			// set up default paths for this kind of parse
//			pItems->auctionsFile = currentAuctionsNew;
			strcpy(pItems->auctionsFile, disk);
			strcat(pItems->auctionsFile, currentAuctionsNew);


			pItems->auctionsFilePath = currentAuctionPathNew;
			pItems->auctionsFilePathNoChar = currentAuctionPathNoCharNew;
			
			// do it
			pItems->parseItemsForSearch((char *)&pItems->auctionsFile, currentAuctionPathNew, 1);
			
			LogEvent("itemsToHTML finished parsing current items");
		}
		
		if(parseAllComp) {
			LogEvent("itemsToHTML starting to parse all completed items");
			
			printf("handling completed items...\n");
			
			// set up default paths for this kind of parse
//			pItems->auctionsFile = completedAuctionsNew;
			strcpy(pItems->auctionsFile, disk);
			strcat(pItems->auctionsFile, completedAuctionsNew);

			pItems->auctionsFilePath = completedAuctionPathNew;
			pItems->auctionsFilePathNoChar = completedAuctionPathNoCharNew;
			
			// do it
			pItems->parseItemsForSearch(pItems->auctionsFile, completedAuctionPathNew, 0);
			
			LogEvent("itemsToHTML finished parsing completed items");
		}
		
		if(expire) {
			
			LogEvent("expire current items starting");			
			printf("handling expired current items (move to completed)...\n");
			pItems->expireItems(currentAuctionPathNoCharNew, 6 * 60 * 60, MOVE_IT);			
			LogEvent("expire current items finished");
			
			LogEvent("expire completed items starting");			
			// expire completed items that should have died at least 30 days ago
			printf("handling expired completed items...\n");
			pItems->expireItems(completedAuctionPathNoCharNew, 30 * 24 * 60 * 60, DELETE_IT);			
			LogEvent("expire completed items finished");
			
		} // expire
		
		if(destroySmut) {		
			LogEvent("destroying current smut starting");			
			printf("destroying smut...\n");
			pItems->destroyCategory(currentAuctionPathNoCharNew, DELETE_CATEGORY);			
			LogEvent("destroying current smut finished");			

			LogEvent("destroying completed smut starting");			
			printf("destroying smut...\n");
			pItems->destroyCategory(completedAuctionPathNoCharNew, DELETE_CATEGORY);			
			LogEvent("destroying completed smut finished");			
		} // destroySmut

	} else {
		if(parseInc) {
			// #ifdef HOURLY_PARSE
			
			LogEvent("itemsToHTML starting to parse current incremental items");
			
			printf("handling current items...\n");
			parseItemsForSearch(currentAuctions, currentAuctionPath, 0);
			
			LogEvent("itemsToHTML starting to parse completed incremental items");
			
			printf("handling completed items...\n");
			parseItemsForSearch(completedAuctions, completedAuctionPath, 1);
			
			LogEvent("itemsToHTML finished incremental parsing");
		}
		
		// #endif // HOURLY_PARSE
		
		
		// #ifdef NIGHTLY_EVERYTHING_PARSE
		
		//
		// parse through the db dump file for current items to add to the index
		//
		if(parseAllCurr) {
			printf("handling current items...\n");
			
			LogEvent("itemsToHTML starting to parse all current items");
			
			parseItemsForSearch(currentAuctionsEverything, currentAuctionPath, 0);
			
			LogEvent("itemsToHTML finished parsing all current items");
		}
		
		//
		// parse through the db dump file for completed items to add to the index
		//
		if(parseAllComp) {
			LogEvent("itemsToHTML starting to parse all completed items");
			
			printf("handling completed items...\n");
			parseItemsForSearch(completedAuctionsEverything, completedAuctionPath, 0);
			
			LogEvent("itemsToHTML finished parsing all completed items");
		}
		
		// #endif // NIGHTLY_EVERYTHING_PARSE
		
		
		// #ifdef EXPIRE_CURRENT
		
		//
		// expire current items that should have died at least 6 hours ago
		// expire completed items that should have died at least 30 days ago
		//
		if(expire) {
			
			LogEvent("expire current items starting");
			
			printf("handling expired current items (move to completed)...\n");
			expireItems(currentAuctionPathNoChar, 6 * 60 * 60, MOVE_IT);
			
			LogEvent("expire current items finished");
			
			
			// #endif // EXPIRE_CURRENT
			
			
			// #ifdef EXPIRE_COMPLETED
			
			LogEvent("expire completed items starting");
			
			// expire completed items that should have died at least 30 days ago
			expireItems(completedAuctionPathNoChar, 30 * 24 * 60 * 60, DELETE_IT);
			
			LogEvent("expire completed items finished");
			
		} // expire
		
		// #endif // EXPIRE_COMPLETED
	}
	
#ifdef DESTROY_CATEGORY
	// destroy items of a particular category
	printf("handling expired current items (move to completed)...\n");
	cleanOutCategoryFromIndex(64);
#endif
}



int main(int argc, char *argv[ ])
{
	bool newRoutines = false;
	bool parseInc = false;
	bool parseIncCurr = false;
	bool parseIncComp = false;
	bool parseAll = false;
	bool parseAllCurr = false;
	bool parseAllComp = false;
	bool expire = false;
	bool destroySmut = false;
	bool newDisk = false;
	char disk[12];

	int i = 2;
	
	if(argc > 1) {
		while(i <= argc) {
			if(strcmp(argv[i-1], "-parseInc") == 0) {
				printf("parsing incremental (all)...\n");
				parseInc = true;
			} else if(strcmp(argv[i-1], "-parseIncCurr") == 0) {
				printf("parsing incremental current...\n");
				parseIncCurr = true;
			} else if(strcmp(argv[i-1], "-parseIncComp") == 0) {
				printf("parsing incremental completed...\n");
				parseIncComp = true;
			} else if(strcmp(argv[i-1], "-parseAllCurr") == 0) {
				printf("parsing all current...\n");
				parseAllCurr = true;
			} else if(strcmp(argv[i-1], "-parseAllComp") == 0) {
				printf("parsing all completed...\n");
				parseAllComp = true;
			} else if(strcmp(argv[i-1], "-parseAll") == 0) {
				printf("parsing all...\n");
				parseAllCurr = true;
			} else if(strcmp(argv[i-1], "-expire") == 0) {
				printf("expiring...\n");
				expire = true;
			} else if(strcmp(argv[i-1], "-destroySmut") == 0) {
				printf("destroySing smut...\n");
				destroySmut = true;
			} else if(strcmp(argv[i-1], "-new") == 0) {
				printf("using new system routines...\n");
				newRoutines = true;
			} else if(strcmp(argv[i-1], "-d") == 0) {
				printf("using new disk path...\n");
				strcpy(disk, argv[i]);
				i++;
				newDisk = true;
			} else {
				printf("ItemsToHTML Copyright 1997 eBay Inc.\n\n");
				printf("valid arguments are:\n");
				printf(" -parseInc	- parse incremental (all)\n");
				printf(" -parseIncCurr	- parse incremental current\n");
				printf(" -parseIncComp	- parse incremental completed\n");	
				
				printf(" -parseAll	- parse all items\n");	
				printf(" -parseAllCurr	- parse all current items\n");	
				printf(" -parseAllComp	- parse all completed items\n");	
				
				printf(" -expire	- expire items\n");	
				printf(" -destroySmut	- destory files in Adult categories (320,321,322,323)\n");	
				
				printf(" -d	<path> - new disk for itemstotext files. ex. d:\n");	

				exit(1);			
				break;
			}
			
			i++;
		}
	} else {
		printf("ItemsToHTML Copyright 1997 eBay Inc.\n\n");
		printf("valid arguments are:\n");
		printf(" -parseIncCurr	- parse incremental current\n");
		printf(" -parseIncComp	- parse incremental completed\n");	
		
		printf(" -parseAllCurr	- parse all current items\n");	
		printf(" -parseAllComp	- parse all completed items\n");	
		
		printf(" -expire		- expire items\n");	
		printf(" -destroySmut	- destory files in Adult categories (320,321,322,323)\n");	
		
		printf(" -d	<path> - new disk for itemstotext files. ex. d:\n");	

		exit(1);			
	}
	
	// create a new app object
	if (!pTestApp)
	{
		pTestApp	= new clsItemsToHTMLApp();
	}
	
	// create a newRoutine object if we need to
	if(newRoutines) {
		pItems = new clsItemsToHTML();
		pItems->newRoutines = true;
	}
	
	// disk
	if(newDisk == true) {
		strcpy(pTestApp->disk, disk);
	} else {
		strcpy(pTestApp->disk, "d:");
		printf("using default path d:\n");	
	}
	
	//	let the object know what we want to do
	pTestApp->newRoutines = newRoutines;
	pTestApp->parseInc = parseInc;
	pTestApp->parseIncCurr = parseIncCurr;
	pTestApp->parseIncComp = parseIncComp;
	pTestApp->parseAll = parseAll;
	pTestApp->parseAllCurr = parseAllCurr;
	pTestApp->parseAllComp = parseAllComp;
	pTestApp->expire = expire;
	pTestApp->destroySmut = destroySmut;
	
	pTestApp->InitShell();
	pTestApp->Run();
	
	return 0;
}


//
// walks through an entire directory tree. will be used to expire items within that tree
//

void clsItemsToHTMLApp::trudgeThroughDirectories(char *whichDir)
{
    struct  _finddata_t file;    
	long	hFile;
	FILE	*stream;
	int		count = 0, i = 0;
	
	// loop through all the files & sub folders for the given directory
	// set the current working directory
	if(_chdir(whichDir) == -1) {
		printf("couldn't change directory to '%s'\n", whichDir);
		return;
	} else {
		/* Find first .html file in current directory */
		if( (hFile = _findfirst( "*", &file )) == -1L ) {
			printf( "No * files in current directory!\n" );
		} else   {            
			// check to see if we have a directory (and not . or ..)
			if((file.attrib & _A_SUBDIR) == _A_SUBDIR && !(strcmp(file.name, ".") == 0) && !(strcmp(file.name, "..") == 0)) {
				printf("hi there directory %s. we'll scan you now.\n", file.name);
				// recursive search
				trudgeThroughDirectories(file.name);
			} else if( (stream  = fopen(file.name, "r" )) != NULL ) {				
				// do something here ...

				// close up the file
				fclose(stream);
			}
			count++;
		}
		
		/* Find the rest of the .html files */
		while( (hFile != -1L) && _findnext( hFile, &file ) == 0 ) {
			// check to see if we have a directory (and not . or ..)
			if((file.attrib & _A_SUBDIR) == _A_SUBDIR && !(strcmp(file.name, ".") == 0) && !(strcmp(file.name, "..") == 0)) {
				printf("hi there directory %s. we'll scan you now.\n", file.name);
				// recursive search
				trudgeThroughDirectories(file.name);
			} else if( (stream  = fopen(file.name, "r" )) != NULL ) {				
				// do something here ...

				// close up the file
				fclose(stream);
			}
			count++;
		}       
		_findclose( hFile );  
	}	

	// go back up a directory
	_chdir("..");
}












#ifdef NO_KERNEL
// stolen from kernel

// The global which is us
// clsApp *gApp = (clsApp *)0;


clsApp::clsApp()
{
//	mpEnvironment		= NULL;
//	mpDatabase			= NULL;
//	mpMarketPlaces		= NULL;
	mAppType				= APP_UNKNOWN;
	mpStream				= NULL;
	mpEventLog			= NULL;
	// #ifdef _MSC_VER

// #endif /* _MSC_VER */

#ifdef _MSC_VER
//	mpISAPIStreamBuf	= NULL;
#endif /* _MSC_VER */

//	SetApp(this);
	return;
}


clsApp::~clsApp()
{
	delete	mpStream;

#ifdef _MSC_VER
//	delete	mpISAPIStreamBuf;
#ifdef EVENT_REPORTING
	delete  mpEventLog;
#endif
#endif /* _MSC_VER */

//	delete	mpMarketPlaces;
//	delete	mpDatabase;
//	delete	mpEnvironment;

	return;
}

//
// InitShell
//
//	This little method sets things up for Shell apps
//
void clsApp::InitShell()
{
	if (!mpStream)
		mpStream	= &cout;

	mAppType		= APP_SHELL;

	return;
}

#ifdef EVENT_REPORTING
void clsApp::LogEvent(char* pMsg)
{
	if (mpEventLog == NULL)
	{
		mpEventLog = new clsEventLog();
	}

	mpEventLog->LogInformationEvent(pMsg);
}
#endif

#endif // NO_KERNEL
