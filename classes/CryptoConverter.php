<?PHP
/**
 * Крипто конвертер
 */
class CryptoConverter
{
	const PATH_CACHE = "cache/ticker.json";

	static public function search_list_ticker($allCurrency){
		$json_file = self::read_ticker();
		$inpCurrency = strtoupper($allCurrency['inpCurrency']);
		$outCurrencyList = $allCurrency['outCurrency'];
		$outCurrencyListItemOne = $allCurrency['outCurrency'][0];
		$outCurrencyListItemTwo = $allCurrency['outCurrency'][1];
		$reСonversion = false;

		$jsonInpCurrency = $json_file[$inpCurrency];
		
		if (isset($jsonInpCurrency[$outCurrencyListItemOne]) && isset($jsonInpCurrency[$outCurrencyListItemTwo])) {
			$curs_one = $jsonInpCurrency[$outCurrencyListItemOne];
			$curs_two = $jsonInpCurrency[$outCurrencyListItemTwo];
			
			if (self::check_update($curs_one) || self::check_update($curs_two) ) {
				$all_curs = self::tiker_update($inpCurrency, $outCurrencyList);
				return $all_curs;
			}else{
				$all_curs[$inpCurrency] = $jsonInpCurrency;
				return $all_curs;
			}
		}

		$all_curs[$inpCurrency][$outCurrencyListItemOne]['price'] = self::search_ticker($inpCurrency, $outCurrencyListItemOne)["curs"];
		$all_curs[$inpCurrency][$outCurrencyListItemTwo]['price'] = self::search_ticker($inpCurrency, $outCurrencyListItemTwo)["curs"];
		return $all_curs;
	}

	static public function search_ticker($inpCurrency, $outCurrency){
		$inpCurrency = strtoupper($inpCurrency);
		$outCurrency = strtoupper($outCurrency);
		$reСonversion = false;

		$json_file = self::read_ticker();
		
		if (isset($json_file[$inpCurrency][$outCurrency])) {
			$curs = $json_file[$inpCurrency][$outCurrency];
		}else if (isset($json_file[$outCurrency][$inpCurrency])) {
			$curs = $json_file[$outCurrency][$inpCurrency];
			$reСonversion = true;
		}else{

			$curs = self::tiker_update($inpCurrency, $outCurrency);
			array($outCurrency => array("update" => time(), "price" => $ticker[$outCurrency]));
			$curs = $curs[$outCurrency]['price'];
			return array("status" => true, "curs" => $curs);
		}

		if (self::check_update($curs)) {
			if ($reСonversion) {
				$curs = self::tiker_update($outCurrency, $inpCurrency);
				$curs = $curs[$inpCurrency];
			}else{
				$curs = self::tiker_update($inpCurrency, $outCurrency);
				$curs = $curs[$outCurrency];
			}
		}

		$curs = $curs['price'];
		
		if ($reСonversion) {
			$curs = 1 / $curs;
		}

		return array("status" => true, "curs" => $curs);
	}

	static public function tiker_update($inpCurrency, $outCurrency){
		$get = self::get_ticker($inpCurrency, $outCurrency);
		$return_new_ticker = array();

		if (isset($get['Response'])) {
			return [];
		}else{
			if (is_array($outCurrency)) {
				foreach($outCurrency as $currency){
					$new_ticker = array($currency => array("update" => time(), "price" => $get[$currency]));
					self::ticker_save($new_ticker, $inpCurrency);
					$return_new_ticker[$currency] = $new_ticker[$currency];
				}
				return array($inpCurrency => $return_new_ticker);
			}

			$new_ticker = array($outCurrency => array("update" => time(), "price" => $get[$outCurrency]));
			self::ticker_save($new_ticker, $inpCurrency);
			return $new_ticker;
		}
	}

	static public function ticker_save($ticker, $inpCurrency){
		$file = file_get_contents(self::PATH_CACHE);  // Открыть файл data.json
		$taskList = json_decode($file,TRUE);                
		unset($file);
		if (isset($taskList[$inpCurrency])) {
			$add_array = array_merge($taskList[$inpCurrency], $ticker);
		}else{
			$add_array = $ticker;
		}
		$taskList[$inpCurrency] = $add_array; 
		file_put_contents(self::PATH_CACHE,json_encode($taskList));
		unset($taskList);   
		return true;
	}

	static public function get_ticker($inpCurrency, $outCurrency){
		if (is_array($outCurrency)) {
			$curl = curl_init("https://cryptocalc.online/api/v1/price?fsym=".$inpCurrency."&tsyms=".implode(",", $outCurrency));
		}else{
			$curl = curl_init("https://cryptocalc.online/api/v1/price?fsym=".$inpCurrency."&tsyms=".$outCurrency);
		}
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_HEADER, false);
		$get = curl_exec($curl);
		curl_close($curl);
		$get =  json_decode($get, true);
		return $get;
	}

	static public function read_ticker(){
		$file = file_get_contents(self::PATH_CACHE);
		$file = json_decode($file, true);

		return $file;
	}

	static public function check_update($json_curs){
		$now_time = time();
		if ($now_time - $json_curs['update'] > 60) {
			return true;
		}

		return false;
	}
}
