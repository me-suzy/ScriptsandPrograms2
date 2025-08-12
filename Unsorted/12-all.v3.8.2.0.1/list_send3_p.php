<?PHP
//INITIAL SENDING
include("engine.inc.php");
if ($s_i_val == ""){
$s_i_val = "go";
?>
<iframe src="list_send3_p.php?&id=<?PHP print $id; ?>&s_i_val=<?PHP print $s_i_val; ?>&sendval=<?PHP print $sendval; ?>&nl=<?PHP print $nl; ?>" id="iView<?PHP print $id; ?>" style="width: 350px; height:200px"></iframe>
<?PHP
}
else {
include("send_app2.php");
}
?>