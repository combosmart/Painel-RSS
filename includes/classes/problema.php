<?php 

	class Problema extends Password {
		
		private $_db;

		private $id;
		private $nome;

		public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getNome(){
			return $this->nome;
		}

		public function setNome($nome){
			$this->nome = $nome;
		}

		function __construct($db) {
	    	parent::__construct();
	    	$this->_db = $db;
	    }

	    public function listar() {
	    	try {
		        $sql = "SELECT id, nome, active from problemas order by nome";			    
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
	            $result = $stmt->fetchAll();
				
				return $result;
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function carregar($id) {
		    try {
		        	$sql = "SELECT id, nome, active from problemas WHERE id = :id order by nome";			    
			    	$stmt = $this->_db->prepare($sql);
					$stmt->execute(array(':id' => $id));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);			
					return $result;
		        
		    } catch (PDOException $e) {
		        	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
		}	

	    public function save(Problema $c) {
	    	try {
	    	
	    		$sql = "INSERT INTO problemas (nome) VALUES (:nome)";
	    		$stmt = $this->_db->prepare($sql);	    	
	    		$result = $stmt->execute(array(
							':nome' => $c->getNome()
				));
	    		
	    		return $result;
	    	
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function update(Problema $c) {
	    	try {
	    		$sql = "UPDATE problemas SET nome = :nome WHERE id = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':nome' => $c->getNome(),
		        			':id'   => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function delete(Problema $c) {
	    	try {
	    		$sql = "";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function restore(Problema $c) {
	    	try {
	    		$sql = "UPDATE problemas SET active = 1 WHERE id = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function checkExistingProblem(Problema $c) {
	    	try {
		        $sql = "SELECT nome from problemas WHERE UPPER(nome) = UPPER(:nome)";			    
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array(						
						':nome' => $c->getNome()
					));			
				
				return $stmt->rowCount();
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function checkDuplicateProblem(Problema $c) {
	    	try {
		        $sql = "SELECT nome from problemas WHERE UPPER(nome) = UPPER(:nome) and id NOT IN (:id)";			    
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array(						
						':nome' => $c->getNome(),
						':id'   => $c->getId()
					));			
				
				return $stmt->rowCount();
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }
	}
?>