<?php

// Announcements
define ('STR_1', 'Announcements');
define ('STR_2', 'announcement');
define ('STR_3', 'announcements');
define ('STR_4', 'You must specify a valid subject for this announcement.');
define ('STR_5', 'You must specify valid text for this announcement.');
define ('STR_6', 'You must specify a valid username for this announcement.');
define ('STR_7', 'You must specify a valid announcement date.');
define ('STR_8', 'This announcement has no key field set.');
define ('STR_9', 'The announcement was successfully added');
define ('STR_10', 'You must be an administrator to add an announcement');

// Application
define ('STR_11', 'The username given could not be found');
define ('STR_12', 'SSApp::getItemTypeFromClassName received an invalid class name: "');
define ('STR_13', 'An attempt was made to modify a %s record during a session where there was no user logged in');
define ('STR_14', 'Unable to modify a %s with an ID of 0');
define ('STR_15', 'Unable to add a view record for an object of type %s with an ID of 0');

// Authoring Page
define ('STR_16', 'Creativity Room');

// Bookmarks
define ('STR_17', 'You must specify a valid subject type for this bookmark.');
define ('STR_18', 'You must specify a subject ID for this bookmark.');
define ('STR_19', 'You must specify a valid user ID for this bookmark.');
define ('STR_20', 'You must specify a valid bookmark date.');
define ('STR_21', 'You must specify a name for this bookmark.');
define ('STR_22', 'This view record has no key field set.');
define ('STR_23', 'The given bookmark with an ID of %s could not be found in the database.');
define ('STR_24', 'The bookmark was successfully changed');
define ('STR_25', 'The bookmark was successfully added');
define ('STR_26', 'You must be logged in to add a bookmark');
define ('STR_27', 'The target for the bookmark is invalid.  Are you sure the story, scene or fork still exists?');
define ('STR_28', 'Bookmarks');

// Classifications
define ('STR_29', 'A user must be specified for this classification.  This is a system error: please notify the webmaster of the problem.');
define ('STR_30', 'A subject_type must be specified for this classification.  This is a system error: please notify the webmaster of the problem.');
define ('STR_31', 'A subject_id must be specified for this classification.  This is a system error: please notify the webmaster of the problem.');
define ('STR_32', 'You must specify a classification.');
define ('STR_33', 'This classification has no key field set.');
define ('STR_34', 'Attempt to add the wrong class to a collection.  Expecting %s, but got %s');

// Config
define ('STR_35', 'Missing important database connection info.');

// General Object Handling
define ('STR_36', 'The scene could not be found so the media could not be retrieved');
define ('STR_37', 'The license could not be updated for this scene');
define ('STR_38', 'The license was changed successfully');
define ('STR_39', 'Only the user who originally created the content may change its license type');
define ('STR_40', 'Unsupported media uploaded.  StoryStream only supports image and flash files at this time.');
define ('STR_41', 'Although there was an error in setting the media file associated with this content, all other properties have been entered into the database successfully.');
define ('STR_42', 'Failed to upload file but other content properties were set successfully.');

// Events
define ('STR_43', 'Event Handler: An unhandled object type was added to the database');
define ('STR_44', 'Event Handler: An unhandled object type was removed from the database');
define ('STR_45', 'Event Handler: An unhandled object type was edited in the database');

// File uploading
define ('STR_46', 'Unable to set the data type in the database: %s');

// Forks
define ('STR_47', 'There is no owner specified for this fork.  Contact the administrator.');
define ('STR_48', 'You must specify a valid name for the fork.');
define ('STR_49', 'You must specify a valid status for the fork.');
define ('STR_50', 'This fork has no key field set.');
define ('STR_51', 'The story given could not be found so the fork could not be created.');
define ('STR_52', 'UNKNOWN STATUS CODE');
define ('STR_53', 'The story given could not be found so the fork could not be created.');
define ('STR_54', 'You cannot add a fork to this scene.  This might happen if the scene has been deleted or is in a draft state');
define ('STR_55', 'This story cannot be added to at this time');
define ('STR_56', 'The source story could not be found');
define ('STR_57', 'The story associated with the fork could not be found.');
define ('STR_58', 'You cannot add another fork to this story because the author has limited the total number of forks in a single stream to %d.');
define ('STR_59', 'You cannot add a fork to a scene that is the last scene in the story');
define ('STR_60', 'When editing a fork, a valid unique story ID must be given.');
define ('STR_61', 'An attempt was made to load an invalid story object');
define ('STR_62', 'An attempt was made to view an invalid fork object');
define ('STR_63', 'You cannot delete this fork because the associated story is "ACTIVE" and is set to have at least one beginning fork.  You must first deactive the story or add another usable fork.');
define ('STR_64', 'The fork called "%s" has been successfully marked as "ACTIVE"');
define ('STR_65', 'You cannot delete a fork that has dependant scenes. First delete the hierarchy below the fork then delete the fork');
define ('STR_66', 'Forks');
define ('STR_67', 'The fork was %s successfully');
define ('STR_68', 'edited');
define ('STR_69', 'added');

// Groups
define ('STR_70', 'groups');
define ('STR_71', 'group');
define ('STR_72', 'You must specify a valid name for the group.');
define ('STR_73', 'You must specify a valid username for the group.');
define ('STR_74', 'You must specify a valid date for the group.');
define ('STR_75', 'You must specify a valid status for the group.');
define ('STR_76', 'This fork has no key field set.');
define ('STR_77', 'Active');
define ('STR_78', 'Frozen');
define ('STR_79', 'UNKNOWN STATUS CODE');
define ('STR_80', 'Administrator');
define ('STR_81', 'Member');
define ('STR_82', 'Invited');
define ('STR_83', 'Banned');
define ('STR_84', 'UNKNOWN USER TYPE CODE');
define ('STR_85', 'You must enter at least one user in the edit box before hitting the Invite Users button');
define ('STR_86', 'User "%s" could not be invited');
define ('STR_87', 'User "%s" is not a member of StoryStream.');
define ('STR_88', 'There was a problem with the way you entered the list of usernames.  Remember to use commas to separate the individual usernames.');
define ('STR_89', 'User could not be added to the group because the group with ID %d could not be found.');
define ('STR_90', 'There was a critical internal error and StoryStream could not submit the changes you specified in the group edit form');
define ('STR_91', 'An attempt was made to view an invalid group object');
define ('STR_92', 'A user could not be added to the group because the given user is already a member of the group');
define ('STR_93', 'Not able to determine if the user is already a member of the group');
define ('STR_94', 'Unable to add %s to the group');
define ('STR_95', 'Not able to uninvite the user due to a database error.');
define ('STR_96', 'Not able to set the user\'s type in the group');
define ('STR_97', 'Unable to find user "%s" in the database"');
define ('STR_98', 'Not able to load the list of users in group "%s"');


// Collector
define ('STR_99', 'An invalid object type was given to the item collector');

// Modification
define ('STR_100', 'Home');
define ('STR_101', 'You must specify a valid target type for this modification record.');
define ('STR_102', 'You must specify a target ID for this modification record.');
define ('STR_103', 'You must specify a valid user ID for this modification record.');
define ('STR_104', 'You must specify a valid modification date.');
define ('STR_105', 'You must specify a valid modification action code.');
define ('STR_106', 'This modification record has no key field set.');

// Notifier
define ('STR_107', 'StoryStream Story Added: ');
define ('STR_108', 'StoryStream Scene Added: ');
define ('STR_109', 'StoryStream Fork Added: ');
define ('STR_110', 'StoryStream Announcement: ');

// Object
define ('STR_111', 'objects');
define ('STR_112', 'object');
define ('STR_113', 'A property was retrieved that does not exist in the object.');
define ('STR_114', 'Property');
define ('STR_115', 'Object');
define ('STR_116', 'Call Stack');

// Page Base
define ('STR_117', "You do not have permission to see this page");

define ('STR_118', 'There was a problem in setting the license information for the content');
define ('STR_119', 'The license could not be updated because the content could not be found');
define ('STR_120', 'ratings');
define ('STR_121', 'rating');
define ('STR_123', 'There is no owner specified for this rating.  Contact the administrator.');
define ('STR_124', 'You must specify a subject type for this rating.');
define ('STR_125', 'You must specify a subject id for this rating.');
define ('STR_126', 'You must specify a rating value.');
define ('STR_127', 'This rating has no key field set.');
define ('STR_128', 'You must be logged in to rate a story, scene or stream');
define ('STR_129', "The object being rated could not be resolved.");
define ('STR_130', 'An attempt was made to view an invalid rating');

// Reading Page
define ('STR_131', 'Reading Room');
define ('STR_132', 'Read');

// Scenes
define ('STR_133', 'The object specified could not be found');
define ('STR_134', 'You must be logged in to read reviews');
define ('STR_135', 'The reply could not be posted to the discussion board.  Contact the webmaster.');
define ('STR_136', 'You must enter a body to the comment.');
define ('STR_137', 'The %s does not have a discussion board topic associated with it.  Contact the webmaster.');
define ('STR_138', 'scenes');
define ('STR_139', 'scene');
define ('STR_140', 'You must specify a valid name for the scene.');
define ('STR_141', 'The scene doesn\'t have a valid source fork');
define ('STR_142', 'You must specify a valid status for the scene.');
define ('STR_143', 'This scene has no key field set.');
define ('STR_144', 'There is no associated story ID for this scene');
define ('STR_145', 'This story cannot be added to at this time');
define ('STR_146', 'The source story could not be found');
define ('STR_147', 'The source fork could not be found in the database.');
define ('STR_148', 'When editing a story, a valid unique story ID must be given.');
define ('STR_149', 'Draft');
define ('STR_150', 'Active');
define ('STR_151', 'Deleted');
define ('STR_152', 'UNKNOWN STATUS CODE');
define ('STR_153', 'Failed to find the source for this scene (%s - %d)');
define ('STR_154', 'An attempt was made to view an invalid scene object');
define ('STR_155', 'The scene called "%s" has been successfully marked as "DRAFT"');
define ('STR_156', 'You cannot delete a scene that has dependant forks. First delete the hierarchy below the scene then delete the scene');
define ('STR_157', 'Unable to determine if the scene called "%s" is a leaf scene due to a database error: %s');
define ('STR_158', 'You cannot read a scene that has been deleted');
define ('STR_159', 'Unable to find a discussion topic associated with this scene');
define ('STR_160', 'An attempt was made to view an invalid scene object');
define ('STR_161', 'Unable to associate a phpBB topic with the newly created scene.  Discussions for the scene may not work correctly');
define ('STR_162', 'Unable to create a phpBB topic for the newly created scene.  Discussions for the scene will not work correctly');
define ('STR_163', 'Scene');
define ('STR_164', 'The story with ID %d could not be found');
define ('STR_165', 'The scene could not be edited because a scene with an ID of %d does  not exist');
define ('STR_166', 'The scene was %s successfully');

// Stories
define ('STR_167', 'stories');
define ('STR_168', 'story');
define ('STR_169', 'You must specify a valid name for the story.');
define ('STR_170', 'You must specify a valid type for the story.');
define ('STR_171', 'You must specify a valid status for the story.');
define ('STR_172', 'This story has no key field set.');
define ('STR_173', 'Exactly One Beginning Fork');
define ('STR_174', 'Exactly One Beginning and Ending Fork');
define ('STR_175', 'No Forks Required');
define ('STR_176', 'You cannot change the type of story if the story is active.  Make it a draft then try again.');
define ('STR_177', "You cannot edit a deleted story.");
define ('STR_178', 'When editing a story, a valid unique Story ID must be given.');
define ('STR_179', 'Active');
define ('STR_180', 'Draft');
define ('STR_181', 'Deleted');
define ('STR_182', 'UNKNOWN STATUS CODE');
define ('STR_183', 'One Beginning Fork');
define ('STR_184', 'One Beginning Fork with an End Fork');
define ('STR_185', 'No Forks Rrequired');
define ('STR_186', 'UNKNOWN TYPE');
define ('STR_187', 'An attempt was made to view an invalid story object');
define ('STR_188', 'The story called "%s" has been successfully marked as "DRAFT"');
define ('STR_189', 'You cannot delete a story that has dependant forks. First delete the hierarchy below the story then delete the story');
define ('STR_190', 'You cannot activate this story because you must have at least one active beginning fork to begin the stream.');
define ('STR_191', 'You cannot activate this story because you must have at least one active ending fork to end the stream');
define ('STR_192', 'You cannot activate this story because the following leaf scenes do not have an end fork associated with them:');
define ('STR_193', 'You cannot activate a story that does not have the "DRAFT" status.');
define ('STR_194', 'You cannot make set a story\'s status to "DRAFT" if it\'s already set to that status.');
define ('STR_195', 'This story has been deleted and is no longer available for reading');
define ('STR_196', 'Unable to find a discussion topic associated with this story');
define ('STR_197', 'An attempt was made to view an invalid story object');
define ('STR_198', 'Unable to associate a phpBB topic with the newly created story.  Discussions for the story may not work correctly');
define ('STR_199', 'Unable to create a phpBB topic for the newly created story.  Discussions for the story will not work correctly');
define ('STR_200', 'Story');
define ('STR_201', 'The story was %s successfully');

// Streams
define ('STR_202', 'Unable to find our way back to the beginning of the story because the scene given did not have a source fork.');
define ('STR_203', 'Unable to find our way back to the beginning of the story because the scene given could not be found');
define ('STR_204', 'Unable to find our way back to the beginning of the story because the fork called %s (%d)did not have a valid source scene');
define ('STR_205', 'Unable to get the story object because the fork called "%s" (%d) did not have a valid story ID');
define ('STR_206', 'Unable to find our way back to the beginning of the story because the fork given could not be found');
define ('STR_207', 'A book could not be generated because the story ID could not be found.');
define ('STR_208', 'An attempt was made to load an object from the database but no unique ID was given: %s');
define ('STR_209', 'The %s was successfully deleted');
define ('STR_210', 'The %s called "%s" was successfully deleted');
define ('STR_211', 'An attempt was made to delete an object but no unique ID was given');
define ('STR_212', 'The search for a rating of this %s failed.');
define ('STR_213', 'The search for a classification of this %s failed.');

// Users
define ('STR_214', 'You must specify a valid username.');
define ('STR_215', 'You must specify a valid password.');
define ('STR_216', 'You must specify a valid user type.');
define ('STR_217', 'You must specify a valid status for the scene.');
define ('STR_218', 'The e-mail address given does not appear to be valid.');
define ('STR_219', 'There cannot be any spaces in the login name.');
define ('STR_220', 'There must be at least one character in the user name');
define ('STR_221', 'There are illegal characters in the name (legal characters are: a-z, A-Z, 0-9, -, _ )');
define ('STR_222', 'There must be at leat 5 characters in the username.');
define ('STR_223', 'There must be no more than 15 characters in the username.');
define ('STR_224', 'The name you have chosen is reserved.');
define ('STR_225', 'The name you have chosen is reserved.');
define ('STR_226', 'The password must be greater than six characters');
define ('STR_227', 'The password must contain at least one digit (0-9)');
define ('STR_228', 'There is already a user registered with that e-mail address. Please note that you can only have one StoryStream account.');
define ('STR_229', 'A user with that username already exists. Please select a new name');
define ('STR_230', 'A confirmation e-mail has been sent to your e-mail address.  Click on the link in that e-mail to confirm your account.');
define ('STR_231', 'Invalid hash given so the user could not be confirmed');
define ('STR_232', 'phpBB login failed.  Contact the administrator');
define ('STR_233', 'You do not have a valid phpBB user ID set in your storystream user record.  Contact the administrator');
define ('STR_234', 'Your account has not been confirmed.  Please check your e-mail for instructions on how to confirm.');
define ('STR_235', 'Your account has been frozen.  Contact the website administrator for more on why this has happened.');
define ('STR_236', 'Your account is currently locked because it has been so long since you have logged in.  Contact the website administrator for more on why this has happened.');
define ('STR_237', 'Your account is no longer available.  You must re-register.');
define ('STR_238', 'Your username and password do not match.  Please try logging in again.');
define ('STR_239', "Password encryption failed because an empty password was given");
define ('STR_240', 'The two passwords you entered must be identical.');
define ('STR_241', 'You must enter a password for your username');
define ('STR_242', 'Your Mojo could not be stored in the database due to the following error: %s');

// User Page
define ('STR_243', 'Member Lounge');
define ('STR_244', 'Account Confirmation');
define ('STR_245', 'Your email address has been confirmed.  Welcome to StoryStream!  You may now login');
define ('STR_246', 'The specified user (%s) could not be found');
define ('STR_247', 'User settings for "%s" were changed successfully');

// PHPBB 
define ('STR_248', 'You must specify a valid target type for this view record.');
define ('STR_249', 'You must specify a target ID for this view record.');
define ('STR_250', 'You must specify a valid view date.');
define ('STR_251', 'This view record has no key field set.');
define ('STR_252', 'phpBB was unable to determine the next user ID so the user could not be added to the database: %s');
define ('STR_253', 'phpBB was unable to determine the next user ID so the user could not be added to the database');
define ('STR_254', 'phpBB was unable to add the user info to the database: %s');
define ('STR_255', 'phpBB was unable to add the group info to the database');
define ('STR_256', 'phpBB was unable to add the user/group info to the database');
define ('STR_257', 'The story could not be found so a phpBB topic could not be created');
define ('STR_258', '[No description given]');
define ('STR_259', 'The story could not be found so a phpBB topic could not be created');
define ('STR_260', 'There must be a logged in user to create a topic for a story.');
define ('STR_261', 'You must be logged in to reply to a topic.');

// Announcement Form
define ('STR_262', 'Add Announcement');
define ('STR_263', 'When an announcement is added to the database, an email wiil be sent out
				to all users who requested them.  You can view a history of announcements
				in the announcement area of the Member Lounge.');
define ('STR_264', '(required fields are in <span class="formLabelRequired">red</span>)');
define ('STR_265', 'Subject');
define ('STR_266', 'Announcement');
define ('STR_267', 'Add Announcement');

// Fork Form
define ('STR_268', 'Edit Fork');
define ('STR_269', 'Create Fork');
define ('STR_270', 'Use this form to change the properties of this fork.');
define ('STR_271', 'Adds a new fork to the scene.  A fork is necessary to tie a 
				scene to another scene and help to guide readers when making decisions about what to read next.');
define ('STR_272', 'Note: This fork is the first in a story');
define ('STR_273', 'Note: This fork will be used as the last fork in the story meaning all streams will lead to this fork.');
define ('STR_274', 'Previous Scene Information:');
define ('STR_275', 'Scene Name');
define ('STR_276', 'Scene Description');
define ('STR_277', 'Fork Properties');
define ('STR_278', 'Name');
define ('STR_279', 'Description');
define ('STR_280', 'Status');
define ('STR_281', 'Edit Fork');
define ('STR_282', 'Add Fork');
define ('STR_283', 'in Story');

// Scene Form
define ('STR_284', 'Edit Scene');
define ('STR_285', 'Add Scene');
define ('STR_286', 'Use this form to change the properties of this scene.');
define ('STR_287', 'Adds a new scene to the database.  Scenes form the content of a story and can be connected together via forks.');
define ('STR_288', 'Source Fork Information');
define ('STR_289', 'Stream');
define ('STR_290', 'Fork Description');
define ('STR_291', 'Scene Properties');
define ('STR_292', 'Title');
define ('STR_293', 'Description');
define ('STR_294', 'Status');
define ('STR_295', 'When a scene is set as "Active" it will be made available publically.  You can leave it as a "Draft" 
					until you feel it\'s ready to be seen by others.');
define ('STR_296', 'End Fork');
define ('STR_297', 'Media File');
define ('STR_298', 'Scene Text');

// Story Form
define ('STR_299', 'Edit Story');
define ('STR_300', 'Add Story');
define ('STR_301', 'Use this form to change the properties of this story. ');
define ('STR_302', 'Adds a new story to the database.  Once your story is added, you must "Activate" it before it can be published.');
define ('STR_303', 'Title');
define ('STR_304', 'Synopsis');
define ('STR_305', 'Media File');
define ('STR_306', 'First Scene');
define ('STR_307', 'Parent Group');
define ('STR_308', 'None');
define ('STR_309', 'Type');
define ('STR_310', 'The type field sets up the framework for the stream.  The choices are "One beginning fork" (which you create), a
				"Beginning fork and an ending fork" (allowing you to dictate how the story should begin <em>and</em> end and
				finally "No Forks Required" which creates a sort of free-for-all story.  When editing a story, you can only change this value to "No Forks Required" if
				 the story is currently Active.');
				 
// Fork View				
define ('STR_311', 'Undelete Fork');
define ('STR_312', 'Permanently Delete Fork');
define ('STR_313', 'Are you sure you want to PERMANENTLY delete this fork?');
define ('STR_314', 'Delete Fork');
define ('STR_315', 'Stream');
define ('STR_316', 'Story');
define ('STR_317', 'From&nbsp;Scene');
define ('STR_318', 'Forks from the story\'s beginning');
define ('STR_319', 'Last Fork In the Story');
define ('STR_320', 'To&nbsp;scenes');
define ('STR_321', 'No scenes have been written for this fork yet.');

// Scene View
define ('STR_322', 'Undelete Scene');
define ('STR_323', 'Permanently Delete Scene');
define ('STR_324', 'Are you sure you want to PERMANENTLY delete this scene?');
define ('STR_325', 'Add Fork');
define ('STR_326', 'Delete Scene');
define ('STR_327', 'Read Scene');
define ('STR_328', 'Add Bookmark');
define ('STR_329', 'Creative Commons License');
define ('STR_330', 'Incoming Fork');
define ('STR_331', 'Outgoing Forks');
define ('STR_332', 'This is the final scene in the story');
define ('STR_333', 'End Fork');
define ('STR_334', 'None Specified');
define ('STR_335', 'There are no outgoing forks from this scene yet.');
define ('STR_336', 'Media (Type: ');
define ('STR_337', 'Scene Text');

// Story View
define ('STR_338', 'Undelete Story');
define ('STR_339', 'Permanently Delete Story');
define ('STR_340', 'Activate Story');
define ('STR_341', 'Are you sure you want to PERMANENTLY delete this story?');
define ('STR_342', 'Make Draft');
define ('STR_343', 'Add Beginning Fork');
define ('STR_344', 'Add End Fork');
define ('STR_345', 'Delete Story');
define ('STR_346', 'Read Story');
define ('STR_347', 'Synopsis');
define ('STR_348', 'Creative Commons License');
define ('STR_349', 'First Scene');
define ('STR_350', 'Status');
define ('STR_351', 'There are no forks in this story yet');
define ('STR_352', 'Ending Forks');


define ('STR_353', 'Deleted');
define ('STR_354', 'You can (optionally) submit a media file such as an image, flash animation or 
music file that will be displayed/heard	when the content is viewed by a reader.');
define ('STR_355', 'Check this box if you wish to remove the media from the database entirely.  Leaving the 
	file selection field empty and NOT checking this box will leave the current media file as is.');
define ('STR_356', 'Click here to view in another window');
define ('STR_357', 'Clear Media File');
define ('STR_358', 'Home');
define ('STR_359', 'Creativity&nbsp;Room');
define ('STR_360', 'Reading&nbsp;Room');
define ('STR_361', 'Member&nbsp;Lounge');
define ('STR_362', 'Join Now!');
define ('STR_363', 'Discuss');
define ('STR_364', 'This user has made no contributions.');
define ('STR_365', 'My Groups');
define ('STR_366', 'None');
define ('STR_367', 'Mebership in Other Groups');
define ('STR_368', 'Invitations Pending In These Groups');
define ('STR_369', 'Accept');
define ('STR_370', 'Decline');
define ('STR_371', 'Avg.');
define ('STR_372', 'Story');
define ('STR_373', 'Author');
define ('STR_374', 'There are no rated stories in the database');
define ('STR_375', '#');
define ('STR_376', 'None of the stories in the database have been seen yet.');
define ('STR_377', 'No bookmarks saved');
define ('STR_378', 'Select a Bookmark');
define ('STR_379', 'My&nbsp;Contributions');
define ('STR_380', 'My Recent Activity');
define ('STR_381', 'Object Name');
define ('STR_382', 'Date&nbsp;of&nbsp;Change');
define ('STR_383', 'Action&nbsp;Type');
define ('STR_384', 'There has been no activity in your account');
define ('STR_385', 'Add');
define ('STR_386', 'Edit');
define ('STR_387', 'You have not written any stories.');
define ('STR_388', 'Add a story now');
define ('STR_389', 'deleted');
define ('STR_390', 'draft');
define ('STR_391', 'None');
define ('STR_392', 'Nothing has been posted in the forums');
define ('STR_393', 'Please Note');
define ('STR_394', 'This scene is licensed by the author under a');
define ('STR_395', 'show all >>');
define ('STR_396', 'No stories classified as ');
define ('STR_397', 'Mojo');
define ('STR_398', 'Active&nbsp;Stories');
define ('STR_399', 'Selfless&nbsp;Scene&nbsp;Contributions');
define ('STR_400', 'Selfish&nbsp;Scene&nbsp;Contributions');
define ('STR_401', 'Selfless&nbsp;Forks&nbsp;Contributions');
define ('STR_402', 'Selfish&nbsp;Fork&nbsp;Contributions');
define ('STR_403', 'Average&nbsp;Story&nbsp;Rating');
define ('STR_404', 'Average&nbsp;Scene&nbsp;Rating');
define ('STR_405', 'Number&nbsp;of&nbsp;Reviews&nbsp;Posted');
define ('STR_406', 'Number&nbsp;of&nbsp;Classifications&nbsp;Posted');
define ('STR_407', 'B'); // For Bold
define ('STR_408', 'I'); // For Italics
define ('STR_409', 'U'); // For Underline
define ('STR_410', 'S'); // For Strikethrough
define ('STR_411', 'Default');
define ('STR_412', 'Select a Fork');
define ('STR_413', 'Fork Link');
define ('STR_414', 'Use the toolbar above to add formatting to your text.  Highlight the word(s) you want to format and choose an item from the toolbar.  The correct formatting tags will be added in the appropriate place.');
define ('STR_415', 'Click here for more information about');
define ('STR_416', 'Use this form to edit a gruop\'s settings.');
define ('STR_417', 'Use this form to create a new group.  Only the creator of a group
				can subsequently edit its properties and add/remove users.  You can
				also send out invitations to join the group once it has been created.');
define ('STR_418', 'Name');
define ('STR_419', 'Description');
define ('STR_420', 'Allow non-members to view content');
define ('STR_421', 'Status');
define ('STR_422', 'Edit Group');
define ('STR_423', 'Add Group');
define ('STR_424', 'user');
define ('STR_425', 'pass');
define ('STR_426', 'login');
define ('STR_427', 'register');
define ('STR_428', 'Edit Member Settings');
define ('STR_429', 'Join StoryStream');
define ('STR_430', 'Use this form to edit your member settings.');
define ('STR_431', 'Once you fill out this form, you will receive an email asking you to confirm 
				the membership application.  Only after you click on the link in the email will
				you be able to login to StoryStream.');
define ('STR_432', '(required fields are in <span class="formLabelRequired">red</span>)');
define ('STR_433', 'User Properties');
define ('STR_434', 'User Name');
define ('STR_435', 'First Name');
define ('STR_436', 'Last Name');
define ('STR_437', 'Email');
define ('STR_438', 'User Notifications');
define ('STR_439', 'Notify me when a new story is added to the project.');
define ('STR_440', 'Notify me when a new scene or fork is added to a story.');
define ('STR_441', 'Notify me of StoryStream announcements or updates');
define ('STR_442', 'Password');
define ('STR_443', 'Leave the password fields blank if you do not wish to change them.');
define ('STR_444', 'Confirm Password');
define ('STR_445', 'Edit Member Settings');
define ('STR_446', 'Become a Member');
define ('STR_447', 'Member Settings');
define ('STR_448', 'View Announcements');
define ('STR_449', 'StoryStream Project Announcements');
define ('STR_450', 'No announcements have been created yet.');
define ('STR_451', 'Click here to add one');
define ('STR_452', 'Delete Announcement');
define ('STR_453', 'Created on');
define ('STR_454', 'Non-members are allowed to read stories in this group');
define ('STR_455', 'Non-members are NOT allowed to read stories in this group');
define ('STR_456', 'Group Stories');
define ('STR_457', 'There are no stories in this group');
define ('STR_458', 'Members');
define ('STR_459', 'Username');
define ('STR_460', 'Date (invited/joined)');
define ('STR_461', 'Type');
define ('STR_462', 'Action');
define ('STR_463', 'There are no members in this group <b>(which is impossible so something is wrong)</b>');
define ('STR_464', 'Invite Users');
define ('STR_465', 'Users');
define ('STR_466', 'Enter the usernames of users you would like to invite separated by commas.');
define ('STR_467', 'Delete Group');
define ('STR_468', 'Edit Bookmark');
define ('STR_469', 'Use this form to change the properties of a bookmark');
define ('STR_470', 'Adds a new bookmark to the database.  The name can be anything you like and you can use the notes
				section as an annotation area for a given scene or story.');
define ('STR_471', 'Bookmark');
define ('STR_472', 'Name');
define ('STR_473', 'Notes');
define ('STR_474', 'Delete Bookmark');
define ('STR_475', 'This chapter is protected by a');
define ('STR_476', 'Print Story');
define ('STR_477', 'by');
define ('STR_478', 'Add a Scene');
define ('STR_479', 'Edit Fork');
define ('STR_480', 'The stream so far');
define ('STR_481', 'This fork leads to the following scenes');
define ('STR_482', 'Reviews for');
define ('STR_483', 'Read Book');
define ('STR_484', 'Add a Fork');
define ('STR_485', 'Edit Scene');
define ('STR_486', 'The following forks branch off of this scene');
define ('STR_487', 'There are no outgoing forks in this scene yet.');
define ('STR_488', 'Login to rate this scene and see how others felt about it');
define ('STR_489', 'Edit Story');
define ('STR_490', 'Choose Next Scene');
define ('STR_491', 'There are no beginning forks in this story yet.');
define ('STR_492', 'Login to rate this story and see how others felt about it.');
define ('STR_493', 'Your Rating');
define ('STR_494', 'Your Classification');
define ('STR_495', 'Post Classification');
define ('STR_496', 'Clear Classification');
define ('STR_497', 'rating');
define ('STR_498', 'View all reviews');
define ('STR_499', 'Classifications');
define ('STR_500', 'classifications');
define ('STR_501', 'View Bookmark');
define ('STR_502', 'Delete');
define ('STR_503', 'Go To');
define ('STR_504', 'Licensed under the GPL');
define ('STR_505', 'FAQ');
define ('STR_506', 'Browse');
define ('STR_507', 'Contribute');
define ('STR_508', 'Settings');
define ('STR_509', 'Join');
define ('STR_510', 'Featured Stories');
define ('STR_511', 'There are no rated stories in the database');
define ('STR_512', 'There are no featured members at this time');
define ('STR_513', 'Error');
define ('STR_514', 'You are logged in as');
define ('STR_515', 'Logout');
define ('STR_516', 'You are not logged in');
define ('STR_517', 'You will be redirected to your original page in');
define ('STR_518', 'seconds');
define ('STR_519', 'Popup');
define ('STR_520', 'The story called "%s" was successfully activated');
define ('STR_521', 'Starting Forks');

// ------
define ('STR_522', 'Delete');
define ('STR_523', 'Object has been deleted');

?>