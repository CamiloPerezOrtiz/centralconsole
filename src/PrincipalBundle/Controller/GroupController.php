<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;

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
			echo 'hostname: '.$hostname.'<br/>'; 
			echo 'ip: '.$ip.'<br/>'; 
			echo 'cliente: '.$cliente.'<br/><br/>';
			$query = "INSERT INTO txtip VALUES (nextval('txtip_id_seq'),'$hostname','$ip','$cliente')";
			$stmt = $db->prepare($query);
			$params =array();
			$stmt->execute($params);
			$flush=$em->flush();
			
			/*Crear carpetas para cada grupo o cliente
			 *Si la carpeta exite ya no la crear de lo contrario si no exite la carpeta lo crea 
			 */
			$serv = $_SERVER['DOCUMENT_ROOT'] . "/var/www/html/centralconsole";
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
			/*
	                 *Se establecen los archivos py para ser copiados en la carpeta que se crean 
			 *de la misma manera es estable el destino 
			 */
			$origenSquidGuardDest = "/var/www/html/centralconsole/web/squidguarddest.py"; 
			$origenSquidGuardAcl = "/var/www/html/centralconsole/web/squidguardacl.py";
			$origenAliases = "/var/www/html/centralconsole/web/aliases.py";
			$destino = "/var/www/html/centralconsole/web/Groups/$ruta";
			/*
			* se crea los archivos encapa carpeta 
			*/
			if (file_exists($destino))
			{      
				copy("$origenSquidGuardDest","$destino");  
				copy("$origenSquidGuardAcl","$destino");  
				copy("$origenAliases","$destino");  
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

	public function listGroupIpAction($id)
	{
		//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
		$query = "SELECT id,ip,cliente FROM txtip WHERE cliente = '$id'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$listaGrupoIp=$stmt->fetchAll();

		//Variable para abrir el archivo nombreGrupo e insertar el nombre del grupo el cual fue seleccionado
		$file=fopen("nombreGrupo.txt","w") or die("Problemas");
		fputs($file,$id);
		fclose($file);
		return $this->render("@Principal/groups/listGroupIp.html.twig", array("grupoIp"=>$listaGrupoIp));
	}
	
	public function saveListIpAction($id)
	{
		//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		//Query para selecionar todas las ip del grupo el cual fue seleccionado
		$query = "SELECT ip FROM txtip WHERE cliente = '$id'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$formato=$stmt->fetchAll();
		$res = array("formatos"=>$formato);
		//Varibale para abrir el archivo ipGrupos.txt y guardar las ip que tenga el grupo seleccionado
		$file=fopen("ipGrupos.txt","w") or die("Problemas");
		foreach ($formato as $formatos) 
		{
			fputs($file,$formatos['ip']."\n");
		}
		fclose($file);
		return $this->redirectToRoute("listGroup");
	}

	public function deleteIpAction($id)
	{
		//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:Txtip")->find($id);
		$em->remove($formato);
		$flush=$em->flush();
		if($flush == null)
		{
			$estatus="Registry successfully deleted";
		}
		else
		{
			$estatus="Problems with the server try later.";
		}
		return $this->redirectToRoute("listGroup");
	}
}
