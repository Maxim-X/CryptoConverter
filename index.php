<?php 
@ob_start(); 
include('classes/CryptoConverter.php');
include('classes/Route.php'); 

// МАРШРУТИЗАЦИЯ

include($_SERVER["DOCUMENT_ROOT"]."/inc/header.php");

Route::path("/", function(){
	include($_SERVER["DOCUMENT_ROOT"]."/pages/index.php");
});

Route::path("/list", function(){
	include($_SERVER["DOCUMENT_ROOT"]."/pages/list.php");
});

include($_SERVER["DOCUMENT_ROOT"]."/inc/footer.php");

$content_page = ob_get_contents();
ob_end_clean();
$content_page = str_replace("{!TITLE!}",Route::$TITLE, $content_page);

echo $content_page;

