<?php
	$dirpath = "$Config_rootdir"."../";
	require_once($dirpath."essential/dbc_essential.php");
	require_once($dirpath."essential/globalfunctions.php");
	$usr = new Html();
	$ucook = new UserCookie();
	$csr = new ComFunc();
	
         if ( !$ucook->LoggedIn() )
         {
          $usr->HeaderOut();
	    $csr->customMessage( 'logout' );
	    $usr->FooterOut();
   
          exit;
      }

       $usr->Header($Config_SiteTitle .' :: help');
       echo ("<br><div align=right><img src=".$dirpath.$Config_imgdir."/{$Config_LangLoad}_headers/help.gif>&nbsp;</div><br>");
?>

                  <p>&nbsp;</p>
<p class="tn">Here are some of the FAQ's written for your help, else you can <a href="feedback.php">mail 
  us</a>...</p>
<ol class=tn>
  <li><span class="tn"><a href="#1">How do i put my photos in the album?</a></span></li>
  <li><span class="tn"><a href="#2">Can I change the name &amp; message of the 
    album/photos once i created it?</a></span></li>
  <li><span class="tn"><a href="#3">I want to remove private status from the album?</a></span></li>
  <li><span class="tn"><a href="#4">How do i tell my friends about my albums?</a></span></li>
  <li><span class="tn"><a href="#5">I want to add 20 photos at one go, how 
    do i do that?</a></span></li>
  <li><span class="tn"><a href="#6">Which photo formats i can add?</a></span></li>
  <li><span class="tn"><a href="#7">Does the thumbnails also account the space 
    i have?</a></span></li>
  <li><span class="tn"><a href="#8">Do i need to login to see my album?</a></span></li>
  <li><span class="tn"><a href="#9">Do i get email address for my account?</a></span></li>
  <li><span class="tn"><a href="#10">Where do i see how much space all my albums 
    take?</a></span></li>
  <li><span class="tn"><a href="#12">I don't want my album list to come when someone 
    search my email...</a></span></li>
  <li><span class="tn"><a href="#13">Why does password change when i change my 
    email address?</a></span></li>
  <li><span class="tn"><a href="#12">I want my private albums also to be seen 
    when someone search my email address...</a></span></li>
  <li><span class="tn"><a href="#16">Are my passwords safe?</a></span></li>
  <li><a href="#19">Do i need to add photo thumbnails also?</a></li>
  <li><a href="#18">Can i change the order by which my photos appear in the 
    album?</a></li>
  <li><a href="#19">The thumbnails made are resized orignal photo or a seprate 
    file is created?</a></li>
  <li><a href="#10">What is the default space alloted to all accounts?</a></li>
  <li><a href="#20">If i delete my photo after sending it as an ecard what will 
    happen?</a></li>
  <li><a href="#21">I want to send a card to 10 people with little difference 
    in the message, can i do that with multiple send?</a></li>
  <li><a href="#22">To how many people can i send one card?</a></li>
  <li><a href="#23">While manipulating i made some blunders and my photo was saved, 
    can i undo it?</a> </li>
  <li><a href="#24">I applied one effect and it took a long time but the result 
    didn't come?</a></li>
  <li><a href="#25">Why don't you support .gif format, it is so common?</a></li>
  <li><a href="#26">I found a bug, where should i report it?</a></li>
</ol>
<p>&nbsp;</p>
<p><span class="tn">Anything else <a href="feedback.php">contact us</a></span></p>
<p>&nbsp;</p>

<a name="1"></a>
<p><span class="tn"><b>1. How do i put my photos in the album?<br>
  <br>
  </b>You can add photos only in an album you created. This can be done by clicking 
  on the add album link on top of the window. Just next to it is the add photo option 
  click on that and follow the simple instructions to put your photo in the 
  album. <a href="#6">Formats Allowed</a></span></p>

<p><a name="2"></a></p>
<p><span class="tn"><b>2. Can I change the name &amp; message of the album/photos 
  once i created it?<br>
  <br>
  </b>Yes, you can change the name and the caption of the album anytime you want. 
  On the navigation bar click on edit and you will be presented with the list 
  of albums you made. Make the changes in which you want and press the button 
  named &quot;change&quot; next to it. For changing the photo details click 
  on the link just below the fields of the album you want named &quot;make changes&quot;. 
  Simillar procedure is followed.</span></p>

<p><a name="3"></a></p>
<p><span class="tn"><b>3. I want to remove private status from the album?<br>
  <br>
  </b>Yes, you can change status from private to public and vice-versa anytime. 
  The option is below the name of the album under &quot;edit&quot; option on the 
  navigation bar. For private to Public click on &quot;make public&quot; under 
  &quot;edit&quot; for Public to Private click on &quot;make private&quot; under 
  &quot;edit&quot; below the Album's name.</span></p>

<p><a name="4"></a></p>
<p><span class="tn"><b>4. How do i tell my friends about my albums?<br>
  <br>
  </b>You can tell about one or all albums to your friends or family or whomsoever 
  :) ... Just click on &quot;my albums&quot; and select the options &quot;tell friends&quot; 
  under the album name. For all albums click on the first link which says &quot;tell 
  all albums to friends&quot;. For telling your friends about our site click on 
  &quot;tell about <?php echo $Config_sitename ?>&quot; link.</span></p>

<p><a name="5"></a></p>
<p><span class="tn"><b>5. I want to add 20 photos at one go, how do i do 
  that?</b><br>
  <br>
  You can do that at the time of adding photos, at the bottom of 
  the page a small table says &quot;show fields&quot; enter the number of photos 
  you want to add at one go and press &quot;show&quot; button. The maximum 
  allowed is 99.</span></p>

<p><a name="6"></a></p>
<p><span class="tn"><b>6. Which photo formats I can add?</b><br><br>
The allowed formats are <?php echo $Config_allow_types_show ?> at the moment.</span></p>

<p><a name="7"></a></p>
<p><span class="tn"><b>7. Does the thumbnails also account the space i have?</b></p>
<p><b> </b>Yes they do add up in the space you have used.</span></p>

<p><a name="8"></a></p>
<p><span class="tn"><b>8. Do i need to login to see my album?</b></p>
<p>No, you need to login to see your album, however private albums 
  need their own passwords to be entered before accessing the photos in them.</span></p>

<p><a name="9"></a></p>
<p><span class="tn"><b>9. Do i get email address for my account?</b></p>
<p>No, at this time we don't offer any such services.</span></p>

<p><a name="10"></a></p>
<p><span class="tn"><b>10. Where do i see how much space all my albums take?</b></p>
<p>You can see that in the settings options, you can also see 
  the space alloted to you. The current default space given is <?php echo $Config_default_space ?> 
  MB.</span></p>

<p><span class="tn"><b>11. I don't want my album list to come when someone search 
  my email...</b></p>
<p>You can change the status in the settings option for no display 
  on search, or public only or all.</span></p>
<p><a name="13"></a></p>
<p><span class="tn"><b>12. Why does password change when i change my email address?</b></p>
<p>The email of your's is the only thing we know about you ... 
  thats why we want to keep track of the real people so for security sake when 
  you change your email address we issue a new password. You can always change 
  the password.</span></p>
<p><a name="16"></a></p>
<p><span class="tn"><b>14. Are my passwords safe?</b></p>
<p>Be rest assured we want your passwords to be safe and sound, 
  thats why they are encrypted and in such a way that even the adminstrator can't 
  decrypt it. So once encrypted... well its only you and only you know it.</span></p>
<p><a name="18"></a><br></p>
<p><span class="tn"><b> 16. Can i change the order by which my photos appear 
  in the album?<br>
  <br>
  </b>Yes, you can change the order, click on the link just below the fields of 
  the album you want named &quot;edit pohotos&quot; under Make Changes option. Just change 
  the Order field of the photos to reorder them. e.g. if you want the last photo 
  to come the first change the first photos's order field value to the field value 
  of the last photo and vice-versa and press &quot;change&quot; button on the 
  bottom of the page.</span></p>
<p><a name="19"></a></p>
<p><span class="tn"><b>17. The thumbnails made are resized orignal photo or a 
  seprate file is created?<br>
  <br>
  </b>The thumbnails are made in a new photo for faster loading of your albums. 
  This is done automatically on adding of the photo you don't need 
  to add them for display.</span></p>
<p><a name="20"></a></p>
<p><span class="tn"><b>19. If i delete my photo after sending it as an ecard 
  what will happen?<br>
  <br>
  </b>If you delete a photo after sending it as an ecard, well we won't be able 
  to tell you that the card is already seen or not... but what we can do is if 
  your card recipient comes later to fetch the card, then we will present sorry 
  message to him/her with a wishes card from our side... we are so thoughtful, 
  aren't we? :)</span></p>
<p><a name="21"></a></p>
<p><span class="tn"><b>20. I want to send a card to 10 people with little difference 
  in the message, can i do that with multiple send?<br>
  <br>
  </b>Well a little change or a lot its a different message for the system... 
  so at this time you will have to make different cards for all.</span></p>
<p><a name="22"></a></p>
<p><span class="tn"><b>21. To how many people can i send one card?<br>
  <br>
  </b>At the moment we allow upto 99 recipients at one go.</span></p>
<p><a name="23"></a></p>
<p><span class="tn"><b>22. While manipulating i made some blunders and my photo 
  was saved, can i undo it? <br>
  <br>
  </b>Sorry, we very well state that we can't undo the changes... please restore 
  the photo from the orignal source.</span></p>
<p><a name="24"></a></p>
<p><span class="tn"><b>23. I applied one effect and it took a long time but the 
  result didn't come?<br>
  <br>
  </b>The applied effect would be time consuming but the result will come but 
  please be patient for it...</span></p>
<p><a name="25"></a></p>
<p><span class="tn"><b>24. Why don't you support .gif format, it is so common?<br>
  <br>
  </b>Its all about copyrights, the company which owns the file format rights have restricted the public editing of the format without their permission, which we don't have. So till it comes I guess you can always use the worlds most common format for photographs (well that's what the site is for) .jpg, which we willingly support.</span></p>
<p><a name="26"></a></p>
<p><span class="tn"><b>25. I found a bug, where should i report it?<br>
  <br>
  </b>Well if you did, please <a href="feedback.php">click here</a>...</span></p>
<p>&nbsp;</p>
                  
<?php

$usr->Footer(); 

?>