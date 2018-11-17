
<?
	//Autor: David Vargas <dvargas@canarias.org>
	//-------------------------------------------------------
	//Funciones que leen el vector de mapping y devuelven 
	//algunos de los elementos del árbol
	//-------------------------------------------------------
	
	function echoHijos($pagina,$titulo='*') {
		include('rec/php/mapping.php');
				
		$flag=0;
		if ($titulo=='*') {$titulo=$mapping[$pagina][3];}

		while (list($clave, $valor ) = each($mapping)){
			if ($valor[0]==$pagina) {
				if ($flag==0) {
					//Mostrar la cabecera
					$flag=1;
					echo $titulo;
					echo '<ul>';
				}
				echo '<li>';
				echo '<a href="'.getPagina($clave).'">';
				echo $valor[3];
				echo '</a></li>';
			}
		}
		if ($flag==1) {echo '</ul>';}
		return true;
	}
	
	function echoSubcategorias2($pagina,$mapping,$titulo='Subcategor&iacute;as') {
		$i=0;

		while (list($clave, $valor ) = each($mapping)){
			if ($valor[0]==$pagina) {
				if ($i==0) {
					//Mostrar la cabecera
					$i=1;
					echo '<tr>';
		      			echo '<td class="textoTituloMenu" align="left" bgcolor="#000000">';
		      	   	//echo '<img src="rec/ico/kwin.jpg" alt="noticias" align="middle" height="30" width="30">';
					echo $titulo;
					echo '</td>';
					echo '</tr>';
				}
				echo '<tr>';
				echo '<td class="enlaceMenu" align="left" height="20" valign="middle">';
				echo '<img src="rec/ico/arrow.gif" alt="flecha" align="middle" height="9" width="11">';
				echo '<a href="'.getPagina($clave).'">';
				echo $valor[3];
				echo '</a>';
				echo '</td>';
				echo '</tr>';
			}
		}
		if ($i==1) {
			echo '<tr><td>&nbsp;</td></tr>';
		}
		return true;
	}

	//-------------------------------------------------------
	function echoCategorias2($pagina,$mapping,$titulo='Categor&iacute;as') {
		//Las categorías no se muestran para las páginas del primer nivel
		//Las que tienen de padre a la página raíz 'index'
		if ($mapping[$pagina][0]=='index' || $pagina=='index') return true;
		if ($pagina=='departamentos.electricidad.index') return true;
		
		echoSubcategorias2($mapping[$pagina][0],$mapping,$titulo);
		return true;
	}

?>
