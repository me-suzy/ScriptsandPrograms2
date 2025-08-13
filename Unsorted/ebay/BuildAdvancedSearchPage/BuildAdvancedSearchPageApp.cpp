/*	$Id: BuildAdvancedSearchPageApp.cpp,v 1.2 1999/02/21 02:21:15 josh Exp $	*/
//
//	File:		BuildAdvancedSearchPage.cpp
//
//	Author:	pete helme
//
//	Function:
//			main()
//
// Modifications:
//				- 08/08/97	pete - Created
//

#define __MAIN__

#include "eBayDebug.h"
#include "eBayTypes.h"
#include "clsBuildAdvancedSearchPageApp.h"

int main(int argc, char* argv[])
{
	char	TimeString[100];
	int		Index = 1;
	char*	pTestingPath = NULL;

	// use the default one
	strcpy(TEMP_LISTING_FILE_PATH, LISTING_FILE_PATH);

	while (--argc)
	{
		switch (argv[Index][1])
		{
		case 'l':
			strcpy(TEMP_LISTING_FILE_PATH, argv[++Index]);
			Index++;
			argc--;
			break;

		case 't':
			pTestingPath = new char[strlen(argv[++Index])+1];
			strcpy(pTestingPath, argv[Index]);
			Index++;
			argc--;
			break;

		default:
			// wrong syntax
			printf("too many parameters!\n");
			printf("Usage:\n\tBuildAdvancedSearchPage [-l new_listing_path] [-t testing_path]\n");
			return 0;
		}
	}


	clsBuildAdvancedSearchPageApp*	pApp = new clsBuildAdvancedSearchPageApp();

	pApp->InitShell();
	pApp->LogMessage("Rebuild started");

	if (pApp->Run(pTestingPath))
	{
		sprintf(TimeString, "Time used for DB: %f", pApp->GetDBTime());
	
		pApp->LogMessage("Rebuild success");
		pApp->LogMessage(TimeString);
		delete pTestingPath;
		delete pApp;
		return 1;
	}

	pApp->LogMessage("Rebuild failed");

	delete pTestingPath;
	delete pApp;

	return 0;
}
