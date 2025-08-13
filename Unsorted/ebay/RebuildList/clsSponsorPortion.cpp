/*	$Id: clsSponsorPortion.cpp,v 1.2 1999/02/21 02:24:05 josh Exp $	*/
//
//	File:	clsSponsorPortion.h
//
//	Class:	clsSponsorPortion
//
//	Author:	Wen Wen
//
//	Function:
//			Replace catid for sponsor
//
// Modifications:
//				- 01/22/98	Wen - Created
//
#include "clsRebuildListApp.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsFileName.h"
#include "clsSponsorPortion.h"

const char pCatTag[] = "<eBayCatId>";

clsSponsorPortion::clsSponsorPortion(clsCategory* pCategory, char* pInputFileName)
{
	mpInputFileName = pInputFileName;
	mpCategory		= pCategory;
}

// It opens the input file and copy the content to the 
// output file
void clsSponsorPortion::Print(ostream* pOutputFile)
{
	FILE*	pIStream;
	char	Buffer[1000];
	char*	p;

	Buffer[999] = 0;

	pIStream = fopen(mpInputFileName, "r");
	while (fgets(Buffer, sizeof(Buffer), pIStream))
	{
		if (p = strstr(Buffer, pCatTag))
		{
			// replace <eBayCatId> CatXX
			*p = 0;
			*pOutputFile << Buffer;
			*pOutputFile << "cat"
						 <<	mpCategory->GetId()
						 << p + strlen(pCatTag);
		}
		else
		{
			*pOutputFile << Buffer;
		}
	}

	fclose(pIStream);
}

