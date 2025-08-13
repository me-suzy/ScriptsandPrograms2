phpAds Update

Author - Michael Harvey
Date - 7/25/2000

See switches at end of config.inc.php3

Added features:
  1- Use of multiple keywords in the keywords field of the banners table.
	- I use this to be able to specify a size as well as one or more other keywords.
	- It doesn't matter what the keywords are separated by (comma, space, etc.).
	- If you turn this feature off in the config.inc.php3 file you must have only 1 keyword in the keyword field otherwise your script won't work.

  2- Use of conditional keywords
	- When you call view(), instead of just using;

	   key1,key2,key3 (Meaning key1 OR key2 OR key3) 

	You may use; 

	   key1,+key2,-key3 (Meaning key1 AND key2 AND NOT key3) 

	- So, to explain further, here's the rules: 
	   - The swithes are "+" (meaning AND) and "-" (meaning NOT). 
	   - The first keyword _must not_ have a switch, the rest may. 
	   - Do not put a space between the switch and the keyword. 
	   - If the "+" or "_" switch is used then that keyword must be in the banner's list for the banner to show. For remote invocation you have to use "_". 
	   - If the "-" switch is used then that keyword must not be in the banner's list for the banner to show. 
	   - If no switch is used then that keyword is considered as OR.

I use the above 2 features combined to be able to "target" the banners as to location on page (insurance in one area and general in another) and a specific type of page and/or site.
   - Example: in my code I might use: 
	view("len150,insurance,-business") to call a small banner which is targeted at a non business insurance clientelle.
	view("len450,general,+business") to call a large banner which is targeted at only business people in an insurance area.
	or view("len450,general,business") to call a large banner which is targeted at business and/or other people in an insurance area.


  3- Use random or sequential banner retrieval
	- If this feature is turned off then selection is random as before.
	- If it is turned on the banner selection is still random but each time a banner is used it is marked in the seq field in the banners table.
	- Each time a view() is called a check is first done to see if a banner exists which meets the criteria and has not been marked.
	   - If so then only an unmarked banner is called.
	   - If not then all banners meeting that criteria are unmarked and any banner meeting the criteria will be called.
	- There is provision in the code to prevent the same banner from being called twice in the same page (see Documentation.html near the bottom). This new feature will prevent the same banner from being used on subsiquent pages or one banner being called more than others (no random number generator is perfect). However, if a page catches this feature at the end of a cycle (ie: the seq column is full) then it is still possible to have the same banner shown twice, especially if there are few banners meeting that criteria. Therefore the above mentioned "repeat prevention" code should still be used for multiple banners on the same page.

  4- Page frame targeting
	- There is no 'switch' in the config file for this one.  It is turned on and off by the way you specify the 'target' perameter.
	- There is no interface in the admin section for this yet because I want to see that all the bugs are out of it first.
	     You will have to enter the target entries directly into the database for now.  Use "update banners set target='_blank' where bannerID='13'" for now. ('_blank' should be the target you want and '13' should be the banner # you want to change.)
	- To use it:
	     - Place a target in the target field in the banners table
	     - In the target perameter in view() put a '+' in front of the default target. Examples:
		- view("len150"); // Will ignore any target entries in the banners table.
		- view("len150",0,"+"); // Will honor any target entries in the banners table and give all others no target.
		- view("len150",0,"+_top"); // Will honor any target entries in the banners table and give all others a target of '_top'.
		- view("len150",0,"_top"); // Will ignore any target entries in the banners table and give all a target of '_top'.
	- If you use this and find it works well please let me know and I will add to the admin interface to simplify entering targets in the banners table.

Aug. 1/2000
  5- Banner decrementing and low views/clicks warning e-mail
	- I had trouble getting the language file to load from multiple directories so I put the text for the e-mails in the view.inc.php3 file around line 160.  If you want to change the text or the language you'll have to do it there for now.  In the future I would like to have the information in the various config files db table based instead of the current file based.  This will prevent such problems as encountered here as well as allowing easier construction of an administration front end later.
	- With this upgrade the view column in the clients table will be decremented on each view of a banner owned by that client and the click column will be decrmented on each click.  Neither will be decremented below 0.
	- When both view and click columns = 0 then all banners for that client are disabled by setting the active field to false for each banner owned by that client.
	- Adding clicks and/or views for a client (or any other action in the "Modify Client" area) will set all banners for that client to active ('true').
	- If $phpAds_warn_admin = "1" then the $phpAds_admin_email address will be notified if the clicks or banners for any client reaches $phpAds_warn_limit (currently set to 100).
	- If $phpAds_warn_client = "1" then the client e-mail address will be sent a notice when $phpAds_warn_limit is reached.


===================
DISCLAIMER (you knew that was comming, right? ;-}):

All of these features have been tested on my site at http://caringphysician.com.  However, I am fairly new at this so they should be used with caution. If you find an error in the code please let me know at mike@ve-studio.com.
