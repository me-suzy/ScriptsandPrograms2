/*	$Id: clsAd.h,v 1.1.26.1 1999/08/01 03:02:03 barry Exp $	*/
//
//	File:	clsAd.h
//
//  Class:	clsAd
//
//	Author:	Mila Bird (mila@ebay.com)
//
//	Function:
//
//				a class to hold ad information 
//
// Modifications:
//				- 05/27/99 mila	- Created
//

#ifndef CLSAD_INCLUDED

class clsAd
{
public:
	clsAd() : 
		mId(0),
		mpName(NULL),
		mpText(NULL),
		mTextLen(0)
	{
	}

	clsAd(const char *pName,
		  const char* pText) :
		mId(0),
		mpName(NULL),
		mpText(NULL)
	{
		SetName(pName);
		SetText(pText);
	}

	clsAd(int id, 
		  const char *pName,
		  const char* pText) :
		mId(id),
		mpName(NULL),
		mpText(NULL)
	{
		SetName(pName);
		SetText(pText);
	}

	virtual ~clsAd()
	{
		delete [] mpName;
		delete [] mpText;
	}

	int				GetId()	{ return mId; }
	const char *	GetName() { return mpName; }
	const char *	GetText() { return mpText; }
	int				GetTextLen()	{ return mTextLen; }

	void			SetId(int id) { mId = id; }
	void			SetName(const char* pName);
	void			SetText(const char* pText);
	
protected:
	int				mId;
	char *			mpName;
	char *			mpText;
	int				mTextLen;
};

typedef vector<clsAd *> AdVector;

// comparing ad
bool ad_comp(clsAd *pAd1, clsAd *pAd2);

#define CLSAD_INCLUDED
#endif /* CLSAD_INCLUDED */
