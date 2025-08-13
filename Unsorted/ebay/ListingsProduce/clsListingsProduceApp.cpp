/*	$Id: clsListingsProduceApp.cpp,v 1.5.54.1 1999/06/11 22:57:58 nsacco Exp $	*/
// modifications:
//	06/09/99	nsacco	Rewritten and added .map files for Australia site
//

#include "eBayTypes.h"
#include "clsDatabase.h"

#include "clsListingsProduceApp.h"
#include "clsFillHeader.h"
#include "clsFillTemplates.h"

#include <stdio.h>
#include <iostream.h>
#include <fstream.h>

clsListingsProduceApp::clsListingsProduceApp(unsigned char *pRequest)
{
	return;
}


clsListingsProduceApp::~clsListingsProduceApp()
{
	return;
}

// A simple run function that just creates a clsFillHeader
// object and runs it, and then writes the files.
void clsListingsProduceApp::Run(ofstream * pStream, MapFileTypeEnum * arrayOfMapFileType,
			int numberOfArrayElements)
{
	int i;
    clsFillHeader theHeaders(arrayOfMapFileType, numberOfArrayElements);

    theHeaders.Run();

	for (i = 0; i < numberOfArrayElements; ++i)
	{	try
		{
			theHeaders.WriteBinaryToStream(pStream + i, i);
		}
		catch (...)
		{
			cout << "Could not create map file [" << i << "]!\n"; 
			if (i < numberOfArrayElements - 1)
				cout << "--- continuing for the next map file: [" << i+1 << "]." << endl;
		}
	}

    return;
}

// A simple run function that just creates a clsFillTemplates
// object and runs it, and then writes the file.
void clsListingsProduceApp::RunTemplates(ofstream *pStream)
{
	clsFillTemplates theTemplates;

	theTemplates.Run();

	theTemplates.WriteBinaryToStream(pStream);

	// theTemplates is destroyed by going out of scope.
	return;
}

static clsListingsProduceApp *pTestApp = NULL;

// main
// Open a file for output, then construct and call an
// app class which will construct and call a fill header
// class (clsFillHeaders) to do our work.
int main(int argc, char *argv[])
{
#ifdef _MSC_VER
	g_tlsindex = 0;
#endif
 
	int p;
	const int maxMapFiles = 6;	// CHANGE THIS WHEN ADDING MORE MAP FILES
	int numMapFiles = argc - 1;


	if (!pTestApp)
	{
		pTestApp	= new clsListingsProduceApp(0);
	}

	pTestApp->InitShell();

	// special case for templates
	if ((3 == argc) && !strcmp(argv[1], "-T"))
	{
		ofstream theStream;

		theStream.open(argv[2], ios::out| ios::binary);

		if (!theStream.is_open())
		{
			fprintf(stdout, "Could not open file: %s", argv[2]);
			exit(1);
		}

		pTestApp->RunTemplates(&theStream);
		delete pTestApp;
		return 0;
	}
	else
	{
		// creating map files and not templates

		ofstream theStreamAllItems[maxMapFiles];	// really only needs to be numMapFiles
		MapFileTypeEnum mapFileTypeArray[maxMapFiles];

		// setup the array of map files
		mapFileTypeArray[0] = mapFileAllItems;
		mapFileTypeArray[1] = mapFileUKItems;
		mapFileTypeArray[2] = mapFileOnlyUKItems;
		mapFileTypeArray[3] = mapFileLA;
		mapFileTypeArray[4] = mapFileAUItems;
		mapFileTypeArray[5] = mapFileOnlyAUItems;

		for (p = 0; p < numMapFiles; ++p)
		{
			theStreamAllItems[p].open(argv[p+1], ios::out| ios::binary);

			if (!theStreamAllItems[p].is_open())
			{
				fprintf(stdout, "Could not open file: %s", argv[p+1]);

				delete pTestApp;
				exit(1);
			}
		}

		pTestApp->Run(theStreamAllItems, mapFileTypeArray, numMapFiles);
		delete pTestApp;
		return 0;
	}
}
