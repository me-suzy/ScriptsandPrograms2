/*	$Id: spammer.cpp,v 1.2 1998/06/23 04:31:49 josh Exp $	*/
//
//	File:		spammer.cpp.
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
#include "clsSpammer.h"

int main(int argc, char *argv[ ])
{	
	int i = 2;

	clsSpammer*	pApp = new clsSpammer();

	// problem
	if(pApp == NULL) {
		exit(-1);
	}
	
	// defaults
	pApp->getHot = true; // always true now
	pApp->getNew = true; // always true now
	pApp->getCompleted = false;
	pApp->verbose = false;
	pApp->yahoo = false;

	if(argc > 1) {
		while(i <= argc) {
			if(strcmp(argv[i-1], "-hot") == 0) {
				pApp->getHot = true;
			} else if(strcmp(argv[i-1], "-new") == 0) {
				pApp->getNew  = true;
			} else if(strcmp(argv[i-1], "-c") == 0) {
				pApp->getCompleted = true;
			} else if(strcmp(argv[i-1], "-v") == 0) {
				pApp->verbose = true;
			} else if(strcmp(argv[i-1], "-y") == 0) {
				pApp->yahoo = true;
			} else {
				printf("Spammer Copyright 1997 eBay Inc.\n\n");
				printf("valid arguments are:\n");
				printf(" -hot	- get hot items\n");
				printf(" -new	- get new items\n");	
				printf(" -y	- use yahoo template\n");	

				printf(" -v	- verbose\n");	
				
				exit(1);			
				break;
			}
			
			i++;
		}
	} else {
		printf("Spammer Copyright 1997 eBay Inc.\n\n");
		printf("valid arguments are:\n");
		printf(" -hot	- get hot items\n");
		printf(" -new	- get new items\n");	
		printf(" -y	- use yahoo template\n");	

		printf(" -v	- verbose\n");	
		
		exit(1);			
	}
	
	pApp->InitShell();
	pApp->Run();
	
	return 0;
}


