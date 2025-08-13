<?php


//---------------------------------------------------
//	Admin Notification of New Member Registrations
//--------------------------------------------------

function admin_notify_reg_title()
{
return <<<EOF
Notification of new member registration
EOF;
}

function admin_notify_reg()
{
return <<<EOF
The following person has submitted a new member registration: {name}

At: {site_name}

Your control panel URL: {control_panel_url}
EOF;
}
// END


//---------------------------------------------------
//	Admin Notification of New Comment
//--------------------------------------------------

function admin_notify_comment_title()
{
return <<<EOF
You have just receieved a comment
EOF;
}

function admin_notify_comment()
{
return <<<EOF
You have just receieved a comment for the following weblog:
{weblog_name}

The title of the entry is:
{entry_title}

Located at: 
{comment_url}
EOF;
}
// END


//---------------------------------------------------
//	Admin Notification of New Trackback
//--------------------------------------------------

function admin_notify_trackback_title()
{
return <<<EOF
You have just receieved a trackback
EOF;
}

function admin_notify_trackback()
{
return <<<EOF
You have just receieved a trackback for the following entry:
{entry_title}

Located at: 
{comment_url}

The trackback was sent from the following weblog:
{sending_weblog_name}

Entry Title:
{sending_entry_title}

Weblog URL:
{sending_weblog_url}
EOF;
}
// END


//---------------------------------------------------
//	Membership Activation Instructions
//--------------------------------------------------

function mbr_activation_instructions_title()
{
return <<<EOF
Enclosed is your activation code
EOF;
}

function mbr_activation_instructions()
{
return <<<EOF
Thank you for your new member registration.

To activate your new account, please visit the following URL:

{activation_url}

Thank You!

{site_name}

{site_url}
EOF;
}
// END


//---------------------------------------------------
//	Member Forgotten Password Instructions
//--------------------------------------------------

function forgot_password_instructions_title()
{
return <<<EOF
Login information
EOF;
}

function forgot_password_instructions()
{
return <<<EOF
{name},

To reset your password, please go to the following page:

{reset_url}

Your password will be automatically reset, and a new password will be emailed to you.

If you do not wish to reset your password, ignore this message. It will expire in 24 hours.

{site_name}
{site_url}
EOF;
}
// END



//---------------------------------------------------
//	Reset Password Notification
//--------------------------------------------------

function reset_password_notification_title()
{
return <<<EOF
New Login Information
EOF;
}

function reset_password_notification()
{
return <<<EOF
{name},

Here is your new login information:

Username: {username}
Password: {password}

{site_name}
{site_url}
EOF;
}
// END



//---------------------------------------------------
//	Validated Member Notification
//--------------------------------------------------

function validated_member_notify_title()
{
return <<<EOF
Your membership account has been activated
EOF;
}

function validated_member_notify()
{
return <<<EOF
{name},

Your membership account has been activated and is ready for use.

Thank You!

{site_name}
{site_url}
EOF;
}
// END




//---------------------------------------------------
//	Mailinglist Activation Instructions
//--------------------------------------------------

function mailinglist_activation_instructions_title()
{
return <<<EOF
Email Confirmation
EOF;
}

function mailinglist_activation_instructions()
{
return <<<EOF
Thank you for joining our mailing list!

Please click the link below to confirm your email.

If you do not want to be added to our list, ignore this email.

{activation_url}

Thank You!

{site_name}
EOF;
}
// END



//---------------------------------------------------
//	Comment Notification
//--------------------------------------------------

function comment_notification_title()
{
return <<<EOF
Someone just responded to your comment
EOF;
}

function comment_notification()
{
return <<<EOF
Someone just responded to the entry you subscribed to at:
{weblog_name}

The title of the entry is:
{entry_title}

You can see the comment at the following URL:
{comment_url}

To stop receiving notifications for this comment, click here:
{notification_removal_url}
EOF;
}
// END


?>