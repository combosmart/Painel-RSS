<?php 

	class RSS_Tecmundo extends Password {
		
		private $_db;

		private $id;
		private $title;
		private $link;
		private $destaque;
		private $dataItem;
		private $exibir;
		private $arquivoImagem;

		public function getId(){
			return $this->id;
		}

		public function setId($id){
			$this->id = $id;
		}

		public function getTitle(){
			return $this->title;
		}

		public function setTitle($title){
			$this->title = $title;
		}

		public function getLink(){
			return $this->link;
		}

		public function setLink($link){
			$this->link = $link;
		}

		public function getDestaque(){
			return $this->destaque;
		}

		public function setDestaque($destaque){
			$this->destaque = $destaque;
		}

		public function getDataItem(){
			return $this->dataItem;
		}

		public function setDataItem($dataItem){
			$this->dataItem = $dataItem;
		}

		public function getExibir(){
			return $this->exibir;
		}

		public function setExibir($exibir){
			$this->exibir = $exibir;
		}
		
		public function getArquivoImagem(){
			return $this->arquivoImagem;
		}

		public function setArquivoImagem($arquivoImagem){
			$this->arquivoImagem = $arquivoImagem;
		}
		
		// métodos

		function __construct($db) {
	    	parent::__construct();
	    	$this->_db = $db;
	    }
		
		public function saveXML() {
			try {
					$feed = "https://rss.tecmundo.com.br/feed";

					//if (test_url($feed)) {

						$xml = simplexml_load_file($feed);
						$lastBuildDate = date_format(date_create(strval($xml->channel->lastBuildDate)),"Y-m-d");
						$result = true;
						$rss = new RSS_Tecmundo($db);
						
						$sql = "SELECT id from rss_tecmundo WHERE data_item = :data_item";			    
						$stmt = $this->_db->prepare($sql);
						$stmt->execute(array(						
								':data_item' => $lastBuildDate
							));			
						
						$checkBuildDate = $stmt->rowCount();
						//print_r($xml->xpath("//item")); exit;
						/*
						foreach($xml->xpath("//item") as $item) {
							echo strval($item->title);  
							echo "<br>";
							echo strval($item->link);
							echo "<br>";
							echo $lastBuildDate;
							echo "<br>";
							echo strval($item->enclosure["url"]);
							echo "<br>";
						}
						exit;
						*/
						//controle para não gravar o mesmo feed de um dia duas vezes
						if ($checkBuildDate == 0) {
							foreach($xml->xpath("//item") as $item) {
								$uid = strtotime(strval($item->pubDate));
								$sql = "INSERT INTO rss_tecmundo 
								        (title, link, data_item, arquivo_imagem, uid, data_import) 
								        VALUES (:title, :link, :data_item, :arquivo_imagem, :uid, NOW())";
								$stmt = $this->_db->prepare($sql);
								$result = $result && $stmt->execute(array(
								 ':title'          => strval($item->title), 
								 ':link'           => strval($item->enclosure["url"]), 						
								 ':data_item'      => strval($lastBuildDate),
								 ':arquivo_imagem' => hash('md5',strval($item->title)),
								 ':uid'            => $uid
						     ));
							}
						}
						
						return $result;

					/*
					} else {
						return true;
					}
					*/
	    	
				} catch (PDOException $e) {
					echo '<p class="bg-danger">'.$e->getMessage().'</p>';
				}
		}

	    public function listar() {
	    	try {
		        $sql  = "SELECT id, title, link, destaque, data_item, exibir ";
				$sql .= "FROM rss_tecmundo ORDER BY data_item DESC";
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
		        	$sql  = "SELECT id, title, link, destaque, data_item, exibir ";
					$sql .= "FROM rss_tecmundo WHERE id = :id ";
			    	$stmt = $this->_db->prepare($sql);
					$stmt->execute(array(':id' => $id));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);			
					return $result;
		        
		    } catch (PDOException $e) {
		        	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
		}	

	    public function save(rss_tecmundo $c) {
	    	try {
	    	
	    		$sql = "INSERT INTO rss_tecmundo (title, link, dataItem) VALUES (:title, :link, :dataItem)";
	    		$stmt = $this->_db->prepare($sql);	    	
	    		$result = $stmt->execute(array(
	    					':title'    => $c->getTitle(), 
							':link'     => $c->getLink(), 							
							':dataItem' => $c->getDataItem()
				));
	    		
	    		return $result;
	    	
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function update(RSS_Tecmundo $c) {
	    	try {
	    		$sql  = "UPDATE rss_tecmundo SET destaque = :destaque, ";
	    		$sql .= "exibir = :exibir, link = :link, title = :title WHERE id = :id	";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':destaque' => $c->getDestaque(),
							':exibir'   => $c->getExibir(),
							':link'     => $c->getLink(),
							':title'    => $c->getTitle(),
		        			':id'   	=> $c->getId()
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }
		
		public function ocultar($id) {
	    	try {
	    		$sql  = "UPDATE rss_tecmundo SET exibir = 0 WHERE id = :id	";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $id
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }
		
		public function exibir($id) {
	    	try {
	    		$sql  = "UPDATE rss_tecmundo SET exibir = 1 WHERE id = :id	";
	    		$stmt = $this->_db->prepare($sql);
	    		$result = $stmt->execute(array(						
							':id' => $id
						));			
	    		return $result;
	    	} catch (PDOException $e) {
	    		echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }

	    public function clearAll() {
	    	try {
	    			$sql  = "UPDATE rss_tecmundo SET exibir = 0";
	    			$stmt = $this->_db->prepare($sql);
	    			$result = $stmt->execute();			
	    			return $result;
	    	} catch (PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }	    
	    
	}
?>