<?

include("prepend.php3");



if ($cmd == "delete") // Delete Question Include

	{

		$dc->query("DELETE FROM faq_questions WHERE id='$id';");

		print "<link rel=\"stylesheet\" href=\"../modules/styles.css\">";

		print "<br><div align=center class=head style=color:green>Question Deleted Successfully!</div>";

		print "<script>function init(){setTimeout(\"parent.opener.location.href='index.php?cmd=faq';window.close()\",3000);}window.onload = init;</script>";

		exit;

	}



if ($cmd == "category") // Create Category Include

	{

		$dc->query("INSERT INTO faq_categories SET id='', name='$name';");

		print "<link rel=\"stylesheet\" href=\"../modules/styles.css\">";

		print "<br><div align=center class=head style=color:green>Category \"$name\" Created Successfully!</div>";

		print "<script>function init(){setTimeout(\"parent.opener.location.href=parent.opener.location.href;window.close()\",3000);}window.onload = init;</script>";

		exit;

	}



if ($cmd == "deleteCategory") // Delete Category Include

	{

		$dc->query("DELETE FROM faq_categories WHERE id='$id';");

		$dc->query("DELETE FROM faq_questions WHERE category='$id';");

		print "<link rel=\"stylesheet\" href=\"../modules/styles.css\">";

		print "<br><div align=center class=head style=color:green>Category Deleted Successfully!</div>";

		print "<script>function init(){setTimeout(\"parent.opener.location.href=parent.opener.location.href;window.close()\",3000);}window.onload = init;</script>";

		exit;

	}

?>