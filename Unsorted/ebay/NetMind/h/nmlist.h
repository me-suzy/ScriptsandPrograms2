/*MAN
MODULE 
     nmlist.h
DESCRIPTION
     List header file.
COPYRIGHT
     Copyright Netmind Services 1996-1997
*/

#ifndef _NM_LIST_H_
#define _NM_LIST_H_

#include <stdarg.h>
#include "nmvector.h"

#ifdef __cplusplus
extern "C" {
#endif

/* a list is simply a vector of named values */
typedef NmVector NmList;

NMEXPORT NmBool list_init(NmList * self, ...);
NMEXPORT NmBool list_vinit(NmList * self, NmNamedValue * mem, NmUInt32 memsize, ...);
NMEXPORT NmBool list_init1(NmList * self, NmNamedValue * mem, NmUInt32 memsize, va_list args);
NMEXPORT NmBool list_init_from_file(NmList * self, const char * file, NmBool reverse);
NMEXPORT NmBool list_read_file(NmList * self, const char * file, NmBool reverse);
NMEXPORT NmBool list_init_from_string(NmList * self, const char * string);
NMEXPORT NmBool list_init_from_string_verbatim(NmList * self, const char * string);
NMEXPORT NmBool list_read_string(NmList * self, char ** str, NmBool reverse,
                                 NmBool verbatim, const char * separator,
                                 NmBool decode, NmBool do_pseudokeys);
NMEXPORT NmBool list_add(NmList * self, const char * name, NmType type, void * value);
NMEXPORT NmBool list_set(NmList * self, const char * name, NmType type, void * value);
NMEXPORT NmBool list_append(NmList * self, const char * name, const char * value);
NMEXPORT NmBool list_nappend(NmList * self, const char * name, const char * value, NmUInt32 len);
NMEXPORT NmBool list_append_number(NmList * self, const char * name, NmUInt32 value);
NMEXPORT NmBool list_prepend(NmList * self, const char * name, const char * value);
NMEXPORT NmBool list_prepend_args(NmList * self, NmInt32 argc, char * args[]);
NMEXPORT NmBool list_copy (NmList *self, NmList *fromList, NmBool deepcopy) ;
NMEXPORT char * list_find(NmList * self, const char * name);
NMEXPORT NmList * list_find_sublist(NmList * self, const char * name);
NMEXPORT char * list_ifind(NmList * self, const char * name);
NMEXPORT char * list_nifind(NmList * self, const char * name, size_t len);
NMEXPORT NmNamedValue * list_get(NmList * self, const char * name, NmType type);
NMEXPORT NmNamedValue * list_iget(NmList * self, const char * name, NmType type);
NMEXPORT NmNamedValue * list_nget(NmList * self, const char * name, size_t len);
NMEXPORT NmNamedValue * list_match(NmList * self, const char * name, NmUInt32 len, NmUInt32 flags);
NMEXPORT char * list_name(NmList * self, NmInt32 index);
NMEXPORT NmNamedValue * list_item(NmList * self, NmInt32 index);
NMEXPORT void list_clear(NmList * self);
NMEXPORT void list_zero_values(NmList * self);
NMEXPORT NmBool list_format(NmList * self, char * pad1, char * pad2, NmUInt32 flags, char ** str);
NMEXPORT NmBool list_read_files(NmList * self, char * path);
NMEXPORT void list_done(NmList * self);
/* list_match flags */
#define LIST_EXACT     0x0001
#define LIST_PARTIAL   0x0002
#define LIST_WILDCARD  0x0004
#define LIST_CASE      0x0010
#define LIST_LENGTH    0x0020

/* list_format flags */
#define LIST_COMPACT   0x0001
#define LIST_QUOTE     0x0002
#define LIST_SAFE      0x0004
#define LIST_SEPARATOR 0x0008

/* convenience macros */
#define list_find_item(self, target)      list_match(self, target, 0, LIST_EXACT)
#define list_substring_item(self, target) list_match(self, target, 0, LIST_PARTIAL | LIST_CASE)
#define list_match_item(self, target)     list_match(self, target, 0, LIST_WILDCARD)

#ifdef __cplusplus
}
#endif

#endif /* _NM_LIST_H_ */
