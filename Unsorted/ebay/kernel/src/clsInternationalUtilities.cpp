/*	$Id: clsInternationalUtilities.cpp	*/
//
//	File:	clsInternationalUtilities.cpp
//
//	Class:	clsInternationalUtilities
//
//	Author:	Barry Boone
//
//	Function:
//
//			Utilities to help with internationalization.
//
//	Modifications:
//				  mm/dd/yy
//				- 11/23/98 barry	- Created
//				- 06/10/99 nsacco	- Fixed FormatPhone()

#include "eBayKernel.h"

//
// Default Constructor
//
clsInternationalUtilities::clsInternationalUtilities()
{
	mCurrentCountry = 0;
}

//
// Destructor
//
clsInternationalUtilities::~clsInternationalUtilities()
{
}

// Some getters and setters. 

void clsInternationalUtilities::SetCurrentCountry(int id) 
{ 
	mCurrentCountry = id; 
}

// char value allocated here. Free it in the caller.
char *clsInternationalUtilities::FormatPhone(const char *part1, const char *part2, const char *part3, const char *part4)
{
	char *pFormattedPhone;
	int   len;

	bool  extension = ( part4 && (strcmp(part4, "default") != 0) );

	if (part1 == NULL || strlen(part1) == 0)
		return NULL;

	switch (mCurrentCountry)
	{
	case Country_None:
	case Country_US:
	case Country_CA: // canada
		if (part1 == NULL || part2 == NULL || part3 == NULL)
			return NULL;
		len = strlen(part1) + strlen(part2) + strlen(part3) + 6;
			// len = all parts plus () - and 3 spaces.

		if (extension)
			len += strlen(part4) + 6;
			// len += part4 + ext. and 2 spaces

		len += 1;

		pFormattedPhone = new char[len + 1];

		if (extension)
			sprintf(pFormattedPhone, "%c%s%c %s %c %s ext. %s",
				'(', part1, ')', part2, '-', part3, part4);
		else
			sprintf(pFormattedPhone, "%c%s%c %s %c %s",
				'(', part1, ')', part2, '-', part3);

		break;
	
	case Country_AU:	// Australia
		if (part1 == NULL || part2 == NULL || part3 == NULL)
			return NULL;
		len = strlen("(") + strlen(part1) + strlen(") ") + strlen(part2) +
				strlen(" ") + strlen(part3);
			// len = all parts plus () - and 2 spaces.

		if (extension)
			len += strlen(" ext. ") + strlen(part4);
			// len += part4 + ext. and 2 spaces

		len += 1;

		pFormattedPhone = new char[len + 1];

		if (extension)
			sprintf(pFormattedPhone, "%c%s%c %s %s ext. %s",
				'(', part1, ')', part2, part3, part4);
		else
			sprintf(pFormattedPhone, "%c%s%c %s %s",
				'(', part1, ')', part2, part3);

		break;
	case Country_UK:	// uk
	case Country_DE:	// Germany		// PH 05/04/99

		if (part1 == NULL || part2 == NULL)
			return NULL;
		len = strlen(part1) + strlen("-") + strlen(part2);
			// len = all parts plus a dash.

		if (extension)
			len += strlen(" ext. ") + strlen(part4);
			// len += part4 + ext. and 2 spaces

		pFormattedPhone = new char[len + 1];

		if (extension)
			sprintf(pFormattedPhone, "%s%c%s ext. %s",
				 part1, '-', part2, part4);
		else
			sprintf(pFormattedPhone, "%s%c%s",
				part1, '-', part2);

		break;

	default: // other
		len = strlen(part1);
		pFormattedPhone = new char[len + 1];
		sprintf(pFormattedPhone, "%s", part1);
	}

	return pFormattedPhone;

}
