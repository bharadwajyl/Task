function buttontype(type){
var url = "";
var data = "&posttype="+type;
if (type == "1" || type == "2" || type == "3"){
data = $("#"+type).serialize() + "&posttype="+type;
}
$.ajax({
type: "POST",
url: url,
data: data,
success: function(data)
{   
if(data.match(/registration/gi)){
alert(data);
window.location.href="#";
return 1;
}
else if(data.match(/reload/gi)){
window.location.reload();
return 1;
}
else if(data.match(/section/gi)){
alert("New Inventory has been added successfully");
$('#table').load(window.location.href + '#table');
return 1;
}
else{
alert(data);
return 1;
}
}
});
}