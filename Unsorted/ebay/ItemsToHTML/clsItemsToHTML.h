/*	$Id: clsItemsToHTML.h,v 1.2 1999/02/21 02:22:51 josh Exp $	*/
/*
clsItemsToHTML.h
*/




#ifndef CLSITEMSTOHTML_INCLUDED

#include "clsApp.h"
#ifndef EVENT_REPORTING
// #include "WINDOWS.H"
#endif
#include <time.h>
#include <stdio.h>
#include <DIRECT.h>
#include <io.h>
#include "clsItemsToHTMLCore.h"

class clsItemsToHTML : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsItemsToHTML();
		~clsItemsToHTML();
		
		// parses item info to build HTML files
		void parseItemsForSearch(char *searchFile, char *whichDir, int checkForCompleted);

		// destroys old items
		void expireItems(char *whichDir, int howLongAgo, int moveOrDelete);
		void readMoveOrExpire(FILE *stream, _finddata_t *html_file, int howLongAgo, int moveOrDelete, char *parentDir);

		void getFileLocation(char *dirname, char *whichdir, char *itemId);
		
		void cleanOutCategoryFromIndex(long categoryID);

		int checkForExtraLFs(FILE *file);

		void clsItemsToHTML::categorySplit(char *str, char *dest, int position);
		void moveOrDeleteFile(FILE *stream, _finddata_t *html_file, int howLongAgo, int moveOrDelete, char *parentDir);
		void trudgeThroughDirectories(char *whichDir, int howLongAgo, int moveOrDelete);
		void deleteCategoryFromStore(FILE *stream, _finddata_t *html_file, char *parentDir);
		void destroyCategory(char *whichDir, int moveOrDelete);

		bool newRoutines;
		char auctionsFile[255];
//		char *auctionsFile;
		char *auctionsFilePath;
		char *auctionsFilePathNoChar;

	private:

};

#define CLSITEMSTOHTML_INCLUDED 1
#endif /* CLSITEMSTOHTML_INCLUDED */
