<?php
$title = basename($_SERVER['SCRIPT_FILENAME'], '.php');
$title = str_replace('_', ' ', $title);
if ($title == 'index'){
	$title = 'Coffee Supply Co.';
}
if ($title == 'createOrder'){
	$title = 'Create Order';
}
if ($title == 'inventory'){
	$title = 'Inventory	';
}

if ($title == 'supplier'){
	$title = 'supplier';
}

if ($title == 'track'){
	$title = 'track';
}

if ($title == 'orders'){
	$title = 'orders';
}

$title = ucwords($title);