#include <stdio.h>
#include <stdlib.h>	/* for getenv */
#include <string.h>
#include <ctype.h>	/* isxdigit, etc */
#include <unistd.h>	/* read() */


/* get_request returns the string that was sent by the browser containing
   the query. The format of this string is a sequence of name,value
   pairs seperated by the '&' character. Something like this:

   "name1=value1&name2=value2&name3=value3"

   Each name, value pair corresponds to a form field, the name is the
   name of the form element and the value is the value typed in or
   selected by the user. 

   All names and values are urlencoded - all spaces are replaced by
   the '+' character and (many) non-alphanumeric characters are encoded as
   hexidecimal equivalent preceded by a '%'. For example, the following
   name, value pairs:

   size:	large
   flavor:	chunky monkey
   price:	$2.49

   could be encoded like this:

   size=large&flavor=chunky+monkey&price=%242.49
   
   the dollar sign in price is converted to the hex equivalent %24.

   get_request returns NULL if there are any errors.

*/


char *get_request( void ) {
  char *request_method;
  char *content_length;
  char *query_string;
  int length,n,bytesread;

  /* get the request method from the REQUEST_METHOD environment variable */

  if (NULL==(request_method = getenv("REQUEST_METHOD"))) {
    return(NULL);
  }

  if (strncasecmp(request_method,"GET",3)==0) {

    /* GET request - the query is in QUERY_STRING environment variable */
    if (!(query_string = getenv("QUERY_STRING"))) {
      return(NULL);
    } else {
      return(strdup(query_string));
    }

  } else if (strncmp(request_method,"POST",4)==0) {

    /* POST - the string is coming in STDIN */
    /* we find out how much to read by lookin at the CONTENT_LENGTH
       environment variable */

    if (!(content_length = getenv("CONTENT_LENGTH"))) {	
      return(NULL);
    } 

    length = atoi(content_length);
    /* Allocate memory to hold the query */
    if (!(query_string = malloc(length+1))) {
      return(NULL);
    }

    /* Read in the query from standard input */
    n=0;
    while (length!=n) {
      bytesread=read(0,query_string+n,length-n);
      if (bytesread<0)
	return(NULL);
      n+=bytesread;
    }
    query_string[length]=0;
    return(query_string);
  }
  /* shouln't ever get this far! */
  return(NULL);
}


/* hexval converts a ascii encoded hex digit to the
   corresponding binary value. If we get a numeric
   character ('0' - '9') we return the corresponding
   value 0-9.
   If we get an alphabetic character we return;

   'a' or 'A' -> 10
   'b' or 'B' -> 11
   ...
   'f' or 'F' -> 15

*/

char hexval (  char x ) {
  if (isdigit(x)) {
    /* converting '0' - '9' */
    return(x-'0');
  } else if ( (x>='a') && (x<='f')) {
    /* lowercase 'a' - 'f' */
    return(10+(x-'a'));
  } else if ( (x>='A') && (x<='F')) {
    /* uppercase 'A' - 'F' */
    return(10+(x-'A'));
  }
  /* we got something we didn't expect - return -1 */
  return(-1);
}


/* fixstring converts a urlencoded string to a normal string.
   This includes:
     replaces all '+' character by ' '
     replaces all hex equivalents by the single character encoded.
     
   The size of the string may change - but it will only shrink,
   so we change it in place (change the original string).
*/

void fixstring( unsigned char *s) {
  char *p = strdup(s);	/* make a copy of the string to work with */
  char *orig=p;		/* keep track of this copy */

  while (*p) {
    if (*p=='+') {
      /* substitute a blank for a plus */
      *s=' ';
      p++; s++;

      /* Look for a hex encoded character - don't trust the user 
         (make really sure this is hex encoded!) */
    } else if ((strlen(p)>=3) && (*p=='%') /*&& 
	       (isxdigit(p+1)) && (isxdigit(p+2))*/ ) {

      /* hex encoded - convert to a char */

      *s = hexval(p[1])<<4 | hexval(p[2]);
      s++; p+=3;
    } else {
      /* normal character - just copy it without changing */
      *s=*p;
      s++; p++;
    }
  }
  /* terminate the new string */
  *s=0;
  /* and get rid of the duplicate */
  free(orig);
}    

/* split_and_parse converts an entire query string into 
   2 arrays of strings (pointers to chars). There is one 
   array for the field names, and another array for the 
   corresponding values. Both arrays (names and vals) must be
   allocated before calling this function and the size of the
   arrays must be at least maxfields. 

   If there are more fields found in the query string than can
   fit in the arrays, the first maxfields are converted and
   returned silently (you won't know there was more). 

   Anything unexpected results in split_and_pair returning
   a 0, for example if a there is no '=' found in a field
   definition.

   split_and_parse returns the number of fields converted to
   name,value pairs and stuffed into the arrays.
   */

int split_and_parse( char *query, char **names, char **vals, int maxfields) {
  int n=0;

  char *field;
  char *name,*val;

  /* get the field (first name,value pair).
     The '&' character is used to delimit
     each field.
  */

  field = strtok(query,"&");

  /* Loop until we have no more room in the arrays, or until
     there are no more field definitions in the query string
  */

  while ((n<maxfields)&&(field!=NULL)) {
    /* the field name is the first part of the field */
    name=field;

    /* the field value is found just past the '=' in the field def. */
    if (!(val = strchr(field,'='))) {
      return(0); /* no '=' found - error */
    }
    *val=0;	/* terminate the name  (overwrite the '=' with '\0') */
    val++;	/* the value starts at the character after the '=' */

    names[n]=strdup(name);	/* make a copy of the name */
    fixstring(names[n]);	/* take care of decoding */
    vals[n]=strdup(val);	/* make a copy of the value */
    fixstring(vals[n]); 	/* take care of decoding */
    n++;
    field = strtok(NULL,"&");	/* find the next field definition */
  }
  return(n);
}

    







