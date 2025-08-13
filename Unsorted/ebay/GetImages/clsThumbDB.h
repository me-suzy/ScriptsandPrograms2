/* $Id: clsThumbDB.h,v 1.2 1999/02/21 02:22:26 josh Exp $ */
//
// File: ThumbDB.h
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: This a database for thumbnail images.
//

#ifndef _THUMBDB_H
#define _THUMBDB_H

/*
#ifdef _MSC_VER
#undef min
#undef max
#endif

#include <vector.h>
*/

#include <stdio.h>

class clsMappedFile;


class clsThumbDB {
public:
	// Fixed-length types
	typedef long	int32_t;
	typedef unsigned long	uint32_t;
	typedef short	int16_t;
	typedef char	int8_t;

	clsThumbDB(const char* lpFileName, bool readOnly = true);
		// Read only constructor

	clsThumbDB(const char* lpFileName, int startIndex, int endIndex);
		// Write only constructor

	~clsThumbDB();

	int GetStartIndex() { return mpHeader->startIndex; }
	int GetEndIndex() { return mpHeader->endIndex; }

	// Methods for write only mode
	int AddUniqueThumb(const char* imageData, long dataSize, int itemID);
	void ClearUniqueThumb(int itemID);

	// Methods for read only mode
	const char* GetThumb(int itemID, int& size);

	unsigned long GetStartID() { return mStartImageID; }


private:

	// The following structure fields are all assumed to
	// be packed, with no padding inbetween.  They are also
	// assumed to have been written in the byte order of
	// the _target_ host, not of the generating host.

	struct Header {
		enum { kVersionLength = 64};

		char version[kVersionLength];	//	64 bytes
		int32_t indexOffset;			//	 4 bytes
		int32_t startIndex;				//	 4 bytes
		int32_t endIndex;				//	 4 bytes
	};

	

#ifdef _MSC_VER
#pragma warning(disable : 4200) // nonstandard extension used : zero-sized array in struct/union
#endif
	// This is normally stuck at the end of the file
	struct IndexHeader {
		int32_t imageMap[0];			//	 4 bytes - maps an index to an image header; 
										//      entry is an offset from Header.imagesOffset
	};
#ifdef _MSC_VER
#pragma warning(default : 4200) // turn it back on
#endif

#ifdef _MSC_VER
#pragma warning(disable : 4200) // nonstandard extension used : zero-sized array in struct/union
#endif
	// This is normally starts right after the header
	struct ImageHeader {
		int32_t imageSize;	
		char image[0];	
	};
#ifdef _MSC_VER
#pragma warning(default : 4200) // turn it back on
#endif

	Header* mpHeader;
	IndexHeader* mpIndexHeader;
	clsMappedFile* mpMap;
	FILE* mpMapFile;
	bool mReadOnly;
	bool mGoodDB;
	int mIndexBytes;
	int mStartImageID;
	int mEndImageID;

	static const char* mCurrentVersion;

	int WriteImage(const char* imageData, long dataSize, long& locationWrittenTo);
	ImageHeader* GetImageHeader(int itemID);
	void FixByteOrdering();
	int ItemIDToIndex(int itemID);
	void OpenForAppending(const char* lpFileName);
	void OpenForReading(const char* lpFileName);
	void CreateForWriting(const char* lpFileName, int startIndex, int endIndex);

};


class clsGroupThumbDB {
public:
	enum { kThumbIDQuantum = 65536, kMaxNoisyThumbs = 100000 };
	
	clsGroupThumbDB(const char* directory, bool readOnly, const char* missingImage);
	~clsGroupThumbDB();

	void Close();
	void Open();

	// Methods for write only mode
	void AddThumb(const char* imageData, long dataSize, unsigned long itemID);
	void ClearThumb(int itemID);

	// Methods for read only mode
	const char* GetThumb(unsigned long itemID, int& size);
	void DumpDB(const char* where);

	int PurgeOld(int maxIDs);
	int MakeNoisy(int item);
	int MakeUnNoisy(int item);
	int NoisyThumbCount();
	
private:
	int QuantizeThumbID(unsigned long id) { return id / kThumbIDQuantum; }
	char mThumbDBDirectory[1024];
	bool mReadOnly;
	bool mOpen;
	clsThumbDB* mThumbDBs[kThumbIDQuantum];
	long mMissingImageSize;
	char* mMissingImage;

//	vector<int> mNoisyThumbs;

	long mNoisyThumbs[kMaxNoisyThumbs];
	int mNoisyThumbCount; // last count of total noisy pictures
};


#endif _THUMBDB_H
