
<?
	//Genera una tabla HTML para los módulos según array de entrada
	//Valores del array: Nombre módulo,horas(int), curso(1,2), pdf(true,false)

	function echoTablaModulosD($modulos) {
		//Cabecera de la tabla
		?>
		<table border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%" id="AutoNumber1">
  		<tr class="textoTituloMenu">
    		<td width="67%" align="center" rowspan="2" bgcolor="#000000"><b>M&oacute;dulos Profesionales</b></td>
    		<td width="9%" align="center" rowspan="2" bgcolor="#000000"><b>Horas semanales</b></td>
    		<td width="10%" align="center" colspan="2" bgcolor="#000000"><b>Curso</b></font></td>
    		<td width="14%" align="center" rowspan="2" colspan="2" bgcolor="#000000"><b>Curriculums</b></td>
  		</tr>
  		<tr class="textoTituloMenu">
    			<td width="5%" align="center" bgcolor="#000000"><b>1</b></td>
    			<td width="5%" align="center" bgcolor="#000000"><b>2</b></td>
  		</tr>
		<?
		//Cuerpo de la tabla
		//while (list($c1,$c2,$c3,$c4) = each($modulo)){
		for ($i=0;$i<count($modulos);$i++) {
			$c1=$modulos[$i][0];
			$c2=$modulos[$i][1];
			$c3=$modulos[$i][2];
			$c4=$modulos[$i][3];

			echo '<tr>';
			echo '<td>'.$c1.'</td>'; //Nombre del módulo
			echo '<td align="center">'.$c2.'</td>'; //Horas semanales
			//Curso
			if ($c3==1) {
				echo '<td align="center"><img border="0" src="rec/ico/apply.jpg"></td>';
				echo '<td>&nbsp</td>';
			} elseif ($c3==2) {
				echo '<td>&nbsp</td>';	
				echo '<td align="center"><img border="0" src="rec/ico/apply.jpg"></td>';
			} else {
				echo '<td>&nbsp</td>';	
				echo '<td>&nbsp</td>';	
			} 

			//pdf
			if ($c4==true) {
				echo '<td align="center"><img border="0" src="rec/ico/pdf_opt.jpg"></td>';
			} else {
				//echo '<td>&nbsp</td>';	
				echo '<td align="center"><img border="0" src="rec/ico/button_cancel.jpg"></td>';
			}
			echo '</tr>';
		}
		//Pie de la tabla
		echo '</table>';
		return true;
	}

	function echoTablaModulosDN($modulos) {
		//Cabecera de la tabla
		?>
		<table border="1" cellpadding="0" cellspacing="0" bordercolor="#111111" width="100%" id="AutoNumber1">
		<tr class="textoTituloMenu">
		  <td width="57%" align="center" rowspan="3" bgcolor="#000000">M&oacute;dulos Profesionales</td>
		  <td width="10%" align="center" rowspan="3" bgcolor="#000000">Horas semanales</td>
		  <td width="19%" align="center" colspan="5" bgcolor="#000000">Curso</td>
		  <td width="14%" align="center" rowspan="3" bgcolor="#000000">Curriculums</td>
		</tr>
		<tr class="textoTituloMenu">
		  <td width="10%" align="center" colspan="2" bgcolor="#000000">Diurno</td>
		  <td width="9%" align="center" colspan="3" bgcolor="#000000">Nocturno</td>
		</tr>
		<tr class="textoTituloMenu">
		  <td width="5%" align="center" bgcolor="#000000">1</td>
		  <td width="5%" align="center" bgcolor="#000000">2</td>
		  <td width="3%" align="center" bgcolor="#000000">1</td>
		  <td width="3%" align="center" bgcolor="#000000">2</td>
		  <td width="3%" align="center" bgcolor="#000000">3</td>
		</tr>
		<?
		//Cuerpo de la tabla
		//while (list($c1,$c2,$c3,$c4) = each($modulo)){
		for ($i=0;$i<count($modulos);$i++) {
			$c1=$modulos[$i][0];
			$c2=$modulos[$i][1];
			$c3d=$modulos[$i][2];
			$c3n=$modulos[$i][3];
			$c4=$modulos[$i][4];

			echo '<tr>';
			echo '<td>'.$c1.'</td>'; //Nombre del módulo
			echo '<td align="center">'.$c2.'</td>'; //Horas semanales
			//Curso

			if ($c3d==1) {
				echo '<td align="center"><img border="0" src="rec/ico/apply.jpg"></td>';
				echo '<td>&nbsp</td>';
			} elseif ($c3d==2) {
				echo '<td>&nbsp</td>';	
				echo '<td align="center"><img border="0" src="rec/ico/apply.jpg"></td>';
			} else {
				echo '<td>&nbsp</td>';	
				echo '<td>&nbsp</td>';	
			} 

			if ($c3n==1) {
				echo '<td align="center"><img border="0" src="rec/ico/apply.jpg"></td>';
				echo '<td>&nbsp</td>';	
				echo '<td>&nbsp</td>';	
			} elseif ($c3n==2) {
				echo '<td>&nbsp</td>';	
				echo '<td align="center"><img border="0" src="rec/ico/apply.jpg"></td>';
				echo '<td>&nbsp</td>';	
			} elseif ($c3n==3) {
				echo '<td>&nbsp</td>';	
				echo '<td>&nbsp</td>';	
				echo '<td align="center"><img border="0" src="rec/ico/apply.jpg"></td>';
			} else {
				echo '<td>&nbsp</td>';	
				echo '<td>&nbsp</td>';	
				echo '<td>&nbsp</td>';	
			}
			//pdf

			if ($c4==true) {
				echo '<td align="center"><img border="0" src="rec/ico/pdf_opt.jpg"></td>';
			} else {
				//echo '<td>&nbsp</td>';	
				echo '<td align="center"><img border="0" src="rec/ico/button_cancel.jpg"></td>';
			}
			echo '</tr>';
		}

		//Pie de la tabla
		echo '</table>';
		return true;

	}

?>
