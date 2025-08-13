#ifndef _NM_STRING_H_
#define _NM_STRING_H_

#include "nmtypes.h"
#include "nmvector.h"

#ifdef __cplusplus
extern "C" {
#endif

#define PN_STANDARD      0x0000
#define PN_EUROPEAN      0x0001
#define PN_PRETTY        0x0002

#define PN_IGNORE_MARKUP 0x0010

NMEXPORT char * nm_downcase(char * string);
NMEXPORT char * nm_downncase(char * string, NmUInt32 len);
NMEXPORT NmBool nm_scan(const char * string, const char * key,char  delimiter, char * value, NmUInt32 len);
NMEXPORT NmBool nm_scan_assignment2(const char * string,const char * key,const char * delimiter,char * value)  ;
NMEXPORT NmBool nm_scan_assignment(const char * string, const char * key, char  delimiter,char * value, NmUInt32 len, NmBool hide);
NMEXPORT NmBool nm_scan_key(const char * string, const char * key, char * value, NmUInt32 len, NmBool hide);
NMEXPORT void nm_safe_url(const char * url, char * safe_url, NmUInt32 len);
NMEXPORT NmBool nm_valid_email(const char * email);
NMEXPORT NmBool nm_valid_domain(const char * domain);
NMEXPORT NmBool nm_email_subdomain(const char * email, char ** domain);
NMEXPORT NmBool nm_valid_url(char * url);
NMEXPORT NmBool nm_fix_url(const char * url1, char * url2);
NMEXPORT NmBool nm_escapify_string(const uchar *src, uchar *dest, NmInt32 destlen);
NMEXPORT NmBool nm_strcmp(const char * s1, const char * s2);
NMEXPORT NmBool nm_strcasecmp(const char * s1, const char * s2);
NMEXPORT NmBool nm_strncmp(const char * s1, const char * s2, NmUInt32 len);
NMEXPORT NmBool nm_strncasecmp(const char * s1, const char * s2, NmUInt32 len);
NMEXPORT NmBool nm_strstrcmp(const char * s1, const char * s2);
NMEXPORT NmBool nm_strstrcasecmp(const char * s1, const char * s2);
NMEXPORT NmBool nm_strwildcmp(const char * s1, const char * s2);
NMEXPORT NmBool nm_strwildcasecmp(const char * s1, const char * s2);
NMEXPORT typedef NmBool (*NmStringCmp)(const char *, const char *, NmUInt32);
NMEXPORT const char * nm_expand(char * old, char * dest, NmUInt32 new_len);
NMEXPORT NmBool nm_predict_time(NmUInt32 start_time, NmUInt32 start_amount, 
                                NmUInt32 current_time, NmUInt32 current_amount, 
                                NmUInt32 target_amount, NmPUInt32 target_time);
NMEXPORT char * nm_quote(char * src, char * dest);
NMEXPORT char * nm_nquote(char * src, char * dest, NmUInt32 len);
NMEXPORT NmUInt32 nm_quote_len(char * src);
NMEXPORT char * nm_unquote(char * src, char * dest);
NMEXPORT char * nm_nunquote(char * src, char * dest, NmUInt32 len);
NMEXPORT char * nm_decode_url(char * src, char * dest);
NMEXPORT char * nm_nencode_url(char * src, char * dest, NmUInt32 len);
NMEXPORT char * nm_trim_right(char * str);
NMEXPORT char * nm_replace_char(char * str, char from, char to);
NMEXPORT char * nm_replace_chars(char * str, const char * from, char to);
NMEXPORT char * nm_ltoa(NmUInt32 value, char * string);
NMEXPORT char * nm_strndup(const char * string, size_t len);
NMEXPORT NmBool nm_parse_time(const char * string, time_t * tt);
NMEXPORT NmBool nm_parse_numbers(const char * str, NmUInt32 mode, NmVector * numbers);
NMEXPORT NmBool nm_split_url_get_data(char * url,char ** get_data,char ** fetch_url) ;
NMEXPORT NmBool nm_create_fetchable_url(char * url,char * get_data, char ** fetch_url) ;
NMEXPORT NmBool nm_substring(const char * s1, const char * s2, size_t index,
                             char ** part, size_t * len);

NMEXPORT NmInt32 nm_htoi (char s[]) ;

/* stuff that should be in the C library */
NMEXPORT char * strcasechr(const char * ss, char cc);
NMEXPORT char * strcasestr(const char * s1, const char * s2);
NMEXPORT char * strncasestr(const char * s1, const char * s2, size_t len1);
NMEXPORT char * strnstr(const char * s1, const char * s2, size_t len1);


#ifdef __cplusplus
}
#endif

#endif /* _NM_STRING_H_ */
