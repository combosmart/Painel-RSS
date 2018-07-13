<?php 
	
	require('includes/config.php'); 
	if(!$user->is_logged_in()){ header('Location: index.php'); } 
	
	$id_ponto = $_GET['q'];
	$equipAssociados  = $equipment->listarEquipamentosAssociados($id_ponto);
	$equipDisponiveis = $equipment->listarEquipamentosDisponiveis();


	$strResultAssociados =  "<section class='panel'>
						<header class='panel-heading'>
							<h2 class='panel-title'>Equipamentos Associados ao Ponto</h2>
						</header>

						<div class='panel-body'>
							<table class='table table-bordered mb-none' id='associados'>
								<thead>
									<tr>
										<th>Tipo</th>
										<th>Marca</th>
										<th>Descrição</th>
										<th>&nbsp</th>
									</tr>
								</thead>
								<tbody>";
	foreach ($equipAssociados as $r) {
		$strResultAssociados .=  "<tr>";
		$strResultAssociados .= "	<td>" . $r['tipo'] . "</td>";
		$strResultAssociados .= "	<td>" . $r['nome'] . "</td>";
		$strResultAssociados .= "	<td>" . $r['specs'] . "</td>";
		$strResultAssociados .= "	<td><button type='button' class='mb-xs mt-xs mr-xs btn btn-primary' class='btnDelete'>Primary</button>
</td>";
		$strResultAssociados .= "</tr>";
	}								

	$strResultAssociados .= "</tbody>
							</table>
						</div>
						</section>	";
	

	echo $strResultAssociados;
	

	$strResultDisponiveis =  "<section class='panel'>
						<header class='panel-heading'>
							<h2 class='panel-title'>Equipamentos Disponíveis</h2>
						</header>

						<div class='panel-body'>
							<table class='table table-bordered mb-none' id='disponiveis'>
								<thead>
									<tr>
										<th>Tipo</th>
										<th>Marca</th>
										<th>Descrição</th>
										<th>&nbsp;</th>
									</tr>
								</thead>
								<tbody>";
	foreach ($equipDisponiveis as $r) {
		$strResultDisponiveis .=  "<tr>";
		$strResultDisponiveis .= "	<td>" . $r['tipo'] . "</td>";
		$strResultDisponiveis .= "	<td>" . $r['nome'] . "</td>";
		$strResultDisponiveis .= "	<td>" . $r['specs'] . "</td>";
		$strResultDisponiveis .= "	<td><button type='button' class='mb-xs mt-xs mr-xs btn btn-danger'>Danger</button>
</td>";
		$strResultDisponiveis .= "</tr>";
	}								

	$strResultDisponiveis .= "</tbody>
							</table>
						</div>
						</section>	";
	

	echo $strResultDisponiveis;						

?>