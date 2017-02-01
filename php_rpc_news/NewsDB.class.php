<?php
/**
* 
*/

function __autoload ($name){
	require "$name.class.php";
} 

class NewsDB implements INewsDB{
		
	const DB_NAME = "news.db";
	const RSS_NAME = "rss.xml";
	const RSS_TITLE = "Последние новости";
	const RSS_LINK = "http://mysite.local/news/news.php";
	private $_db;

	/*function get_db(){
		return $this->_db;
	}*/
	function __get($name){
		if ($name == '_db')
			return $this->_db;
		throw new Exception("Unknown property");
	}

	function __construct(){
		$this->_db = new SQLite3(self::DB_NAME);
		if (filesize(self::DB_NAME) == 0){
			$db = $this->_db;
			try{
				$sql = "CREATE TABLE msgs(
					id INTEGER PRIMARY KEY AUTOINCREMENT,
					title TEXT,
					category INTEGER,
					description TEXT,
					source TEXT,
					datetime INTEGER
				)";
				$result = $db->exec($sql);
				if (!$result) throw new Exception($db->lastErrorMsg());
				;
				$sql = "CREATE TABLE category(
					id INTEGER,
					name TEXT
				)";
				$result = $db->exec($sql);
				if (!$result) throw new Exception($db->lastErrorMsg());
				;
				$sql = "INSERT INTO category(id, name)
					SELECT 1 as id, 'Политика' as name
					UNION SELECT 2 as id, 'Культура' as name
					UNION SELECT 3 as id, 'Спорт' as name ";
				
				$result = $db->exec($sql);
				if (!$result) throw new Exception($db->lastErrorMsg());
				;
			} catch (Exception $e){
				echo "Все плохо!";
			}
			$this->_db = $db;
		}
		// else $this->_db->open(self::DB_NAME); 
	}

	function __destruct(){
		unset($this->_db);
	}

	function saveNews($title, $category, $description, $source){
		$dt = time();
		$sql = "INSERT INTO msgs (title, category, description, source, datetime) VALUES (:title, :category, :description, :source, :dt)";
		$stmt = $this->_db->prepare($sql);
		$stmt->bindParam(':title', $title);
		$stmt->bindParam(':category',$category);
		$stmt->bindParam(':description',$description);
		$stmt->bindParam(':source',$source);
		$stmt->bindParam(':dt',$dt);
		$result = $stmt->execute();
		if(!$result) return false;
		$this->createRss();
		$stmt->close();
		return true;
	}
	protected function db2Arr($data){
		$arr = [];
		while ($row = $data->fetchArray(SQLITE3_ASSOC)) {
			$arr[] = $row;
		}
		return $arr;
	}
	function getNews(){
		$sql = "SELECT msgs.id as id, title, category.name as category, 
					       description, source, datetime 
						FROM msgs, category 
						WHERE category.id = msgs.category 
						ORDER BY msgs.id DESC ";
		$result = $this->_db->query($sql);
		// while ($row = $result->fetchArray(SQLITE3_ASSOC)) 
		// 	if ($row) $arr[] = $row;
		if (!$result) return false;
		else return $this->db2Arr($result);
		// return $arr;
	}
	function deleteNews($id){
		$sql = "DELETE FROM msgs WHERE id = $id";
		echo "$id";
		$result = $this->_db->exec($sql);
		return $result;
	}
	private function createRss(){
		$dom = new DomDocument("1.0", "utf-8");
		// echo print_r($dom);
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		// $root = $dom->documentElement;

		$rss = $dom->createElement("rss");
		$version = $dom->createAttribute("version"); 
		$channel = $dom->createElement("channel");
		$title = $dom->createElement("title", self::RSS_TITLE);
		$link = $dom->createElement("link",self::RSS_LINK);

		$dom->appendChild($rss);
		$version->value = '2.0'; 
		$rss->appendChild($version);
		$rss->appendChild($channel);
		$channel->appendChild($title);
		$channel->appendChild($link);

		$getNewsArr = $this->getNews();
		foreach ($getNewsArr as $key => $value) {
			$item = $dom->createElement("item");
			$itemTitle = $dom->createElement("title",$value['title']);
			$itemLink = $dom->createElement("link",$value['source']);
			$itemDescription = $dom->createElement("description");
			$descriptionContent = $dom->createCDATASection($value['description']);
			$itemDescription->appendChild($descriptionContent);
			$pubDate = $dom->createElement("pubDate",date("Y-m-d H:i:s",$value['datetime']));
			$itemCategory = $dom->createElement("category",$value['category']);

			$item->appendChild($itemTitle);
			$item->appendChild($itemLink);
			$item->appendChild($itemDescription);
			$item->appendChild($pubDate);
			$item->appendChild($itemCategory);
			$channel->appendChild($item);
		}
		$dom->save(self::RSS_NAME);
	}
	function clearStr($data){
		$data = strip_tags($data);
		return $this->_db->escapeString($data);
	}
	function clearInt($data){
		return abs((int)$data);
	}
}
// $news = new NewsDb();
