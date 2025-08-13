/*	$Id: clsDeliverableFiles.h,v 1.3 1999/02/21 02:21:25 josh Exp $	*/
#ifndef clsDeliverableFiles_h
#define clsDeliverableFiles_h

// This structure describes a deliverable file.
struct deliverableFile
{
	char *mpName;
	char *mpText;
	unsigned long mLength;
	unsigned long mHeaderLength;
	time_t mLastUpdate;
	time_t mExpires;
};

class clsFileSet
{
private:
	deliverableFile *mppFiles;
	unsigned long mNumFiles;

	const char *mpHeader;
	const char *mpFooter;

public:

	deliverableFile *GetFile(const char *pName);
};

endif /* clsDeliverableFiles_h */
