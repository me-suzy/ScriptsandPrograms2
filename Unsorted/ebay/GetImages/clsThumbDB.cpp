/* $Id: clsThumbDB.cpp,v 1.2 1999/02/21 02:22:25 josh Exp $ */
//
// File: clsThumbDB
//
// Author: Martin Hess (marty@ebay.com)
//
// Description: See clsThumbDB.h
//

#include "clsThumbDB.h"
#include "ByteOrdering.h"
#include "clsMappedFile.h"
#include <stdexcept>
#include <iostream>
#include "clsDirectoryWalker.h"
//#include <vector.h>
//#include <algo.h>

const char* clsThumbDB::mCurrentVersion = "eBay: Thumb Image DB - Version 1";

inline int CalcIndexBytes(int startIndex, int endIndex)
{
	return ((endIndex - startIndex) + 1) * sizeof(clsThumbDB::int32_t);
}

void clsThumbDB::OpenForAppending(const char* lpFileName)
{
	mReadOnly = false;
	mpHeader = NULL;
	mpIndexHeader = NULL;
	mpMap = 0;
	mpMapFile = 0;
	mGoodDB = false;
	mIndexBytes = 0;
	mStartImageID = 0;
	mEndImageID = 0;

	mpMapFile = fopen(lpFileName, "r+b");
	if (!mpMapFile)
		throw errno;

	try {
		mpHeader = new Header;
		memset(mpHeader, 0, sizeof(Header));

		// Read in the database header
		int amountRead = fread(mpHeader, sizeof(Header), 1, mpMapFile);
		if (amountRead != 1)
			throw errno;

		// How big is the index
		mIndexBytes = CalcIndexBytes(mpHeader->startIndex, mpHeader->endIndex);

		// Create a buffer to hold the index
		mpIndexHeader = reinterpret_cast<IndexHeader*>( new char[mIndexBytes] );
		memset(mpIndexHeader, 0, mIndexBytes);

		// Position to read the indes
		int seekResult = fseek(mpMapFile, mpHeader->indexOffset, SEEK_SET);
		if (seekResult)
			throw errno;

		// Read the index
		amountRead = fread(mpIndexHeader, mIndexBytes, 1, mpMapFile);
		if (amountRead != 1)
			throw errno;

		// Position to append new thumbs
		seekResult = fseek(mpMapFile, mpHeader->indexOffset, SEEK_SET);
		if (seekResult)
			throw errno;

		mStartImageID = mpHeader->startIndex;
		mEndImageID = mpHeader->endIndex;

		mGoodDB = true;
	}
	catch(int caughtError)
	{
		fclose(mpMapFile);

		throw caughtError;
	}
}

void clsThumbDB::OpenForReading(const char* lpFileName)
{
	mReadOnly = true;
	mpHeader = 0;
	mpIndexHeader = 0;
	mpMap = 0;
	mpMapFile = 0;
	mGoodDB = false;
	mIndexBytes = 0;
	mStartImageID = 0;
	mEndImageID = 0;

	mpMap = new clsMappedFile(lpFileName);

	mpHeader = reinterpret_cast<Header*>(mpMap->GetBaseAddress());
	if (strcmp(mCurrentVersion, mpHeader->version))
		throw -1;

	mpIndexHeader = reinterpret_cast<IndexHeader*>(mpHeader->indexOffset + (char*) mpMap->GetBaseAddress());

	mStartImageID = mpHeader->startIndex;
	mEndImageID = mpHeader->endIndex;

	mGoodDB = true;
}

void clsThumbDB::CreateForWriting(const char* lpFileName, int startIndex, int endIndex) 
{
	mReadOnly = false;
	mpHeader = 0;
	mpIndexHeader = 0;
	mpMap = 0;
	mpMapFile = 0;
	mGoodDB = false;
	mIndexBytes = 0;
	mStartImageID = startIndex;
	mEndImageID = endIndex;

	if (startIndex < 0 ||
		endIndex < 0 ||
		startIndex > endIndex)
		throw -1;

	mpMapFile = fopen(lpFileName, "wb");

	if (!mpMapFile)
		throw errno;

	try {
		mpHeader = new Header;
		memset(mpHeader, 0, sizeof(Header));

		// mlh FIXME this looks bogus
		//DebugBreak();
		int written = fwrite(&mpHeader, sizeof(Header), 1, mpMapFile);
		if (written != 1)
			throw errno;

		mIndexBytes = CalcIndexBytes(mStartImageID, mEndImageID);

		mpIndexHeader = reinterpret_cast<IndexHeader*>( new char[mIndexBytes] );
		memset(mpIndexHeader, 0, mIndexBytes);

		mGoodDB = true;
	}
	catch(int caughtError)
	{
		fclose(mpMapFile);

		throw caughtError;
	}
}


clsThumbDB::clsThumbDB(const char* lpFileName, bool readOnly)
{
	if (readOnly)
	{
		OpenForReading(lpFileName);
	}
	else
	{
		OpenForAppending(lpFileName);
	}
}

clsThumbDB::clsThumbDB(const char* lpFileName, int startIndex, int endIndex)
{
	CreateForWriting(lpFileName, startIndex, endIndex);
}

clsThumbDB::~clsThumbDB()
{
	if (!mReadOnly)
	{
		if (mGoodDB)
		{
			try
			{
				int fileIOResult;

				if (!mpMapFile)
					throw -1;

				long currentPos = ftell(mpMapFile);
				if (currentPos == -1L)
					throw -1;

				// Prepare header for writing
				strcpy(mpHeader->version, mCurrentVersion);
				mpHeader->indexOffset = currentPos;
				mpHeader->startIndex = mStartImageID;
				mpHeader->endIndex = mEndImageID;

				FixByteOrdering();

				// Write the index
				fileIOResult = fwrite(mpIndexHeader, mIndexBytes, 1, mpMapFile);
				if (fileIOResult != 1)
					throw -1;

				// Write the header
				fileIOResult = fseek(mpMapFile, 0, SEEK_SET);
				if (fileIOResult)
					throw -1;

				fileIOResult = fwrite(mpHeader, sizeof(Header), 1, mpMapFile);
				if (fileIOResult != 1)
					throw -1;
			}
			catch(...)
			{
			}

			delete mpHeader;
			delete mpIndexHeader;
		}

		if (mpMapFile)
			fclose(mpMapFile);
	}
	else
	{
		delete mpMap;
	}
}

int clsThumbDB::AddUniqueThumb(const char* imageData, long dataSize, int itemID)
{
	if (mReadOnly)
		return -1;

	if (!mGoodDB)
		return -1;

	int itemIndex = ItemIDToIndex(itemID);
	if (itemIndex < 0)
		return 0;

	// We are going to let users overwrite a good image
	// This causes a memory leak in the db but we don't care
	// because the db will be deleted in 8 or 9 days anyway
//	if (mpIndexHeader->imageMap[itemIndex] != 0)
//		return -1; // item already has an image

	long locationWrittenTo;

	if (WriteImage(imageData, dataSize, locationWrittenTo))
		return -1;

	mpIndexHeader->imageMap[itemIndex] = locationWrittenTo;

	return 0;
}

void clsThumbDB::ClearUniqueThumb(int itemID)
{
	int itemIndex = ItemIDToIndex(itemID);
	if (itemIndex < 0)
		return;

	mpIndexHeader->imageMap[itemIndex] = 0;
}

int clsThumbDB::WriteImage(const char* imageData, long dataSize, long& locationWrittenTo)
{
	locationWrittenTo = ftell(mpMapFile);
	if (locationWrittenTo == -1L)
	{
		mGoodDB = false;
		return -1;
	}

	ImageHeader header;

	header.imageSize = dataSize;

	FIX_BYTE_ORDER32(header.imageSize);

	if (!mpMapFile)
	{
		mGoodDB = false;
		return -1;
	}

	int written = fwrite(&header, sizeof(ImageHeader), 1, mpMapFile);
	if (written != 1)
	{
		mGoodDB = false;
		return -1;
	}

	written = fwrite(imageData, dataSize, 1, mpMapFile);
	if (written != 1)
	{
		mGoodDB = false;
		return -1;
	}

	return 0;
}

const char* clsThumbDB::GetThumb(int itemID, int& size)
{
	if (!mReadOnly)
		return 0;

	if (!mGoodDB)
		return 0;

	ImageHeader* imageHeader = GetImageHeader(itemID);

	if (!imageHeader)
		return 0;

	size = imageHeader->imageSize;

	return &imageHeader->image[0];
}

clsThumbDB::ImageHeader* clsThumbDB::GetImageHeader(int itemID)
{
	int itemIndex = ItemIDToIndex(itemID);
	if (itemIndex < 0)
		return 0;

	if (!mpIndexHeader->imageMap[itemIndex])
		return 0;

	// Images are offset from Header
	return reinterpret_cast<ImageHeader*>(
		((char*)mpHeader) + mpIndexHeader->imageMap[itemIndex]  ) ;
}

void clsThumbDB::FixByteOrdering()
{
	// Fix byte ordering before writing
	FIX_BYTE_ORDER32(mpHeader->indexOffset);
	FIX_BYTE_ORDER32(mpHeader->startIndex);
	FIX_BYTE_ORDER32(mpHeader->endIndex);

	int maxItems = (mEndImageID - mStartImageID) + 1;

	for (int i = 0; i < maxItems; ++i)
	{
		FIX_BYTE_ORDER32(mpIndexHeader->imageMap[i]);
	}

}

int clsThumbDB::ItemIDToIndex(int itemID)
{
	if (itemID < mStartImageID || itemID > mEndImageID)
		return -1;

	return itemID - mStartImageID;
}

clsGroupThumbDB::clsGroupThumbDB(const char* directory, bool readOnly, const char* missingImage) :
	mReadOnly(readOnly),
	mOpen(false)
{
	strcpy(mThumbDBDirectory, directory);
	memset(mThumbDBs, 0, sizeof(mThumbDBs));

	FILE* missingImageFile = fopen(missingImage, "r+b");
	if (!missingImageFile)
		throw std::runtime_error("clsGroupThumbDB::clsGroupThumbDB: can't open missing image file");
		
	int seekResult = fseek(missingImageFile, 0, SEEK_END);
	if (seekResult)
	{
		fclose(missingImageFile);
		throw std::runtime_error("clsGroupThumbDB::clsGroupThumbDB: can't seek_end missing image file");
	}

	mMissingImageSize = ftell(missingImageFile);
	if (mMissingImageSize == -1)
	{
		fclose(missingImageFile);
		throw std::runtime_error("clsGroupThumbDB::clsGroupThumbDB: can't tell missing image file");
	}

	seekResult = fseek(missingImageFile, 0, SEEK_SET);
	if (seekResult)
	{
		fclose(missingImageFile);
		throw std::runtime_error("clsGroupThumbDB::clsGroupThumbDB: can't seek_set missing image file");
	}

	mMissingImage = new char[mMissingImageSize];

	int readResult = fread(mMissingImage, mMissingImageSize, 1, missingImageFile);
	if (readResult != 1)
	{
		fclose(missingImageFile);
		delete [] mMissingImage;
		throw std::runtime_error("clsGroupThumbDB::clsGroupThumbDB: can't read missing image file");
	}

	fclose(missingImageFile);

	// initialize noisy array
	mNoisyThumbs[0] = -1;
	mNoisyThumbCount = 0;
}

clsGroupThumbDB::~clsGroupThumbDB()
{
	Close();
}

void clsGroupThumbDB::Close()
{
	for (int i = 0; i < kThumbIDQuantum; ++i)
	{
		if (mThumbDBs[i])
			delete mThumbDBs[i];
	}

	mOpen = false;
}

void clsGroupThumbDB::Open()
{
	if (mOpen)
		throw std::runtime_error("clsGroupThumbDB::Open: db already open");

	clsDirectoryWalker directoryWalker(mThumbDBDirectory, "*.map");

	while (directoryWalker.GetNextItem())
	{
		char thumbDBFileName[1024];
		strcpy(thumbDBFileName, mThumbDBDirectory);
		strcat(thumbDBFileName, directoryWalker.GetName());

		clsThumbDB* newThumbDB = new clsThumbDB(thumbDBFileName, mReadOnly);
		unsigned long startID = newThumbDB->GetStartID();

		mThumbDBs[QuantizeThumbID(startID)] = newThumbDB;
	}

	mOpen = true;
}

void clsGroupThumbDB::AddThumb(const char* imageData, long dataSize, unsigned long itemID)
{
	if (!mOpen)
		throw std::runtime_error("clsGroupThumbDB::AddThumb: db not open");

	unsigned long quantizedID = QuantizeThumbID(itemID);

	if (!mThumbDBs[quantizedID])
	{
		unsigned long rangeStart = quantizedID * kThumbIDQuantum;
		unsigned long rangeEnd = rangeStart + (kThumbIDQuantum - 1);

		char newThumbDBName[30];
		itoa(quantizedID, newThumbDBName, 10);
		strcat(newThumbDBName, ".map");

		char thumbDBPath[1024];
		strcpy(thumbDBPath, mThumbDBDirectory);
		strcat(thumbDBPath, newThumbDBName);

		mThumbDBs[quantizedID] = new clsThumbDB(thumbDBPath, rangeStart, rangeEnd);
	}

	mThumbDBs[quantizedID]->AddUniqueThumb(imageData, dataSize, itemID);
}

void clsGroupThumbDB::ClearThumb(int itemID)
{
	if (!mOpen)
		throw std::runtime_error("clsGroupThumbDB::AddThumb: db not open");

	unsigned long quantizedID = QuantizeThumbID(itemID);

	// If there isn't a db we just leave
	if (!mThumbDBs[quantizedID])
		return;

	mThumbDBs[quantizedID]->ClearUniqueThumb(itemID);
}

const char* clsGroupThumbDB::GetThumb(unsigned long itemID, int& size)
{
	const char* image = 0;
	int j = 0;

	if (!mOpen)
		throw std::runtime_error("clsGroupThumbDB::GetThumb: db not open");

	// is this item considered noisy?
/*	vector<int>::iterator j;

	j = find(mNoisyThumbs.begin(), mNoisyThumbs.end(), itemID);
	if (j != mNoisyThumbs.end())
	{
		// if we found it, just return
		size = mMissingImageSize;
		image = mMissingImage;
	
		return image;
	}
*/
	// check our cheapo noisy thumb array to see if we should not show this item
	while ((mNoisyThumbs[j] != -1) && (j < kMaxNoisyThumbs)) {
		if(mNoisyThumbs[j] == itemID)
		{
			// if we found it, just return
			size = mMissingImageSize;
			image = mMissingImage;
			
			return image;
		}
		
		j++;
	}
	

	unsigned long quantizedID = QuantizeThumbID(itemID);

	if (mThumbDBs[quantizedID])
	{
		image = mThumbDBs[quantizedID]->GetThumb(itemID, size);
	}

	if (!image)
	{
		size = mMissingImageSize;
		image = mMissingImage;
	}

	return image;
}

void clsGroupThumbDB::DumpDB(const char* where)
{
	if (!mOpen)
		throw std::runtime_error("clsGroupThumbDB::DumpDB: db not open");

	for (int i = 0; i < kThumbIDQuantum; ++i)
	{
		clsThumbDB* thumbDB = mThumbDBs[i];

		if (!thumbDB)
			continue;

		unsigned long begin = thumbDB->GetStartIndex();
		unsigned long end = thumbDB->GetEndIndex();

		for (; begin < end; begin++)
		{
			int size;
			const char* const image = thumbDB->GetThumb(begin, size);
			if (image)
			{
				FILE* outFile = NULL;

				try
				{
					char nameBuf[1024];
					strcpy(nameBuf, where);
					itoa(begin, &nameBuf[strlen(nameBuf)], 10);
					strcat(nameBuf, ".jpg");

					outFile = fopen(nameBuf, "wb");
					if (!outFile)
					{
						std::cout << "bad open " << nameBuf << std::endl;
						continue;
					}

					int writeResult = fwrite(image, size, 1, outFile);
					if (writeResult != 1)
					{
						std::cout << "bad write " << nameBuf << std::endl;
						throw -1;
					}
				}
				catch(...)
				{
				}

				if (outFile)
					fclose(outFile);
			}
		}
	}

}

int clsGroupThumbDB::PurgeOld(int maxIDs)
{
	if (!mOpen)
		throw std::runtime_error("clsGroupThumbDB::PurgeOld: db not open");

	// Find start and end of thumbDbs
	int count = 0;

	for (int i = 0; i < kThumbIDQuantum; ++i)
	{
		if (mThumbDBs[i])
		{
			++count;
		}
	}

	// No dbs, so nothing to delete
	if (!count)
		return 0;

	int maxDBs = maxIDs / kThumbIDQuantum + 1;
	int deleteCount = count - maxDBs;
	if (deleteCount <= 0)
		return 0;

	for (int j = 0; j < kThumbIDQuantum; ++j)
	{
		if (mThumbDBs[j])
		{
			delete mThumbDBs[j];
			mThumbDBs[j] = 0;

			char deleteThumbDBName[30];
			itoa(j, deleteThumbDBName, 10);
			strcat(deleteThumbDBName, ".map");

			char thumbDBPath[1024];
			strcpy(thumbDBPath, mThumbDBDirectory);
			strcat(thumbDBPath, deleteThumbDBName);

			int removeResult = remove(thumbDBPath);
			if (removeResult)
				return errno;

			if (!--deleteCount)
				break;
		}
	}

	return 0;
}

int clsGroupThumbDB::MakeNoisy(int item)
{
	int j = 0;
	
	// add item to vector
	//	mNoisyThumbs.push_back(item);
	
	while((mNoisyThumbs[j] != -1) && (j < kMaxNoisyThumbs)) 
		j++;
	
	// add the item
	mNoisyThumbs[j] = item;
	j++;
	mNoisyThumbs[j] = -1;
	
	mNoisyThumbCount = j;
	
#if 0
	char str[255];
	
	StartContent(pCtxt);
	WriteTitle(pCtxt);
	
	*pCtxt << _T("The Gallery picture for item ");
	sprintf(str, "%d has been removed.\r\n", item);	
	*pCtxt << _T(str);
	*pCtxt << _T("<br><br>Press the back button on your browser to continue.");
	
	//	this->EbayRedirect(pCtxt, newURL);
	
	EndContent(pCtxt);
	
#endif
	
	return 0;
}

int clsGroupThumbDB::MakeUnNoisy(int item)
{
	long *tempNoisyThumbs;
	int i = 0;
	int j = 0;
	bool itemFound = false;

	tempNoisyThumbs = new long[kMaxNoisyThumbs + 1];
	
	// add item to vector
	//	mNoisyThumbs.push_back(item);
	
	while((mNoisyThumbs[j] != -1) && (j < kMaxNoisyThumbs)) 
	{
		if(mNoisyThumbs[j] == item) {
			j++;
			itemFound = true;
		} else {
			tempNoisyThumbs[i] = mNoisyThumbs[j];
			i++;
			j++;
		}
	}
	// set end flag
	tempNoisyThumbs[i] = -1;

	j = 0;
	
	// copy back to the member variable
	while((tempNoisyThumbs[j] != -1) && (j < kMaxNoisyThumbs)) 
	{
		mNoisyThumbs[j] = tempNoisyThumbs[j];
		j++;
	}
	
	// set end flag
	mNoisyThumbs[j] = -1;

	delete [] tempNoisyThumbs;

	mNoisyThumbCount = j;

	if(itemFound)
		return 0;
	else 
		return -1;
}

// returns the current count of noisy thumbnails
int clsGroupThumbDB::NoisyThumbCount()
{
	return mNoisyThumbCount;
}


#if 0
int DeleteOldThumbDB(Options& options)
{
	int thumbDBCount = 0;

	{
		clsDirectoryWalker directoryWalker(options.mThumbDB.c_str(), "*.map");

		while (directoryWalker.GetNextItem())
			++thumbDBCount;
	}

	int toDelete = thumbDBCount - 3;

	if (toDelete > 0)
	{
		clsDirectoryWalker directoryWalker(options.mThumbDB.c_str(), "*.map");

		while (directoryWalker.GetNextItem() && toDelete > 0)
		{
 			if (!directoryWalker.IsFileCurrentlyUnaccessed())
				continue;

			char fullPath[1024];
			strcpy(fullPath, options.mThumbDB.c_str());
			strcat(fullPath, directoryWalker.GetName());

			int removeResult = remove(fullPath);
			
			if (removeResult)
			{
				std::cout << "Failed to remove " << fullPath << std::endl;
			}

			--toDelete;
		}

	}

	return 0;
}
#endif

