/*	$Id: clsTemplate.cpp,v 1.2 1999/02/21 02:24:10 josh Exp $	*/
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
#include "clsRebuildListApp.h"
#include "clsTemplate.h"


typedef struct
{
	char*	pTag;
	int		TagSize;
	Portion	Id;
} eBayTemplateTag;

eBayTemplateTag TemplateTag[] =
{
	{"<eBayHeader>",			12, HEADER},
	{"<eBayTrailer>",			13, TRAILER},
	{"<eBayFocalLink>",			15, FOCAL_LINK},
	{"<eBayCategoryNavigator>", 23, CATEGORY_NAVIGATOR},
	{"<eBayFeaturedItem>",		18, FEATURE_ITEM},
	{"<eBayCategory>",			14, CATEGORY},
	{"<eBayItemList>",			14, ITEM_LIST},
	{"<eBayHotItem>",			13, HOT_ITEM},
	{"<eBayPageLink>",			14, PAGE_LINK},
	{"<eBayTime>",				10, TIME},
	{"<eBaySponsor>",			13, SPONSOR}
};

clsTemplate::clsTemplate (const char* pTemplateFile)
{
	mpTemplateFile = pTemplateFile;

	// initialize the portion order to END_PORTION
	for (mPortionIndex = 0; 
		 mPortionIndex < sizeof(mPortionOrder)/sizeof(Portion); 
		 mPortionIndex++)
	{
		mPortionOrder[mPortionIndex] = END_PORTION;
	}

	mPortionIndex = 0;
}

bool clsTemplate::Parse()
{
	FILE*	pFile;
	char	Line[256];
	int		Index;
	int		NumOfTags;

	// Open the template file
	pFile = fopen(mpTemplateFile, "r");

	if (pFile == NULL)
		return false;

	// Read the lines until eof
	NumOfTags = sizeof(TemplateTag)/sizeof(eBayTemplateTag);
	mPortionIndex = 0;
	while (fgets(Line, sizeof(Line), pFile))
	{
		for (Index = 0; Index < NumOfTags; Index++)
		{
			if (strncmp(Line, TemplateTag[Index].pTag, TemplateTag[Index].TagSize) == 0)
				break;
		}

		if (Index >= NumOfTags)
		{
			fclose(pFile);
			return false;
		}

		switch(TemplateTag[Index].Id)
		{
		case HEADER:
			ParseHeaderLine(Line, TemplateTag[Index].Id);
			break;

		case TRAILER:
			ParseTrailerLine(Line, TemplateTag[Index].Id);
			break;
			
		case FOCAL_LINK:
			ParseFocalLink(Line, TemplateTag[Index].Id);
			break;

		case SPONSOR:
			ParseSponsor(Line, TemplateTag[Index].Id);
			break;

		default:
			ParseDefaultLine(Line, TemplateTag[Index].Id);
		}
	}

	fclose(pFile);

	return true;
}

void clsTemplate::ParseHeaderLine(char* pLine, Portion PagePortion)
{
	char* p;

	// Get the header file name
	p = strchr(pLine, '>');
	memset(mHeaderFileName, 0, sizeof(mHeaderFileName));
	strncpy(mHeaderFileName, p+1, (strrchr(pLine, '<') - p - 1));
	RemoveWhitespace(mHeaderFileName);

	// Insert the page portion in the order
	mPortionOrder[mPortionIndex++] = PagePortion;

}

void clsTemplate::ParseTrailerLine(char* pLine, Portion PagePortion)
{
	char* p;

	// Get the trailer file name
	p = strchr(pLine, '>');
	memset(mTrailerFileName, 0, sizeof(mTrailerFileName));
	strncpy(mTrailerFileName, p+1, (strrchr(pLine, '<') - p - 1));
	RemoveWhitespace(mTrailerFileName);

	// Insert the page portion in the order
	mPortionOrder[mPortionIndex++] = PagePortion;
}

void clsTemplate::ParseFocalLink(char* pLine, Portion PagePortion)
{
	char* p;

	// Get the focal link file name
	p = strchr(pLine, '>');
	memset(mFocalLinkFileName, 0, sizeof(mFocalLinkFileName));
	strncpy(mFocalLinkFileName, p+1, (strrchr(pLine, '<') - p - 1));
	RemoveWhitespace(mFocalLinkFileName);

	// Insert the page portion in the order
	mPortionOrder[mPortionIndex++] = PagePortion;
}

void clsTemplate::ParseSponsor(char* pLine, Portion PagePortion)
{
	char* p;

	// Get the focal link file name
	p = strchr(pLine, '>');
	memset(mSponsorFileName, 0, sizeof(mSponsorFileName));
	strncpy(mSponsorFileName, p+1, (strrchr(pLine, '<') - p - 1));
	RemoveWhitespace(mSponsorFileName);

	// Insert the page portion in the order
	mPortionOrder[mPortionIndex++] = PagePortion;
}


void clsTemplate::ParseDefaultLine(char* pLine, Portion PagePortion)
{
	// Insert the page portion in the order
	mPortionOrder[mPortionIndex++] = PagePortion;
}

void clsTemplate::RemoveWhitespace(char* pLine)
{
	char* pBuffer;

	pBuffer = new char[strlen(pLine) + 1];
	strcpy(pBuffer, pLine);
	int i = 0;
	int j = 0;

	// Remove white spaces
	while(pBuffer[i])
	{
		if (pBuffer[i] != ' ' && pBuffer[i] != '\t')
		{
			pLine[j++] = pBuffer[i];
		}
		i++;
	}
	pLine[j] = '\0';

	delete [] pBuffer;
}

// Get the first portion
Portion clsTemplate::GetFirstPortion()
{
	mPortionIndex = 0;
	return mPortionOrder[0];
}

// Get next protion
void clsTemplate::GetNextPortion(Portion& PagePortion)
{
//	assert(PagePortion == mPortionOrder[mPortionIndex]);

	PagePortion = mPortionOrder[++mPortionIndex];
}

// Check whether a specific portion exists
bool clsTemplate::HasPortion(Portion PagePortion)
{
	int	Index;

	Index = 0;
	while (mPortionOrder[Index] != END_PORTION)
	{
		if (PagePortion == mPortionOrder[Index])
		{
			return true;
		}
		Index++;
	}

	return false;
}

// Get header file name
char* clsTemplate::GetHeaderFileName()
{
	return mHeaderFileName;
}

// Get trailer file name
char* clsTemplate::GetTrailerFileName()
{
	return mTrailerFileName;
}

// Get FocalLink file name
char* clsTemplate::GetFocalLinkFileName()
{
	return mFocalLinkFileName;
}

// Get sponsor file name
char* clsTemplate::GetSponsorFileName()
{
	return mSponsorFileName;
}
