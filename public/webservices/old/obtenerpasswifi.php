<?php
include "configuracion.php";
//obtener listado de canales para un producto dada su mac
//este es el web service

if(isset($_GET['mac']))
	$mac= $_GET['mac'];
	
	$password="passdefault";

	if(isset($mac))
	{	
		//test database connection
		// Create connection
		$db=mysqli_connect($ip_bd, $usuario_bd, $password_bd,$nombre_bd);
		if (!$db) {
		  //die('Not connected : ' . mysql_error());
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
					$sel ="select password,flagwifiact from clientes_iptvs
							where
							iptv_id = (select id from iptvs where mac='$mac')";
						
					$query = mysqli_query($db,$sel) or die(mysqli_error($db));
					$actualizado=0;
					
					while ($row = mysqli_fetch_assoc($query))
					{
						$password=$row["password"];
						$actualizado=$row["flagwifiact"];
					}
					
					
					//si esta marcado para actualizar, devuelvo el listado de canales
					if($actualizado==1)
					{
						//echo $password;
						//nada, ya he asignado arriba
						//marco como actualizado
						//seteamos actualizado =1 pk ya lo ha descargado
						$sel3 ="update clientes_iptvs set flagwifiact=0
							where iptv_id = (select id from iptvs where 
							mac='$mac')";
							
						$query3 = mysqli_query($db,$sel3) or die(mysql_error($db));
						
					}
					else
						$password="NULL";
					
					mysqli_close($db);
	
			}	
		}	
	
		
	}
	
	echo $password;
	
//curl -s 192.168.60.15/superplataformagestioniptv/obtenercanales.php?mac=b8:27:eb:2e:2e:f5


?>


