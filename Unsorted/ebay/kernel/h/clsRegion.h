/*	$Id: clsRegion.h,v 1.2 1999/04/18 01:59:11 wwen Exp $	*/
//
//	File:	clsRegion.h
//
//	Class:	clsRegion
//
//	Author:	Wen Wen (wwen@ebay.com)
//
//	Function:
//
//				Represents region
//
// Modifications:
//				- 04/14/99 wen	- Created

#ifndef CLSREGION_INCLUDE
#define CLSREGION_INCLUDE

class clsRegion
{
public:

	// constructor
	clsRegion(int RegionID, const char* pName);
	clsRegion();

	// destructor
	~clsRegion();

	// sets and gets
	void SetID(int RegionID) { mID = RegionID; }
	void SetName(const char* pName);

	// Add a zip code to the region. pZip is released by clsRegion
	void AddZip(char* pZip);

	int  GetID() { return mID; }
	const char* GetName() {return mpName; }

	// testing whether pZip belongs to the region
	bool IsInRegion(const char* pZip);
	
protected:
	int		mID;
	char*	mpName;
	bool	mSorted;
	vector<char*>	mZips;

};

#endif // CLSREGION_INCLUDE
