<?php 

	class RSS_Minhaserie extends Password {
		
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
					$feed = "http://rss.minhaserie.com.br/feed";
					$xml = simplexml_load_file($feed);
					$lastBuildDate = date_format(date_create(strval($xml->channel->lastBuildDate)),"Y-m-d");
					$result = true;
					$rss = new RSS_Minhaserie($db);
					
					$sql = "SELECT id from rss_minhaserie WHERE data_item = :data_item";			    
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
						echo str_replace("thumb_","",strval($item->image));
						echo "<br>";
					}
					
					echo "checkBuildDate: " . $checkBuildDate;
					exit;
					*/
					
					//controle para não gravar o mesmo feed de um dia duas vezes
					if ($checkBuildDate == 0) {
						if ($xml) {
							foreach($xml->xpath("//item") as $item) {
								$sql = "INSERT INTO rss_minhaserie (title, link, data_item, arquivo_imagem) VALUES (:title, :link, :data_item, :arquivo_imagem)";
								$stmt = $this->_db->prepare($sql);
								$result = $result && $stmt->execute(array(
											':title'     => strval($item->title), 
											':link'      => str_replace("thumb_","",strval($item->image)), 							
											':data_item' => strval($lastBuildDate),
											':arquivo_imagem' => hash('md5',strval($item->title))
										  ));
							}
						}	
					}
					
					return $result;
	    	
				} catch (PDOException $e) {
					echo '<p class="bg-danger">'.$e->getMessage().'</p>';
				}
		}

	    public function listar() {
	    	try {
		        $sql  = "SELECT id, title, link, destaque, data_item, exibir ";
				$sql .= "FROM rss_minhaserie ORDER BY id desc";
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
					$sql .= "FROM rss_minhaserie WHERE id = :id ";
			    	$stmt = $this->_db->prepare($sql);
					$stmt->execute(array(':id' => $id));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);			
					return $result;
		        
		    } catch (PDOException $e) {
		        	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
		}	

	    public function save(rss_minhaserie $c) {
	    	try {
	    	
	    		$sql = "INSERT INTO rss_minhaserie (title, link, dataItem) VALUES (:title, :link, :dataItem)";
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

	    public function update(RSS_Minhaserie $c) {
	    	try {
	    		$sql  = "UPDATE rss_minhaserie SET destaque = :destaque, ";
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
	    		$sql  = "UPDATE rss_minhaserie SET exibir = 0 WHERE id = :id	";
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
	    		$sql  = "UPDATE rss_minhaserie SET exibir = 1 WHERE id = :id	";
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
	    			$sql  = "UPDATE rss_minhaserie SET exibir = 0";
	    			$stmt = $this->_db->prepare($sql);
	    			$result = $stmt->execute();			
	    			return $result;
	    	} catch (PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }
	    
	}
?>