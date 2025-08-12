<HEAD>
<TITLE>WebRing</TITLE>
<LINK REL=STYLESHEET HREF=../style.css>
</HEAD>
<BODY>
<DIV CLASS=headline>Ring Admin</DIV>
<DIV CLASS=Normal>
<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=0 WIDTH=450>
	<TR>
		<TD WIDTH=450>
<?
if (!isset($mode))
{
	$mode = 'index';
}

require("../config.php");
require("../functions.php");

switch($mode)
{

	case 'index':
        echo "Select from the options below to manage the ring.
              <HR>
              <B>List Options</B>
              <UL>
              <LI><A HREF=$PHP_SELF?mode=incoming>Submissions</A> - View all new ring submissions and approve/reject submissions.</LI><BR>
	      <LI><A HREF=$PHP_SELF?mode=members>Members</A> - View all ring members and edit/delete members.</LI><BR>
              </UL>";
	break;

	case 'incoming':
		echo "<B>Viewing Current Submissions</B><HR>";
		view_submissions();
		break;

	case 'members':
		echo "<B>Viewing Current Members</B><HR>";
		view_members();
		break;

	case 'approve':
		echo "<B>Approve Site</B><HR>";
		$status = approve_site($site);
		if ($status == 'true')
		{
			echo "Site approved.";
                }
		else
		{
			echo "An error has occured.";
		}
		break;

	case 'reject':
		echo "<B>Reject Site</B><HR>";
		$status = reject_site($site);
		if ($status == 'true')
		{
			echo "Site rejected.";
                }
		else
		{
			echo "An error has occured.";
		}
		break;

	case 'edit':
		echo "<B>Edit Site</B><HR>";
		edit_site($site);
		break;

	case 'edit_confirm':
		echo "<B>Edit Site</B><HR>";
		$status = edit_confirm($name, $email, $site_name, $site_url, $description, $id);
		if ($status == 'true')
		{
			echo "Site edited.";
		}
		else
		{
			echo "An error has occured.";
		}
		break;

	case 'delete':
		echo "<B>Delete Site</B><HR>";
		$status = delete_site($site);
		if ($status == 'true')
		{
			echo "Site deleted.";
		}
		else
		{
			echo "An error has occured.";
		}
		break;

}

echo "<P><A HREF=$PHP_SELF?mode=index>Main</A>";

?>
</DIV>
		</TD>
	</TR>
</TABLE>