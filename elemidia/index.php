<?php
	$numOfCols = 3;
	$rowCount = 0;
	$bootstrapColWidth = 12 / $numOfCols;
	$feed = "http://brin.elemidia.com.br/seahorse/misc/monitoramento/monitoramento_status_all.php";
	$xml = simplexml_load_file($feed);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Elemídia Sorocaba</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>  
</head>
<body>

<div class="container-fluid">
  <br/>	
  <h1>Elemídia Sorocaba</h1>
  <br/>
  <div class="row">
	<?php foreach($xml->xpath("//predio[@estado='SO' or @estado='VT']") as $item) { 
			if (strval($item["status"]) == "online") {
				$class = "p-3 mb-2 bg-success text-white";
			} else {
				$class = "p-3 mb-2 bg-danger text-white";
			}
	?>
			<div class="col-md-<?php echo $bootstrapColWidth; ?> <?php echo $class; ?> "><?php echo strval($item["nome"]); ?></div>    
	<?php 
			$rowCount++;
			if($rowCount % $numOfCols == 0) echo '</div><div class="row">'; 
	?>
	<?php } ?>
  </div>
</div>

</body>
</html>