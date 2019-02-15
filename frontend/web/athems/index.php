<!DOCTYPE html>
<html lang="ru">
<head>
	<meta charset="UTF-8">
	<title>Проекты</title>
	<style>
		body {
			background: #999;
			font-family: 'Calibri', Arial, sans-serif;
			size: 14px;
		}
		.well-wrapper {
			position: absolute;
			width: 99%;
			height: 99%;
		}
		.well {
			position: relative;
			width: 800px;
			/*height: 500px;*/
			left: 50%;
			top: 50%;
			margin-left: -400px;
			margin-top: -250px;
			padding: 20px;
			background: #ddd;
			color: #333;
			border-radius: 10px;
			box-shadow: 0 0 50px rgba(0,0,0,.9);
		}
		h2 {
			font-size: 25px;
			text-align: center;
		}
		ul {
			list-style: none;
		}
		li {
			/*float: left;*/
			display: block;
			/*height: 90px;*/
			/*width: 90px;*/
			border: 1px solid #999;
			border-radius: 10px;
			text-align: center;
			line-height: 90px;
			margin-left: 10px;
			margin-top: 10px;
			transition: all .2s ease-in;
			cursor: pointer;
		}
		li:hover {
			font-size: 20px;
			/*height: 100px;*/
			/*width: 100px;*/
			line-height: 100px;
			margin-top: 5px;
			font-weight: bold;
			background: #888;
		}
		a {
			color: #333;
			text-decoration: none;
		}
	</style>
</head>
<body>
	<div class="well-wrapper">
		<div class="well">
			<h2>Проекты в разработке</h2>
			<ul>
				<?php
					if ($handle = opendir('.')){
						while (false !== ($entry = readdir($handle))){
							if ($entry != "." && $entry != ".."){
								echo "<li><a href='".$entry."'>$entry</a></li>";
							}
						}
						closedir($handle);
					}
				?>
			</ul>
           <div style="clear: both"></div>
		</div>
        <div style="clear: both"></div>
	</div>
</body>
</html>
