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
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$srcbeginport = $_POST['srcbeginport'];
			$dstbeginport_cust = $_POST['dstbeginport_cust'];
			$srcendport = $_POST['srcendport'];

			$dstendport_cust = $_POST['dstendport_cust'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$dstendport = $_POST['dstendport'];
			$dstbeginport_cust2 = $_POST['dstbeginport_cust2'];
			$dstendport2 = $_POST['dstendport2'];
			$dstendport_cust2 = $_POST['dstendport_cust2'];
			$localip = $_POST['localip'];

			$localbeginport = $_POST['localbeginport'];
			$localbeginport_cust = $_POST['localbeginport_cust'];
			$descr = $_POST['descr'];
			$nosync = $_POST['nosync'];
			$natreflection = $_POST['natreflection'];
			$associated_rule_id = $_POST['associated_rule_id'];
			//$position_order = $_POST['position_order'];
			$query = "INSERT INTO nat(disabled, nordr, interface, proto, srcnot, srctype, src, srcmask, srcbeginport, dstbeginport_cust, srcendport, dstendport_cust, dstnot, dsttype, dst, dstmask, dstendport, dstbeginport_cust2, dstendport2, dstendport_cust2, localip, localbeginport, localbeginport_cust, descr, nosync, natreflection, associated_rule_id) VALUES ('$disabled','$nordr','$interface', '$proto', '$srcnot', '$srctype', '$src', '$srcmask', '$srcbeginport', '$dstbeginport_cust', '$srcendport', '$dstendport_cust','$dstnot','$dsttype', '$dst', '$dstmask', '$dstendport', '$dstbeginport_cust2', '$dstendport2', '$dstendport_cust2', '$localip', '$localbeginport', '$localbeginport_cust', '$descr', '$nosync', '$natreflection', '$associated_rule_id')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupNat");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/nat/registerNat.html.twig");
	}

	public function registerNatOneAction(Request $request, $id)
	{
        if(isset($_POST['enviar']))
		{
			return $this->redirectToRoute("listGroupNat");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/nat/registerNatOne.html.twig");
	}

	public function refreshAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$position = $_POST['position'];
		$i=1;
		foreach($position as $k=>$v){
		    $sql = "Update nat SET position_order=".$i." WHERE id=".$v;
		    $stmt = $db->prepare($sql);
			$stmt->execute(array());
			$i++;
		}
	}
}
