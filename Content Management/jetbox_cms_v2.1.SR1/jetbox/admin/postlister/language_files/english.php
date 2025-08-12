<?php
$s1 = "Help";
$s2 = "Compose";
$s3 = "Add/delete subscribers";
$s4 = "Edit a list";
$s5 = "Create/delete lists";
$s6 = "Now we'll create Postlister's main table. This only needs to be done once. You have chosen to name the main table <i>$mainTable</i>. If you want to change this name you will have to open the file <i>settings.php</i> and change the <i>\$mainTable</i> variable. Otherwise all you need to do is to push the button below to create the table.";
$s7 = "Create the table";
$s8 = "An error occured";
$s9 = "Back";
$s10 = "The name of the table is invalid. It can only contain letters and numbers -- no spaces or special characters.";
$s11 = "Postlister's main table <i>$mainTable</i> has been created. You may now begin <a href=lists.php>creating mailing lists</a>.";
$s12 = "Pick a mailing list:";
$s13 = "OK";
$s14 = "No mailing lists available.";
$s15 = "Create the mailing list";
$s16 = "Create a mailing list";
$s17 = "Mailing list name:";
$s18 = "Choose a name for the new mailing list. The name cannot be longer than 20 characters, and it cannot contain spaces or other special characters -- just the letters a-z and numbers.";
$s19 = "Delete a mailing list";
$s20 = "Which mailing list do you want to delete?";
$s21 = "Delete";
$s22 = "The mailing list <i>$listeOpret</i> has been created. You may now <a href=edit.php?liste=$listeOpret>edit the list</a>.";
$s23 = "Are you sure that you want to delete the mailing list named <i>$listeSlet</i>? If you do so, you will lose all email addresses contained in it.";
$s24 = "Cancel";
$s25 = "Delete the list";
$s26 = "The mailing list named <i>$listeSletBekraeft</i> has been deleted.";
$s27 = "Sender address, i.e. <i>Your name &lt;your.name@$SERVER_NAME&gt;</i>:";
$s28 = "The signature to be inserted at the bottom of emails that are sent to the list:";
$s29 = "The subscribe message -- the message which is sent to those who want to subscribe to the list.";
$s30 = "Save the changes";
$s31 = "The subscribe message <b>must</b> contain the word <i>[SUBSCRIBE_URL]</i>.";

# The following variable will go into an email body. Therfore, you need to break all lines after 72 characters.
$s32 = "You have received this email because you or somebody else
has subscribed you to the mailing list $listeOpret at
http://$HTTP_HOST.
Before we can add your email address to our mailing list we need to
make sure that the email address exists and is working, and that you
actually want to subscribe to our mailing list. Therefore, we ask you
to confirm your subscription by visiting the following URL:

<[SUBSCRIBE_URL]>

Thank you.";

$s33 = "The changes to the <i>$liste</i> list has been saved.";
$s34 = "Add email addresses";
$s35 = "Delete email addresses";
$s36 = "Add";
$s37 = "Enter the new email address to be added to the list -- i.e. <i>joe.blow@example.com</i>:";
$s38 = "<i>$epostadresseTilfoej</i> is not a valid email address.";
$s39 = "The email address <i>$epostadresseTilfoej</i> has been added to the list <i>$liste</i>.";
$s40 = "Apparently the email address <i>$epostadresseTilfoej</i> already exists in the list.";
$s41 = "Show";
$s42 = "all subscribers";
$s43 = "approved";
$s44 = "non-approved";
$s45 = "beginning with";
$s46 = "containing";
$s47 = "No result.";
$s48 = "approved";
$s49 = "non-approved";
$s50 = "The email address <i>$sletDenne</i> has been deleted from the mailing list named <i>$liste</i>.";
$s51 = "Write a message to the <i>$liste</i> mailing list";
$s52 = "From:";
$s53 = "Subject:";
$s54 = "Body:";
$s55 = "Line wrap at 72 characters";
$s56 = "Preview";
$s57 = "Print";
$s58 = "Word count";
$s59 = "Functions";
$s60 = "Number of characters:";
$s61 = "Number of words:";
$s62 = "You need the right username and password to access this page.";
$s63 = "You can use the following variables in the body of the email:";
$s64 = "The recipient's email address.";
$s65 = "The unsubscribe URL -- the URL which the recipient needs to visit in order to unsubscribe from the list.";
$s66 = "To:";
$s67 = "Send";
$s68 = "Back -- I want to edit the email";
$s69 = "Mailing lists";
$s70 = "Subscribe to our mailing list(s):";
$s71 = "Your email address:";
$s72 = "Select a mailing list:";
$s73 = "Subscribe";
$s74 = "Unsubscribe";
$s75 = "<i>$email</i> is not a valid email address.";
$s76 = "You did not specify whether you want to subscribe or unsubscribe to the mailing list. The problem may be caused by an error in the formular which you submitted. Please contact the website administrator.";
$s77 = "Subscription to the $list mailing list";
$s78 = "Unsubscription from the $list mailing list";
$s79 = "Thank you for subscribing to the <i>$list</i> mailing list. Before we can add you to the list we ask you to confirm your subscription request. Within a few minutes you will receive an email containing an URL that you must visit in order to confirm your subscription request.";
$s80 = "In order for us to remove you from the <i>$list</i> mailing list we ask you to confirm your unsubscription request. Within a few minutes you will receive an email containing an URL that you must visit in order to confirm your unsubscription request.";

# The following variable will go into an email body. Therfore, you need to break all lines after 72 characters.
$s81 = "You have received this email because you or somebody else
has unsubscribed you from the mailing list $listeOpret at
http://$HTTP_HOST.
Before we can remove your email address from our mailing list we need
to make sure that you, the owner of the email address, actually want to
be removed from the list. Therefore, we ask you to visit the following
URL in order to confirm your unsubscription request:

<[UNSUBSCRIBE_URL]>

Thank you.";

$s82 = "The unsubscribe message <b>must</b> contain the word <i>[UNSUBSCRIBE_URL]</i>.";
$s83 = "The unsubscribe message -- the message which is to be sent to those who wish to unsubscribe from the list.";
$s84 = "Apparently the email address <i>$email</i> already exists on the list.";
$s85 = "Done! The email has now been sent to all addresses on the list.";
$s86 = "Postlister is sending email number";
$s87 = "through";
$s88 = "Do NOT close this browser window! Don't touch anything while the program is delivering the remaining messages.";
$s89 = "The email address <i>$email</i> does not exist on the list. Thus, you can't unsubscribe it.";
$s90 = "No email address has been specified.";
$s91 = "You did not specify whether you want to subscribe or unsubscribe to the list.";
$s92 = "You did not specify the email address ID.";
$s93 = "No mailing list was specified.";
$s94 = "You did not specify the correct ID for the email address <i>$epost</i>.";
$s95 = "Done! You have now been added to the <i>$liste</i> mailing list.";
$s96 = "You have now been removed from the <i>$liste</i> mailing list, and you will receive no more email from this list.";
$s97 = "out of";
$s98 = "Import email addresses";
$s99 = "Open and import";
$s100 = "The file <i>$importfil</i> was not found.";
$s101 = "That's it! All of the email addresses in the file <i>$importfil</i> have been imported into the <i>$liste</i> mailing list.";
$s102 = "If you have a file containing a number of email addresses, you can import the addresses into the <i>$liste</i> mailing list. However, it is important that the file only contains one email address per line, and that it does not contain anything but email addresses. In other words, the format of the file should be something like this:<p><i>jens.hansen@eksempel.dk<br>Joe Johnson &lt;joe.johnson@example.com&gt;<br>php@php.net</i>";
$s103 = "File On Server:";
$s104 = "Back to Postlister's main page";
$s105 = "Import/export";
$s106 = "Export email addresses";
$s107 = "Export";
$s108 = "Using this function you can export the email addresses in <i>$liste</i>. That is, all of the email addresses will be written to a file - one address per line. The name of the file will be <i>postlister-$liste.txt</i>, and it will be placed in the directory specified below. <b>It is very important that the directory in which the file will be placed has the right permissions. This means that you will have to chmod the directory 777 using an FTP client or SSH/telnet.</b>";
$s109 = "The directory in which you want to place the file:";
$s110 = "<i>$eksport</i> is not a directory. You need to specify the directory in which you want to place the file with the email addresses.";
$s111 = "That's it! All of the email addresses in the <i>$liste</i> mailing list have been written to the file <i>$eksport/postliste-$liste.txt</i>.";
$s112 = "File on local PC:";
$s113 = "There are ";
$s114 = " email addresses in the <i>$liste</i> mailing list";
$s115 = " Start Here";
?>
