<?php
class Contato extends Password {

	private $id;
	private $nome;
	private $cargo;
	private $email;
	private $telefone;
	private $cliente;
	private $dataNascimento;
	private $timeFutebol;

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

	public function getCargo(){
		return $this->cargo;
	}

	public function setCargo($cargo){
		$this->cargo = $cargo;
	}

	public function getEmail(){
		return $this->email;
	}

	public function setEmail($email){
		$this->email = $email;
	}

	public function getTelefone(){
		return $this->telefone;
	}

	public function setTelefone($telefone){
		$this->telefone = $telefone;
	}

	public function getCliente(){
		return $this->cliente;
	}

	public function setCliente($cliente){
		$this->cliente = $cliente;
	}

	public function getDataNascimento(){
		return $this->dataNascimento;
	}

	public function setDataNascimento($dataNascimento){
		$this->dataNascimento = $dataNascimento;
	}

	public function getTimeFutebol(){
		return $this->timeFutebol;
	}

	public function setTimeFutebol($timeFutebol){
		$this->timeFutebol = $timeFutebol;
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
}	