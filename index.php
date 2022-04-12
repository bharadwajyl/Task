<?php
session_start();
require("database.php");
//DEFAULT OPTIONS
$usname = 'Admin';
$dropdowncontent = '
<form id="2">
<a><input type="text" name="usname" placeholder="Username or email*" autocomplete="off" maxlength="100" required=""></a>
<a><input type="password" name="password" placeholder="Password*" autocomplete="off" maxlength="20" required=""></a>
<a><button class="btn1" onclick="event.preventDefault();buttontype(2)">LogIn</button></a>
</form>
<a href="#open-modal">Register</a>';
$navbuttons = '';
$modal = '
<div id="open-modal" class="modal-window signup-modal">
<div>
<a href="#" title="Close" class="modal-close" id="modal-close"><i class="fa fa-times"></i></a>
<h1>SignUp</h1>
<div class="modal-content signup-content">
<form id="1">
<input type="text" name="usname" placeholder="Username" maxlength="50" required="" />
<input type="email" name="email" placeholder="Email" maxlength="100" required="" />
<input type="text" name="password" placeholder="Password" maxlength="20" required="" />
<button class="btn1" onclick="event.preventDefault();buttontype(1)">Create an account</button>
</form>
</div>
</div>
</div>';


//FETCH ALL THE INVENTORIES FROM DB
$defaultsql = "SELECT * FROM Inventory ORDER BY id DESC LIMIT 30";
$defaultresult = mysqli_query($conn, $defaultsql);



//CHECK IF SESSION IS UNSET OR NOT
if(!empty($_SESSION["session_code"])){
$sessioncode = $_SESSION["session_code"];
$sql = "SELECT * FROM BookInventory WHERE phrase='$sessioncode'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$usname = "" . $row["usname"]. "";
$dropdowncontent = '
<a><button class="btn1" onclick="event.preventDefault();buttontype(0)">LogOut</button></a>';
$navbuttons = '<a href="portal" class="float_right">Dashboard</a>';
$modal = '';
}}
}


//POST METHOD
if(isset($_POST["posttype"])){
if($_POST["posttype"] == "1"){
$regname = $_POST["usname"];
$regemail = $_POST["email"];
$regpswd = $_POST["password"];
//CHECK USERNAME IN DB
$sql0 = "SELECT * FROM BookInventory WHERE usname='$regname'";
$result0 = mysqli_query($conn, $sql0);
if (mysqli_num_rows($result0) > 0) {
echo"username not found";
return 1;
}
//CHECK USERNAME IN DB
$sql1 = "SELECT * FROM BookInventory WHERE email='$regemail'";
$result1 = mysqli_query($conn, $sql1);
if (mysqli_num_rows($result1) > 0) {
echo"email already in use";
return 1;
}
//CHECK FOR WHITE SPACE & SPECIAL CHARS IN USERNAME
if(preg_match('/[^A-Z]/i', $regname)){
echo"username should not contain space or special characters";
return 1;
}
//VALIDATE EMAIL
$domains = array('gmail.com', 'outlook.com', 'yahoo.in', 'yahoo.com', 'hotmail.com');
$pattern = "/^[a-z0-9._%+-]+@[a-z0-9.-]*(" . implode('|', $domains) . ")$/i";
if (!preg_match($pattern, $regemail)) {
echo'Email not allowed';   
return 1;
}
else if (!filter_var($regemail, FILTER_VALIDATE_EMAIL)) {
echo 'Invalid email format';
return 1;
}
//VALIDATE PASSWORD
else if (strlen($regpswd) <= 7) {
echo'Password should contain atleast 8 Char';
return 1;
}
else if(!preg_match("#[0-9]+#",$regpswd)) {
echo'Password should contain atLeast a digit';
return 1;
}
else if(!preg_match("#[A-Z]+#",$regpswd)) {
echo'Password should contain atleast one uppercase letter';
return 1;
}
else if(!preg_match("#[a-z]+#",$regpswd)) {
echo'Password should contain atleast one lowercase letter';
return 1;
}
else{
$sql2 = "INSERT INTO BookInventory (usname, email, password, phrase)
VALUES ('$regname', '$regemail', '$regpswd', '')";
if ($conn->query($sql2) === TRUE) {
echo"Registration successfull";
return 1;
}
else{
echo"Failed to register";
return 1;
}
}
}
if($_POST["posttype"] == "2"){
$logusname = $_POST["usname"];
$logpswd = $_POST["password"];
//CHECK USERNAME AND PASSWORD IN DB
$sql1 = "SELECT * FROM BookInventory WHERE usname='$logusname' OR email='$logusname' AND password='$logpswd'";
$result1 = mysqli_query($conn, $sql1);
if (mysqli_num_rows($result1) > 0) {
//SESSION CODE GENERATE
function random_strings($length_of_string)
{$str_result = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
return substr(str_shuffle($str_result),
0, $length_of_string);}
$session_code = random_strings(30);
//SET COOKIE AND SESSION
setcookie("sessioncode", $session_code,time() + (10 * 365 * 24 * 60 * 60));
$_SESSION["session_code"] = $session_code;
//UPDATE SESSION ROW IN DB
$update = "UPDATE BookInventory SET phrase = '$session_code' WHERE usname='$logusname' OR email='$logusname'";
mysqli_query($conn, $update);
echo"reload";
return 1;
}  
else{
echo"Credentials Unfound";
return 1;
}
}
if($_POST["posttype"] == "0"){
if(!empty($_SESSION['session_code'])){
unset($_SESSION['session_code']);
setcookie("sessioncode", "", time() - 3600);
session_destroy();
echo"reload";
return 1;
}
}
}
?>

<!DOCTYPE html>
<html>
<head>

<!--TITLE-->
<title>BookInventory</title>

<!--META TAGS-->
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="author" content="" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no" />

<!--FONTAWESOME-->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!--PLUGIN-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<!--GOOGLE FONTS-->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet"> 

<!--EXTERNAL CSS-->
<link rel="stylesheet" href="css/main.css" />
<link rel="stylesheet" href="css/animation.css" />

<body>

<!--HEADER-->
<header>
<div class="topnav" id="myTopnav">
<a href="#">Home</a>
<div class="dropdown">
<button class="dropbtn"><i class="fa fa-user-circle-o"></i> <?php echo $usname ?>
  <i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-content">
<?php echo$dropdowncontent ?>
</div>
</div> 
<?php echo$navbuttons ?>
<a href="javascript:void(0);" class="icon" onclick="nav()">
<i class="fa fa-bars"></i>
</a>
</div>
</header>


<!--CONTAINR-->
<div class="container">
<?php
if (mysqli_num_rows($defaultresult) > 0) {
while($row = mysqli_fetch_assoc($defaultresult)) {
$inventory = "" . $row["inventory"]. "";
if (strlen($inventory) > 20){
$inventorname = substr($inventory, 0, 18) . '...';
}
else{
$inventorname = $inventory;
}
echo'
<div class="card">
<i class="fa fa-book"></i>
<p>'.$inventorname.'</p>
</div>
';
}} 
?>
</div>




<!--MODAL-->
<?php echo$modal  ?>


<!--JAVASCRIPT-->
<script type="text/javascript" src="js/main.js"></script>
<script type="text/javascript" src="js/ajax.js"></script>
</body>
</html>
