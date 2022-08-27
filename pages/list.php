<?php 
Route::$TITLE = "Курс криптовалют";
$outCrypto = array("BTC", "ETH", "SHIB", "USDT", "USDC", "BUSD", "ADA", "LTC", "LINK", "ATOM");
$outCurrency = array("USD", "RUB");
// $list_ticker = array();
// foreach($outCrypto as $crypto){
// 	array_push($list_ticker, array("inpCurrency" => $crypto, "outCurrency" => $outCurrency[0]));
// 	array_push($list_ticker, array("inpCurrency" => $crypto, "outCurrency" => $outCurrency[1]));
// }
// 	// var_dump($list_ticker);
// 	$c = CryptoConverter::search_list_ticker($list_ticker);
// 	echo "<pre>";
// 	var_dump($c);
// 	echo "</pre>";


?>
<section id="converter">
	<div class="container">
		<h1 class="name_page">Курс криптовалют</h1>
		<div class="main_block">
			<table class="out_curs">
				<thead>
				    <th>Криптовалюта</th>
				    <th>Цена в USD</th>
				    <th>Цена в RUB</th>
			   </thead>
			   <?php 
			   function sum($curs){
					$decimals = (number_format($curs) > 0) ? 2 : 8; 
					$sum = number_format( $curs, $decimals, ',', ' ' );
					return $sum;
			   }
			   // $list_ticker = CryptoConverter::search_list_ticker(array(array("inpCurrency" => "BTC","outCurrency" => array("RUB","EUR"))));



			   	foreach($outCrypto as $crypto){
			   		$list_ticker = CryptoConverter::search_list_ticker(array("inpCurrency" => $crypto, "outCurrency" => array("USD","RUB")));
				   	echo "<tbody><td>$crypto</td>";
					echo "<td>".sum($list_ticker[$crypto]['USD']['price'])."</td>";
					echo "<td>".sum($list_ticker[$crypto]['RUB']['price'])."</td>";
					// echo "<td>sum($ticker['EUR'])</td>";
				}
				echo "</tbody>";
			   ?>
			</table>
			
		</div>
		<nav class="nav_bar">
			<ul>
				<li><a href="/">Конвертер криптовалют</a></li>
				<li><a href="/list">Курсы криптовалют</a></li>
			</ul>
		</nav>
	</div>
</section>