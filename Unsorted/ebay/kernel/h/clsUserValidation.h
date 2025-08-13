/*	$Id: clsUserValidation.h,v 1.4 1998/09/30 02:58:51 josh Exp $	*/
#ifndef clsUserValidation_h
#define clsUserValidation_h

class clsUser;

class clsUserValidation
{
public:
	bool IsSoftValidated() const; // Soft validation is a cookie -- a password will do too.
	bool IsHardValidated() const; // Hard validation is a password.
	const char *GetValidatedUserId() const; // Returns NULL if no validated user.
	clsUser *GetValidatedUser() const; // NULL if no validated user.
									   // Owned by this object.

	clsUserValidation();
	~clsUserValidation();

	// These functions invalidate the clsUser object if called with an id
	// different than that returned by GetValidatedUserId
	void SetHardValidation(bool on, const char *pUserId);
	void SetSoftValidation(bool on, const char *pUserId);

	// This clears the validation.
	void ClearValidation();

private:

	// We don't want these generated for us.
	clsUserValidation(const clsUserValidation &);
	clsUserValidation & operator=(const clsUserValidation &);

	// We can have one of these states of validation for each
	// hard validation and soft validation.
	enum validation_state
	{
		unvalidated,
		validated,
		unknownvalidation
	};

	// Some of this stuff is mutable because it's lazy loaded, which
	// shouldn't mean the function can't be const.
	mutable validation_state mSoftValidation;
	validation_state mHardValidation;

	// Private function to attempt to soft validate a user.
	void AttemptValidation() const;

	// The user and his/her id.
	mutable clsUser *mpUser;
	mutable char *mpUserId;
};

#endif /* clsUserValidation_h */
