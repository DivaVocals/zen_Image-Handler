/***********************************************
* DD Tab Menu II script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

//Set tab to intially be selected when page loads:
//[which tab (1=first tab), ID of tab content to display]:
var initialtab=[1, "sc1"]

//Turn menu into single level image tabs (completely hides 2nd level)?
var turntosingle=0 //0 for no (default), 1 for yes

//Disable hyperlinks in 1st level tab images?
var disabletablinks=0 //0 for no (default), 1 for yes

////////Stop editting////////////////

var previoustab=""

if (turntosingle==1)
document.write('<style type="text/css">\n#tabcontentcontainer{display: none;}\n</style>')

function expandcontent(cid, aobject){
if (disabletablinks==1)
aobject.onclick=new Function("return false")
if (document.getElementById && turntosingle==0){
highlighttab(aobject)
if (previoustab!="")
document.getElementById(previoustab).style.display="none"
document.getElementById(cid).style.display="block"
previoustab=cid
}
}

function highlighttab(aobject){
if (typeof tabobjlinks=="undefined")
collectddimagetabs()
for (i=0; i<tabobjlinks.length; i++)
tabobjlinks[i].className=""
if (aobject != null)
 {
  aobject.className="current"
 }
}

function collectddimagetabs(){
var tabobj=document.getElementById("slidetabsmenu")
tabobjlinks=tabobj.getElementsByTagName("a")
}


function do_onload(){
if (document.getElementById("slidetabsmenu") != null)
{
collectddimagetabs()
expandcontent(initialtab[1], tabobjlinks[initialtab[0]-1])
}
//do_tabmagic()
//ShowTabs()
}

function do_tabmagic() {
	var tabmain= document.getElementById('tabcontentcontainer')
	if (tabmain != undefined) {
		var tabContainer= tabmain.getElementsByTagName("*");
	}
	var productDetails= document.getElementById('productDetailsList');
	var productDetailstab= document.getElementById('productDetailsList_tab');
	if (tabContainer != undefined) {
		var hackArr = new Array();
		var NewArr = new Array();
		
		hackArr[0] = document.getElementById('cartAdd');
		hackArr[1] = document.getElementById('productDetailsList');
		hackArr[2] = document.getElementById('productAttributes');
		hackArr[3] = document.getElementById('productQuantityDiscounts');
		hackArr[4] = document.getElementById('productAdditionalImages');
		hackArr[5] = document.getElementById('alsoPurchased');
		hackArr[6] = document.getElementById('crossSell');
		hackArr[7] = document.getElementById('reviewsDefault');
		hackArr[8] = document.getElementById('productDescription');
		hackArr[9] = document.getElementById('attribsOptionsText');
		hackArr[10] = document.getElementById('productMainImage');
		hackArr[11] = document.getElementById('productName');
		hackArr[12] = document.getElementById('productPrices');
		hackArr[13] = document.getElementById('freeShippingIcon');
		
		NewArr[0] = document.getElementById('cartAdd_tab');
		NewArr[1]  = document.getElementById('productDetailsList_tab');
		NewArr[2] = document.getElementById('productAttributes_tab');
		NewArr[3]  = document.getElementById('productQuantityDiscounts_tab');
		NewArr[4]  = document.getElementById('productAdditionalImages_tab');
		NewArr[5] = document.getElementById('alsoPurchased_tab');
		NewArr[6] = document.getElementById('crossSell_tab');
		NewArr[7]  = document.getElementById('reviewsDefault_tab');
		NewArr[8]  = document.getElementById('productDescription_tab');
		NewArr[9]  = document.getElementById('attribsOptionsText_tab');
		NewArr[10] = document.getElementById('productMainImage_tab');
		NewArr[11] = document.getElementById('productName_tab');
		NewArr[12] = document.getElementById('productPrices_tab');
		NewArr[13] = document.getElementById('freeShippingIcon_tab');
		

		for (var j = 0; j<hackArr.length; j++){
			if (hackArr[j] != undefined) {
				 for (var i = 0; i<tabContainer.length; i++){
				 sTmp = tabContainer[i].id.split("_");
					 if(hackArr[j].id == sTmp[0]) {
						////alert(sTmp[0] + '(' + j + ') = ' + hackArr[j].id + '(' + j + ')');
						////alert(hackArr[j].innerHTML);
						 hackArr[j].style.display="none";
						 hackArr[j].innerHTML='';
						 hackArr[j].outerHTML='';
						 break;
					 }
				 }	
			}
		}

		if (hackArr[8] != undefined) {
			hackArr[8].style.display="none";
		}				

		for (var j = 0; j<NewArr.length; j++){
			if (NewArr[j] != undefined) {
				for (var i = 0; i<tabContainer.length; i++){
					if(tabContainer[i].id == NewArr[j].id)  {
						sTmp = NewArr[j].id.split("_");
						NewArr[j].id=sTmp[0];
						break;
					}
				}	
			}
		}
	}
}

function ShowTabs() {
	var tppblock = document.getElementById('tpptabBlock')
	if (tppblock != undefined) {
		tppblock.style.display = 'block';
	}
}

if (window.addEventListener)
window.addEventListener("load", do_onload, false)
else if (window.attachEvent)
window.attachEvent("onload", do_onload)
else if (document.getElementById)
window.onload=do_onload
