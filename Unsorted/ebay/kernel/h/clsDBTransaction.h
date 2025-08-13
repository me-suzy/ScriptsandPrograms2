/*	$Id: clsDBTransaction.h,v 1.2 1999/02/21 02:46:28 josh Exp $	*/
//
// File: clsDBTransaction.h
//
// Class: clsDBTransaction
//

#ifndef CLSDBTRANSACTION_H
#define CLSDBTRANSACTION_H

class clsDBTransaction
{
public:
	clsDBTransaction(clsDatabaseOracle& db, unsigned char*& cursor, const char* sqlStatement);
	~clsDBTransaction();

	void Bind(const char *pBindName, int *pVar, short *pInd = NULL);
	void Bind(const char *pBindName, unsigned int *pVar, short *pInd = NULL);
	void Bind(const char *pBindName, const char *pVar, short *pInd = NULL);
	void Bind(const char *pName, const char *pVar, int length, short *pInd = NULL);
	void Bind(const char *pBindName, float *pVar, short *pInd = NULL);
	void Bind(const char *pBindName, double *pVar, short *pInd = NULL);
	void BindLongRaw(const char *pName, unsigned char *pVar, int varLength, short *pInd = NULL);

	void Define(int position, int *pTarget, short *pIndicator = NULL);
	void Define(int position, unsigned int *pTarget, short *pIndicator = NULL);
	void Define(int position, float *pTarget, short *pIndicator = NULL);
	void Define(int position, char *pTarget, int targetLength, short *pIndicator = NULL);
	void DefineLongRaw(int position, unsigned char *pTarget, int targetLength, short *pIndicator = NULL);
	void ArrayDefine(int position, int *pTarget, short skip, short *pInd = NULL, short indicatorSkip = 0);
	void ArrayDefine(int position, char *pTarget, int size, short skip, short *pInd = NULL, short indicatorSkip = 0);

	void Execute();
	void ExecuteAndFetch(int count = 1);
	bool CheckForNoRowsFound();
	bool CheckForNoRowsUpdated();
	void Commit();
	
private:
	clsDatabaseOracle& mDB;
//	struct cda_def *& mCursor;
	unsigned char*& mCursor;
};

#if 0
inline void clsDBTransaction::ArrayDefine(int position, int *pTarget, short skip, short *pInd, short indicatorSkip)
{
	mDB.ArrayDefine(position, pTarget, skip, pInd, indicatorSkip);
}

inline void clsDBTransaction::ArrayDefine(int position, char *pTarget, int size, short skip, short *pInd, short indicatorSkip)
{
	mDB.ArrayDefine(position, pTarget, size, skip, pInd, indicatorSkip);
}
#endif

inline clsDBTransaction::clsDBTransaction(clsDatabaseOracle& db, unsigned char*& cursor, const char* sqlStatement) :
	mDB(db),
	mCursor(cursor)
{
	mDB.OpenAndParse(&mCursor, sqlStatement);
}

inline clsDBTransaction::~clsDBTransaction()
{
	mDB.Close(&mCursor);
	mDB.SetStatement(NULL);
}

inline void clsDBTransaction::Bind(const char *pBindName, int *pVar, short *pInd)
{
	mDB.Bind(pBindName, pVar, pInd);
}

inline void clsDBTransaction::Bind(const char *pBindName, unsigned int *pVar, short *pInd)
{
	mDB.Bind(pBindName, pVar, pInd);
}

inline void clsDBTransaction::Bind(const char *pBindName, const char *pVar, short *pInd)
{
	mDB.Bind(pBindName, pVar, pInd);
}

inline void clsDBTransaction::Bind(const char *pName, const char *pVar, int length, short *pInd)
{
	mDB.Bind(pName, pVar, length, pInd);
}

inline void clsDBTransaction::Bind(const char *pBindName, float *pVar, short *pInd)
{
	mDB.Bind(pBindName, pVar, pInd);
}

inline void clsDBTransaction::Bind(const char *pBindName, double *pVar, short *pInd)
{
	mDB.Bind(pBindName, pVar, pInd);
}

inline void clsDBTransaction::BindLongRaw(const char *pName, unsigned char *pVar, int varLength, short *pInd)
{
	mDB.BindLongRaw(pName, pVar, varLength, pInd);
}

inline void clsDBTransaction::Define(int position, int *pTarget, short *pIndicator)
{
	mDB.Define(position, pTarget, pIndicator);
}

inline void clsDBTransaction::Define(int position, unsigned int *pTarget, short *pIndicator)
{
	mDB.Define(position, pTarget, pIndicator);
}

inline void clsDBTransaction::Define(int position, float *pTarget, short *pIndicator)
{
	mDB.Define(position, pTarget, pIndicator);
}

inline void clsDBTransaction::Define(int position, char *pTarget, int targetLength, short *pIndicator)
{
	mDB.Define(position, pTarget, targetLength, pIndicator);
}

inline void clsDBTransaction::DefineLongRaw(int position, unsigned char *pTarget, int targetLength, short *pIndicator)
{
	mDB.DefineLongRaw(position, pTarget, targetLength, pIndicator);
}

inline void clsDBTransaction::Execute()
{
	mDB.Execute();
}

inline void clsDBTransaction::ExecuteAndFetch(int count)
{
	mDB.ExecuteAndFetch(count);
}

bool clsDBTransaction::CheckForNoRowsFound()
{
	if (((struct cda_def *)mCursor)->rc == 1403)
		return true;

	return false;
}

bool clsDBTransaction::CheckForNoRowsUpdated()
{
	if (((struct cda_def *)mCursor)->rpc == 0)
		return true;

	return false;
}

inline void clsDBTransaction::Commit()
{
	mDB.Commit();
}

#endif // CLSDBTRANSACTION_H
