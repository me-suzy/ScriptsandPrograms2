/*	$Id: clsAd.cpp,v 1.1.26.1 1999/08/01 03:02:16 barry Exp $	*/
//
//	File:	clsAd.cpp
//
//  Class:	clsAd
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				a class for ads 
//
// Modifications:
//				- 05/27/99 mila		- Created
//
#include "eBayKernel.h"
#include "clsAd.h"

// set name
//
void clsAd::SetName(const char* pName)
{
	delete [] mpName;
	mpName = NULL;

	if (pName)
	{
		mpName = new char [strlen(pName) + 1];
		strcpy(mpName, pName);
	}
}

//
// set text
//
void clsAd::SetText(const char* pText)
{
	delete [] mpText;
	mpText = NULL;
	mTextLen = 0;

	if (pText)
	{
		mpText = new char [strlen(pText) + 1];
		strcpy(mpText, pText);
		mTextLen = strlen(mpText);
	}
}

// compare function for ad
//
bool ad_comp(clsAd *pAd1, clsAd *pAd2)
{
	return (pAd1->GetId() < pAd2->GetId());
}
