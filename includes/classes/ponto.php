<?php 
	class Ponto extends Password {
		private $_db;
		private $id;
		private $nome;
		private $endereco;
		private $numero;
		private $bairro;
		private $cep;
		private $cidade;
		private $uf;
		private $id_client;
		private $observacao;

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

		public function getEndereco(){
			return $this->endereco;
		}

		public function setEndereco($endereco){
			$this->endereco = $endereco;
		}

		public function getNumero(){
			return $this->numero;
		}

		public function setNumero($numero){
			$this->numero = $numero;
		}

		public function getBairro(){
			return $this->bairro;
		}

		public function setBairro($bairro){
			$this->bairro = $bairro;
		}

		public function getCep(){
			return $this->cep;
		}

		public function setCep($cep){
			$this->cep = $cep;
		}

		public function getCidade(){
			return $this->cidade;
		}

		public function setCidade($cidade){
			$this->cidade = $cidade;
		}

		public function getUf(){
			return $this->uf;
		}

		public function setUf($uf){
			$this->uf = $uf;
		}

		public function getCliente(){
			return $this->cliente;
		}

		public function setCliente($cliente){
			$this->cliente = $cliente;
		}

		public function getObservacao(){
			return $this->observacao;
		}

		public function setObservacao($observacao){
			$this->observacao = $observacao;
		}

		function __construct($db) {
	    	parent::__construct();
	    	$this->_db = $db;
	    }

	    public function listar() {
	    	try {
		        $sql  = "SELECT p.id, p.nome, p.endereco, p.numero, p.bairro, p.cep, ";  
		        $sql .= "       p.cidade, p.uf, c.nome as cliente, p.observacao, p.active, ";
		        $sql .= "	   (SELECT COUNT(*) FROM equip_ponto e WHERE e.id_ponto = p.id) as equipamentos ";
		        $sql .= "  FROM pontos p, clients c ";
		        $sql .= " WHERE p.id_client = c.id ";
		        $sql .= " ORDER BY c.nome";
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
	            $result = $stmt->fetchAll();
				
				return $result;
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function listarAtivos() {
	    	try {
		        $sql  = "SELECT p.id, p.nome, p.endereco, p.numero, p.bairro, p.cep, ";  
		        $sql .= "       p.cidade, p.uf, c.nome as cliente, p.observacao, p.active ";
		        $sql .= "  FROM pontos p, clients c ";
		        $sql .= " WHERE p.id_client = c.id ";
		        $sql .= "   AND p.active = 1 ";
		        $sql .= " ORDER BY c.nome";
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
	            $result = $stmt->fetchAll();
				
				return $result;
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function listarPorCliente($id) {
	    	try {
	    		
		        $sql  = "SELECT p.id, p.nome ";  
		        $sql .= "  FROM pontos p ";
		        $sql .= " WHERE p.id_client = :id ";
		        $sql .= " ORDER BY p.nome";
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array(':id' => $id));
	            $result = $stmt->fetchAll();
				
				return $result;
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function carregar($id) {
		    try {
		        	$sql  = "SELECT p.id, p.nome, p.endereco, p.numero, p.bairro, p.cep, ";  
		        	$sql .= "       p.cidade, p.uf, p.id_client, p.observacao, p.active ";
		        	$sql .= "  FROM pontos p ";
		        	$sql .= " WHERE p.id = :id";
			    	$stmt = $this->_db->prepare($sql);
					$stmt->execute(array(':id' => $id));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);			
					return $result;
		        
		    } catch (PDOException $e) {
		        	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
		}	

		public function save(Ponto $c) {
	    	try {
	    	
	    		$sql  = "INSERT INTO pontos (nome, endereco, numero, bairro, cep, cidade, uf, id_client, observacao) ";
	    		$sql .= "VALUES (:nome, :endereco, :numero, :bairro, :cep, :cidade, :uf, :id_client, :observacao) ";
	    		$stmt = $this->_db->prepare($sql);	    	
	    		$result = $stmt->execute(array(
							':nome' 	  => $c->getNome(),
							':endereco'   => $c->getEndereco(),
							':numero' 	  => $c->getNumero(),
							':bairro' 	  => $c->getBairro(),
							':cep' 		  => $c->getCep(),
							':cidade' 	  => $c->getCidade(),
							':uf' 		  => $c->getUf(),
							':id_client'  => $c->getCliente()->getId(),
							':observacao' => $c->getObservacao()
				));
	    		
	    		return $result;
	    	
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function update(Ponto $c) {
	    	try {
	    		$sql  = "UPDATE pontos SET nome = :nome, endereco = :endereco, numero = :numero, bairro = :bairro, ";
	    		$sql .= "cep = :cep, cidade = :cidade, uf = :uf, id_client = :id_client, observacao = :observacao ";
	    		$sql .= "WHERE id = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':nome' 	  => $c->getNome(),
							':endereco'   => $c->getEndereco(),
							':numero' 	  => $c->getNumero(),
							':bairro' 	  => $c->getBairro(),
							':cep' 		  => $c->getCep(),
							':cidade' 	  => $c->getCidade(),
							':uf' 		  => $c->getUf(),
							':id_client'  => $c->getCliente()->getId(),
							':observacao' => $c->getObservacao(),
							':id'         => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function delete(Ponto $c) {
	    	try {
	    		$sql  = "UPDATE pontos SET active = 0 WHERE id = :id; ";
	    		$sql .= "DELETE FROM equip_ponto WHERE id_ponto = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function deleteByClient(Cliente $c) {
	    	try {
	    		$sql = "UPDATE pontos SET active = 0 WHERE id_client = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function restore(Ponto $c) {
	    	try {
	    		$sql = "UPDATE pontos SET active = 1 WHERE id = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function checkExisting(Ponto $c) {
	    	try {
		        $sql = "SELECT nome from pontos WHERE UPPER(nome) = UPPER(:nome)";			    
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array(						
						':nome' => $c->getNome()
					));			
				
				return $stmt->rowCount();
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function checkDuplicate(Ponto $c) {
	    	try {
		        $sql = "SELECT nome from pontos WHERE UPPER(nome) = UPPER(:nome) and id NOT IN (:id)";			    
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

	    public function checkClienteAtivo(Ponto $c) {
	    	try {
		        $sql  = "SELECT c.id FROM clients c, pontos p ";			    
		        $sql .= "WHERE c.id = p.id_client ";
		        $sql .= "AND c.active = 1 ";
		        $sql .= "AND p.id = :id";
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array( ':id'   => $c->getId() ));			
				
				return $stmt->rowCount();
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }
	}