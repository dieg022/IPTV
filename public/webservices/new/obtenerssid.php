<?php
include "configuracion.php";
//obtener listado de canales para un producto dada su mac
//este es el web service

if(isset($_GET['mac']))
	$mac= $_GET['mac'];
	
	$ssid="ssiddefault";

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
					$sel ="select ssid,flagwifiact from clientes_iptvs
							where iptv_id = (select id from iptvs where 
							mac='$mac')";
					
					//echo $sel;		
					$query = mysqli_query($db,$sel) or die(mysqli_error($db));
					$actualizado=0;
					
					while ($row = mysqli_fetch_assoc($query))
					{
						$ssid=$row["ssid"];
						$actualizado=$row["flagwifiact"];
					}
					
					
					//si esta marcado para actualizar, devuelvo el listado de canales
					if($actualizado==1)
					{
						//echo $ssid;
						//ya esta asignado
						//seteamos actualizado =1 pk ya lo ha descargado
						//AKI NO PUEDO HACER ESTO PK SI NO LA COGIDA xd DEL PASSWORD FALLA
						//LO HAGO EN PASSWORD
						/*
						$sel3 ="update productosinstalados set flagwifiact=0
							where
							idproducto = (select idproducto from productos where mac='$mac')";
							
						$query3 = mysql_query($sel3) or die(mysql_error());		*/				
					}
					else
						$ssid="NULL";
					
					mysqli_close($db);
	
			}	
		}	
	
		
	}
	
	echo $ssid;
	
//curl -s 192.168.60.15/superplataformagestioniptv/obtenercanales.php?mac=b8:27:eb:2e:2e:f5


?>


