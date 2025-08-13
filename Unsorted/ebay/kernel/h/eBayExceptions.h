/*	$Id: eBayExceptions.h,v 1.2 1998/06/23 04:29:11 josh Exp $	*/
//
//	File:	eBayExceptions.h
//
//	Class:	Various
//
//	Author:	Michael Wilson (michael@ebay.com)
//
//	Function:
//
//			eBay Exceptions
//
// Modifications:
//				- 05/12/97 michael	- Created
//
#ifndef CLSEXCEPTIONS_INCLUDED

//
//	eBayOracleException
//
//	Our standard Oracle exception wrapper
//
class eBayOracleException
{
	public:
		eBayOracleException() :
			mRc(0),
			mFt(0),
			mRpc(0),
			mPeo(0),
			mFc(0),
			mWrn(0),
			mOse(0),
			mpErrorMsg(NULL),
			mpSQL(NULL),
			mpFile(NULL),
			mLine(0)
		{ return; }

		eBayOracleException(int		rc,		// Oracle return code
							int		ft,		// Function type
							int		rpc,	// Rows processed count
							int		peo,	// Parse Error Offset
							int		fc,		// OCI function code
							int		wrn,	// Warn flags
							int		ose,	// O/S Error
							char	*pErrorMsg,
							char	*pSQL,
							char	*pFile,	// File getting error
							int		line) :
			mRc(rc),
			mFt(ft),
			mRpc(rpc),
			mPeo(peo),
			mFc(fc),
			mWrn(wrn),
			mOse(ose),
			mLine(line)
		{
			mpErrorMsg		= new char[strlen(pErrorMsg) + 1];
			strcpy(mpErrorMsg, pErrorMsg);
			if (pSQL)
			{
				mpSQL			= new char[strlen(pSQL) + 1];
				strcpy(mpSQL, pSQL);
			}
			else
				mpSQL			= NULL;
			mpFile			= new char[strlen(pFile) + 1];
			strcpy(mpFile, pFile);
		}

		~eBayOracleException()
		{
		}


		int			mRc;			// Oracle Return code
		int			mFt;			// Function Type
		int			mRpc;			// Rows processed
		int			mPeo;			// Parse Error offset
		int			mFc;			// Function Code
		int			mWrn;			// Warning
		int			mOse;			// O/S Error Code
		char		*mpErrorMsg;	// From oerhms
		char		*mpSQL;			// SQL Statement
		char		*mpFile;		// File
		int			mLine;

};

class eBayGlobalLockException
{
	public:
		eBayGlobalLockException(unsigned int lastError) :
		  mLastError(lastError)
		{
		}

		~eBayGlobalLockException()
		{
		}

		unsigned int	mLastError;
};

class eBayNoAppException
{
	public:
		eBayNoAppException()
		{
		}

		~eBayNoAppException()
		{
		}

		int		mNothing;
};

//
// eBayStructuredException
//
//	Translated version of Microsoft Structured Exception
//
class eBayStructuredException
{
	public:
   		unsigned int		mExceptionCode; 
   		unsigned int		mExceptionFlags; 
   		void				*mpExceptionAddress; 
		unsigned int		mStorageViolationCode;
		void				*mpStorageViolationAddress;

	eBayStructuredException() :
		mExceptionCode(0),
		mExceptionFlags(0),
		mpExceptionAddress(NULL),
		mStorageViolationCode(0),
		mpStorageViolationAddress(NULL)
	{
	};

	~eBayStructuredException()
	{
	};

	eBayStructuredException(unsigned int code,
							unsigned int flags,
							void *pAddress) :
		mExceptionCode(code),
		mExceptionFlags(flags),
		mpExceptionAddress(pAddress),
		mStorageViolationCode(0),
		mpStorageViolationAddress(NULL)
	{
	};


	eBayStructuredException(unsigned int code,
							unsigned int flags,
							void *pAddress,
							unsigned int storageViolationCode,
							void *pStorageViolationAddress) :
		mExceptionCode(code),
		mExceptionFlags(flags),
		mpExceptionAddress(pAddress),
		mStorageViolationCode(storageViolationCode),
		mpStorageViolationAddress(pStorageViolationAddress)
	{
	};

}; 
 


#define CLSEXCEPTIONS_INCLUDED 1
#endif /* CLSEXCEPTIONS_INCLUDED */
