<?php
include "configuracion.php";
$PUBLICA_ACTUAL="89.140.16.75";
//obtener listado de canales para un producto dada su mac
//este es el web service

if(isset($_GET['mac']))
	$mac= $_GET['mac'];

	$archivoConfigCanales="NULL";

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
					
					/*modificacion febrero 2018, cada cablero puede tener su wowza local, por lo que dicha ip reemplazará la publica por defecto*/
					$sel="select ipwowzalocal from users where id = (select user_id from iptvs where mac='$mac')";
					
					$query = mysqli_query($db,$sel) or die(mysqli_error($db));
					
					while ($row = mysqli_fetch_assoc($query)) 
					{	
						$ipwowzalocal=$row["ipwowzalocal"];
					}						
					
					
					$sel ="select paquetecanal_id,flagpaquetecanalact from clientes_iptvs where iptv_id = (select id from iptvs where mac='$mac')";
					
					$actualizado="";	
					$query = mysqli_query($db,$sel) or die(mysqli_error($db));
					while ($row = mysqli_fetch_assoc($query)) 
					{
						$idpaquetecanal=$row["paquetecanal_id"];
						$actualizado=$row["flagpaquetecanalact"];
					}

					//si esta marcado para actualizar, devuelvo el listado de canales
					if($actualizado==1)
					{
						/*$sel2 ="select pais,canal,urlcanal,urllogo,acronimopais from canales 
								where
								idcanal in (select canal_id from canalesporpaquetes
								where paquetecanal_id=$idpaquetecanal order by ordencanal)";
						

						*/	

						$sel2="select canales.pais,canales.canal,canales.urlcanal,canales.urllogo,
						canales.acronimopais from canales,canales_paquetes where
						canales.id=canales_paquetes.canal_id and
						canales_paquetes.paquetecanal_id=$idpaquetecanal
						order by ordencanal";



						$query2 = mysqli_query($db,$sel2) or die(mysqli_error($db));

						$archivoConfigCanales="#EXTM3U\n";
						while ($row = mysqli_fetch_assoc($query2)) 
						{
							$pais=$row["pais"];
							$canal=$row["canal"];

							$urlCanal=$row["urlcanal"];
							//cambiar la ip por defecto publica de los canales por la local del wifero ?¿?
							//tampoco se si esto será asi, de momento asumo que si, que será la misma url con distinta ip
							$urlCanal = str_replace($PUBLICA_ACTUAL, $ipwowzalocal, $urlCanal);
							
							
							$urlLogo=$row["urllogo"];
							$acronimoPais=$row["acronimopais"];
							
							$fullPais=$acronimoPais.$canal;
							
							$archivoConfigCanales=$archivoConfigCanales."#EXTINF:-1 group-name=\"$pais\" group-title=\"$pais\",tvg-name=\"$canal\",tvg-logo=\"$urlLogo\",$fullPais\n$urlCanal\n";
						}
						
						//seteamos actualizado =1 pk ya lo ha descargado
						$sel3 ="update clientes_iptvs set flagpaquetecanalact=0
							where iptv_id = (select id from iptvs where 
							mac='$mac')";
							
						$query3 = mysqli_query($db,$sel3) or die(mysql_error($db));
					}		
					mysqli_close($db);	
			}	
		}	
	
		
	}
	
	echo $archivoConfigCanales;
	
//curl -s 192.168.60.15/superplataformagestioniptv/obtenercanales.php?mac=b8:27:eb:2e:2e:f5


?>



