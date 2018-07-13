<?php

class FeedClass extends Password {

	private $parceiro;
	private $tabela;
	private $urlRSS;
	private $camposRSS;

	function __construct($db) {
		parent::__construct();
		$this->_db = $db;
	}

	function setParceiro($parceiro) { $this->parceiro = $parceiro; }
	function getParceiro() { return $this->parceiro; }
	function setTabela($tabela) { $this->tabela = $tabela; }
	function getTabela() { return $this->tabela; }
	function setUrlRSS($urlRSS) { $this->urlRSS = $urlRSS; }
	function getUrlRSS() { return $this->urlRSS; }
	function setCamposRSS($camposRSS) { $this->camposRSS = $camposRSS; }
	function getCamposRSS() { return $this->camposRSS; }

	public function save(RSS_Megacurioso $c) {
		try {
	    	
			$sql = "INSERT INTO rss_megacurioso (title, link, dataItem) VALUES (:title, :link, :dataItem)";
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

}