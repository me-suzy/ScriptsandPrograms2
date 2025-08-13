/*	$Id: clsPackedStructures.h,v 1.4 1999/04/17 20:21:53 wwen Exp $	*/
#ifndef CLSPACKEDSTRUCTURES_INCLUDE
#define CLSPACKEDSTRUCTURES_INCLUDE

// Some typedefs for portability.
typedef long	int32_t;
typedef short	int16_t;
typedef char	int8_t;

// We define this to prevent us from
// getting all the class information for
// the structures -- we only want the
// raw structures.

// The include is ugly, but VC++ wasn't honoring my project-level
// include directive.
#define CLSITEMMAP_WANT_STRUCTURES_ONLY
#include "../Listings/clsItemMap.h"
#undef CLSITEMMAP_WANT_STRUCTURES_ONLY

#define CLSTEMPLATESMAP_WANT_STRUCTURES_ONLY
#include "../Listings/clsTemplatesMap.h"
#undef CLSTEMPLATESMAP_WANT_STRUCTURES_ONLY

// The above file is reproduced here for informational
// purposes, but is located in the 'Listings' project.
// The following structure fields are all assumed to
// be packed, with no padding inbetween.  They are also
// assumed to have been written in the byte order of
// the _target_ host, not of the generating host.
#if 0
// One record at the very start of the file
// Offsets here are absolute offsets from
// the beginning of the file.

/*
This contains the definition of headerEntry, categoryEntry and itemEntry
 */
#include "entries.h"

// Here go the item offset lists. These are
// no structure, just int32_t's.
#endif /* 0 */

#endif /* CLSPACKEDSTRUCTURES_INCLUDE */