function nameDefined(c,n) {
 var s=removeBlanks(c)
 var pairs=s.split(";")
 for(var i=0;i<pairs.length;++i) {
  var pairSplit=pairs[i].split("=")
  if(pairSplit[0]==n) return true
 }
 return false
}
function removeBlanks(s) {
 var temp=""
 for(var i=0;i<s.length;++i) {
  var c=s.charAt(i)
  if(c!=" ") temp += c
 }
 return temp
}
function getCookieValue(c,n) {
 var s=removeBlanks(c)
 var pairs=s.split(";")
 for(var i=0;i<pairs.length;++i) {
  var pairSplit=pairs[i].split("=")
  if(pairSplit[0]==n) return pairSplit[1]
 }
 return ""
}
