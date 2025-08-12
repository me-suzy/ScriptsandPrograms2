<? header("Cache-Control: no-cache"); 
 session_start();
 session_destroy();
?>
<script language="javascript">
parent.frames['top'].location.href = 'index.php';
</script>