<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
#Se importa el componente request de Symfony esto permite hacer peticiones
use Symfony\Component\HttpFoundation\Request;

class NatController extends Controller
{
	// Se declara la variable session 
	private $session;

	// Se realiza el constructor 
	public function __construct()
	{
		$this->session = new Session();
	}

	public function listGroupNatAction()
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
				return $this->render("@Principal/nat/listGroup.html.twig", array("grupo"=>$listaGrupo));
	        }
	        if($role == "ROLE_ADMIN")
	        {
	        	return $this->redirectToRoute("listNat");
	        }
	        if($role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("listNat");
	        }
	    }
		return $this->redirectToRoute("dashboard");
	}

	// Funcion para listar las acl groups del sistema 
    public function listNatSuperUserAction($id)
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
				$query = "SELECT * FROM nat";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$acl=$stmt->fetchAll();
				return $this->render("@Principal/nat/listNat.html.twig", array("acls"=>$acl));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }

    // Funcion para listar las acl groups del sistema 
    public function listNatAction()
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
	        if($role == "ROLE_ADMIN")
	        {
	        	$grupo=$u->getNameGroup();
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$query = "SELECT * FROM acl where namegroup ='$grupo'";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$acl=$stmt->fetchAll();
				return $this->render("@Principal/nat/listNat.html.twig", array("acls"=>$acl));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$grupo=$u->getNameGroup();
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$query = "SELECT * FROM acl where namegroup ='$grupo'";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$acl=$stmt->fetchAll();
				return $this->render("@Principal/nat/listNat.html.twig", array("acls"=>$acl));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }

    public function registerNatAction(Request $request, $id)
	{
        if(isset($_POST['enviar']))
		{
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$disabled = $_POST['disabled'];
			$nordr = $_POST['nordr'];
			$interface = $_POST['interface'];
			$proto = $_POST['proto'];
			$invert = $_POST['invert'];
			$type = $_POST['type'];
			$address = $_POST['addressmask'];
			$mask = $_POST['mask'];
			$sourceport = $_POST['sourceport'];
			$custom = $_POST['custom'];
			$toport = $_POST['toport'];
			$custom2 = $_POST['custom2'];
			$invert2 = $_POST['invert2'];
			$type2 = $_POST['type2'];
			$address2 = $_POST['address2'];
			$mask2 = $_POST['mask2'];
			$port = $_POST['port'];
			$custom3 = $_POST['custom3'];
			$toport2 = $_POST['toport2'];
			$custom4 = $_POST['custom4'];
			$redirect = $_POST['redirect'];
			$targetport = $_POST['targetport'];
			$toport3 = $_POST['toport3'];
			$type2 = $_POST['type2'];
			$description = $_POST['description'];
			$sync = $_POST['sync'];
			$nat = $_POST['nat'];
			$filter = $_POST['filter'];
			//$position_order = $_POST['position_order'];
			$query = "INSERT INTO nat(disabled, nordr, interface, proto, invert, type, address, mask, sourceport, custom, toport, 
			custom2,invert2, type2, address2, mask2, port, custom3, toport2,custom4, redirect, targetport, toport3, description, sync, nat,filter) 
						VALUES ('$disabled','$nordr','$interface','$proto','$invert', '$type','$address','$mask','$sourceport','$custom','$toport', '$custom2','$invert2','$type2',
						'$address2','$mask2','$port', '$custom3','$toport2','$custom4','$redirect', '$targetport','$toport3','$description','$sync', '$nat','$filter')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupNat");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/nat/registerNat.html.twig");
	}
}
