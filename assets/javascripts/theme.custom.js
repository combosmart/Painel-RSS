/* Add here all your JS customizations */
function getEquipamentos(id) {
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
	  if (this.readyState == 4 && this.status == 200) {
	    document.getElementById("result").innerHTML = this.responseText;
	  }
	};
	xhttp.open("GET", "get_equip.php?q=" + id, true);
	xhttp.send();
}

$(window).load(function(){      
	$('#change_img').click(function(){
	    //$('#post_img').click();
	    alert('teste');
	});
});