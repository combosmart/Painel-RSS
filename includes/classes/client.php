<?php 

	class Cliente extends Password {
		
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
		        $sql  = "SELECT c.id, c.nome, c.active, ";
		        $sql .= "(SELECT COUNT(*) FROM pontos p WHERE p.id_client = c.id AND p.active = 1) as pontos ";
		        $sql .= "FROM clients c ORDER BY c.nome ";
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
	            $result = $stmt->fetchAll();
				
				return $result;
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function listarClientesComPontos() {
	    	try {
		        $sql = "SELECT c.id, c.nome, c.active FROM clients c WHERE c.active = 1 AND EXISTS (select 1 from pontos p WHERE p.id_client = c.id AND p.active = 1) ORDER BY c.nome";			    
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
	            $result = $stmt->fetchAll();
				
				return $result;
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function carregar_cliente($id) {
		    try {
		        	$sql = "SELECT id, nome, active from clients WHERE id = :id order by nome";			    
			    	$stmt = $this->_db->prepare($sql);
					$stmt->execute(array(':id' => $id));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);			
					return $result;
		        
		    } catch (PDOException $e) {
		        	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
		}	

	    public function save(Cliente $c) {
	    	try {
	    	
	    		$sql = "INSERT INTO clients (nome) VALUES (:nome)";
	    		$stmt = $this->_db->prepare($sql);	    	
	    		$result = $stmt->execute(array(
							':nome' => $c->getNome()
				));
	    		
	    		return $result;
	    	
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function update(Cliente $c) {
	    	try {
	    		$sql = "UPDATE clients SET nome = :nome WHERE id = :id";
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

	    public function delete(Cliente $c) {
	    	try {
	    		$sql = "CALL desativarCliente(:id)";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function restore(Cliente $c) {
	    	try {
	    		$sql = "UPDATE clients SET active = 1 WHERE id = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function checkExistingClient(Cliente $c) {
	    	try {
		        $sql = "SELECT nome from clients WHERE UPPER(nome) = UPPER(:nome)";			    
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array(						
						':nome' => $c->getNome()
					));			
				
				return $stmt->rowCount();
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function checkDuplicateClient(Cliente $c) {
	    	try {
		        $sql = "SELECT nome from clients WHERE UPPER(nome) = UPPER(:nome) and id NOT IN (:id)";			    
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