<?
include ('../includes/global.php');

//DB connectivity
$link=dbconnect();

if($t == "credit")
{
    $contents=file_reader("$admin_path/credit.html");
    print <<<HTM
     <meta Content-type: text/html>
     <body>
     <b><font color=red>$message</font></b><br><br>
     $contents
HTM;

print  <<<HTM
<br><br><pre>Click the link. <a href="$admin_url/admin.php" target="_top">Go to Admin Index Page</a></pre>
HTM;
}
elseif($t == "debit")
{
     $contents=file_reader("$admin_path/debit.html");
     print <<<HTM
        <meta Content-type: text/html>
        <body>
        <b><font color=red>$message</font></b><br><br>
        $contents
HTM;

print  <<<HTM
<br><br><pre>Click the link. <a href="$admin_url/admin.php" target="_top">Go to Admin Index Page</a></pre>
HTM;
}
elseif($t == "creditdone")
{
    //to get the mem_id of the persons email id
    $mem_id=get_mem_id($u);
    $qry="INSERT member_credit set mem_id=$mem_id,r_credit='$reason',credits=$points,c_date=CURDATE()";
    mysql_query($qry);   
    header("Location:$admin_url/transactions.php?t=credit&message=Account+Credited\n\n");
}
elseif($t == "debitdone")
{
    //to get the mem_id of the persons email id
    $mem_id=get_mem_id($u);
    $qry="INSERT member_debit set mem_id=$mem_id,r_debit='$reason',debits=$points,d_date=CURDATE()";
    mysql_query($qry);
    header("Location:$admin_url/transactions.php?t=debit&message=Account+Debited\n\n");
}
dbclose($link);
?>
