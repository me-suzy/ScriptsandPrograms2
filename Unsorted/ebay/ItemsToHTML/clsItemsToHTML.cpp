/*	$Id: clsItemsToHTML.cpp,v 1.2 1999/02/21 02:22:50 josh Exp $	*/
/*
	clsItemsToHTML
*/

#include "eBayTypes.h"
#include "eBayDebug.h"
#include "clsItemsToHTML.h"
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


#define fgetAndStrip(it)						\
	if (!fgets(buffer, sizeof(buffer), pIn))	\
		quitSoon = true;						\
	it[sizeof (it) - 1] = 0;					\
	strncpy(it, buffer, sizeof(it) - 1);		\
	pIt		= strchr(it, '\n');					\
	if (pIt)									\
		*pIt	= '\0';

#define zeroOut(it)			\
	i = 0;					\
	while(i < sizeof(it)) {	\
		it[i] = '\0';		\
		i++;				\
	}

clsItemsToHTML::clsItemsToHTML()
{
}

clsItemsToHTML::~clsItemsToHTML()
{
}

int clsItemsToHTML::checkForExtraLFs(FILE *file)
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
				
	} else {
		// reset position to before test
		fsetpos(file, &pos );
	}

	return err;
}

void clsItemsToHTML::getFileLocation(char *dirname, char *whichDir, char *itemId)
{
	char tempDirname[128];
	size_t length;

	if(newRoutines) {
		// get the last 2 digits of the item, we need this to build the directory it lives in
		length = strlen(itemId);
		if(length == 1) {
			// get the last digit and preface it with a '0'
			tempDirname[0] = '0';
			tempDirname[1] = itemId[1];
			tempDirname[2] = '\0';
		} else {
			// get the last two digits
			tempDirname[0] = itemId[length-2];
			tempDirname[1] = itemId[length-1];
			tempDirname[2] = '\0';
		}
		sprintf(dirname, whichDir, tempDirname);
		
	} else {
		// get the first letter of the item, we need this to build the directory it lives in
		sprintf(dirname, whichDir, itemId[0]);		
	}
}

//
// splits category up into a string based on its position
//
void clsItemsToHTML::categorySplit(char *str, char *dest, int position)
{
	char *pdest, *pdest2;
	int pos;
	int i;
	
	if(position == 0) {
		// find occurrence of " "
		pdest = strstr(str,  " ");
		pos = pdest - str;
		pdest = str;
	} else {
		
		pdest = strstr(str,  " ");
				
		while(position > 0) {
			// point the buffer to the value (past "endsort" CONTENT =")
			
			// get the length of the string
			pdest2 = strstr(pdest+1, " ");
			if(pdest2 == 0)
				pdest2 = str + strlen(str);
			pdest += 1;
			pos = pdest2 - pdest;
			
			position -= 1;
			// check to see if we need to cycle again
			if(position > 0)
				pdest += pos;
		}
	}
	
	// copy the string and terminate it
	for(i=0;i<pos;i++) {
		dest[i] = pdest[i];
	}
	dest[pos] = '\0';
}


void clsItemsToHTML::parseItemsForSearch(char *searchFile, char *whichDir, int checkForCompleted)
{
	FILE	*pIn;
	
	char	itemId[64];
	char	start[16];
	char	end[16];
	char	startPrice[16];
	char	startPriceSort[16];
	char	currentPrice[16];
	char	currentPriceSort[16];
	char	category[36];
	char	reservePrice[16];
	char	quantity[16];
	char	bidCount[16];
	char	bidCountSort[16];
	char	flags[8];
	char	seller[64];
	char	highBidder[16*1024]; // possible high count of bidders for dutch auctions
	char	location[128];
	char	title[128];
	// items new to search item files (v 1.5+) after this point
	char	featured[2];
	char	superFeatured[2];
	char	boldTitle[2];
	char	privateSale[2];
	char	registeredOnly[2];
	char	pictureURL[512];
	char	category1[8];
	char	category2[8];
	char	category3[8];
	char	category4[8];
	char	adult[2];
	// future use 
	// - seller rating
	
	// other items
	long	pricesize, bidsize;
	double	currentpriced;
	double	startpriced;
	long	i;
	
	char	junk[128];
	char	buffer[16*1024];
	char	tmpbuffer[16];
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
	char	HTMLFileName[128], tmpFileName[256], tmpNewFileName[256];
	FILE	*pOut;
	
	int			count	= 0;
	fpos_t		totalLength, pos;
	double		ratio;
	
	char dirname[256], currentDirName[256], completedDirName[256];
	int quitSoon = false;

	char bodyText[1024];

	// make sure we are working with clean arrays
	zeroOut(privateSale);
	zeroOut(start);
	zeroOut(end);
	zeroOut(startPrice);
	zeroOut(startPriceSort);
	zeroOut(currentPrice);
	zeroOut(currentPriceSort);
	zeroOut(category);
	zeroOut(reservePrice);
	zeroOut(quantity);
	zeroOut(bidCount);
	zeroOut(bidCountSort);
	zeroOut(flags);
	zeroOut(seller);
	zeroOut(highBidder); // possible high count of bidders for dutch auctions
	zeroOut(location);
	zeroOut(title);
	// items new to search item files (v 1.5+) after this point
	zeroOut(featured);
	zeroOut(superFeatured);
	zeroOut(boldTitle);
	zeroOut(privateSale);
	zeroOut(registeredOnly);
	zeroOut(pictureURL);
	zeroOut(category1);
	zeroOut(category2);
	zeroOut(category3);
	zeroOut(category4);
	zeroOut(adult);
	
	// Let's open the input file
	//	pIn		= fopen("E:item-info.dump.mw", "r");
	//	pIn		= fopen("D:item-info.dump.pvh", "r");
	
	printf("trying to open %s input file\n", searchFile);
//	cout << "trying to open " << searchFile << " input file\n";
	
	pIn		 = fopen(searchFile, "r");
	
	if (!pIn)
	{
		cout << "couldn't open " << searchFile << " input file!!\n";
//		printf("couldn't open %s!", searchFile);
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
	//	item-id
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
		
		fgetAndStrip(itemId);
		fgetAndStrip(start);
		fgetAndStrip(end);
		fgetAndStrip(startPrice);
		fgetAndStrip(currentPrice);
		fgetAndStrip(reservePrice);
		fgetAndStrip(category);
		fgetAndStrip(quantity);
		fgetAndStrip(bidCount);
		fgetAndStrip(flags);
		if(!newRoutines) {
			// for some reason we need to have code here for the test and macro to work
			printf("");
			fgetAndStrip(junk);	// unused long int
		}
		fgetAndStrip(seller);
		fgetAndStrip(highBidder);
		fgetAndStrip(location);
		fgetAndStrip(title);
		if(!newRoutines) {
			// for some reason we need to have code here for the test and macro to work
			printf("");
			fgetAndStrip(junk); // location2
		}
		
		// 1.5 items
		if(newRoutines) {
			fgetAndStrip(featured); 
			fgetAndStrip(superFeatured); 
			fgetAndStrip(boldTitle); 
			fgetAndStrip(privateSale); 
			fgetAndStrip(registeredOnly); 
			fgetAndStrip(pictureURL); 
			fgetAndStrip(adult); 
		}
		
		// get our current pos in the file and show our progress
		fgetpos(pIn, &pos);
		ratio = (double)pos/(double)totalLength;
		printf("progress: %.2f%%  : %s\n", ratio*100, itemId);
		
		// check once more for end of file
		if(totalLength == pos) {
			printf("we're at the end of the file!\n");
			goto leave;
		}
		
			// look for those occasional extra LFs that have snuck their way in
		this->checkForExtraLFs(pIn);
		
		// check to see if we have any adult (or other private) items. we don't want to add those to the index
//		if(privateSale[0] == '0' || adult[0] == '1') {
		if(adult[0] == '0') {
			if(itemId[0] == '\0') {
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
			pColon	= strchr(itemId, ':');
			if (pColon)
				*pColon	= '\0';
			
			// check to see if this item exists in the "current" directory, if it does delete it.
			// this is only used for matching against the completed file search list
			if(checkForCompleted) {
				// if we are using the new routines, we have to check to see if we need
				// to move the file to completed ourselves
				if(newRoutines) {
					if(time(0) > atol(end)) {
						// create the path to the file
						getFileLocation(currentDirName, currentAuctionPathNew, itemId);
						sprintf(tmpFileName, 
							"%s\\%s.html",
							currentDirName,
							itemId);
						
						// get a path to the completed directory
						getFileLocation(completedDirName, completedAuctionPathNew, itemId);
						
						// make it if it doesn't exist
						mkdir(completedDirName);

						// make completed path dirname
						sprintf(tmpNewFileName, 
							"%s\\%s.html",
							completedDirName,
							itemId);
						
						// if we can't rename it (file probably already exists in completed)...
						if(rename(tmpFileName, tmpNewFileName) != 0) {   
							// ...remove it instead
							if(remove(tmpFileName) == 0)
								printf("deleted (could not move to completed): %s\n", itemId);
						} else {
							printf("moved to completed: %s\n", itemId);
						}
					}
				} else {
					// create the path to the file
					sprintf(currentDirName, currentAuctionPath, itemId[0]);		
					sprintf(tmpFileName, 
						"%s\\%s.html",
						currentDirName,
						itemId);
					
					// remove it
					if(remove(tmpFileName) == 0) {
						printf("deleted (moved to completed): %s\n", itemId);
					}
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
				"%m/%d",
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
				"%m/%d",
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
			currentpriced = atof(currentPrice);

			// the new system does not fill in a default currentPrice when new, this can screw up our sort later
			// so we'll copy in the start price if the current price is 0.00
			if(currentpriced == 0.00) {
				strcpy(currentPrice, startPrice);
				currentpriced = atof(currentPrice);
			}
			
			// init defaults & get basic string size
			currentPriceSort[0] = 0;
			pricesize = strlen(currentPrice);
			
			// build up a bunch of 0's in front of the price
			for(i=15;i > pricesize;i--) {
				// prepend 0
				strcat(currentPriceSort, "0");
			}
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
			
			
//		broken
			if(newRoutines) {
			// split the category into n different strings
				categorySplit(category, category1, 0);
				categorySplit(category, category2, 1);
				categorySplit(category, category3, 2);
				categorySplit(category, category4, 3);
			}
			
			//
			// file creation code
			//			
			
			// get file location from file name
			getFileLocation(dirname, whichDir, itemId);
			
			// make it if it doesn't exist
			mkdir(dirname);
			
			// Okay, let's make some HTML in the right directory
			sprintf(HTMLFileName, 
			/*				"E:\\INetPub\\eBay Products\\%s.html",
			itemAndDescriptionId);		sprintf(HTMLFileName, */
			"%s\\%s.html",
			dirname,
			itemId);
			pOut	= fopen(HTMLFileName, "w");
			if (!pOut) {
				printf("couldn't open %s", HTMLFileName);
				goto leave;
			} else {
				
				fputs("<HTML>\n", pOut);
				fprintf(pOut,"<HEAD><TITLE>%s</TITLE></HEAD>\n",
					title);
				fprintf(pOut, "<META NAME=\"item\" CONTENT=\"%s\">\n",
					itemId);
				fprintf(pOut, "<META NAME=\"category\" CONTENT=\"%s\">\n",
					category);
				fprintf(pOut, "<META NAME=\"endtime\" CONTENT=\"%s\">\n",
					cEndTime);
				fprintf(pOut, "<META NAME=\"enddate\" CONTENT=\"%s\">\n",
					cEndDate);
				fprintf(pOut, "<META NAME=\"endsort\" CONTENT=\"%s\">\n",
					end);
				fprintf(pOut, "<META NAME=\"currentprice\" CONTENT=\"%s\">\n",
					currentPrice);
				fprintf(pOut, "<META NAME=\"currentpricesort\" CONTENT=\"%s\">\n",
					currentPriceSort);
				fprintf(pOut, "<META NAME=\"bidcount\" CONTENT=\"%s\">\n",
					bidCount);

// removed for testing fewer properties
/*

				fprintf(pOut, "<META NAME=\"startdate\" CONTENT=\"%s\">\n",
					cStartDate);
				fprintf(pOut, "<META NAME=\"starttime\" CONTENT=\"%s\">\n",
					cStartTime);
				fprintf(pOut, "<META NAME=\"startsort\" CONTENT=\"%s\">\n",
					start);
				fprintf(pOut, "<META NAME=\"startprice\" CONTENT=\"%s\">\n",
					startPrice);
				fprintf(pOut, "<META NAME=\"startpricesort\" CONTENT=\"%s\">\n",
					startPriceSort);
				fprintf(pOut, "<META NAME=\"bidcountsort\" CONTENT=\"%s\">\n",
					bidCountSort);
				fprintf(pOut, "<META NAME=\"seller\" CONTENT=\"%s\">\n",
					seller);
				fprintf(pOut, "<META NAME=\"highbidder\" CONTENT=\"%s\">\n",
					highBidder);
				fprintf(pOut, "<META NAME=\"location\" CONTENT=\"%s\">\n",
					location);
				// 1.5 items
				if(newRoutines) {
					fprintf(pOut, "<META NAME=\"featured\" CONTENT=\"%s\">\n",
						featured);
					fprintf(pOut, "<META NAME=\"superFeatured\" CONTENT=\"%s\">\n",
						superFeatured);
					fprintf(pOut, "<META NAME=\"boldTitle\" CONTENT=\"%s\">\n",
						boldTitle);
					fprintf(pOut, "<META NAME=\"privateSale\" CONTENT=\"%s\">\n",
						privateSale);
					fprintf(pOut, "<META NAME=\"registeredOnly\" CONTENT=\"%s\">\n",
						registeredOnly);
					fprintf(pOut, "<META NAME=\"pictureURL\" CONTENT=\"%s\">\n",
						pictureURL);
					fprintf(pOut, "<META NAME=\"category1\" CONTENT=\"%s\">\n",
						category1);
					fprintf(pOut, "<META NAME=\"category2\" CONTENT=\"%s\">\n",
						category2);
					fprintf(pOut, "<META NAME=\"category3\" CONTENT=\"%s\">\n",
						category3);
					fprintf(pOut, "<META NAME=\"category4\" CONTENT=\"%s\">\n",
						category4);
					}
*/

// for abstract
/*					strcpy(bodyText, "Ending Date/Time: ");
					strcat(bodyText, cEndDate);
					strcat(bodyText, " ");
					strcat(bodyText, cEndTime);
					strcat(bodyText, " Current Price: ");
					strcat(bodyText, currentPrice);
					strcat(bodyText, " Bid count: ");
					strcat(bodyText, bidCount);
					strcat(bodyText, cEndDate);
					strcat(bodyText, cEndTime);
					strcat(bodyText, cEndDate);
					strcat(bodyText, cEndTime);
					strcat(bodyText, cEndDate);
					strcat(bodyText, cEndTime);
					strcat(bodyText, cEndDate);
					strcat(bodyText, cEndTime);
*/	
					
				
				//				fputs("<BODY></BODY>\n", pOut);
				fprintf(pOut,"<BODY>%s</BODY>\n",
					title);		
// for abstract
//				fprintf(pOut,"<BODY>%s</BODY>\n",
//					bodyText);		
			
				fputs("</HTML>", pOut);
				
				fclose(pOut);
			}
		} else {
			// tell the console we're skipping this item because its nasty
			printf("skipping: '%s' because it's in category '%s (a private item)'\n", itemId, category);		
		}
		
		count++;
		
	} while (!quitSoon);
	
leave:
	fclose(pIn);
	
	return;
}


void clsItemsToHTML::moveOrDeleteFile(FILE *stream, _finddata_t *html_file, int howLongAgo, int moveOrDelete, char *parentDir)
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
				if(newRoutines)
					strcat(oldFileName, currentAuctionPathNoCharNew);
				else
					strcat(oldFileName, currentAuctionPathNoChar);
				strcat(oldFileName, parentDir);
				strcat(oldFileName, "\\");
				strcat(oldFileName, html_file->name);
				
				newFileName[0] = 0;
				if(newRoutines)
					strcat(newFileName, completedAuctionPathNoCharNew);
				else
					strcat(newFileName, completedAuctionPathNoChar);
				strcat(newFileName, parentDir);
				
				// make the directory if it doesn't exist
				mkdir(newFileName);
				
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

//
// walks through an entire directory tree. will be used to expire items within that tree
//

void clsItemsToHTML::trudgeThroughDirectories(char *whichDir, int howLongAgo, int moveOrDelete)
{
    struct  _finddata_t file;    
	long	hFile;
	FILE	*stream;
	int		count = 0, i = 0;
//	char	parentDir[6];

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
				trudgeThroughDirectories(file.name, howLongAgo, moveOrDelete);
			} else if( (stream  = fopen(file.name, "r" )) != NULL ) {				
				// do something here ...
				if(moveOrDelete == MOVE_IT || moveOrDelete == DELETE_IT)
					moveOrDeleteFile(stream, &file, howLongAgo, moveOrDelete, whichDir);
				else if(moveOrDelete == DELETE_CATEGORY)
					deleteCategoryFromStore(stream, &file, whichDir);

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
				trudgeThroughDirectories(file.name, howLongAgo, moveOrDelete);
			} else if( (stream  = fopen(file.name, "r" )) != NULL ) {				
				// do something here ...
				if(moveOrDelete == MOVE_IT || moveOrDelete == DELETE_IT)
					moveOrDeleteFile(stream, &file, howLongAgo, moveOrDelete, whichDir);
				else if(moveOrDelete == DELETE_CATEGORY)
					deleteCategoryFromStore(stream, &file, whichDir);

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


void clsItemsToHTML::deleteCategoryFromStore(FILE *stream, _finddata_t *html_file, char *parentDir)
{
	int		pos, numread, i, readSize = 17*1024, result;   
	char	strBuf[17*1024];	// large buffer
	char	*pdest, *pdest2;
	char	str[] = "category4";
	char	quote = '"';
	char	category[16];
	
	numread = fread( &strBuf, sizeof( char ), readSize, stream );
	if(numread == 0) {
		printf( "error reading %s\n", html_file->name);
	} else {					
		// find occurrence of endsort
		pdest = strstr(strBuf,  str);
		
		// point the buffer to the value (past "category4" CONTENT =")
		pdest += 20;
		
		// get the length of the string
		pdest2 = strchr(pdest, quote);
		pos = pdest2 - pdest;
		
		// copy the string and terminate it
		for(i=0;i<pos;i++) {
			category[i] = pdest[i];
		}
		category[pos] = '\0';
		
		// compare endtime of item to (current time - passed in value)
		if(atol(category) == ADULT_GENERAL  || atol(category) == ADULT_VIDEO || atol(category) == ADULT_CD  
			|| atol(category) == ADULT_PHOTOGRAHPIC || atol(category) == ADULT_BOOKS_MAGS) {
			printf("%s is smut. nuke it!\n", html_file->name);
			
			// delete it
			fclose(stream);
			result = remove(html_file->name);
			if( result != 0 ) {
				printf( "Could not remove '%s'\n", html_file->name );   	
			} else { 
				printf( "'%s' was deleted!\n", html_file->name );   	
			}
		} else {
			printf("%s\n", html_file->name);
		}
	}
}

void clsItemsToHTML::expireItems(char *whichDir, int howLongAgo, int moveOrDelete)
{
	trudgeThroughDirectories(whichDir, howLongAgo, moveOrDelete);
}

void clsItemsToHTML::destroyCategory(char *whichDir, int moveOrDelete)
{
	trudgeThroughDirectories(whichDir, 0, moveOrDelete);
}
