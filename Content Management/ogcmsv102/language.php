<?php
/*-----------------------------------------------------------------------------+
|                            OG CMS v1.02                         |
+------------------------------------------------------------------------------+
| This file is component of OG CMS :: web management system                    |
|                                                                              |
|                    Please, send any comments, suggestions and bug reports to |
|                                                      olegu@soemme.no         |
| Original Author: Vidar Løvbrekke Sømme                                       |
| Author email   : olegu@soemme.no                                             |
| Project website: http://www.soemme.no/                                       |
| Licence Type   : FREE                                                        |
+------------------------------------------------------------------------------+ */
//defines all prompts and texts in the sccript

//start
$lang[delete_prompt] = 'really delete post?';
$lang[delete_error] = 'something whent really wrong';

$lang[edit_error] = 'Terrible fault, please evacuate';


$lang[static_missing] = 'no static articles present';
$lang[static_edit_link] ='edit';
$lang[static_delete_link] = 'delete';
$lang[static_form_header] ='To make a new static page, please
                          insert name and filename and click "add static page".';
$lang[static_name_prompt] = 'name:';
$lang[static_filenanme_prompt] = 'filename:';
$lang[static_upload_button] = 'Upload Page';
$lang[static_add_button] = 'add static page';
$lang[static_add_success] = 'New static page Added';
$lang[static_edit_button] = 'Edit Static Page';
$lang[static_edit_success] = 'Static page editet';
$lang[static_delete_sucess] = 'Static page deleted';
$lang[static_warning] = 'Really delete static page from database?';


$lang[system_t_img_prompt] = 'Top Image file name:';
$lang[system_adm_t_img_prompt] = 'Admin Top Image file name:';
$lang[system_small_img_size_prompt] = 'Small images size properties:';
$lang[system_max_w] = 'Max W:';
$lang[system_max_h] = 'Max H:';
$lang[system_img_size_prompt] = 'Main images size properties:';
$lang[system_post_count] = 'Number of posts to show per page:';
$lang[system_submit_button] = 'ok';
$lang[system_update_success] = 'System Variables (not password) Update performed OK';
$lang[system_old_pw_error] = 'old password did not match';
$lang[system_new_pw_error] = 'new passwords did not match';
$lang[system_pw_update_success] = 'password updated';
$lang[system_pw_form_header] = 'Change password:';
$lang[system_pw_old] = 'old password:';
$lang[system_pw_new] = 'new password:';
$lang[system_pw_repeat_new] = 'repeat new password:';
$lang[system_error] = 'terrible fault, please evacuate';
$lang[system_password_update_button] = 'Update Password';
$lang[system_site_title] = 'site title:';
$lang[system_webmaster_email] = 'webmaster e-mail adress:';
$lang[system_footer] = 'footer strip text:';
$lang[system_rh_options] = 'options for right coloumn';
$lang[system_rh_enable] = 'enable right coloumn:';
$lang[system_top_dl] = 'enable top five downloads listing:';
$lang[system_last_commented] = 'enable last five comments:';


$lang[zone_edit_link] = 'edit';
$lang[zone_delete_link] = 'delete';
$lang[zone_add_form_header] = 'To make a new category, please type name, and click "add category".';
$lang[zone_add_button] = 'add category';
$lang[zone_add_success] = 'New category Added';
$lang[zone_edit_button] = 'Edit category';
$lang[zone_edit_success] = 'category editet';
$lang[zone_delete_success] = 'category deleted';
$lang[zone_warning] = 'Really delete category?  All posts in this category will allso be deleted!,<br>
(and by the way, there is NO undo), so are you sure...?';


$lang[comment_name_prompt] = 'Your name:';
$lang[comment_mail_prompt] = 'Your e-mail:';
$lang[comment_prompt] = 'Your comment:';
$lang[comment_textfield_fill] = 'Your comment here';
$lang[comment_add_button] = 'Add Your Comment';
$lang[comment_field_too_long] = 'to long, cannot be longer than';
$lang[comment_characters] = 'characters';
$lang[comment_field_too_short] = 'to short, has to be at least';
$lang[comment_edit_button] = 'Edit Comment';
$lang[comment_delete_confirm_button] = 'Yes, delete it';
$lang[comment_delete_success] = 'Comment deleted';
$lang[comment_continue_button] = 'Continue';
$lang[comment_name] = 'name';
$lang[comment_mail] = 'mail';
$lang[comment] = 'Comment';
$lang[comment_add_success] = 'Comment successfully added,';
$lang[comment_click_here] = 'click here';
$lang[comment_go_back] = 'to go back';
$lang[comment_edit_success] = 'Comment successfully edited,';
$lang[comment_error] = 'error, during comment processing....';


$lang[nav_home] = 'home';
$lang[nav_downloads] = 'downloads';
$lang[nav_add_post] = 'add post';
$lang[nav_zones] = 'categories';
$lang[nav_statics] = 'static pages';
$lang[nav_system] = 'system';
$lang[nav_logout] = 'logout';


$lang[login_prompt] = 'Password please:';
$lang[login_ok_button] = 'ok';
$lang[login] = 'LogIn';
$lang[login_error] = 'Wrong password, please try again';


$lang[logout_success] = 'Logout performed OK';
$lang[logout_error] = 'something went wrong during logout';


$lang[post_no] = 'no';
$lang[post_yes] = 'yes';
$lang[post_title] = 'Title';
$lang[post_intro] = 'Introduction';
$lang[post_main_text_prompt] = 'Please insert text:';
$lang[post_file_name_prompt] = 'insert file name:';
$lang[post_upload_file] = 'Upload File';
$lang[post_image_name_prompt] = 'insert image file name:';
$lang[post_upload_image] = 'Upload Image';
$lang[post_zone] = 'Category / Zone';
$lang[post_allow_comments] = 'Allow Comments?';
$lang[post_submit] = 'Submit';
$lang[post_reset] = 'reset';
$lang[post_error] = 'something  wrong';
$lang[post_add_success] = 'successfully added new post';
$lang[post_error_intro] = 'Introduction has to be at least 5 characters long, and no more than 250';
$lang[post_error_title] = 'Title has to be at least 2 chacarcters long, and no more than 50';
$lang[post_edit_button] = 'Edit Post';
$lang[post_edit_success] = 'Post edited successfully';
$lang[post_delete_confirm] = 'Delete';
$lang[post_delete_cancel] = 'No, let it be';
$lang[post_delete_success] = 'Post Deleted';
$lang[post_continue_button] = 'Continue';
$lang[post_download_prompt] = 'download file:';
$lang[post_downloaded] = 'Downloaded';
$lang[post_downloads_title] = 'Downloads';
$lang[post_times_since] = 'times, last download:';
$lang[post_delete_link] = 'delete';
$lang[post_edit_link] = 'edit';
$lang[post_showing_posts] = 'Showing Posts';
$lang[post_through] = 'through';
$lang[post_of] = 'of';
$lang[post_previous] = 'Previous';
$lang[post_page] = 'Page';
$lang[post_next] = 'Next';
$lang[post_no_comments] = 'No comments yet';
$lang[post_comment_posted_by] = 'Posted by';
$lang[post_comment_posted_at] = 'at';
$lang[post_comment_author_mail] = 'Author e-mail adress:';
$lang[post_comment_edit_link] = 'edit comment';
$lang[post_comment_delete_link] = 'delete comment';
$lang[post_comment_add_link] = 'add comment';


$lang[aux_top_five_dl] = 'top five downloads:';
$lang[aux_five_last_comments] = 'last five comments:';
$lang[aux_no_comments_yet_text] = 'This is not exactly the most visited and popular site on the web, so;<br>
            surprise surprise, there are no comments yet..<br>
            <i>(be the first)</i>';
$lang[aux_no_downloads_yet_text] = 'no downloads yet...';


$lang[upload_file_form_header] = 'Select a File to Upload:';
$lang[upload_file_button] = 'Upload File';
$lang[upload_ext_error] = 'The file extension is invalid, please try again!';
$lang[upload_size_error1] = 'The file size is invalid, please try again! The maximum file size is:';
$lang[upload_size_error2] = 'and your file was:';
$lang[upload_excist_error] = 'This file already exists on the server, please try again.';
$lang[upload_failed] = 'Your file could not be uploaded';
$lang[upload_success1] = 'Your file has been successfully uploaded to the server.';
$lang[upload_success2] = 'Upload performed ok';
$lang[upload_continue] = 'continue';
$lang[upload_image_form_header] = 'Select an Image File to Upload:';
$lang[upload_image_button] = 'Upload Image';
$lang[upload_image_only] = 'Image files only please';
$lang[upload_small_resize_failed] = 'small resize failed';
$lang[upload_resize_failed] = 'resize failed';
$lang[upload_resize_success] = 'Resize performed ok';
$lang[upload_static_form_header] = 'Upload File';
$lang[upload_static_ext_error] = 'html, php, or txt files only please';

?>