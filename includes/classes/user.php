<?php

include('password.php');

class User extends Password{

    private $_db;

    function __construct($db) {
    	parent::__construct();

    	$this->_db = $db;
    }

	// 
	private function get_user_hash($username){

		try {
		    $sql = "SELECT u.password, u.username, u.nome, u.id, p.descr, u.role_id FROM users u, roles p ";
		    $sql .= "WHERE u.username = :username AND u.active = 1 AND u.role_id = p.id";
			$stmt = $this->_db->prepare($sql);
			$stmt->execute(array('username' => $username));

			return $stmt->fetch();

		} catch(PDOException $e) {
		    echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		}
	}

	public function login($username,$password){

		$row = $this->get_user_hash($username);

		if($this->password_verify($password,$row['password']) == 1){

		    $_SESSION['loggedin'] = true;
		    $_SESSION['username'] = $row['username'];
		    $_SESSION['usuario_id'] = $row['id'];
			$_SESSION['nome_completo'] = $row['nome'];
			$_SESSION['descr'] = $row['descr'];
			$_SESSION['role_id'] = $row['role_id'];
		    return true;
		}
	}

	public function password_compare($username,$password) {
		$row = $this->get_user_hash($username);		
		if($this->password_verify($password,$row['password']) == 1) {
			return true;
		} else {
			return false;
		}
	}
	
	public function listar_users() {
	    try {
	        $sql = "SELECT u.username, u.nome, u.id, u.role_id, r.descr, u.email, u.active FROM users u, roles r  ";
		    $sql .= "WHERE r.id = u.role_id ORDER BY u.nome";
			$stmt = $this->_db->prepare($sql);
			$stmt->execute();
            $result = $stmt->fetchAll();
			
			return $result;
	        
	    } catch (PDOException $e) {
	        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    }
	}
	
	public function list_roles() {
	    try {
	        	$sql = "SELECT p.id, p.descr FROM roles p ORDER BY p.descr ";
		    	$stmt = $this->_db->prepare($sql);
				$stmt->execute();
            	$result = $stmt->fetchAll();
			
				return $result;
	        
	    } catch (PDOException $e) {
	        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    }
	}	

	public function carregar_usuario($id) {
	    try {
	        	$sql = "SELECT u.username, u.nome, u.id, p.descr, u.role_id, u.email, u.active FROM users u, roles p ";
		    	$sql .= "WHERE u.role_id = p.id AND u.id = :id ORDER BY u.nome";
		    	$stmt = $this->_db->prepare($sql);
				$stmt->execute(array(':id' => $id));
				$result = $stmt->fetch(PDO::FETCH_ASSOC);			
				return $result;
	        
	    } catch (PDOException $e) {
	        	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    }
	}	
	
	
	public function logout(){
		session_destroy();
	}

	public function is_logged_in(){
		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
			return true;
		}
	}

}


?>
