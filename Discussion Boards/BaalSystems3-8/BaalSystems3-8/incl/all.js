function check1form(n){
//txt=document.y.detail;nme=document.y.name
sbj=document.y.title;
if((nme.value!='')&&(sbj.value!='')){return true}
else{alert(n);return false}}

function check1form1(n){
pwd=document.y.password;nme=document.y.name

if((pwd.value!='')&&(nme.value!='')){return true}
else{alert(n);return false}}

 function check1form2(n){

  if (document.y.name.value == "")
  {
    alert("Please enter a value for the \"User Name\" field.");
    document.y.name.focus();
    return (false);
  }

  if (document.y.password.value == "")
  {
    alert("Please enter a value for the \"Password\" field.");
    document.y.password.focus();
    return (false);
  }
   if (document.y.mail.value == "")
  {
    alert("Please enter a value for the \"Email\" field.");
    document.y.mail.focus();
    return (false);
  }
  else
  {
    if (!checkEmail(document.y.mail.value))
    {
        alert("Please enter a valid email id.");
            document.y.mail.focus();
            return (false);
    }
  }             
 
  return (true);    
}
function checkEmail(mail) {

if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail)){
return (true);
}
return (false);
}

function moveBoth(fid) {
    pos=prompt("Please, specify the new position", 1);
    if (!pos) return;
    pos=parseInt(pos);
    if (!pos) moveBoth(fid);
    else document.location.href="order.php?fid=" + fid + "&action=both&pos=" + pos;
}