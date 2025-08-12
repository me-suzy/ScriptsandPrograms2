<?
   require "conf/sys.conf";
   require "lib/mysql.lib";
   require "lib/group.lib";
   $db = c();
   include "tpl/top.ihtml";
?> 
<blockquote>
  <p><b><font face="Arial, Helvetica, sans-serif" size="3" color="#333333">HELP &gt;</font></b><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br>
    </font></p>
  <p> <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><br>
    <b><br>
    How do I get started?</b><br>
    <br>
    1. First sign up for a free account.<br>
    2. Create a website campaign (Add a site to the system).<br>
    3. After your site is reviewed and approved, edit your web pages and insert 
    the html code.<br>
    4. Add some credits to your campaign using 'manage credits' in campaigns page, 
    to start receiving traffic fast.<br>
    5. Finished, now you will start earning traffic to your site. Dont forget 
    to use your referal link to earn even more traffic to your websites. </font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><br>
    How much traffic will I receive?</b><br>
    <br>
    For every 2 visitors to your site you get another visitor. This is real visitors 
    not just banner impressions, meaning a 50% guaranteed traffic increase to 
    your website. Our Referral Program also gives you the opportunity to receive 
    credits for use in your campaigns for every new user you refer to our traffic 
    exchange. Meaning that you can receive almost unlimited traffic to your websites.</font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><br>
    What are the benefits of beeing a free member ?</b></font></p>
  <ul>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Multiple 
      website campaigns under the same account. </font></li>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Target 
      your campaigns, select from 60 categories.</font></li>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Many 
      different exchange types : banner (image, flash, text), popup, popunder, 
      exit, startpage.</font></li>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Detailed 
      real-time statistics. </font></li>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">You 
      can limit the hits to each of your sites. </font></li>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">You 
      can transfer credits between your campaigns (websites) and store credits 
      for using later.</font></li>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Unique 
      visitors are counted for each of your sites. Don't waste many credits for 
      the same visitors.</font></li>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Referral 
      program. Earn credits for every new member you refer.</font></li>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Add 
      great content to your websites (search box, top, surf categories, ...) and 
      also receive credits when visitors use this.<br>
      </font></li>
  </ul>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b><br>
    How do I become an advertiser ?<br>
    <br>
    </b>Buy credits from member area and automatically become an advertiser.</font></p>
  <p><br>
    <font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>What 
    are the extra benefits for beeing an advertiser ?</b></font></p>
  <ul>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Bid 
      on keywords for the ppc search engine and the advertiser site top listings.</font></li>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Send 
      paid emails to members. Target all/trusted/advertisers/free members. Set 
      custom extra email payment.</font></li>
    <li><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Request 
      trusted status. Transfer credits to other accounts, if enabled.</font><br>
      <br>
    </li>
  </ul>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b>What 
    if I don't want to receive paid emails ?</b></font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333">Just unsubscribe 
    from the newsletter and mail services.</font></p>
  <p><font face="Arial, Helvetica, sans-serif" size="2" color="#333333"><b></b></font></p>
  <p> 
  </p>
</blockquote>
<?php
  include "tpl/bottom.ihtml";
   d($db);
?>

