<?php
include "configuracion.php";
//obtener listado de canales para un producto dada su mac
//este es el web service

if(isset($_GET['mac']))
	$mac= $_GET['mac'];

if(isset($_GET['ip']))
	$ip= $_GET['ip'];
	

	if(isset($mac) && isset($ip))
	{	
		//echo "yea";
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
			
					$db=mysqli_connect($ip_bd, $usuario_bd, $password_bd,$nombre_bd) or die(mysqli_error($db));
					$sel ="update clientes_iptvs set ipactual='$ip'
							where
							iptv_id = (select id from iptvs where mac='$mac')";
						
					//echo $sel;	
					$query = mysqli_query($db,$sel) or die(mysql_error($db));

					mysql_close($db);
	
				}	
		}	
	
		
	}
	
?>


