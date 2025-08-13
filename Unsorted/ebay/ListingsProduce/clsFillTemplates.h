/*	$Id: clsFillTemplates.h,v 1.3.626.1 1999/08/01 02:51:17 barry Exp $	*/
#ifndef CLSFILLTEMPLATES_INCLUDE
#define CLSFILLTEMPLATES_INCLUDE

#include "clsPackedStructures.h"

// Class forward references.
class clsTextPool;
class ostream;

#include "vector.h"
#include "list.h"

#include <time.h>

class clsFillTemplates
{
private:
	// Not a pointer.
	templatesHeaderEntry mHeader;

	// Pointers to the various things we need to fill.
	// We know how many we'll need, so we just allocate them.
	templatesPartnerEntry *mpPartners;

    // How many partners we have.
    int mNumPartners;

	// A pointer to the list of templates pieces.
	list<templatesPieceEntry> *mpPieces;
	// A pointer to the list of header pices.
//	list<templatesCategoryHeaderEntry> *mpHeaders;

	// A pointer to the list of ads
	list<int32_t> * mpAds;

	// The common text block.
	clsTextPool *mpText;

public:
	clsFillTemplates();
	~clsFillTemplates();

    // Calling this fills in all of the structures necessary
    // to write the binary file, but does not write it.
    // (Use WriteBinaryToStream for that.)
	void Run();

	// This fills one partner entry structure.
	void FillPartner(templatesPartnerEntry *pPartner);

    // Writes a packed file onto pStream. The reader from
    // the 'listings' project knows how to use this file.
	void WriteBinaryToStream(ostream *pStream);
};

#endif /* CLSFILLTEMPLATES_INCLUDE */
