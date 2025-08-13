/*	$Id: clsNeighbor.h,v 1.3 1999/02/21 02:46:44 josh Exp $	*/
#ifndef clsNeighbor_h
#define clsNeighbor_h

class clsNeighbor
{
private:
	long mUserId;
	bool mApproved;
	char mComment[256];

public:
	clsNeighbor(long user_id, bool approved, const char *pComment) : mUserId(user_id),
		mApproved(approved)
	{ strncpy(mComment, pComment, 255); mComment[255] = '\0'; }
	~clsNeighbor() { }

	void SetUserId(long user_id) { mUserId = user_id; }
	void SetApproved(bool approved) { mApproved = approved; }
	void SetComment(const char *pComment) { strncpy(mComment, pComment, 255); mComment[255] = '\0'; }

	long GetUserId() { return mUserId; }
	bool GetApproved() { return mApproved; }
	const char *GetComment() { return mComment; }
};

#endif /* clsNeighbor_h */
