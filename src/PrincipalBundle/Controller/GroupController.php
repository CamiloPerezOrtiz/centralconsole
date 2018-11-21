<?php

################################################################
# This Code Has Been Developed By Camilo Perez                 #
################################################################

namespace PrincipalBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
class GroupController extends Controller
{
	public function txtIpAction()
	{
		//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		//Query para borrar la tabla txtip de la base de datos
		$queryDrop = "DELETE FROM txtip";
		$stmtDrop = $db->prepare($queryDrop);
		$paramsDrop =array();
		$stmtDrop->execute($paramsDrop);
		$flushDrop=$em->flush();
		//Query para que la secuencia del contador regrese a 1
		$queryReset = "ALTER SEQUENCE txtip_id_seq RESTART WITH 1";
		$stmtReset = $db->prepare($queryReset);
		$paramsReset =array();
		$stmtReset->execute($paramsReset);
		$flushReset=$em->flush();
		//Variable para leer el archivo informacionGrupo.txt e insertar en la tabla txtip de la base de datos
		$filas=file('informacionGrupos.txt'); 
		foreach($filas as $value)
		{
			list($hostname, $ip, $cliente) = explode("|", $value);
			'hostname: '.$hostname.'<br/>'; 
			'ip: '.$ip.'<br/>'; 
			'cliente: '.$cliente.'<br/><br/>';
			$query = "INSERT INTO txtip VALUES (nextval('txtip_id_seq'),'$hostname','$ip','$cliente','Null')";
			$stmt = $db->prepare($query);
			$params =array();
			$stmt->execute($params);
			$flush=$em->flush();
			/*Crear carpetas para cada grupo o cliente
			*Si la carpeta exite ya no la crear de lo contrario si no exite la carpeta lo crea 
			*/
			$serv = '/var/www/html/centralizedConsole/web/Groups/';
			$ruta = $serv . $cliente;
			if(!file_exists($ruta))
			{
			  mkdir ($ruta);
			  echo "Se ha creado el directorio: " . $ruta;
			} 
			else 
			{
			  echo "la ruta: " . $ruta . " ya existe ";
			}
		}
		return $this->redirectToRoute("listGroup");
	}
	public function listGroupAction()
	{
		$authenticationUtils = $this->get("security.authentication_utils");
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		$u = $this->getUser();
		if($u != null)
		{
			//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
			$em = $this->getDoctrine()->getEntityManager();
	        $db = $em->getConnection();
	        $role=$u->getRole();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$querySelect = "SELECT DISTINCT cliente FROM txtip ORDER BY cliente ASC";
				$stmtSelect = $db->prepare($querySelect);
				$paramsSelect =array();
				$stmtSelect->execute($paramsSelect);
				$listaGrupo=$stmtSelect->fetchAll();
				return $this->render("@Principal/groups/listGroup.html.twig", array("grupo"=>$listaGrupo));
	        }
	        if($role == "ROLE_ADMIN")
	        {
	        	$grupo=$u->getNameGroup();
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$querySelect = "SELECT DISTINCT cliente FROM txtip WHERE cliente = '$grupo' ORDER BY cliente ASC";
				$stmtSelect = $db->prepare($querySelect);
				$paramsSelect =array();
				$stmtSelect->execute($paramsSelect);
				$listaGrupo=$stmtSelect->fetchAll();
				return $this->render("@Principal/groups/listGroup.html.twig", array("grupo"=>$listaGrupo));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$grupo=$u->getNameGroup();
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$querySelect = "SELECT DISTINCT cliente FROM txtip WHERE cliente = '$grupo' ORDER BY cliente ASC";
				$stmtSelect = $db->prepare($querySelect);
				$paramsSelect =array();
				$stmtSelect->execute($paramsSelect);
				$listaGrupo=$stmtSelect->fetchAll();
				return $this->render("@Principal/groups/listGroup.html.twig", array("grupo"=>$listaGrupo));
	        }
	    }
		return $this->redirectToRoute("dashboard");
	}

	public function listGroupIpAction()
	{
		$authenticationUtils = $this->get("security.authentication_utils");
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		$u = $this->getUser();
		$grupo=$u->getNameGroup();
		//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
		$query = "SELECT * FROM txtip WHERE cliente = '$grupo'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$listaGrupoIp=$stmt->fetchAll();
		//Variable para abrir el archivo nombreGrupo e insertar el nombre del grupo el cual fue seleccionado
		$file=fopen("Groups/$grupo/nombreGrupo.txt","w") or die("Problemas");
		fputs($file,$grupo);
		fclose($file);
		if(isset($_POST['enviar']))
		{
			$ids = $_POST['ids'];
			//print_r($ids);
			$file=fopen("Groups/$grupo/ipGrupos.txt","w") or die("Problemas");
			foreach ($ids as $formatos) 
			{
				fputs($file,$formatos."\n");
			}
			fclose($file);
			# Aplicar cambios en el sistema #
			/*$archivoConfig = "Groups/$grupo/config.xml";
			$destinoConfig = "pf.xml";
		   	if (!copy($archivoConfig, $destinoConfig)) 
		   	{
			    echo "Error al copiar $archivoConfig...\n";
			}

			$archivoipGrupos = "Groups/$grupo/ipGrupos.txt";
			$destinoipGrupos = "ipGrupos.txt";
		   	if (!copy($archivoipGrupos, $destinoipGrupos)) 
		   	{
			    echo "Error al copiar $archivoipGrupos...\n";
			}*/
			$archivoipGrupos = "Groups/$grupo/ipGrupos.txt";
			$destinoipGrupos = "centralizedConsole/changes.txt";
		   	if (!copy($archivoipGrupos, $destinoipGrupos)) 
		   	{
			    echo "Error al copiar $archivoipGrupos...\n";
			}
			exec("python centralizedConsole/apply.py");
			#exec("python apply.py");
			# Ejecutar python aliases # 
			/*$process = new Process('python apply.py');
			$process->run();
			// executes after the command finishes
			if (!$process->isSuccessful()) {
			    throw new ProcessFailedException($process);
			}
			echo $process->getOutput();*/
			if($u != null)
			{
				//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
				$em = $this->getDoctrine()->getEntityManager();
		        $db = $em->getConnection();

		        $role=$u->getRole();
		        if($role == "ROLE_SUPERUSER")
		        {
		        	return $this->redirectToRoute('listGroup');
		        }
		        if($role == "ROLE_ADMIN")
		        {	        	
					return $this->redirectToRoute('listGroupIp');
				}
			}
			return $this->redirectToRoute('dashboard');
		}
		return $this->render("@Principal/groups/listGroupIp.html.twig", array("grupoIp"=>$listaGrupoIp));
	}

	public function listGroupIpSuperUserAction($id)
	{
		if(isset($_POST['enviar']))
		{
			$ids = $_POST['ids'];
			//print_r($ids);
			$file=fopen("Groups/$id/ipGrupos.txt","w") or die("Problemas");
			foreach ($ids as $formatos) 
			{
				fputs($file,$formatos."\n");
			}
			fclose($file);
			# Aplicar cambios en el sistema #
			/*$archivoConfig = "Groups/$id/conf.xml";
			$destinoConfig = "centralizedConsole/conf.xml";
		   	if (!copy($archivoConfig, $destinoConfig)) 
		   	{
			    echo "Error al copiar $archivoConfig...\n";
			}*/

			$archivoipGrupos = "Groups/$id/ipGrupos.txt";
			$destinoipGrupos = "centralizedConsole/changes.txt";
		   	if (!copy($archivoipGrupos, $destinoipGrupos)) 
		   	{
			    echo "Error al copiar $archivoipGrupos...\n";
			}
			exec("python centralizedConsole/apply.py");
			# Ejecutar python aliases # 
			/*$process = new Process('python centralizedConsole/apply.py');
			$process->run();
			// executes after the command finishes
			if (!$process->isSuccessful()) {
			    throw new ProcessFailedException($process);
			}
			echo $process->getOutput();*/

			$authenticationUtils = $this->get("security.authentication_utils");
			$error = $authenticationUtils->getLastAuthenticationError();
			$lastUsername = $authenticationUtils->getLastUsername();
			$u = $this->getUser();
			if($u != null)
			{
				//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
				$em = $this->getDoctrine()->getEntityManager();
		        $db = $em->getConnection();

		        $role=$u->getRole();
		        if($role == "ROLE_SUPERUSER")
		        {
		        	return $this->redirectToRoute('listGroup');
		        }
		        if($role == "ROLE_ADMIN")
		        {	        	
					return $this->redirectToRoute('listGroupIp');
				}
			}
			return $this->redirectToRoute('dashboard');
		}

		//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
		$query = "SELECT * FROM txtip WHERE cliente = '$id'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$listaGrupoIp=$stmt->fetchAll();
		//Variable para abrir el archivo nombreGrupo e insertar el nombre del grupo el cual fue seleccionado
		$file=fopen("Groups/$id/nombreGrupo.txt","w") or die("Problemas");
		fputs($file,$id);
		fclose($file);
		return $this->render("@Principal/groups/listGroupIp.html.twig", array("grupoIp"=>$listaGrupoIp));
	}
}