<?PHP
/**
 * Конвертер
 */
class CryptoConverter
{
	const PATH_CACHE = "cache/ticker.json";

	// function __construct(argument)
	// {

	// }

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
			$get = self::get_ticker($inpCurrency, $outCurrency);
			if (isset($get['Response'])) {
				return [];
			}else{
				$new_ticker = array($outCurrency => array("update" => time(), "price" => $get[$outCurrency]) );
				$dd = self::ticker_save($new_ticker, $inpCurrency);
				return array("status" => true, "curs" => $get[$outCurrency]);
			}

		}

		if (self::check_update($curs)) {
			if ($reСonversion) {
				$curs = self::tiker_update($outCurrency, $inpCurrency);
				echo "1";
				var_dump($curs);
				$curs = $curs[$inpCurrency];
			}else{
				$curs = self::tiker_update($inpCurrency, $outCurrency);
				echo "2";
				var_dump($curs);
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
		if (isset($get['Response'])) {
			return [];
		}else{
			$new_ticker = array($outCurrency => array("update" => time(), "price" => $get[$outCurrency]));
			$dd = self::ticker_save($new_ticker, $inpCurrency);
			// echo $new_ticker;
			return $new_ticker;
		}
	}

	static public function generate_std_class($ticker, $outCurrency){
		$Std = new StdClass();
		$Std->update = time();
		$Std->price = $ticker[$outCurrency];

		$std_class = array($outCurrency => array("update" => time(), "price" => $ticker[$outCurrency]));
		return $std_class;
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
		$curl = curl_init("https://cryptocalc.online/api/v1/price?fsym=".$inpCurrency."&tsyms=".$outCurrency);
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
