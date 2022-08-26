<?php 
$outCrypto = array("BTC", "ETH", "SHIB");
$outCurrency = array("USD", "RUB");
?>
<section id="converter">
	<div class="container">
		<h1 class="name_page">Курс криптовалют</h1>
		<div class="main_block">
			<table class="out_curs">
				<tr>
				    <th>Криптовалюта</th>
				    <th>Цена в USD</th>
				    <th>Цена в RUB</th>
			   </tr>
			   <?php 
			   	foreach($outCrypto as $crypto){
				   	echo "<tr><td>$crypto</td>";
				   	foreach($outCurrency as $Currency){
				  		$sum = CryptoConverter::search_ticker($crypto, $Currency);
				  		$sum = $sum['curs'];
						$decimals = (number_format($sum) > 0) ? 2 : 8; 
						$sum = number_format( $sum, $decimals, ',', ' ' );
						echo "<td>$sum</td>";
					}
				}
				echo "</tr>";
			   ?>

				<tr>
					
					
					<td>Цена в RUB</td>
				</tr>
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