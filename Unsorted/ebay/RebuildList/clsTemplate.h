/*	$Id: clsTemplate.h,v 1.2 1999/02/21 02:24:11 josh Exp $	*/
//
//	File:	clsTemplate.h
//
//	Class:	clsTemplate
//
//	Author:	Wen Wen
//
//	Function:
//			Parsing the template
//
// Modifications:
//				- 07/07/97	Wen - Created
//
#ifndef CLSTEMPLATE_INCLUDED
#define CLSTEMPLATE_INCLUDED

#include <stdlib.h>


class clsTemplate
{
public:
	clsTemplate (const char* pTemplateFile);

	// Get the first portion
	Portion GetFirstPortion();

	// Get next protion
	void GetNextPortion(Portion& PagePortion);

	// Check whether a specific portion exists
	bool HasPortion(Portion PagePortion);

	// Get header file name
	char*	GetHeaderFileName();

	// Get trailer file name
	char*	GetTrailerFileName();

	// Get FocalLink file name
	char*	GetFocalLinkFileName();

	// Get Sponsor file name
	char*	GetSponsorFileName();

	// Parse
	bool	Parse();

private:
	void ParseHeaderLine(char* pLine, Portion PagePortion);
	void ParseTrailerLine(char* pLine, Portion PagePortion);
	void ParseFocalLink(char* pLine, Portion PagePortion);
	void ParseSponsor(char* pLine, Portion PagePortion);
	void ParseDefaultLine(char* pLine, Portion PagePortion);
	void RemoveWhitespace(char* pLine);

	// template file name
	const char*	mpTemplateFile;

	// header file name
	char	mHeaderFileName[_MAX_PATH];

	// trailer file name
	char	mTrailerFileName[_MAX_PATH];

	// focal-link file name
	char	mFocalLinkFileName[_MAX_PATH];

	// sponsor file name
	char	mSponsorFileName[_MAX_PATH];

	// keep order of the portions
	Portion	mPortionOrder[20];

	// cursor to the current portion
	int		mPortionIndex;
};

#endif // CLSTEMPLATE_INCLUDED
