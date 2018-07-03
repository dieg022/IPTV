<?php
include "configuracion.php";
//obtener listado de canales para un producto dada su mac
//este es el web service

if(isset($_GET['mac']))
	$mac= $_GET['mac'];
	
	$estado="STOCK";

	if(isset($mac))
	{	
		//test database connection
		// Create connection
		$db=mysqli_connect($ip_bd, $usuario_bd, $password_bd,$nombre_bd);
		if (!$db) {
		  #die('Not connected : ' . mysql_error());
		  //error devuelvo nul
		}
		else
		{	
				//test database table exists
				$db_selected = mysqli_select_db($db,$nombre_bd);
				if (!$db_selected) {
				  //error dejo k devuelva nulo
				}	
				else
				{		

					//lo suyo es hacerlo con orientación a objetos, de momento a pelo
					$db=mysqli_connect($ip_bd, $usuario_bd, $password_bd,$nombre_bd) or die(mysqli_error($db));
					$sel ="select estado from iptvs where 
							mac='$mac'";
					
					//echo $sel;		
					$query = mysqli_query($db,$sel) or die(mysqli_error($db));
					$actualizado=0;
					
					while ($row = mysqli_fetch_assoc($query))
					{
						$estado=$row["estado"];
					}
					
					
					//si es retirado => asumo que lo consume y se pone de fabrica
					//luego yo aki lo vuelvo a poner en stock
					if($estado=="RETIRADO")
					{

						$sel3 ="update iptvs set estado='STOCK' where mac='$mac'";
							
						$query3 = mysqli_query($db,$sel3) or die(mysqli_error($db));						
					}
					
					//aki puede venir el estado DEFAULT-XXXX
					//donde xxx es el estado antiguo, eseta claro que sera instalado, paso, 
					//siempre que venga default, tras la resintalacion, lo dejo en instalado
					
					//si default lo vuelvo a dejar como estaba
					//luego yo aki lo vuelvo a poner en stock
					if($estado=="DEFAULT")
					{

						$sel3 ="update iptvs set estado='INSTALADO' where mac='$mac'";
							
						$query3 = mysqli_query($db,$sel3) or die(mysqli_error($db));						
					}					
					
					mysqli_close($db);
	
			}	
		}	
	
		
	}
	
	echo $estado;
	

?>


