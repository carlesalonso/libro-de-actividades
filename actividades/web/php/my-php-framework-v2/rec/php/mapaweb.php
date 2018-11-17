
<?
	//Genera el Mapa Web de forma automática a partir del array Mapping

	echo '<ul>';

	$nivel[0]="";
	$nivel[1]="";
	$i=1;
	while (list($clave, $valor ) = each($mapping)){
		//Estas páginas no deben aparecer en el mapa web
	   if ($clave=='error' || $clave=='buscador' || $clave=='imprimir') continue;
	   
		$opcion=-1;
		$j=0;
		while($j<=$i) {
			if ($nivel[$j]==$valor[0]) $opcion=$j;
			$j=$j+1;
		}
		if ($opcion<0) {
			$nivel[$i+1]=$valor[0];
			$opcion=$i+1;
		}

		if ($opcion==$i) {
			echo "<li><a href=\"".getPagina($clave)."\">".$valor[3]." </a>: ".$valor[4]."</li>";
		} else if ($opcion>$i) {
			echo "<ul>";
			echo "<li><a href=\"".getPagina($clave)."\">".$valor[3]." </a>: ".$valor[4]."</li>";
		} else if ($opcion<$i) {
			$j=$i;
			while($j>$opcion) { echo "</ul>"; $j=$j-1;}
			echo "<li><a href=\"".getPagina($clave)."\">".$valor[3]." </a>: ".$valor[4]."</li>";
		}
		$i=$opcion;
	}
	echo '</ul>';
?>

