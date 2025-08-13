/*	$Id: ItemsToTextApp.cpp,v 1.3 1999/02/21 02:22:56 josh Exp $	*/
//
//	File:		ItemsToTextApp.cpp
//
//	Author:	pete helme (pete@ebay.com)
//
//	Function:
//			main()
//
// Modifications:
//				- 07/22/97	pete - Created
//
#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsItemsToTextApp.h"

int main(int argc, char *argv[ ])
{
#ifdef _MSC_VER
	g_tlsindex = 0;
#endif
	int i = 2;
	int hours = 1;
	printf("\nItemsToTextApp ... Starting \n\n");

	clsItemsToTextApp*	pApp = new clsItemsToTextApp;

	// problem
	if(pApp == NULL) 
	{	printf("ItemsToTextApp *** ERROR: pApp is NULL... Exiting *** \n");
		exit(-1);
	}

	// defaults
	pApp->getActive = false;
	pApp->getModified = false;
	pApp->getOutdated = false;
	pApp->getComplete = false;
	pApp->getStarted = false;
	pApp->verbose = false;
	pApp->daysComplete = -1;

	if(argc > 1) {
		while(i <= argc) {
			if(strcmp(argv[i-1], "-a") == 0) {
				pApp->getActive = true;
			} else if(strcmp(argv[i-1], "-m") == 0) {
				pApp->getModified  = true;
				if (argc > i) 
				{ 
					hours = atoi(argv[i]);
					i++;
				}
			} else if(strcmp(argv[i-1], "-s") == 0) {
				pApp->getStarted  = true;
				if (argc > i) 
				{ 
					hours = atoi(argv[i]);
					i++;
				}
			} else if(strcmp(argv[i-1], "-d") == 0) {
				pApp->getOutdated = true;
			} else if(strcmp(argv[i-1], "-c") == 0) {
				pApp->getComplete = true;
			} else if(strcmp(argv[i-1], "-C") == 0) {
				pApp->getComplete = true;
				if (i == argc) 
				{
					printf("\nItemsToTextApp *** ERROR: Must specify number of days... Exiting *** \n");
					exit(1);
				}
				pApp->daysComplete = atoi(argv[i]);
				i++;
			} else if(strcmp(argv[i-1], "-v") == 0) {
				pApp->verbose = true;
			} else {
				printf("ItemsToTextApp Copyright 1997 eBay Inc.\n\n");
				printf("valid arguments are:\n");
				printf(" -a	- get active items\n");
				printf(" -m	- get modified items\n");
				printf(" -s	- get started items\n");
				printf(" -d	- get outdated items\n");
				printf(" -c     - get newly completed items\n");
				printf(" -C day - get completed items (n days back)\n");

				printf(" -v	- verbose\n");
				printf("\nItemsToTextApp ... Exiting \n");

				exit(1);
				break;
			}

			i++;
		}
	} else {
		printf("ItemsToTextApp Copyright 1997 eBay Inc.\n\n");
		printf("valid arguments are:\n");
		printf(" -a	- get active items\n");
		printf(" -m	- get modified items\n");
		printf(" -s	- get started items\n");
		printf(" -d	- get outdated items\n");
		printf(" -c     - get newly completed items\n");
		printf(" -C day - get completed items (n days back)\n");

		printf(" -v	- verbose\n");

		printf("\nItemsToTextApp ... Exiting \n");
		exit(1);
	}

	pApp->InitShell();
	pApp->Run(hours);

	printf("\nItemsToTextApp ... Ending \n");

	// delete pApp;

	return 0;
}
