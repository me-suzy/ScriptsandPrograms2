/*	$Id: clsMail.h,v 1.3 1999/04/07 05:42:37 josh Exp $	*/
//
//	File:	clsMail.h
//
//	Class:	clsMail
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//		clsMail makes it easy to send mail to a user.
//
// Modifications:
//				- 05/09/97 michael	- Created
//

#ifndef CLSMAIL_INCLUDED
#ifdef _MSC_VER
#include "strstrea.h"
#else
#include "strstream.h"
#endif /* _MSC_VER */

class clsMail
{
	public:
		// CTOR, DTOR
		clsMail();
		~clsMail();

		//
		// GetMailStream 
		//	Returns a stream (actually, an ofstream) to which 
		//	mail can be written
		//
		ostrstream *OpenStream();

		//
		// Send
		//	Actually sends the mail to the passed
		//	user. Calling Send() without calling
		//	GetMailStream is, well, a no=no
		//
		int Send(char *pTo,
				 char *pFrom,
				 char *pSubject,
				 char **pvCC = NULL,
				 char **pvBCC = NULL,
				 int MailPooling = 0);

	private:
		ostrstream	*mpStream;

};


#define CLSMAIL_INCLUDED 1
#endif /* CLSMAIL_INCLUDED */
