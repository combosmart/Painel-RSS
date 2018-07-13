<?php 

	class Ocorrencia extends Password {
		
		private $_db;

		private $id;
		private $dataAbertura;
		private $dataFechamento;
		private $idPonto;
		private $idProblema;
		private $descricao;
		private $status;
		private $idUser;

		public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getDataAbertura(){
			return $this->dataAbertura;
		}

		public function setDataAbertura($dataAbertura){
			$this->dataAbertura = $dataAbertura;
		}

		public function getDataFechamento(){
			return $this->dataFechamento;
		}

		public function setDataFechamento($dataFechamento){
			$this->dataFechamento = $dataFechamento;
		}

		public function getIdPonto(){
			return $this->idPonto;
		}

		public function setIdPonto($idPonto){
			$this->idPonto = $idPonto;
		}

		public function getIdProblema(){
			return $this->idProblema;
		}

		public function setIdProblema($idProblema){
			$this->idProblema = $idProblema;
		}

		public function getDescricao(){
			return $this->descricao;
		}

		public function setDescricao($descricao){
			$this->descricao = $descricao;
		}

		public function getStatus(){
			return $this->status;
		}

		public function setStatus($status){
			$this->status = $status;
		}

		public function getIdUser(){
			return $this->idUser;
		}

		public function setIdUser($idUser){
			$this->idUser = $idUser;
		}

		function __construct($db) {
	    	parent::__construct();
	    	$this->_db = $db;
	    }

	    public function listar() {
	    	try {
		        
		        $sql  = "SELECT o.id, DATE(o.data_abertura) as dt, TIME(o.data_abertura) as tm, o.id_ponto, o.id_problema, ";
		        $sql .= "       o.descr, o.status, o.data_fechto, o.id_user, p.nome as ponto, ";
		        $sql .= "	    t.nome as problema, c.nome as cliente ";
		        $sql .= "FROM   ocorrencias o, pontos p, problemas t, clients c ";
		        $sql .= "WHERE  o.id_ponto = p.id ";
		        $sql .= "   AND o.id_problema = t.id ";
		        $sql .= "   AND p.id_client = c.id ";
		        $sql .= "ORDER BY o.data_abertura desc, c.nome;";

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
		        	$sql  = "SELECT o.id, DATE(o.data_abertura) as dt, TIME(o.data_abertura) as tm, o.id_ponto, o.id_problema, "; 
		        	$sql .= "       o.descr, o.status, o.data_fechto, u.nome ";
		        	$sql .= "FROM   ocorrencias o, users u ";
		        	$sql .= "WHERE  o.id = :id ";
		        	$sql .= "AND    u.id = o.id_user;";
			    	
			    	$stmt = $this->_db->prepare($sql);
					$stmt->execute(array(':id' => $id));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);			
					return $result;
		        
		    } catch (PDOException $e) {
		        	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
		}	

	    public function save(Ocorrencia $c) {
	    	try {
	    	
	    		$sql  = "INSERT INTO ocorrencias (data_abertura, id_ponto, id_problema, descr, status, id_user, id_user_update) ";
	    		$sql .= "VALUES (:data_abertura, :id_ponto, :id_problema, :descr, :status, :id_user, :id_user_update)";
	    		
	    		$stmt = $this->_db->prepare($sql);	    	
	    		$result = $stmt->execute(array(
							':data_abertura'  => $c->getDataAbertura(),
							':id_ponto'       => $c->getIdPonto(), 
							':id_problema'    => $c->getIdProblema(), 
							':descr'          => $c->getDescricao(), 
							':status'         => $c->getStatus(), 
							':id_user'        => $c->getIdUser(),
							':id_user_update' => $c->getIdUser()
						  ));
	    		
	    		return $result;
	    	
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function update(Ocorrencia $c) {
	    	try {
	    		
	    		$sql =  "UPDATE ocorrencias SET id_ponto = :id_ponto, id_problema = :id_problema, ";
	    		$sql .= "descr = :descr, id_user_update = :id_user_update WHERE id = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id_ponto'       => $c->getIdPonto(), 
							':id_problema'    => $c->getIdProblema(), 
							':descr'          => $c->getDescricao(), 
							':id_user_update' => $c->getIdUser(),
							':id'             => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function encerrar(Ocorrencia $c) {
	    	try {
	    		
	    		$sql =  "UPDATE ocorrencias SET status = :status, data_fechto = now(), id_user_update = :id_user_update ";
	    		$sql .= "WHERE id = :id";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':status' => $c->getStatus(), 
							':id_user_update' => $c->getIdUser(),
							':id'     => $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function listarHistorico($id_ocorrencia) {
	    	try {
	    		
		        $sql  = "SELECT id_ocorrencia, usuario, campo, old_value, new_value, DATE(modified) as dt, TIME(modified) as tm ";  
		        $sql .= "  FROM log_ocorrencias WHERE id_ocorrencia = :id_ocorrencia";
		        $stmt = $this->_db->prepare($sql);
				$stmt->execute(array(':id_ocorrencia' => $id_ocorrencia));
	            $result = $stmt->fetchAll();
				
				return $result;
		        
		    } catch (PDOException $e) {
		        echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
	    }


	}
?>