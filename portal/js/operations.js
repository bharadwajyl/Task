function deleteinventory(id) {
var url = ""; 
$.ajax({
type: "POST",
url: url,
data: "&deleteinventory="+id,
success: function(data)
{
if(data.match(/deleted/gi)){
alert("Record deleted successfully");
$('#table').load(window.location.href + '#table');
return 1;
}
else{
alert(data);
}
}
});
}





function inventorybooks(id) {
var url = ""; 
$.ajax({
type: "POST",
url: url,
data: "&inventorybooks="+id,
success: function(data)
{
if(data.match(/section/gi)){
document.getElementById("books").innerHTML=data;
return 1;
}
else{
alert(data);
}
}
});
}






function addbook(id) {
var url = ""; 
$.ajax({
type: "POST",
url: url,
data: $("#"+id).serialize() + "&posttype="+id,
success: function(data)
{
if(data.match(/success/gi)){
alert("New book added to the Inventory");
$('#table').load(window.location.href + '#table');
return 1;
}
else{
alert(data);
}
}
});
}