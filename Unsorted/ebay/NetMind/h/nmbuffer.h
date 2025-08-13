#ifndef _NM_BUFFER_H_
#define _NM_BUFFER_H_

#include "nmtypes.h"						/* for NmBool */
#include "nmlist.h"							/* for NmList */

/* used to denote negative filtering in buf_filter_regex
   Negative filtering retains the regex match in the buffer; positive
   filtering retains the content that does not match.
   Firm negative filtering says to delete all buffer contents on no match,
   Gentle negative filtering says leave the buffer intact on no
   match.
*/
#define BUF_NEG_GENTLE '!'
#define BUF_NEG_FIRM '~'

#define TAG_START '['
#define TAG_COND  '?'
#define TAG_NOT   '!'
#define TAG_EQUAL '='
#define TAG_PART  '.'
#define TAG_END   ']'
#define TAG_RANGE_BEGIN '{'
#define TAG_RANGE_SEP   ','
#define TAG_RANGE_END   '}'

#ifdef __cplusplus
extern "C" {
#endif

/* a buffer is a just a byte vector */
typedef NmVector NmBuffer;

NMEXPORT NmBool buf_init(NmBuffer * self, const char * name, NmBool text);
NMEXPORT NmBool buf_init_from_file(NmBuffer * self, const char * file, NmBool text);
NMEXPORT NmBool buf_init_from_string(NmBuffer * self, const char * data);
NMEXPORT NmBool buf_alloc(NmBuffer * self, NmUInt32 new_max);
NMEXPORT NmBool buf_read_stream(NmBuffer * self, FILE * fp);
NMEXPORT NmBool buf_write_file(NmBuffer * self, const char * file);
NMEXPORT NmBool buf_replace(NmBuffer * self, NmList * strings, NmBuffer * copy, NmError * error);
NMEXPORT NmBool buf_replace_copy(NmBuffer * self, NmList * strings, char ** srcp, char end, NmError * error);
NMEXPORT NmBool buf_expand_tag(NmBuffer * self, NmList * strings, char ** srcp, NmError * error);
NMEXPORT NmBool buf_skip_tag(NmBuffer * self, char ** srcp, NmError * error);
NMEXPORT NmBool buf_make_links(NmBuffer * self, char * str);
NMEXPORT NmBool buf_copy(NmBuffer * self, NmBuffer * other);
NMEXPORT NmBool buf_append(NmBuffer * self, const char * data, size_t len);
NMEXPORT NmBool buf_append_text(NmBuffer * self, const char * text);
NMEXPORT NmBool buf_append_byte(NmBuffer * self, const NmInt32 byte);
NMEXPORT NmBool buf_insert(NmBuffer *self, NmInt32 offset,
                           const char *data, size_t len);
NMEXPORT NmBool buf_delete(NmBuffer *self, NmInt32 first, size_t len);
NMEXPORT NmBool buf_terminate(NmBuffer * self);
NMEXPORT NmBool buf_base64_encode(NmBuffer *self);
NMEXPORT NmBool buf_base64_decode(NmBuffer *self, NmBool text);
NMEXPORT NmBool buf_filter_region(NmBuffer * self,
                                  const char * begin, const char * end);
NMEXPORT NmBool buf_filter_regex(NmBuffer * self,
                                 const char * pattern, NmBool persistent);
NMEXPORT NmBool buf_check_keywords(NmBuffer *self,
                                   const char *keywords, NmBool sensitive);
NMEXPORT NmBool buf_find_keywords(NmBuffer * self, const char * keywords,
                                  NmBool sensitive, char ** match);
NMEXPORT NmUInt32  buf_sum(NmBuffer *self);
NMEXPORT void   buf_done(NmBuffer *self);
#define buf_data(buf)        ((char*)((buf)->data))
#define buf_length(buf)      ((buf)->length)
NMEXPORT NmBool buf_text(NmBuffer * self) ;
NMEXPORT void buf_set_text(NmBuffer * self, NmBool text);
NMEXPORT NmBool buf_remove_null (NmBuffer* body) ;
NMEXPORT NmBool buf_filter_between(NmBuffer * self, const char * tag);
NMEXPORT NmBool buf_translate(NmBuffer * self);

#ifdef __cplusplus
}
#endif

#endif /* _NM_BUFFER_H_ */
