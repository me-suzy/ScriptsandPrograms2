nn=new Image;nn.src='pics/ou.png';
mm=new Image;mm.src='pics/of.png';
oo=new Image;oo.src='pics/on.png';
qq=new Image;qq.src='pics/sbm.png';
rr=new Image;rr.src='pics/sbe.png';

function cho(r){
document.a1.src=mm.src
document.a2.src=mm.src
document.a3.src=mm.src
document.a4.src=mm.src
document.a5.src=mm.src
switch(r){
case 1:document.a1.src=oo.src;break
case 2:document.a2.src=oo.src;break
case 3:document.a3.src=oo.src;break
case 4:document.a4.src=oo.src;break
case 5:document.a5.src=oo.src;break
default:document.a5.src=oo.src;break
}document.y.rating.value=r}

function ssm(b){
a=document.y.rating.value
switch(a){
case '1':w="POOR";break
case '2':w="FAIR";break
case '3':w="GOOD";break
case '4':w="VERY GOOD";break
default:w="EXCELLENT";break
}
er=confirm('You are about to rate '+b+' as '+w+' at HOTSCRIPTS.COM. Process?')
if(er){document.y.submit()}}

function mov(u){if(u.src!=oo.src){u.src=nn.src}}
function mot(u){if(u.src==nn.src){u.src=mm.src}}
