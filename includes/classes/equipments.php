<?php 

	class Equipamento extends Password {
		
		private $_db;

		private $id;
		private $nome;
		private $tipo;
		private $specs;
		private $ano;
		private $serialNum;
		private $flgActive;

		public function getAno(){
			return $this->ano;
		}

		public function setAno($ano){
			$this->ano = $ano;
		}
		
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

		public function getTipo(){
			return $this->tipo;
		}

		public function setTipo($tipo){
			$this->tipo = $tipo;
		}

		public function getSpecs(){
			return $this->specs;
		}

		public function setSpecs($specs){
			$this->specs = $specs;
		}

		public function getFlgActive(){
			return $this->flgActive;
		}

		public function getSerialNum(){
			return $this->serialNum;
		}

		public function setSerialNum($serialNum){
			$this->serialNum = $serialNum;
		}

		function __construct($db) {
	    	parent::__construct();
	    	$this->_db = $db;
	    }

	    public function listar() {
	    	try {
		        $sql  = "SELECT e.id, t.nome as tipo, e.nome, e.specs, e.active, e.serial_num, ";
				$sql .= "	   COALESCE((SELECT c1.nome ";
				$sql .= "	              FROM equipments e1, pontos p1, clients c1, equip_ponto ep ";
				$sql .= "		         WHERE p1.id_client = c1.id ";
				$sql .= "		           AND p1.id = ep.id_ponto ";
				$sql .= "		           AND e1.id = ep.id_equipment "; 
				$sql .= "		           AND e1.id = e.id), 'DESALOCADO') AS cliente	";
				$sql .= "FROM equipments e, equip_types t WHERE t.id = e.equip_type_id ORDER BY e.nome, t.nome ";
				$stmt = $this->_db->prepare($sql);
				$stmt->execute();
	            $result = $stmt->fetchAll();
				
				return $result;
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function listarEquipamentosDisponiveis() {
	    	try {
		        $sql  = "SELECT e.id, t.nome as tipo, e.nome, e.specs, e.active, e.serial_num ";	
		        $sql .= "FROM equipments e, equip_types t WHERE t.id = e.equip_type_id ";
				$sql .= "AND NOT EXISTS (SELECT 1 FROM equip_ponto WHERE id_equipment = e.id)";
		        $sql .= "AND e.active = 1 ORDER BY e.nome, t.nome";
		        $stmt = $this->_db->prepare($sql);
				$stmt->execute();
	            $result = $stmt->fetchAll();
				
				return $result;
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function listarEquipamentosAssociados($id_ponto) {
	    	try {
		        $sql  = "SELECT e.id, t.nome as tipo, e.nome, e.specs, e.active, e.serial_num ";	
		        $sql .= "  FROM equip_ponto a, equipments e, equip_types t ";
				$sql .= " WHERE e.id = a.id_equipment ";
				$sql .= "   AND t.id = e.equip_type_id ";
				$sql .= "   AND a.id_ponto = :id ";
		        $sql .= "ORDER BY e.nome, t.nome";
		        $stmt = $this->_db->prepare($sql);
		        $stmt->execute(array(						
							':id' => $id_ponto
							));			

		        $result = $stmt->fetchAll();
	    		return $result;				
				
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function carregar($id) {
		    try {
		        	$sql = "SELECT id, equip_type_id, nome, specs, ano, serial_num,  active from equipments WHERE id = :id";
			    	$stmt = $this->_db->prepare($sql);
					$stmt->execute(array(':id' => $id));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);			
					return $result;
		        
		    } catch (PDOException $e) {
		        	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
		}	

	    public function save(Equipamento $c) {
	    	try {
	    	
	    		$sql = "INSERT INTO equipments (equip_type_id, nome, specs, ano, serial_num) VALUES (:equip_type_id, UPPER(:nome), :specs, :ano, :serial_num)";
	    		$stmt = $this->_db->prepare($sql);	    	
	    		$result = $stmt->execute(array(
	    					':equip_type_id' => $c->getTipo()->getId(), 
							':nome'          => $c->getNome(), 							
							':specs'         => $c->getSpecs(),
							':ano'           => $c->getAno(),
							':serial_num'    => $c->getSerialNum()
				));
	    		
	    		return $result;
	    	
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function update(Equipamento $c) {
	    	try {
	    		$sql  = "UPDATE equipments SET equip_type_id = :equip_type_id, ";
	    		$sql .= "nome = :nome, specs = :specs, ano = :ano, serial_num = :serial_num WHERE id = :id	";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':equip_type_id' => $c->getTipo()->getId(), 
							':nome' 		 => $c->getNome(),
							':specs'         => $c->getSpecs(),
							':ano'           => $c->getAno(),
							':serial_num'    => $c->getSerialNum(),
		        			':id'   		 => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function delete(Equipamento $c) {
	    	try {
	    		$sql  = "UPDATE equipments SET active = 0 WHERE id = :id; ";
	    		$sql .= "DELETE FROM equip_ponto WHERE id_equipment = :id ";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function restore(Equipamento $c) {
	    	try {
	    		$sql = "UPDATE equipments SET active = 1 WHERE id = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function checkExisting(Equipamento $c) {
	    	try {
		        $sql = "SELECT serial_num from equipments WHERE UPPER(serial_num) = UPPER(:serial_num)";			    
				$stmt = $this->_db->prepare($sql);
				$stmt->execute(array(						
						':serial_num' => $c->getSerialNum()
					));			
				
				return $stmt->rowCount();
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    
	    public function checkDuplicate(Equipamento $c) {
	    	try {
		        $sql = "SELECT serial_num from equipments WHERE UPPER(serial_num) = UPPER(:serial_num) and id NOT IN (:id)";		
		        $stmt = $this->_db->prepare($sql);
				$stmt->execute(array(						
						':serial_num' => $c->getSerialNum(),
						':id'   => $c->getId()
					));			
				
				return $stmt->rowCount();
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function checkTipoEquipamentoAtivo(Equipamento $c) { 
	    	try {
		        
		        $sql  = "SELECT t.id FROM equip_types t, equipments e ";		
		        $sql .= "WHERE e.equip_type_id = t.id AND t.active = 0 ";
		        $sql .= "AND e.id = :id";
		        $stmt = $this->_db->prepare($sql);
				
				$stmt->execute(array(						
						':id'   => $c->getId()
					));			
				
				return $stmt->rowCount();
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }

	    public function removerEquipamentos($id_equipment, $id_ponto) {
	    	try {
	    		$sql = "DELETE FROM equip_ponto WHERE id_equipment = :id_equipment AND id_ponto = :id_ponto";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id_equipment' => $id_equipment,
							':id_ponto'     => $id_ponto
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function adicionarEquipamentos($id_equipment, $id_ponto) {
	    	try {
	    		$sql = "INSERT INTO equip_ponto (id_equipment, id_ponto) VALUES (:id_equipment, :id_ponto)";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id_equipment' => $id_equipment,
							':id_ponto'     => $id_ponto
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }
	    
	}
?>