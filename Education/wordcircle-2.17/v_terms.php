<?php

$GLOBALS['page']->head("wordcircle","","After reading these terms you must click the agree box on the registration form",0);

	

	$GLOBALS['page']->tableStart("","100%","TAB","Terms");
	$GLOBALS['page']->tableStart("","100%","TEXT");
	echo('<br><a href="index.php?a=register">Go Back to Registration Page</a><br>
	<br>
	');
	echo('<div class="terms"><strong>Terms and Conditions</strong><br>
	<br>
	We are not responsible for any messages, ideas or documents posted to this website. We do not vouch for or warrant the accuracy, completeness or usefulness of any message, idea or document and are not responsible for the contents of any message or document. All messages and documents express the views of the author of the message or document, not necessarily the views of Word Circle staff. Any user who feels that a message or document is objectionable is encouraged to contact us immediately by email. We have the ability to remove objectionable content and we will make every effort to do so, within a reasonable time frame, if we determine that removal is necessary. You agree, through your use of this service, that you will not use Word Circle to post any material which is knowingly false and/or defamatory, inaccurate, abusive, vulgar, hateful, harassing, obscene, profane, sexually oriented, threatening, invasive of a person\'s privacy, or otherwise violative of any law. You agree not to post any copyrighted material unless the copyright is owned by you.</div>');
	echo('<br><a href="index.php?a=register">Go Back to Registration Page</a>');
	$GLOBALS['page']->tableEnd("TEXT");
	$GLOBALS['page']->tableEnd("TAB");
	
?>