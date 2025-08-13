/*	$Id: clsHTMLPortion.cpp,v 1.2 1999/02/21 02:23:56 josh Exp $	*/
//
//	File:	clsHTMLPortion.h
//
//	Class:	clsHTMLPortion
//
//	Author:	Wen Wen
//
//	Function:
//			Copy the content of the input file to the output file
//
// Modifications:
//				- 07/07/97	Wen - Created
//
#include "clsRebuildListApp.h"
#include "clsCategories.h"
#include "clsCategory.h"
#include "clsFileName.h"
#include "clsHTMLPortion.h"

clsHTMLPortion::clsHTMLPortion(char* pInputFileName)
{
	mpInputFileName = pInputFileName;
}

// It opens the input file and copy the content to the 
// output file
void clsHTMLPortion::Print(ostream* pOutputFile)
{
	FILE*	pIStream;
	char	Buffer[1000];
	size_t	SizeRead;

	pIStream = fopen(mpInputFileName, "r");
	while ((SizeRead = fread(Buffer, sizeof(char), sizeof(Buffer), pIStream)) == sizeof(Buffer))
	{
		*pOutputFile << Buffer;
	}
	Buffer[SizeRead] = '\0';
	*pOutputFile << Buffer;

	fclose(pIStream);
}

