
// $Id: dbl_select_mover.js,v 1.4 2005/06/21 16:45:09 alexander Exp $

function cleanselect(name) {
  var myfield=getelement(name);
  if(myfield) {
     size=myfield.options.length-1;
     count=size;
     for(i=0;i<=size;i++) {
        myfield.options[count]=null;
        count=count-1;
     }
  }
}

// function RemoveOption(name, index) {
//   if(myfield=getelement(name)) {
//     myfield.options[index]=null;
//   }
// }


function MoveOption(source,target) {
  var mytarget=getelement(target);
  if(mytarget) {
    var mysource=getelement(source);
    if(mysource) {
      do{
      if(mysource.selectedIndex >=0 && mysource.options[mysource.selectedIndex].value!='') {
        newtext=mysource.options[mysource.selectedIndex].text;
        newvalue=mysource.options[mysource.selectedIndex].value;
        size=mytarget.options.length;
        newoption=new Option(newtext,newvalue,false,false);
        mytarget.options[size]=newoption;
        mytarget.options[size].selected=true;
        mysource.options[mysource.selectedIndex]=null;
        mysource.value='';
        mysource.focus();
      }
      }while(mysource.type=='select-multiple' && mysource.selectedIndex >=0);
    }
  }
}

function in_array(val, arr){
    for(var i in arr){
        if(arr[i] == val){
            return true;
        }
    }
    return false;
}

// function preselect_options(values, field1, field2) {
//     for(var i=0; i < document.forms['frm'].elements[field1].options.length; i++){
//         if(in_array(document.forms['frm'].elements[field1].options[i].value, values)){
//             document.forms['frm'].elements[field1].options[i].selected = true;
//         }
//     }
//     MoveOption(field1, field2);
// }

function getelement(n, d) { //v4.01
    return MM_findObjSelmover(n, d);
}


function MM_findObjSelmover(n, d) { //v4.01
  var p,i,x;
  if(!d) d=document;
  if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);
  }
  if(!(x=d[n])&&d.all) x=d.all[n];
  for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObjSelmover(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n);
  return x;
}

function moveposition(field,how) {
    var myfield=getelement(field)
    if(myfield) {
    tomove=myfield.selectedIndex;
    moveme=false;
    if(how=='up' && tomove>0) {
    newpos=tomove-1;
    moveme=true;
    };
    if(how=='down' && tomove<myfield.options.length-1) {
     newpos=tomove+1;
    moveme=true;
    };
    if(moveme==true) {
      var before=myfield.options[newpos];
      var actual=myfield.options[tomove];
      myfield.options[newpos]=new Option('New____Move___option','New____Move___option',false,false);
      myfield.options[tomove]=new Option('New____actual___option','New____actual___option',false,false);
      myfield.options[newpos]=actual;
      myfield.options[tomove]=before;
    }
    }

    checkbuttons(field);
    }

function checkbuttons(name) {
      var myfield=getelement(name);
      if(myfield.options.length==0 || myfield.value=='') {
        getelement(name+'movedown').disabled=true;
        getelement(name+'moveup').disabled=true;
        getelement(name+'delete').disabled=true;
      } else {
        getelement(name+'delete').disabled=false;
        if(myfield.selectedIndex==0) {
          getelement(name+'moveup').disabled=true;
        } else {
          getelement(name+'moveup').disabled=false;
        };
        if(myfield.selectedIndex==myfield.options.length-1) {
          getelement(name+'movedown').disabled=true;
        } else {
          getelement(name+'movedown').disabled=false;
        }
      }
}

function delfield(name) {
        var myfield=getelement(name);
        if(myfield) {
          todelete=myfield.selectedIndex;
      fieldoptions=myfield.options[myfield.selectedIndex].value.split('|');
      fieldname=fieldoptions[1];
          if(confirm('Are you sure?')) {
            myfield.options[todelete]=null;
        if(myfield.options.length==todelete && myfield.options.length!=0) {
              myfield.options[myfield.options.length-1].selected=true;
        }
        else {
          if(myfield.options.length!=0) myfield.options[todelete].selected=true;
        }
     }
  }
  checkbuttons(name);
}


function selectall(name) {
  var myfield=getelement(name);
  if(myfield) {
    for(i=0;i<myfield.options.length;i++) {
      myfield.options[i].selected=true;
    }
  }
}
