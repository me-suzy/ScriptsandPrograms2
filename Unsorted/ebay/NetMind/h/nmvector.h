/*MAN
MODULE 
     nmvector.h
DESCRIPTION
     Vector header file.
COPYRIGHT
     Copyright Netmind Services 1996-1997
*/

#ifndef _NM_VECTOR_H_
#define _NM_VECTOR_H_

#include <stdio.h>							/* for FILE */
#include "nmtypes.h"						/* for NmBool, NmNamedValue, etc. */
#include "nmfile.h"

#ifdef __cplusplus
extern "C" {
#endif


/* general-purpose vector */
/* XDR */
typedef struct {
  NmType type;									/* type of element */
  char * data;									/* array of elements */
  NmUInt32 length;									/* current number of elements */
  NmUInt32 max;										/* maximum number of elements (data size) */
  NmUInt32 elem_size;							/* size of each element */
  char * name;									/* (optional) name */
  NmBool caller_data;						/* TRUE if caller owns data memory */
	NmBool copy;									/* TRUE if copy element data */
} NmVector;

typedef long NmCursor;
typedef NmBool (*NmEquality)(void *, void *);

NMEXPORT NmBool vec_init(NmVector * self, NmType type);
NMEXPORT NmBool vec_init1(NmVector * self, NmUInt32 elem_size);
NMEXPORT NmBool vec_vinit(NmVector * self, NmType type, void * mem, NmUInt32 memsize);
NMEXPORT NmBool vec_vinit1(NmVector * self, NmUInt32 elem_size, void * mem, NmUInt32 max);
NMEXPORT NmUInt32  vec_elem_size(NmType type);
NMEXPORT NmBool vec_clear(NmVector * self);
NMEXPORT NmBool vec_alloc(NmVector * self, NmUInt32 new_max);
NMEXPORT NmBool vec_set_data(NmVector * self, void * data, NmUInt32 length);
NMEXPORT NmBool vec_add(NmVector * self, void * elem);
NMEXPORT NmBool vec_append(NmVector * self, NmVector * other);
NMEXPORT NmBool vec_prepend(NmVector * self, void * elem);
NMEXPORT NmBool vec_copy(NmVector * self, NmVector * copy);
NMEXPORT NmBool vec_equal(NmVector * self, NmVector * other, NmEquality elem_equal);
NMEXPORT void * vec_new(NmVector * self);
NMEXPORT void * vec_first(NmVector * self, NmCursor * cursor);
NMEXPORT void * vec_next(NmVector * self, NmCursor * cursor);
NMEXPORT void * vec_find_number(NmVector * self, NmUInt32 num, NmUInt32 pos);
NMEXPORT void * vec_find_string(NmVector * self, const char * str, NmUInt32 pos);
NMEXPORT void * vec_item(NmVector * self, NmUInt32 index);
NMEXPORT void * vec_last(NmVector * self);
NMEXPORT NmUInt32  vec_index(NmVector * self, void * elem);
NMEXPORT NmBool vec_encode(NmVector * self, char ** data, NmUInt32 * len);
NMEXPORT NmBool vec_decode(NmVector * self, char * data, NmUInt32 len);
NMEXPORT NmBool vec_to_file(NmVector *self, NmFileHandle file);
NMEXPORT NmBool vec_from_file(NmVector *self, NmFileHandle file);
NMEXPORT void   vec_compress(NmVector * self);
NMEXPORT void   vec_done(NmVector * self);

/*
 * macro equivalents of vec_first and vec_next
 * use these instead of functions in tight loops
 */
#define VEC_FIRST(self, cursor, elem) \
  elem = ((self)->length == 0 ? 0 : (void*) (self)->data), \
  (cursor) = ((self)->length == 0 ? 0 : 1)

#define VEC_NEXT(self, cursor, elem) \
  (elem) = ((NmUInt32)(cursor) == (self)->length ? 0 : \
    ((self)->data) + ((cursor)++ * (self)->elem_size))

#define VEC_LAST(self, cursor, elem) \
  (elem) = ((self)->length == 0 ? 0 : \
    ((self)->data) + (((self)->length - 1) * (self)->elem_size)), \
  (cursor) = ((self)->length == 0 ? -1 : (long)((self)->length) - 2)

#define VEC_PREV(self, cursor, elem) \
  (elem) = ((cursor) == -1 ? 0 : \
    ((self)->data) + ((cursor)-- * (self)->elem_size))

#define VEC_ITEM(self, index) \
  ((self)->data + ((index) * (self)->elem_size))

#ifdef __cplusplus
}
#endif

#endif /* _NM_VECTOR_H_ */

