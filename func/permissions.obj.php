<?
class PolyPermissions {
	private $ownerpid, $collaborators;
	function __construct($ownerpid, $collaborators = array()){
		$this->ownerpid = $ownerpid;
		$this->collaborators = $collaborators;
	}
	function hasPermissions($pidToCheck){
		if($this->ownerpid == $pidToCheck){
			//Is Owner
			return true;
		}
		if(in_array($pidToCheck, $this->collaborators)){
			return true;
		}
		return false;
	}
	function addCollaborator($pidToAdd){
		if(!in_array($pidToAdd, $this->collaborators)){
			array_push($this->collaborators, $pidToAdd);
		}
	}
	function removeCollaborator($pidToRemove){
		if(in_array($pidToRemove, $this->collaborators)){
			for($x = 0; $x < count($this->collaborators); $x++){
				if($this->collaborators[$x]==$pidToRemove){
					unset($this->collaborators[$x]);
				}
			}
		}
	}
}

?>