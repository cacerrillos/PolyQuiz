<?
include_once("config.func.php");
class imageGroup {
	public $images;
	function __construct(){
		$this->images = array();
	}
	function addImage($image){
		if(is_object($image)){
			array_push($this->images, $image);
		} else {
			$temp = new image();
			$temp -> setUUID($image);
			array_push($this->images, $temp);
		}
	}
	function removeImage($uuid){
		foreach($this->images as $index=>$image){
			if($uuid==$image->getUUID()){
				unset($this->images[$index]);
			}
		}
		$this->images = array_values($this->images);
	}
	function getImage($uuid){
		foreach($this->images as $index=>$image){
			if($uuid==$image->getUUID()){
				return $image;
			}
		}
		return null;
	}
	function printThumbnails($type){
		$imagegroup = md5(serialize($this->images));
		if($type=="normal"){
			foreach($this->images as $index=>$image){
				$url = $image->getURL();
				if($url!=null){
					echo '<a href="'.$url.'" rel="lightbox['.$imagegroup.']" onclick="window.onbeforeunload = null;"><img src="'.$url.'" width="100px" height="100px"></a>';
				} else {
					echo "Error Finding Image!";
				}
			}
		}
	}
	function printThumbnailsAdmin($uuid, $num){
				$imagegroup = md5(serialize($this->images));
					?>
            <table border="2px">
            <tr>
            <td>Image</td>
            <td>Options</td>
            </tr>
            <?
			foreach($this->images as $index=>$image){
				$url = $image->getURL();
				?>
                <tr>
                <td>
                <?
				if($url!=null){
					echo '<a href="'.$url.'" rel="lightbox['.$imagegroup.']" onclick="window.onbeforeunload = null;"><img src="'.$url.'" width="100px" height="100px"></a>';
				} else {
					echo "Error Finding Image!";
				}
				?>
                </td>
                <td><a href="<? echo 'func/img.remove.php?uuid='.$uuid.'&num='.$num.'&img='.$image->getUUID(); ?>">Remove</a></td>
                <?
			}
			?>
            </tr></table>
            <?
	}
}
class image {
	public $uuid, $url, $filename;
	function __construct(){
		$this->uuid = null;
		$this -> url = null;
		$this -> filename = null;
	}
	function setUUID($uuid){
		$this->uuid = $uuid;
	}
	function setURL($url){
		if($url!=null){
			$this ->filename = $url;
			$url = "http://".$_SERVER['HTTP_HOST']."/polyquiz/images/".$url;
		}
		$this -> url = $url;
	}
	function getUUID(){
		return $this->uuid;
	}
	function getURL(){
		if($this->url==null){
			$this->fetchSQL();
		}
		return $this->url;
	}
	function getFileName(){
		return $this->filename;
	}
	function fetchSQL(){
		global $db_host, $db_user, $db_password, $db_name;
		$mysqli = new mysqli($db_host, $db_user, $db_password);
		$mysqli -> select_db($db_name);
		if(mysqli_connect_errno()) {
			echo "Connection Failed: " . mysqli_connect_errno();
			exit();
		}
		if($stmt = $mysqli -> prepare("SELECT url FROM images WHERE uuid = ?")){
			$stmt -> bind_param("s", $this->uuid);
			$stmt -> execute();
			$stmt -> bind_result($result);
			$stmt -> store_result();
			$stmt -> fetch();
			$stmt -> close();
			$this -> setURL($result);
		} else {
			echo $mysqli -> error;
			$this -> setURL(null);
		}
	}
}
?>