clr=new Array('00','11','22','33','44','55','66','77','88','99','aa','bb','cc','dd','ee','ff');
 document.write("<table border=0 cellpadding=0 cellspacing=0 id=\"picker\" style=\"border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;\">");
 document.write("<tr height=14>");
 var red=15;
 var green=0;
 var blue=0;
 for (green=0;green<16;green++) { 
 document.write('<td width=2  bgcolor="#'+clr[red]+clr[green]+clr[blue]+'" onClick=\"document.all.bgcolor.value = this.bgColor; preview.bgColor = this.bgColor;\"></td>'); 
 }
 green=15;
 blue=0;
 red=15;
 for (red=15;red>-1;red--) { 
 document.write('<td width=2  bgcolor="#'+clr[red]+clr[green]+clr[blue]+'" onClick=\"document.all.bgcolor.value = this.bgColor; preview.bgColor = this.bgColor;\"></td>'); 
 }
 green=15;
 blue=0;
 red=0;
 for (blue=0;blue<16;blue++) { 
 document.write('<td width=2  bgcolor="#'+clr[red]+clr[green]+clr[blue]+'" onClick=\"document.all.bgcolor.value = this.bgColor; preview.bgColor = this.bgColor;\"></td>'); 
 }
 
 green=15;
 blue=15;
 red=0;
 for (green=15;green>-1;green--) { 
 document.write('<td width=2  bgcolor="#'+clr[red]+clr[green]+clr[blue]+'" onClick=\"document.all.bgcolor.value = this.bgColor; preview.bgColor = this.bgColor;\"></td>'); 
 }
 green=0;
 blue=15;
 red=0;
 for (red=0;red<16;red++) { 
 document.write('<td width=2  bgcolor="#'+clr[red]+clr[green]+clr[blue]+'" onClick=\"document.all.bgcolor.value = this.bgColor; preview.bgColor = this.bgColor;\"></td>'); 
 }
 green=0;
 blue=15;
 red=15;
 for (blue=15;blue>-1;blue--) { 
 document.write('<td width=2  bgcolor="#'+clr[red]+clr[green]+clr[blue]+'" onClick=\"document.all.bgcolor.value = this.bgColor; preview.bgColor = this.bgColor;\"></td>'); 
 }
 document.write("</tr>");
 document.write("</table>"); 
 
 
 document.write("<table border=0 cellpadding=0 cellspacing=0 style=\"border-bottom: 1px solid black; border-left: 1px solid black; border-right: 1px solid black;\">");
 document.write("<tr height=6>");
 for (i=15;i>-1;i--) { 
 document.write('<td width=12  bgcolor="#'+clr[i]+clr[i]+clr[i]+'" onClick=\"document.all.bgcolor.value = this.bgColor; preview.bgColor = this.bgColor;\"></td>'); 
 }
 document.write("</tr>");
 
 
 document.write("</table>");