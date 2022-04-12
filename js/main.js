//NAV
function nav() {
var x = document.getElementById("myTopnav");
if (x.className === "topnav") {
x.className += " responsive";
} else {
x.className = "topnav";
}
}



//LOADER
var myVar;
function loader() {
myVar = setTimeout(showPage, 1000);
}
function showPage() {
document.getElementById("loader").style.display = "none";
document.getElementById("myDiv").style.display = "block";
clearTimeout(myVar);
}



//IF SIGNLE CHECKBOX IS CHECKED THEN POPUP
var slides = document.getElementsByClassName("delete");
var inventory = document.getElementsByClassName("inputinventory");
for (var i = 0; i < slides.length; i++) {
slides[i].addEventListener("change", function(){trashicon()});
}
function trashicon(){
var ln = 0;
for(var i=0; i< slides.length; i++) {
if(slides[i].checked)
ln++
}
if(ln > 0){
document.getElementById("main_trash").style.display="block";
localStorage.getItem("bookinventoryarrow");
if(localStorage.getItem("bookinventoryarrow") == "" || localStorage.getItem("bookinventoryarrow") === null){
localStorage.setItem("bookinventoryarrow", "true");
var g = document.createElement('div');
g.setAttribute("id", "arrow");
g.innerHTML="<img src='../images/shape.svg'>";
g.style.animationName = "fadeInAnimation"; 
document.body.appendChild(g);
setTimeout(function(){
g.style.display="none";
}, 5000);
}
}
else{
document.getElementById("main_trash").style.display="none";
}
}


//DELETION
function deletion(category){
alert(category);
}



//DETECTIONs
document.getElementById("modal-close").addEventListener("click", function(){
if(document.getElementById("main_trash").style.display="block"){
document.getElementById("main_trash").style.display="none";
for(var i=0; i< slides.length; i++) {
slides[i].checked = false;
}
}
});



//ESC KEY DETECTION
document.onkeydown = function(evt) {
evt = evt || window.event;
var isEscape = false;
if ("key" in evt) {
isEscape = (evt.key === "Escape" || evt.key === "Esc");
} else {
isEscape = (evt.keyCode === 27);
}
if (isEscape) {
document.getElementById("main_trash").style.display="none";
window.location.href="#";
for(var i=0; i< slides.length; i++) {
slides[i].checked = false;
}
}
};





//MODAL
function modal(type){

}
