<?php
include_once("config.php");
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Cell Phone Shop</title>
<style type="text/css">
    .items-wrp{max-width: 740px;padding: 10px;margin: 20px auto;}
    .items-wrp ul{margin:0px;list-style:none;padding:0px;}
    .items-wrp ul li{list-style: none;display: inline-block;margin: 10px;border: 1px solid #ddd;}
    .items-wrp ul li div.item-name{text-align: center;font-weight: bold;font-size: 18px;color: #494949;}
    .items-wrp ul li div.btn-wrap{display: block;text-align: center;background: #E0E0E0;padding: 5px;font-size: 14px;text-decoration: none;color: #3D3D3D;margin-top: 5px;}
</style>
</head>
<body>
<div class="items-wrp">
<h2 style="text-align:center">My Online Cell Phone Shop</h2>
<?php
//select product information from database
$results = $mysqli->query("SELECT id, product_code, product_name, product_price, image_name FROM my_products");

//wrap with form tag
print 	'<form action="payment_option.php" method="post">';
print '<ul>';

//list products from database
while($row = $results->fetch_object()) {
    print '<li>';
    print '<img src="images/' . $row->image_name . '" width="220" height="220">';
    print '<div class="item-name">' . $row->product_name . '</div>';
    print '<div class="btn-wrap">';
    print 	'Qty:';
    print 	'<select name="'.$row->product_code.'_qty">';
    print 		'<option>1</option>';
    print 		'<option>2</option>';
    print 		'<option>3</option>';
    print 		'<option>4</option>';
    print 		'<option>5</option>';
    print 		'<option>8</option>';
    print 		'<option>10</option>';
    print 	'</select> ';
    print 	'<input type="checkbox" name="item_id[]" value="' . $row->id . '" />';
    print '</div>';
    print '</li>';
} 

print '</ul>'; 
print '<div align="center"><input type="submit" value="Buy Selected Items" /></div>';
print '</form>';
?>
</div>

</body>
</html>

