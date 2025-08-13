//obtained from http://webdeveloper.earthweb.com/webjs/

function advice()
{
 noun1   = new Array("The frog ","A tree ","A mountain ","A monk ","The woman ","A child ","A snake ","A midget ","A king ","a monkey ","A man ","A reed ","A person ","The lion ","The one ","A friends ","A mob ");
 verb1   = new Array("inside ","coveting ","holding ","walking on ","carrying ","wandering in ","immersed in ","praying to ","floating on ","speaking to ","seeking ","when eating ","who knows ","who needs ","asking for ","arguing over ","lusting for ","wishing for ","washing ");
 noun2   = new Array("the wind ","a dream ","a cloud ","the sea ","a rock ","a dollar ","a fork ","a cup of tea ","a basket of wheat ","a puddle of mud ","a mirror ","a bottle of ginseng ","wisdom ","a fly ","a cow ","the forest ","the desert ","a hot bath ");
 yesorno = new Array("never ","always ","seldom ","usually ","happily ","normally ","frequently ");
 verb2   = new Array("steals ","catches ","pushes ","kills ","destroys ","pursues ","embraces ","plays with ","walks on ","angers ","comments on ","negotiates with ","converses with ","trips ","recieves ","confuses it with ","betrays ","bends ","uses ","abuses ", "makes peace with ");
 noun3   = new Array("a dog.","a dragon.","a brother.","a horse.","a mother.","a priest.","an owl.","a flower.","a monkey.","a llama.","God.","humanity.","a spoon.","a superior being.","a warrior.","a sword","an ally.");

 var number1 = Math.floor(noun1.length * Math.random());
 var number2 = Math.floor(verb1.length * Math.random());
 var number3 = Math.floor(noun2.length * Math.random());
 var number4 = Math.floor(yesorno.length * Math.random());
 var number5 = Math.floor(verb2.length * Math.random());
 var number6 = Math.floor(noun3.length * Math.random());

 return noun1[number1] + verb1[number2] + noun2[number3] + yesorno[number4] + verb2[number5] + noun3[number6];
}

document.writeln(advice());