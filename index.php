<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<title>Конвертер</title>
</head>
<body>
	<?php include('classes/CryptoConverter.php') ?>
	<!-- <?php echo "<pre>"; ?>

	?>
	<?php echo "</pre>"; ?> -->
	<?php  
		if (!isset($_POST['conver_currency'])) {
			$_POST['conver_currency'] =  "1 BTC in RUB";
		}
		// preg_match('/\d{1,}\s[A-Za-z]{3}\s(in)\s[A-Za-z]{3}/m', $_POST['conver_currency'], $matches, PREG_OFFSET_CAPTURE);
		preg_match('/\d{1,}/', $_POST['conver_currency'], $count);
		preg_match_all('/[A-Za-z]{3}/', $_POST['conver_currency'], $currencies);

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
		// echo "<pre>";
		// var_dump($matches);
		// var_dump($count);
		// echo "</pre>";
	?>
	<!-- <?php var_dump($_POST);?> -->
	<section id="converter">
		<div class="container">
			<h1 class="name_page">Конвертер криптовалют</h1>
			<div class="main_block">
				<form method="POST">
					<input type="text" name="conver_currency" placeholder="10 BTC in USD" value="<?=$_POST['conver_currency'];?>" pattern="\d{1,}\s[A-Za-z]{3}\s(in)\s[A-Za-z]{3}">
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
					<li><a href="/">Курсы криптовалют</a></li>
				</ul>
			</nav>
		</div>
	</section>


</body>
</html>