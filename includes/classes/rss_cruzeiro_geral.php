<?php 

	class RSS_JCruzGer extends Password {
		
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
					$feed = "https://www.jornalcruzeiro.com.br/feed";
					$xml = simplexml_load_file($feed);
					$lastBuildDate = date_format(date_create(strval($xml->channel->pubDate)),"Y-m-d");
					$result = true;
					$rss = new RSS_JCruzGer($db);

					
					$sql = "SELECT id from rss_cruzeiro_geral WHERE data_item = :data_item";			    
					$stmt = $this->_db->prepare($sql);
					$stmt->execute(array(						
							':data_item' => $lastBuildDate
						));			
					
					$checkBuildDate = $stmt->rowCount();
					
					/*
					foreach($xml->xpath("//item") as $item) {
						echo "title: " . strval($item->title);  
						echo "<br>";
						echo "link: " . strval($item->link);
						echo "<br>";
						echo "lastBuildDate: " . $lastBuildDate;
						echo "<br>";
						//echo strval($item->children("content", true));
						preg_match('~<img.*?src=["\']+(.*?)["\']+~', strval($item->children("content", true)), $match);
						$url = $match[1];
						echo "url: " . $url;
						echo "<br>";
					}
					exit;
					*/
					
					//controle para não gravar o mesmo feed de um dia duas vezes
					if ($checkBuildDate == 0) {
						if ($xml) {
							foreach($xml->xpath("//item") as $item) {

								// problema: cruzeiro passou a colocar a imagem dentro da
								// tag <content> como CDATA, ou seja, texto puro sem indexação
								// para entender acesse a url do feed declarada em $feed
								// então peguei a url da imagem dentro do cdata
								// ele busca pela <img> e busca o que está dentro do 
								// atributo src e coloca numa variável chamada $url
								// essa variável é um array. na segunda posição dela
								// está a informação que eu preciso ($url[1]).

								preg_match('~<img.*?src=["\']+(.*?)["\']+~', strval($item->children("content", true)), $url);

								// fim da alteração	

								$uid = strtotime(strval($item->pubDate));
								$sql = "INSERT INTO rss_cruzeiro_geral 
								        (title, link, data_item, arquivo_imagem, uid, data_import) 
								        VALUES (:title, :link, :data_item, :arquivo_imagem, :uid, NOW())";
								$stmt = $this->_db->prepare($sql);
								$result = $result && $stmt->execute(array(
								 ':title'          => strval($item->title), 
								 ':link'           => $url[1], 					
								 ':data_item'      => strval($lastBuildDate),
								 ':arquivo_imagem' => hash('md5',strval($item->title)),
								 ':uid'            => $uid
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
				$sql .= "FROM rss_cruzeiro_geral ORDER BY id DESC";
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
					$sql .= "FROM rss_cruzeiro_geral WHERE id = :id ";
			    	$stmt = $this->_db->prepare($sql);
					$stmt->execute(array(':id' => $id));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);			
					return $result;
		        
		    } catch (PDOException $e) {
		        	echo '<p class="bg-danger">'.$e->getMessage().'</p>';
		    }
		}	

	    public function save(RSS_JCruzGer $c) {
	    	try {
	    	
	    		$sql = "INSERT INTO rss_cruzeiro_geral (title, link, dataItem) VALUES (:title, :link, :dataItem)";
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

	    public function update(RSS_JCruzGer $c) {
	    	try {
	    		$sql  = "UPDATE rss_cruzeiro_geral SET destaque = :destaque, ";
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
	    		$sql  = "UPDATE rss_cruzeiro_geral SET exibir = 0 WHERE id = :id	";
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
	    		$sql  = "UPDATE rss_cruzeiro_geral SET exibir = 1 WHERE id = :id	";
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
	    			$sql  = "UPDATE rss_cruzeiro_geral SET exibir = 0";
	    			$stmt = $this->_db->prepare($sql);
	    			$result = $stmt->execute();			
	    			return $result;
	    	} catch (PDOException $e) {
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
	    	}
	    }	    
	    
	}
?>