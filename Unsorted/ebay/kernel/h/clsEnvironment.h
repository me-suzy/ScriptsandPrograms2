/*	$Id: clsEnvironment.h,v 1.8.166.3.68.1 1999/08/01 03:02:07 barry Exp $	*/
//
//	File:		clsEnvironment.h
//
// Class:	Environment
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//				Encapsulates all the little nasty thing
//				in the environment, like the HTTP_USER_AGENT,
//				etc.
//
// Modifications:
//				- 02/10/97 michael	- Created
//				- 12/24/97 michael	- Added support for HTTP environment 
//									  variables, and repeat initialization.
//				- 05/13/99 jennifer - added function IsMSIE30
//				- 05/25/99 nsacco	- added ServerName variable
//

#ifndef CLSENVIRONMENT_INCLUDED

// Some convienent macros
#define CHAR_VARIABLE(name, size)	\
protected:							\
	char	m##name[size];			\
public:								\
	char	*Get##name();			\
	void	Set##name(char *pNew)


class clsEnvironment
{
	public:
		//
		// Constructor, Destructor
		//
		clsEnvironment();
		virtual ~clsEnvironment();

		//
		// GetParameterValue
		//		Returns the value of a given parameter, or
		//		null if it's not present.
		//
		virtual const char 
				*GetParameterValue(char *pName) = 0;

		//
		// GetFormValue
		//		Returns the value of a given form field name,
		//		or null if it's not present. Of course, this
		//		really only makes sense for Web-Apps
		//
		// *** Note ***
		//	If we always guarantee that fields in forms AND
		// paramters (path variables), then we could unify
		// GetParameterValue and GetFormValue. Probably
		// makes sense
		// *** Note ***
		virtual const char *GetFormValue(char *pName) = 0;

		//
		// Reset
		//
		//	Reinitializes ourselves.
		//
		virtual void Reset(unsigned char *pThing) = 0;

		// Some browser sniffing stuff
		virtual int GetMozillaLevel() = 0;
		virtual bool IsWebTV() = 0;
		virtual bool IsAOL() = 0;
		virtual bool IsMSIE30() = 0;
		virtual bool IsWin16() = 0;
		virtual bool IsOpera() = 0;
		
		CHAR_VARIABLE(RemoteAddr, 16);
		CHAR_VARIABLE(RemoteHost, 256);
		CHAR_VARIABLE(RemoteUser, 256);
		CHAR_VARIABLE(ScriptName, 512);
		CHAR_VARIABLE(Browser, 256);
		CHAR_VARIABLE(Cookie, 4096);
		CHAR_VARIABLE(Referrer, 256);
		CHAR_VARIABLE(ServerName, 256);
};

	
#define CLSENVIRONMENT_INCLUDED 1
#endif /* CLSENVIRONMENT_INCLUDED */
