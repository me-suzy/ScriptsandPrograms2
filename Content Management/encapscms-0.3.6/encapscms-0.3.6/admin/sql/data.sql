--
-- Table structure for table 'blogs_cats'
--

CREATE TABLE blogs_cats (
  id int(11) NOT NULL auto_increment,
  title varchar(255) default NULL,
  pos varchar(255) default NULL,
  vis varchar(255) default NULL,
  anch int(11) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'blogs_cats'
--


INSERT INTO blogs_cats VALUES (1,'home','3','on',0);
INSERT INTO blogs_cats VALUES (2,'bio','2','on',0);
INSERT INTO blogs_cats VALUES (4,'equipment','1','on',0);

--
-- Table structure for table 'blogs_page_block'
--

CREATE TABLE blogs_page_block (
  id int(11) NOT NULL auto_increment,
  title blob,
  text blob,
  text_detail blob,
  block_position int(11) default NULL,
  img varchar(255) default NULL,
  img_position varchar(255) default NULL,
  img_visible varchar(255) default NULL,
  page_sub_id int(11) default NULL,
  visible varchar(255) default NULL,
  img_popup varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'blogs_page_block'
--


INSERT INTO blogs_page_block VALUES (1,'uiyoi','','',1,'','bottom','on',1,'on','');
INSERT INTO blogs_page_block VALUES (2,'OVERVIEW ON CAMERAS, FORMATS AND FILM','kjh','I use <strong>Nikon cameras and lenses</strong> for the <strong>35\r\n            mm</strong> and <strong>use <u>film</u> (vs.\r\n            digital) <u><em>exclusively</em></u></strong>, favoring <strong>Velvia,\r\n            Provia</strong> and <strong>Kodachrome</strong> for most photos.\r\n            In the medium format I use <strong>Hasselblad bodies and lenses</strong> and\r\n            the same film as 35 mm with the vast majority being <strong>Fuji\r\n            film</strong> (both <strong>Velvia 50</strong> and <strong>100</strong> and <strong>Provia\r\n            100P</strong> and <strong>400</strong>). I use a variety of cameras\r\n            and film, trying to match the situation with what I think will work\r\n            best for me. Certainly there are times a 35 mm camera will outperform\r\n            all others, say for wildlife or bird photography. Having said that\r\n            I have used all three \\\"formats\\\" from <strong>35 mm</strong> to <strong>medium\r\n            format</strong> (6 cm x 6 cm) and now <strong>large format</strong> (4x5\r\n            and 8x10 inch cameras). This includes both 4\\\"x 5\\\" negatives\r\n            and my \\\"new favorite camera\\\", the <strong>8x10 wooden camera</strong> similar\r\n            to the very one Ansel Adams used for forty years. It extends out\r\n            36 inches from lens to film plane and it is an awesome sight when\r\n            it is unfolded but it is very hard to keep steady in a wind of any\r\n            kind! <br>\r\n           <br>\r\n           I will show you some of the different gear in this section. The medium\r\n           and large formats allow me <em><strong>far more latitude</strong></em> to\r\n           capture the type of images I see in my mind and working with 35 mm\r\n           gives me <em><strong>the flexibility</strong></em> to use lenses that\r\n           are simply not available even with medium format. In the Hasselblad\r\n           system the largest lens is the <strong>Zeiss 500 f8 Tele-Tessar</strong>,\r\n           (shown in photo above, next to the Nikon camera and lens) which is\r\n           pretty slow for any shots unless you use a tripod. I enjoy using different\r\n           formats, mixing it up sometimes using medium and large format for\r\n           landscapes, macro work and even wildlife and 35 mm primarily for birds\r\n           and wildlife, where more magnification is usually needed.In this section\r\n           there is a photo wherever mentioned (see photo) to click on, then\r\n           just hit the back button to return to this section.          ',1,'1116276463_RayCameras2.jpg','right','on',4,'on','');
INSERT INTO blogs_page_block VALUES (4,'MEDIUM FORMAT EQUIPMENT','','I also use several <strong>medium\r\n           format cameras</strong> from <strong>Pentax</strong> to <strong>Mamiya</strong>,\r\n           but my current system is Hasselblad. Without a doubt the <strong>Zeiss\r\n           lenses</strong>           in the <strong>Hasselblad system</strong> are\r\n           first rate, even though they tend to be slow compared to todays faster\r\n           lenses but that is due to the fact they have the shutter IN the lens,\r\n           NOT the camera body. The biggest advantage to this is I can sync at\r\n           any speed up to 1/500th of a second. I primarily use the <strong>Distagon\r\n           50 mm</strong> for wide-angle work, the <strong>Sonnar\r\n           250 mm</strong> as a medium telephoto (and macro work with a bellows)\r\n           and especially the <strong>Tele-Tessar 500 mm</strong> lens for my\r\n           longer telephoto shots. With many people now shooting digital instead\r\n           of film these systems, camera bodies and lenses are very affordable\r\n           and a tremendous value.</span><br>\r\n           <br>\r\n           <span class=\\\"bioText\\\">I use both the <strong>500 C</strong> and the <strong>500\r\n           ELM body</strong>, (which\r\n           has a motordrive when I want the option of not having to wind the\r\n           camera). I prefer the <strong>Hasselblad 500 C</strong> or <strong>500\r\n           CM</strong> because it is TOTALLY\r\n           manual and has no battery to fail! This is one reason I LOVE the Hasselblad\r\n           lenses. I use several hand held exposure meters and like the <strong>Pentax\r\n           1% spot meter</strong> the best (it is in my left hand in this photo\r\n           with me and the 500 mm f8 Zeiss lens here). Most people shy away from\r\n           this system and this lens because it is too slow and big, but I love\r\n           it, especially if I use faster film. I nearly always use it with a\r\n           tripod and if needed, use <strong>Provia 400</strong> speed slide film or <strong>400\r\n           print film</strong>. <strong>Fuji</strong> even makes an outstanding 800 speed print film that I have\r\n           used and love, OR you can push the <strong>Provia</strong> 100 AND 400 speed film one\r\n           stop (and in some cases two stops). In essence the 500 f8 is then\r\n           an 500 f4, gaining two full stops. Don\\\'t get hung up on how \\\"fast\\\" a\r\n           lens is. In 35 mm the difference between a 300 f4 and a 300 f2.8 is\r\n           ONE stop, but the cost is six to ten times as much for the faster\r\n           lens!</span>',4,'1118163905_RayHasselblad.jpg','left','on',4,'on','');
INSERT INTO blogs_page_block VALUES (3,'MY 35mm EQUIPMENT','','	   I use <strong>35 mm Nikon cameras and lenses exclusively</strong>, from <strong>24mm\r\n           to 600 mm lenses</strong>. My favorite lens by far is my <strong>600mm\r\n           f5.6 ED Nikon</strong> telephoto shown in photo above. ',2,'1118164861_Bee.jpg','left','on',4,'on','');
INSERT INTO blogs_page_block VALUES (9,'','','I have even\r\n           used it with extension tubes and a bellows for extreme macro work,\r\n           giving me a healthy working distance for bees, spider and even snakes.\r\n           This photo to the left was taken with the 600 mm 5.6 ED Nikon lens,\r\n           an extension tube, a bellows and two teleconverters, at a distance\r\n           of 7 feet! That is a dandelion that it is sitting on.  <br>\r\n           <br>\r\n           One reason I like <strong>Nikon lenses</strong> is I have an adapter\r\n           to use them on my <strong>Canon XL-1S video camera</strong>, giving\r\n           me far more telephoto opportunities, although <strong>Canon</strong> offers\r\n           their 35 mm lenses for their video camera as well. I often use the\r\n           600 mm lens on this camera  and I have filmed sheep and other\r\n           animals as far as two miles away! It DOES require a very sturdy tripod\r\n           and I use the <strong>older wooden tripod</strong> you see in this\r\n           photo most of the time. I use this system, with other lenses as well,\r\n           to capture video footage for television shows and much of this footage\r\n           will show the photos being taken. (In the near future that footage\r\n           will appear on this web site. The viewer will be able to see the photo\r\n           and then link to a video clip of when and how this photo was taken,\r\n           seeing the actual footage of the scene or situation where this photograph\r\n           was shot. This dimension will enhance the viewer\\\'s understanding of\r\n           the photo taken, giving them more feeling for the photo and how it\r\n           was shot).',3,'1118164938_NikonF3.jpg','right','on',4,'on','');
INSERT INTO blogs_page_block VALUES (5,'FILM CHOICES','','Most\r\n											of my images are shot on <strong>Fuji\r\n											film</strong>,\r\n           usually <strong>Velvia 50</strong> and <strong>100</strong> and <strong>Provia\r\n           100</strong> and <strong>400</strong>, and on several types\r\n           of negative film for prints, sometimes up to 800 ASA film speed. I\r\n           like both <strong>Kodak</strong> and <strong>Fuji film</strong> and\r\n           still have a cache of the out of production <strong>Kodachrome 25</strong>.\r\n           I use the same for both medium format and even large format. I sometimes\r\n           use black and white film <strong>(T-MAX\r\n           100 and 400)</strong>, but seldom stray from color film or transparencies.\r\n           (I own a magazine so many of my shots are used in my publication,\r\n           so transparencies are far preferred for this type of work and <em>',5,'1118164178_Cheetah.jpg','right','on',4,'on','');
INSERT INTO blogs_page_block VALUES (6,'','','I use\r\n           them more often). However there IS one major advantage to print film-<em><strong>it\r\n           has a much higher latitude for exposure than slide film</strong></em>. <em></em>I\r\n           can \\\"push\\\" this\r\n           film two even three stops, where one stop is the limit with slide\r\n           film (sometimes two stops, but not often). You can also use 400 speed\r\n           film (print or slide film) and gain two \\\"stops\\\" in any camera\r\n           over 100 ASA film. This is a HUGE advantage\r\n           if you are in a <strong>low light situation</strong>, say photographing\r\n           animals just at first or last light. <br>\r\n           <br>\r\n           Both <strong>Kodak</strong> and <strong>Fuji</strong> have\r\n           outstanding film in both 100 and 400 speed ASA. <strong>Provia</strong> is\r\n           the professional film and <strong>Sensia</strong> is their\r\n           lower priced amateur film. Really, there is little difference between\r\n           the two. For print film I use their <strong>Reala</strong> or <strong>Superia</strong>, which comes\r\n           in speeds up to 1600 ASA! Fuji has a more saturated look that I find\r\n           appealing, however it has a lot of contrast like you see in this photo\r\n           of a cheetah shot on <strong>Provia 100</strong> in South Africa.<br>\r\n           <br>\r\n           Sometimes, <em><strong>especially for wildlife</strong></em> I prefer\r\n           the warmer \\\"look\\\" that\r\n           <strong>Kodak film</strong> gives me, (usually for \\\"earth tone\r\n           colors\\\").\r\n           I was fortunate to find 50 more rolls of <strong>Kodachrome\r\n           25 ASA slide film</strong>           (a father\\\'s day gift from my\r\n           oldest son). Since it is out of production I horde it religiously,\r\n           which is to say I only use it on Sundays! I recently returned from\r\n           Africa where I took over 3,000 images, many of them on Kodachrome\r\n           25 speed film (two stops slower than 100 speed film and four stops\r\n           slower than 400 speed film), but I nearly always used a tripod or\r\n           monopod. I was amazed how well it still holds up in todays world of\r\n           fast and ultra fast films. Here is a lion shot with the Kodachrome\r\n           25 ASA speed film for comparison. Notice the difference between this\r\n           photo and the Cheetah above. The new <strong>Kodak 100 SW</strong> and\r\n           other 100 ASA speeds films are <strong>simply outstanding</strong> and\r\n           I will be using more of them in the future. <br>\r\n           <br>\r\n           Want to have a 600 f4 but cannot afford it? NO PROBLEM, just use your\r\n           300 mm f4 with a 2x converter (I have done it many times) but use\r\n           400 speed film instead of 100 speed film. (I use Fuji Provia slide\r\n           film in both, or Sensia, or even their 100 and 400 speed print film).\r\n           The 300 f4 lens with the converter gives you an effective 600 f8 (since\r\n           you lose two stops of light with this converter), BUT if you use 400\r\n           speed film instead of traditional 100 speed film you gain these two\r\n           stops back, so you have an effective 600 mm lens, <em><strong>shooting\r\n           at an effective f4 instead of f8</strong></em>.',5,'1118164227_Lion.jpg','left','on',4,'on','');
INSERT INTO blogs_page_block VALUES (7,'','','<br> Thanks,<br><br>\r\n',6,'','bottom','on',4,'on','');
INSERT INTO blogs_page_block VALUES (8,'','','',7,'1118164802_RaymondSignature.gif','bottom','on',4,'on','');

--
-- Table structure for table 'blogs_page_sub'
--

CREATE TABLE blogs_page_sub (
  id int(11) NOT NULL auto_increment,
  title varchar(255) default NULL,
  position int(11) default NULL,
  page_link varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'blogs_page_sub'
--



--
-- Table structure for table 'blogs_page_sub_block'
--

CREATE TABLE blogs_page_sub_block (
  id int(11) NOT NULL auto_increment,
  title blob,
  text blob,
  text_detail blob,
  block_position int(11) default NULL,
  img varchar(255) default NULL,
  img_position varchar(255) default NULL,
  img_visible varchar(255) default NULL,
  page_sub_id int(11) default NULL,
  visible varchar(255) default NULL,
  img_popup varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'blogs_page_sub_block'
--



--
-- Table structure for table 'blogs_page_sub_block_sub'
--

CREATE TABLE blogs_page_sub_block_sub (
  id int(11) NOT NULL auto_increment,
  title blob,
  text blob,
  text_detail blob,
  block_position int(11) default NULL,
  img varchar(255) default NULL,
  img_popup varchar(255) default NULL,
  img_position varchar(255) default NULL,
  img_visible varchar(255) default NULL,
  block_id int(11) default NULL,
  visible varchar(255) default NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

--
-- Dumping data for table 'blogs_page_sub_block_sub'
--

