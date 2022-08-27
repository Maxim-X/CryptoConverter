<?php 
Route::$TITLE = "Курс криптовалют";
$outCrypto = array("BTC", "ETH", "SHIB", "USDT", "USDC", "BUSD", "ADA", "LTC", "LINK", "ATOM");
$outCurrency = array("USD", "RUB");

// Функция приведения чисел к формату валюты
function sum($curs){
	$decimals = (number_format($curs) > 0) ? 2 : 8; 
	$sum = number_format( $curs, $decimals, ',', ' ' );
	return $sum;
}


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

			    <tbody>
			   	<?php
			   	foreach($outCrypto as $crypto):
			   		$list_ticker = CryptoConverter::search_list_ticker(array("inpCurrency" => $crypto, "outCurrency" => array("USD","RUB")));
			   	?>
				   	<tr>
				   	<td><?=$crypto;?></td>
					<td><?=sum($list_ticker[$crypto]['USD']['price']);?></td>
					<td><?=sum($list_ticker[$crypto]['RUB']['price']);?></td>

				<?php endforeach; ?>
			   </tbody>
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
