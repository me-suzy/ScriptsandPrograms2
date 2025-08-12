SKINS README

Editing the skins in your favorite editor gives you all controll about how your weblog looks. I tried to make the templating-system as flexible as possible. To do so, I decided to seperate code from design, so you don't have to wade through hundreds of hardly understandable lines of php-code, just to change the background-color of your site.

...Well, this is not completely true: I did use some php-code in the templates. Just because it is so much more flexible than using template-tags like "{title}".

Ok, so you've never edited a line of php in your life, and you downloaded Zomplog so you can leave the dirty work over to others... Well, be assured: you won't have edit a single line of php-code while editing the templates! As long as you remember that everything between "<?" and "?>"-tags stands for a dynamic part of your site. I named every tag appropriately, so there's a good chance you can figure out what every tag does.

For example <? displayAuthors('<br />'); ?> gives you a list of all authors that contribute to your weblog. Similarly, <? displayCategories('<br />'); ?> provides you with a list of all categories you've created. And really, this is all there's to it! 

So you can create your own lay-out exactly as you have in mind, and then place the tags anywhere you want them to be, to add the functionality. You can even leave out all tags you're not gonna need! Though there are some tags I think you're gonna need. These are the tags in MAINPAGE.PHP, because that's the core of your weblog. And really, if you want, you can leave out all other tags.

Ok, let's get started! A skin basically consists of 5 parts:
* header.php --> the head of each page, this is where your weblog-title went.
* footer.php --> the bottom of each page, you could put your copyright info over here
* sidemenu.php --> this is the part where all lists go (latest posts, pages, categories, etc.)
* mainpage.php --> well, need I say more, this is where all your writings go.
* style.css --> the main stylesheet for your blog

It's a good idea to make a design in your favorite editor, and later split it in pieces. As long as you keep the filenames identical to the standard filenames (header.php, footer.php, mainpage.php and sidemenu.php) everything else depends on your imagination!

TIPS & TRICKS

Ok, I see you're getting a hang of it! Let's move on to some extended features I built into the templating system:

TIP 1: As you might have noticed, in many tags you see I've included the HTML-tag for a line break: '<br />'. This line break makes sure every result starts on a new line. This is fine if you would like a vertical list of let's say categories or authors. But what if a horizontal list would fit into your design much better? All you have to do is change the line-break-tag to a space. So change '<br />' to ' ' and your results will line up horizontally! Didn't quite get it? Here's an example of how to display your category list horizontally:

change <? displayCategories('<br />'); ?> to <? displayCategories(' '); ?>

Easy, ain't it?

TIP 2: You might wonder what these tags mean that start with "$lang"... These tags refer to words in your language file. So if you've chosen english as the main language for your weblog, <? echo "$lang_categories"; ?> would be replaced by the word "categories", or in Dutch (which is my main language) it would be replaced by "categorien".

TIP 3: Once you've created a Zomplog skin that you like very much, you could share it with other Zomplog-users! Wouldn't it be great if one day just surfing on the web, you bump onto a website which has your skin? Anyway sharing good ideas is nice, and Zomplog is my idea I want to share. So if you think others could benefit from your ideas, just upload the skin to the zomplog forum at http://zomplog.zomp.nl, and I'll make sure it gets featured on the Zomplog site!



