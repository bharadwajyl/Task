<?php
session_start();
require_once __DIR__ . '/../database.php';
//DEFAULT OPTIONS
$usname = 'Admin';
$dropdowncontent1 = '
<form id="2">
<a><input type="text" name="usname" placeholder="Username or email*" autocomplete="off" maxlength="100" required=""></a>
<a><input type="password" name="password" placeholder="Password*" autocomplete="off" maxlength="20" required=""></a>
<a><button class="btn1" onclick="event.preventDefault();buttontype(2)">LogIn</button></a>
</form>
<a href="#open-modal">Register</a>';
$navbuttons = '';
$dropdowncontent2 = '';



//CHECK IF SESSION IS UNSET OR NOT
if(!empty($_SESSION["session_code"])){
$sessioncode = $_SESSION["session_code"];
$sql = "SELECT * FROM BookInventory WHERE phrase='$sessioncode'";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$usname = "" . $row["usname"]. "";
$userid = "" . $row["id"]. "";



$dropdowncontent1 = '
<a><button class="btn1" onclick="event.preventDefault();buttontype(0)">LogOut</button></a>';
$navbuttons = '<a href="portal" class="float_right">Dashboard</a>';
$dropdowncontent2 = '
<div class="dropdown">
<button class="dropbtn"><i class="fa fa-plus"></i> Inventory
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-content">
<form id="3">
<a><input value="'.$userid.'" name="userid" hidden><input type="text" name="inventory" placeholder="New inventory*" autocomplete="off" maxlength="100" required=""></a>
<a><button class="btn1" onclick="event.preventDefault();buttontype(3)">Add</button></a>
</form>
</div>
</div> 
';
}}
}
else{
header("location:/BOOKINVENTORY/");
return 1;
}



//FETCH ALL THE INVENTORIES FROM DB
$defaultsql = "SELECT * FROM Inventory WHERE userid='$userid'";
$defaultresult = mysqli_query($conn, $defaultsql);



//POST TYPE
if(isset($_POST["posttype"])){
if($_POST["posttype"] == "0"){
if(!empty($_SESSION['session_code'])){
unset($_SESSION['session_code']);
session_destroy();
echo"reload";
return 1;
}
}
if($_POST["posttype"] == "3"){
$inventor = $_POST["inventory"];   
$userid = $_POST["userid"]; 
$sql = "INSERT INTO Inventory (inventory, quantity, userid)
VALUES ('$inventor', '0', '$userid')";
if (mysqli_query($conn, $sql)) {
echo'
<section>
<span><a href="#open-modal" onclick="modal(1)">'.$inventory.'</a></span>
<span>0</span>
<span>Refresh Page</span>
</section>
';
return 1;
}
}
if($_POST["posttype"] == "5"){
$inventoryid = $_POST["inventoryid"];   
$book = $_POST["book"]; 
$quantity = $_POST["quantity"]; 
$price = $_POST["price"]; 
$sql = "INSERT INTO InventoryBooks (book, quantity, price, inventoryid)
VALUES ('$book', '$quantity', '$price', '$inventoryid')";
if (mysqli_query($conn, $sql)) {
$fetch = "SELECT * FROM InventoryBooks WHERE inventoryid='$inventoryid'";
$trows = (mysqli_query($conn, $fetch));
$totalrows = mysqli_num_rows($trows);
$update = "UPDATE Inventory SET quantity = '$totalrows' WHERE id='$inventoryid'";
mysqli_query($conn, $update);
echo"successfull";
return 1;
}
}
}



//DELETE INVENTORY
if(isset($_POST["deleteinventory"])){
$inventoryid = $_POST["deleteinventory"];
$sql = "DELETE FROM Inventory WHERE id='$inventoryid'";
$sqlb = "DELETE FROM InventoryBooks WHERE inventoryid='$inventoryid'";
mysqli_query($conn, $sqlb);
if (mysqli_query($conn, $sql)) {
echo"deleted";
return 1;
}
else{
echo"Try again";
return 1;
}
}



//INVENTORY MODAL
if(isset($_POST["inventorybooks"])){
$inventoryid = $_POST["inventorybooks"]; 
$sql = "SELECT * FROM InventoryBooks WHERE inventoryid='$inventoryid' ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
while($row = mysqli_fetch_assoc($result)) {
$bookid = "" . $row["id"]. "";
$bookname = "" . $row["book"]. "";
$bookquantity = "" . $row["quantity"]. "";
$bookprice = "" . $row["price"]. "";
$totalbooks = mysqli_num_rows($result);
if (strlen($bookname) > 20){
$book = substr($bookname, 0, 18) . '...';
}
else{
$book = $bookname;
}
echo'
<section>
<span><input type="checkbox" class="delete" name="delete[]"> <a href="#">'.$book.'</a></span>
<span>'.$bookquantity.'</span>
<span>'.$bookprice.'</span>
<span>
<input type="text" name="inputinventory[]" class="inputinventory" maxlength="100" placeholder="New inventory*" required>
<i class="fa fa-check" onclick="updatebook('.$bookid.')"></i>
</span>
</section>
';
}
return 1;
}
else{
echo'
<section>
<div id="no-code">No Details Found</div>
</section>
';
}
return 1;
}
?>

<!DOCTYPE html>
<html>
<head>

<!--TITLE-->
<title>BookInventory WebPortal</title>

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
<link rel="stylesheet" href="../css/main.css" />
<link rel="stylesheet" href="../css/animation.css" />

</head>
<body id="table">

<div class="animate-bottom">

<!--HEADER-->
<header>
<div class="topnav" id="myTopnav">
<a href="#">Home</a>
<div class="dropdown">
<button class="dropbtn"><i class="fa fa-user-circle-o"></i> <?php echo $usname ?>
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-content">
<?php echo$dropdowncontent1 ?>
</div>
</div> 
<?php echo$dropdowncontent2 ?>

<div class="dropdown float_right">
<button class="dropbtn"><i class="fa fa-plus"></i> Books
<i class="fa fa-caret-down"></i>
</button>
<div class="dropdown-content" >
<form id="5">
<a>
<select name="inventoryid">

<?php
$insql = "SELECT * FROM Inventory WHERE userid='$userid'";
$inresult = mysqli_query($conn, $insql);
if (mysqli_num_rows($inresult) > 0) {
while($inrow = mysqli_fetch_assoc($inresult)) {
$inventoryname = "" . $inrow["inventory"]. "";
$inventoryid = "" . $inrow["id"]. "";
echo'
<option value="'.$inventoryid.'">'.$inventoryname.'</option>
';
}}
else{
echo'
<option value="0" disabled>Inventories UnFound</option>
';
}
?>
</select>    
</a>
<a><input type="text" name="book" maxlength="80" placeholder="Book name*" required=""></a>
<a><input type="number" name="quantity" maxlength="5" placeholder="Quantity available*" required=""></a>
<a><input type="number" name="price" maxlength="5" placeholder="Cost per book*" required=""></a>
<a><button class="btn1" onclick="event.preventDefault();addbook(5)">Add book</button></a>
</form>
</div>
</div> 

<?php echo$navbuttons ?>
<a href="javascript:void(0);" class="icon" onclick="nav()">
<i class="fa fa-bars"></i>
</a>
</div>
</header>


<!--MAIN-->
<main>
<div>
<section class="title_header">
<span>Inventory</span>
<span>Quantity</span>
<span>Action</span>
</section>
<?php
if (mysqli_num_rows($defaultresult) > 0) {
while($row = mysqli_fetch_assoc($defaultresult)) {
$inventoryid = "" . $row["id"]. "";
$inventory = "" . $row["inventory"]. "";
$quantity = "" . $row["quantity"]. "";
if (strlen($inventory) > 15){
$inventorname = substr($inventory, 0, 12) . '...';
}
else{
$inventorname = $inventory;
}
echo'
<section>
<span><a href="#open-modal" onclick="inventorybooks('.$inventoryid.')">'.$inventorname.'</a></span>
<span>'.$quantity.'</span>
<span><i class="fa fa-trash" onclick="deleteinventory('.$inventoryid.')"></i></span>
</section>
';
}}
else{
echo'
<section>
<span>0 Records Found</span>
</section>
';
}
?>
</div>
</main>



<!--MODAL-->
<div id="open-modal" class="modal-window">
<div>
<a href="#" title="Close" class="modal-close" id="modal-close"><i class="fa fa-times"></i></a>
<div class="modal-content">
<section>
<span>Book</span>
<span>Quantity</span>
<span>Price</span>
<span>Action</span>
</section>
<div id="books">

</div>
</div>
</div>
</div>
</div>


<!--ADDITIONAL-->
<i class="fa fa-trash main_trash" id="main_trash" onclick="deletion(1)" title="Delete"></i>
</div>


<!--JAVASCRIPT-->
<script type="text/javascript" src="../js/main.js"></script>
<script type="text/javascript" src="../js/ajax.js"></script>
<script type="text/javascript" src="js/operations.js"></script>
</body>
</html>
