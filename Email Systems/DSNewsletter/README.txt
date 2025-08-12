DSNEWSLETTER 1.0 README
-------------------------

CONTENTS:
 1) Installation
 2) Explanation
 
1) INSTALLATION
 -Upload all the files (including the folders) somewhere onto your site
 -CHMOD:
  -emails, temp, perm, pend, logs to 0777.
  -issue.html to 0777.
  -year.html to 0777.
  -config.php to 0666.
 -Edit config.php to the things prompted.
 -Re-upload config.php.
 -Enjoy!
 
2) EXPLANATION
 Here's a small explanation so you know what things are and how they work.
  Users submit their articles. They then get an article ID that they can
  search under ID Search to find out if their article is still pending,
  accepted, or rejected. 
  The administrator can login to their control panel and now accept, reject,
  or edit the submissions. Rejected ones get deleted, accepted ones go under
  permanent, and edited ones get edited.
  The admin can also reject (delete) permanent ones, and edit/reject logs
  that say the status of the articles.
  Admins will need to click Publish at the top of the control panel to
  make the articles still under the permanent section with the same month prefix
  (01_, 02_, etc..) appear on the main page. Issues will always appear on the
  archives page even if the issue hasn't been published.
  Archives allows you to choose a prefix code (month prefix) and view
  all the articles for that issue.
  You can subscribe and unsubscribe and receive emails with the new issue and
  a link to the new issue if the user's email vlient doesn't support html.
  YOU CAN ONLY PUBLISH ONCE A MONTH. You get the choice of which month to publish
  just in case you missed the old one, but still, you can only publish once
  a month. Not once a week, not once a day. Once a month.
  Once a year when you login to your admin control panel, it will say
  "Deleted all old articles:" and then list them. This is deleting all
  the old articles from last year so they do not get mixed up with this
  year's issues.
  If you want to skin it, it's easy. Look for things inside the "echo"'s
  in the php code and look for stuff inside the regular HTML code. I recommend
  just putting a frame around it or something like that. Feel free to change
  the layout however you like.
  NEW: Easier skinning!! You can now edit header.php and footer.php to change the
  top and bottom of the page. LEAVE THE POWERED BY LINK ON. Remember, this 
  freeware, so appreciate the time I spent to make this. Also,
  I did so much work for FREE. So don't take off the powered by link. Don't.
  
  
  TROUBLESHOOTING:
  If you have any trouble, just post under the DSNewsletter 1.0 topic on the
  Dvondrake Studios forums at http://forums.dvondrake.com.
  
----------------------------------------------------------------------------------
Everything, even this readme, is copyright 2005 Dvondrake Studios
http://www.dvondrake.com/