<?php
$result = $news->getNews();

if (is_array($result)) echo "Всего записей: " . count($result).'<br>';
else $errMsg = 'Произошла ошибка при выводе новостной ленты';
foreach ($result as $key => $value) {
	echo "<p>Новость в разделе ".$value['category']. " с названием ". $value['title'].": <br>". $value['description']." </p>";
	echo "<a href='".$_SERVER['PHP_SELF']."?delId=".$value['id']."'>X (Удалить новость)</a>";
	// echo serialize($value)."<br>";
	// echo $key.':'.$value['title'].'<br>';
}

