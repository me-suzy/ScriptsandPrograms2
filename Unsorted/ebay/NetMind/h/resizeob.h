/* Copyright (C) RSA Data Security, Inc. created 1996.  This is an
   unpublished work protected as such under copyright law.  This work
   contains proprietary, confidential, and trade secret information of
   RSA Data Security, Inc.  Use, disclosure or reproduction without the
   express written authorization of RSA Data Security, Inc. is
   prohibited.
 */

#ifndef _RESIZEOB_H_
#define _RESIZEOB_H_ 1

/* In C++:
class ResizeContext {
public:
  ResizeContext ();
  ~ResizeContext ();
  int makeNewContext (unsigned int contextSize);
  POINTER context () {return z.context;}

private:
  struct {
    POINTER context;
    unsigned int contextSize;
    CONTEXT_DESTRUCTOR ContextDestructor;
  } z;
};
*/

typedef void (*CONTEXT_DESTRUCTOR) PROTO_LIST ((POINTER ));

typedef struct ResizeContext {
  struct {
    POINTER context;
    unsigned int contextSize;
    CONTEXT_DESTRUCTOR ContextDestructor;
  } z;                                            /* zeriozed by constructor */
} ResizeContext;

void ResizeContextConstructor PROTO_LIST ((ResizeContext *));
void ResizeContextDestructor PROTO_LIST ((ResizeContext *));
int ResizeContextMakeNewContext PROTO_LIST ((ResizeContext *, unsigned int));

#endif
