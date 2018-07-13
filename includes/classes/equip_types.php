<?php 

	class TipoEquipamento extends Password {
		
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
		        $sql = "SELECT id, nome, active from equip_types order by nome";			    
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
		        	$sql = "SELECT id, nome, active from equip_types WHERE id = :id";			    
			    	$stmt = $this->_db->prepare($sql);
					$stmt->execute(array(':id' => $id));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);			
					return $result;
		        
		    } catch (PDOException $e) {
		        	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
		}	

	    public function save(TipoEquipamento $c) {
	    	try {
	    	
	    		$sql = "INSERT INTO equip_types (nome) VALUES (:nome)";
	    		$stmt = $this->_db->prepare($sql);	    	
	    		$result = $stmt->execute(array(
							':nome' => $c->getNome()
				));
	    		
	    		return $result;
	    	
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function update(TipoEquipamento $c) {
	    	try {
	    		$sql = "UPDATE equip_types SET nome = :nome WHERE id = :id";
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

	    public function delete(TipoEquipamento $c) {
	    	try {
	    		$sql = "UPDATE equip_types SET active = 0 WHERE id = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function restore(TipoEquipamento $c) {
	    	try {
	    		$sql = "UPDATE equip_types SET active = 1 WHERE id = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function checkExisting(TipoEquipamento $c) {
	    	try {
		        $sql = "SELECT nome from equip_types WHERE UPPER(nome) = UPPER(:nome)";			    
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array(						
						':nome' => $c->getNome()
					));			
				
				return $stmt->rowCount();
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    
	    public function checkDuplicate(TipoEquipamento $c) {
	    	try {
		        $sql = "SELECT nome from equip_types WHERE UPPER(nome) = UPPER(:nome) and id NOT IN (:id)";			    
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

	    public function checkEquipamentoAtivo(TipoEquipamento $c) {
	    	try {
		        $sql = "SELECT id FROM equipments WHERE equip_type_id = :id AND active = 1";			    
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array(												
						':id'   => $c->getId()
					));			
				
				return $stmt->rowCount();
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }
	}
?>