amp='&'; if(document.images){jj=0
bpx=new Image();bpx.src='pics/psw.gif'
bp0=new Image();bp0.src='pics/img.png'
bp1=new Image();bp1.src='pics/ou.png'
bp2=new Image();bp2.src='pics/lb.png'
bp3=new Image();bp3.src='pics/rb.png'
bp4=new Image();bp4.src='pics/s1.gif'}

if(typeof document.layers!='object'){document.write('<link rel="stylesheet" type="text/css" href="css/all.css">')}
if(typeof document.all=='object'){msie=1}else{msie=0}
if(msie==1){document.write('<link rel="stylesheet" type="text/css" href="css/msie.css">')}

function change_color(f,m){url=window.location.toString()
if(url.indexOf('main.php')==-1){window.location='index.php?gcss='+m}
else{window.location='main.php?f='+f+'&gcss='+m}}

function refresh(f){url=window.location.toString()
if(url.indexOf('main.php')==-1&&url.indexOf('show.php')==-1&&url.indexOf('search.php')==-1){ey='index'}else{ey='main'}
pp=Math.round(999999*Math.random());window.location=ey+'.php?f='+f+'&n='+pp}

function check1form(n){
txt=document.y.text;nme=document.y.name
sbj=document.y.title;img=document.y.image
img=img.value.toLowerCase()
if((img.indexOf('.gif')==-1)&&(img.indexOf('.jpg')==-1)&&(img.indexOf('.png')==-1)&&(img.indexOf('.jpeg')==-1)&&(img.indexOf('.jfif')==-1)||(document.y.image.value==null)||(img=='http://')){document.y.image.value=''}
if((txt.value!='')&&(nme.value!='')&&(sbj.value!='')){return true}
else{alert(n);return false}}

function check2form(n){
mll=document.y.mail;nme=document.y.name
sbj=document.y.pass;img=document.y.image
img=img.value.toLowerCase()
if(nme.value.length<3){nme.value=''}if(sbj.value.length<3){sbj.value=''}
if((mll.value.indexOf('@')==-1)||(mll.value.indexOf('.')==-1)||(mll.value.indexOf(' ')!=-1)||(mll.value.length<8)){mll.value=''}
if((img.indexOf('.gif')==-1)&&(img.indexOf('.jpg')==-1)&&(img.indexOf('.png')==-1)&&(img.indexOf('.jpeg')==-1)&&(img.indexOf('.jfif')==-1)||(document.y.image.value==null)||(img=='http://')){document.y.image.value=''}
if((mll.value!='')&&(nme.value!='')&&(sbj.value!='')){return true}
else{alert(n);return false}}

function add_bb(m){a=document.y.text;a.value=a.value+m}

function choose_mempic(s){a=document.y.mem_pic;switch(s){
case'w1':document.qq.src='pics/w1.gif';break;case'w2':document.qq.src='pics/w2.gif';break;
case'w3':document.qq.src='pics/w3.gif';break;case'w4':document.qq.src='pics/w4.gif';break;
case'w5':document.qq.src='pics/w5.gif';break;case'w6':document.qq.src='pics/w6.gif';break;
case'w7':document.qq.src='pics/w7.gif';break;case's1':document.qq.src='pics/s1.gif';break;
case's2':document.qq.src='pics/s2.gif';break;case's3':document.qq.src='pics/s3.gif';break;
case's4':document.qq.src='pics/s4.gif';break;case's5':document.qq.src='pics/s5.gif';break;
case's6':document.qq.src='pics/s6.gif';break;case's7':document.qq.src='pics/s7.gif';break;
default:document.qq.src='pics/w1.gif';break}a.value=s;return false}

function cheat1validator(){document.write('<img src="pics/w1.gif" name="qq" hspace="2" alt="" />')}
function cheat2validator(){document.write('<img src="pics/w1.gif" hspace="2" alt="" onclick="choose_sex(this)" />')}
function choose_sex(q){a=document.y.sex;if(jj==0){q.src='pics/s1.gif';jj=1;a.value='f'}else{q.src='pics/w1.gif';jj=0;a.value='m'}}

function start_impress(){if(msie==1){d=1;mmw=window.setInterval('impress()',20)}}
function stop_impress(){if(msie==1){window.clearInterval(mmw);lnk.style.color='#ffffff'}}

function impress(){
if(d==1){lnk.style.color='#eeeeee';d++}
else if(d==2){lnk.style.color='#dddddd';d++}
else if(d==3){lnk.style.color='#cccccc';d++}
else if(d==4){lnk.style.color='#bbbbbb';d++}
else if(d==5){lnk.style.color='#aaaaaa';d++}
else if(d==6){lnk.style.color='#999999';d++}
else if(d==7){lnk.style.color='#888888';d++}
else if(d==8){lnk.style.color='#777777';d++}
else if(d==9){lnk.style.color='#666666';d++}
else if(d==10){lnk.style.color='#555555';d++}
else if(d==11){lnk.style.color='#444444';d++}
else if(d==12){lnk.style.color='#333333';d++}
else if(d==13){lnk.style.color='#222222';d++}
else if(d==14){lnk.style.color='#111111';d++}
else if(d==15){lnk.style.color='#000000';d++}
else{d=0}}

function preview_post(){
crq=document.y.action
dd=window.open('','prv','height=200,width=550,resizable=1,scrollbars=1')
document.y.action='preview.php';document.y.target='prv'
document.y.submit();dd.focus()
setTimeout("document.y.action=crq;document.y.target=''",500)}

function preview_user(){
crq=document.y.action
dd=window.open('','pru','height=300,width=310,resizable=1')
document.y.action='mempre.php';document.y.target='pru'
document.y.submit();dd.focus()
setTimeout("document.y.action=crq;document.y.target=''",500)}

function show_image(n,m){b=n.src.toString()
if(!n.complete){n.src='pics/img.png'}else if(b.indexOf('pics/img.png')!=-1){n.src=m}
else{dd=window.open(m,'img','height=200,width=300,resizable=1,scrollbars=1,status=1');dd.focus()}}

function attach_image(m){x=document.y.image.value;if(x==''){x='http://'}
z=prompt(m,x);if(z!=null){document.y.image.value=z}}

function no_undo(u){fno=confirm(u);if(fno){return true}else{return false}}

function ban(f,w){w=escape(w);url='ban.php?f='+f+'&ban='+w
aa=window.open(url,'ban','height=270,width=250,resizable=1,scrollbars=1');aa.focus()}

function show_mail(a,b){document.write('<a href="mailto:'+a+'@'+b+'">'+a+'@'+b+'</a>')}

function set_pass(m){x=document.y.key.value;
z=prompt(m,x);if(z!=null){document.y.key.value=z}}

function usr(n){a='memview.php?us='+n;dd=window.open(a,'usr','height=300,width=310,resizable=1');dd.focus()}