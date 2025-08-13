/*	$Id: clsFocalLink.cpp,v 1.2 1999/02/21 02:23:52 josh Exp $	*/
//
//	File:	clsFocalLink.cpp
//
//	Class:	clsFocalLink
//
//	Author:	Wen Wen
//
//	Function:
//		Process Link exchange
//
// Modifications:
//				- 09/02/97	Wen - Created
//
#include "clsRebuildListApp.h"
#include "clsFocalLink.h"
#include <stdio.h>
#include <stdlib.h>

clsFocalLink::clsFocalLink(char* pInputFileName)
{
	mpInputFileName = pInputFileName;
}

// It opens the input file and copy the content to the 
// output file
void clsFocalLink::Print(ostream* pOutputFile)
{
	FILE*	pIStream;
	char	Buffer[1000];
	char*	pTemp;
	char	theDir[3];

	// Prepare the link exchange directory
#ifdef _MSC_VER
	sprintf(theDir, "%d", rand() % 99 + 1);
#else
	sprintf(theDir, "%d", random() % 99 + 1);
#endif

	pIStream = fopen(mpInputFileName, "r");
	while ( fgets(Buffer, sizeof(Buffer), pIStream) )
	{
		if (pTemp = strstr(Buffer, "<eBayLinkExchangeDir>"))
		{
			// Output the first part
			*pTemp = 0;
			*pOutputFile << Buffer;

			// Replace eBayLinkExchangeDir> with random nuber between 1-99
			*pOutputFile << theDir;

			// the last part
			*pOutputFile << pTemp + strlen("<eBayLinkExchangeDir>");
		}
		else
		{
			*pOutputFile << Buffer;
		}
	}

	fclose(pIStream);
}

