<?php  
Route::$TITLE = "Конвертер криптовалют";
if (empty(trim($_POST['conver_currency']))) {
	$_POST['conver_currency'] =  "1 BTC in RUB";
}

if (isset($_POST['conver_currency'])) {
	preg_match('/\d{1,}/', $_POST['conver_currency'], $count);
	preg_match_all('/[A-Za-z]{3,4}/', $_POST['conver_currency'], $currencies);

	$count = $count[0];
	$inpCurrency = $currencies[0][0];
	$outCurrency = $currencies[0][1];
	$sum = CryptoConverter::search_ticker($inpCurrency, $outCurrency);

	if ($sum['status']) {
		$sum = $count * $sum['curs'];
		$decimals = (number_format($sum) > 0) ? 2 : 8; 
		$sum = number_format( $sum, $decimals, ',', ' ' );
		$res = "$count $inpCurrency = <b>$sum $outCurrency</b>";
	}
}

?>
	<section id="converter">
		<div class="container">
			<h1 class="name_page">Конвертер криптовалют</h1>
			<div class="main_block">
				<form method="POST">
					<input type="text" name="conver_currency" placeholder="10 BTC in USD" value="<?=$_POST['conver_currency'];?>" pattern="\d{1,}\s[A-Za-z]{3,4}\s(in)\s[A-Za-z]{3,4}\s{0,}">
					<button id="search_ticker">Расчитать</button>
				</form>
				<p class="txt-info">Шаблон ввода: 10 BTC in USD</p>
				<div class="response_ticker">
					<p><?=$res;?></p>
				</div>
				
			</div>
			<nav class="nav_bar">
				<ul>
					<li><a href="/">Конвертер криптовалют</a></li>
					<li><a href="/list">Курсы криптовалют</a></li>
				</ul>
			</nav>
		</div>
	</section>