<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.0.3                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 05/02/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               default.php                      #
# File purpose            Default language file            #
# File created by         AzDG <support@azdg.com>          #
############################################################

define('C_HTML_DIR','ltr'); // HTML direction for this language
define('C_CHARSET', 'iso-8859-1'); // HTML charset for this language

### !!!!! Please read it: RULES for translate!!!!! ###
### 1. Be carefull in translate - don`t use ' { } characters
###    You can use them html-equivalent - &#39; &#123; &#125;
### 2. Don`t translate {some_number} templates - you can only replace it - 
###    {0},{1}... - is not number - it templates
###################################

$w=array(
'<font color=red size=3>*</font>', //0 - Symbol for requirement field
'Security error - #', //1
'This is email already in database. Please select another!', //2
'Bad first name. First name must be {0} - {1} chars', //3 - Don`t change {0} and {1} - See rule 2 !!!
'Bad Last name. Last name must be {0} - {1} chars', //4
'Bad Birthday', //5
'Bad password. Password must be {0} - {1} chars', //6
'Please select your gender', //7
'Please select seeking gender', //8
'Enter your type of relation', //9
'Please select your country', //10
'Incorrect or empty email', //11
'Incorrect webpage', //12
'Bad ICQ number', //13
'Bad AIM', //14
'Enter your Phone', //15
'Enter your city', //16
'Select your marital status', //17
'Please choose the answer about your children', //18
'Select your height', //19
'Select your weight', //20
'Select seeking height', //21
'Select seeking weight', //22
'Select your hair color', //23
'Select your eyes color', //24
'Select your ethnicity', //25
'Select your religion', //26
'Select seeking ethnicity', //27
'Select seeking religion', //28
'Select about smoking', //29
'Select about drinking', //30
'Select about your education', //31
'Enter about your job', //32
'Write seeking age', //33
'Select how did you find us', //34
'Please write your hobby', //35
'Bad hobby field. Hobby not may be bigger {0} symbols', //36
'Bad hobby wordsize. Hobby wordsize not may be bigger {0} symbols', //37
'Please write about you', //38
'Bad description field. Description not may be bigger {0} symbols', //39
'Bad description wordsize. Description wordsize not may be bigger {0} symbols', //40
'Your photo are require!', //41
'Congratulations! <br>Your activization code has been sended to your email. <br>You must confirm your registering from email!', //42 - Message after register if need confirm by email
'Confirm your registration', //43 - Confirm mail subject
'Thanks for registering in our site...
Please enter this link for confirm your register:

', //44 - Confirm message
'Thanks for Registering. Your profile will be approved in short time. Please visit some later...', //45 - Message after registering if admin allowing is needed
'Congratulations! <br>Your Profile has been added to database!<br><br>Your login id:', //46
'<br>Your password:', //47
'Please retype your password', //48
'The passwords are not identical', //49
'Register user', //50
'Your First name', //51
'chars', //52
'Your Last name', //53
'Password', //54
'Retype password', //55
'Birthday', //56
'Gender', //57
'The type of relation', //58
'Country', //59
'Email', //60
'Webpage', //61
'ICQ', //62
'AIM', //63
'Phone', //64
'City', //65
'Marital status', //66
'Children', //67
'Height', //68
'Weight', //69
'Hair color', //70
'Eye color', //71
'Ethnicity', //72
'Religion', //73
'Smoking', //74
'Drinking', //75
'Education', //76
'Job', //77
'Hobby', //78
'Describe yourself and the sort of person you are looking for as a potential partner.', //79
'Seeking for', //80
'Seeking ethnicity', //81
'Seeking religion', //82
'Seeking age', //83
'Seeking height', //84
'Seeking weight', //85
'How did you find us?', //86
'Photo', //87
'Home', //88
'Register', //89
'Members area', //90
'Search', //91
'Feedback', //92
'FAQ', //93
'Statistic', //94
'Members menu ID#', //95
'View messages', //96
'My bedroom', //97
'My profile', //98
'Change profile', //99
'Change password', //100
'Remove profile', //101
'Exit', //102
'Processing time:', //103
'sec.', //104
'Users online:', //105
'Quests online:', //106
'Powered by <a href="http://www.azdg.com" target="_blank" class="desc">AzDG</a>', //107 - Don`t change link - only for translate - read GPL!!!
'Only registered users can access to advanced search', //108
'Sorry, "Age From" must be smaller than "Age to"', //109
'Search don`t return any matches', //110
'None', //111 Picture available?
'Yes', //112 Picture available?
'Can`t connect to server<br>Your mysql login or mysql password is wrong.<br>Check it in config file', //113
'Can`t connect to server<br>Database don`t exist<br>Or Change Database name in config', //114
'Pages :', //115
'Search results', //116
'Total : ', //117 
'Username', //118
'Purposes', //119
'Age', //120
'Country', //121
'City', //122
'Last access', //123
'Register date', //124
'Advanced Search', //125
'User ID#', //126
'First Name', //127
'Last Name', //128
'Mark of the zodiac', //129
'Height', //130
'Weight', //131
'Gender', //132
'Type of relation', //133
'Marital status', //134
'Children', //135
'Hair color', //136
'Eyes color', //137
'Ethnicity', //138
'Religion', //139
'Smoker', //140
'Drinker', //141
'Education', //142
'Search users with', //143
'Webpage', //144
'ICQ', //145
'AIM', //146
'Phone', //147
'Registered in ', //148
'Sort results by', //149
'Results on page', //150
'Simple Search', //151
'Access closed for non members', //152
'Access closed for send bad profiles', //153
'User already in Bad profiles table', //154
'Thanks, User has been added to Bad profiles and will be checked by admin in short time', //155
'Access closed for use bedroom', //156
'User already in Your bedroom', //157
'Thanks, User has been successfully added to Your BedRoom', //158
'You profile has been succesfully added for admin check!', //159
'Your profile has been succesfully added to database', //160
'Profile activization error. May be it already active', //161
'FAQ database is empty', //162
'FAQ answer#', //163
'All field must be filled', //164
'Your message has been succesfully send', //165
'Please enter your subject', //166
'Please enter your message', //167
'Subject', //168
'Message', //169
'Send message', //170
'For members', //171
'Login ID', //172
'Forgot password', //173
'Recommend Us', //174
'Friend-{0} email', //175
'Today Birthdays', //176
'No birthdays', //177
'Welcome to our AzDGDating Site', //178 Welcome message header
'AzDGDatingLite - is a great way to find new friends or partners, for fun, dating and long term relationships. Meeting and socializing with people is both fun and safe. Common sense precautions should be taken however when arranging to meet anyone face to face for the first time.<br><br>You can also find new friends through our own privately hosted email system. This lets you communicate with other members to find out more about each other and develop a relationship.<br>', //179 Welcome message
'Last {0} registered users', //180
'Quick search', //181
'Adv. search', //182
'Photo of the day', //183
'Simple Statistic', //184
'Your ID must be numeric', //185
'Incorrect Login ID# or password', //186
'Access closed for send messages to email', //187
'Send message to email to user ID#', //188
'No users online', //189
'Recommend page unavailable', //190
'Greetings from {0}', //191 "Recommend Us" subject, {0} - username
'Hello from {0}!

How are you:)

Please visit this site - its fine:
{1}', //192 "Recommend Us" message, {0} - username, {1} - site url
'Please write correct friend#{0} email', //193
'Please enter your Name and E-mail', //194
'Your password from {0}', //195 Reming password email subject
'This is account deactive or don`t exist in database.<br>Please write to admin about this problem from feedback. Include your ID please.', //196
'Hello!

Your login ID#:{0}
Your Password:{1}

_________________________
{2}', //197 Remind password email message, Where {0} - ID, {1} - password, {2} - C_SNAME(sitename)
'Your password has been succesfully send to your email.', //198
'Please enter your ID', //199
'Send password', //200
'Access closed for send messages', //201
'Send message to user ID#', //202
'Notify me when user will read the message', //203
'No user in database', //204
'Statistic don`t available', //205
'Such active ID does not exist', //206
'Profile ID#', //207
'User First name', //208
'User Last name', //209
'Birthday', //210
'Email', //211
'Message from AzDGDating', //212 - Subject for email
'Job', //213
'Hobby', //214
'About', //215
'Popularity', //216
'Send email', //217
'Bad profile', //218
'Add to my bedroom', //219
'Either here was no file uploaded, <br>or the file you tried to upload was bigger than the {0} Kb limit. Your file is {1} Kb', //220
'The file you tried to upload was width bigger than the {0} px or height bigger than the {1} px limit.', //221
'The type of file that you tried to upload was incorrect (only jpg, gif and png is available). Your type - ', //222
'(Max. {0} Kb)', //223
'Statistic by Countries', //224
'You have`nt messages', //225
'Total messages - ', //226
'Num', //227 Number
'From', //228
'Date', //229
'Del', //230 Delete
'<sup>New</sup>', //231 New messages
'Delete selected messages', //232
'Message from - ', //233
'Reply', //234
'Hello, You writed {0}:\n\n_________________\n{1}\n\n_________________', //235 Reply to message {0} - date, {1} - message
'Your message has been readed', //236
'Your message:<br><br><span class=dat>{0}</span><br><br>has been readed by {1} [ID#{2}] in {3}', //237 {0} - message, {1} - Username, {2} - UserID, {3} - Date and Time
'{0} messages succesfully deleted!', //238
'Please enter old password', //239
'Please enter new password', //240
'Please retype new password', //241
'Change password', //242
'Old password', //243
'New password', //244
'Retype new password', //245
'You have`nt any user in bedroom', //246
'Date of addition', //247
'Delete selected users', //248
'Are you sure that you want delete own profile?<br>All your messages, pictures will be removed from database.', //249
'User with ID#={0} has been succesfully deleted from database', //250
'Your profile will be removed after admin secure check', //251
'{0} users succesfully removed from your bedroom!', //252
'Don`t identical passwords or password content incorrect chars', //253
'You have`nt access to change password', //254
'Incorrect old password. Please goback and retype it!', //255
'Password has been succesfully changed!', //256
'Don`t possible remove all photo', //257
'Your profile successfully changed', //258
' - Delete picture', //259
'Your session has been destroyed. You can close browser', //260
'Flag images not available', //261
'Languages', //262
'Enter', //263
'Login [3-16 chars [A-Za-z0-9_]]', //264
'Login', //265
'Your login must consist of 3-16 chars and only A-Za-z0-9_ chars is available', //266
'This is login already in database. Please select another!', //267
'Total users - {0}', //268
'The messages are not visible. You should be the privileged user see the messages.<br><br>You can purshase from <a href="'.C_URL.'/members/index.php?l=default&a=r" class=head>here</a>', //269 change l=default to l=this_language_name
'User type', //270
'Purshase date', //271
'Search results position', //272
'Price', //273
'month', //274
'Purshase Last date', //275
'Higher than', //276
'Purshase', //277
'Purshase with', //278
'PayPal', //279
'Thanks for your registration. Payment has been succesfully send and will be checked by admin in short time.', //280
'Incorrect error. Please try again, or contact with admin!', //281
'Send congratulation letter about privilegies activating', //282
'User type has successfully changed.', //283
'Email with congratulations has been send to user.', //284
'ZIP',// 285 Zip code
'Congratulations, 

Your status is changed to {0}. This privilegies will be available in next {1} month.

Now you can check your messages in your box.

__________________________________
{2}', //286 {0} - Ex:Gold member, {1} - month number, {2} - Sitename from config
'Congratulations', //287 Subject
'ZIP code must be numeric', //288
'Keywords', //289
'We are sorry, but the following error occurred:', //290
'', //291
'', //292
'', //293
'', //294
'', //295
'', //296
'', //297
'', //298
'' //299
); 
?>
