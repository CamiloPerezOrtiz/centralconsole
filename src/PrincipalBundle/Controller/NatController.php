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
				$query = "SELECT * FROM acl WHERE namegroup = '$id'";
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
			$name = $_POST['name'];
			$description = $_POST['description'];
			$status = $_POST['status'];
			$value1 = $_POST['input'];
			$value2 = $_POST['input2'];
			$res1 = implode(" ",$value1);
			$res2 = implode(" ||",$value2);
			$query = "INSERT INTO nat(name, description, status, ip, descriptionhost, namegroup) 
						VALUES ('$name','$description','$status','$res1','$res2', '$id')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupNat");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/nat/registerNat.html.twig");
	}
}
