/*	$Id: clsItemsToHTMLApp.h,v 1.2 1999/02/21 02:22:53 josh Exp $	*/
//
//	File:	clsItemsToHTMLApp.h
//
//	Class:	clsItemsToHTMLApp
//
//	Author:	pete helme (pete@ebay.com)
//
//	Function:
//
//
// Modifications:
//
//
#ifndef CLSITEMSTOHTMLAPP_INCLUDED

#include "clsApp.h"
#ifndef EVENT_REPORTING
// #include "WINDOWS.H"
#endif
#include <time.h>
#include <stdio.h>
#include <DIRECT.h>
#include <io.h>
#include "clsItemsToHTMLCore.h"
#include "clsItemsToHTML.h"

class clsItemsToHTMLApp : public clsApp
{
	public:
		
		// Constructor, Destructor
		clsItemsToHTMLApp();
		~clsItemsToHTMLApp();
		
		// Runner
		void Run();

		// parses item info to build HTML files
		void parseItemsForSearch(char *searchFile, char *whichDir, int checkForCompleted);

		// destroys old items
		void expireItems(char *whichDir, int howLongAgo, int moveOrDelete);
		void clsItemsToHTMLApp::readMoveOrExpire(FILE *stream, _finddata_t *html_file, int howLongAgo, int moveOrDelete, char *parentDir);

		void cleanOutCategoryFromIndex(long categoryID);

		int clsItemsToHTMLApp::checkForExtraLFs(FILE *file);

		void clsItemsToHTMLApp::trudgeThroughDirectories(char *whichDir);

//		clsItemsToHTML	*pItems;
		
		bool newRoutines;
		bool parseInc;
		bool parseIncCurr;
		bool parseIncComp;
		bool parseAll;
		bool parseAllCurr;
		bool parseAllComp;
		bool expire;
		bool destroySmut;

		// drive name
		char disk[12];

void clsItemsToHTMLApp::strstrtest(void);

// void clsItemsToHTMLApp::LogEvent(char* pMsg);

	private:

};

#define CLSITEMSTOHTMLAPP_INCLUDED 1
#endif /* CLSITEMSTOHTMLAPP_INCLUDED */
