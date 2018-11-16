<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
#Se importa el componente request de Symfony esto permite hacer peticiones
use Symfony\Component\HttpFoundation\Request;

class FirewallController extends Controller
{
	// Se declara la variable session 
	private $session;

	// Se realiza el constructor 
	public function __construct()
	{
		$this->session = new Session();
	}

	public function listGroupFirewallAction()
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
				return $this->render("@Principal/firewall/listGroup.html.twig", array("grupo"=>$listaGrupo));
	        }
	        if($role == "ROLE_ADMIN")
	        {
	        	return $this->redirectToRoute("listFirewall");
	        }
	        if($role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("listFirewall");
	        }
	    }
		return $this->redirectToRoute("dashboard");
	}

	// Funcion para listar las acl groups del sistema 
    public function listFirewallSuperUserAction($id)
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
				$query = "SELECT * FROM firewallwan WHERE namegroup = '$id' ORDER BY position_order";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$acl=$stmt->fetchAll();
				//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$queryOne = "SELECT * FROM firewallwan WHERE namegroup = '$id' ORDER BY position_order";
				$stmtOne = $db->prepare($queryOne);
				$paramsOne =array();
				$stmtOne->execute($paramsOne);
				$aclOne=$stmtOne->fetchAll();
				return $this->render("@Principal/firewall/listFirewall.html.twig", array("acls"=>$acl,"aclsOne"=>$aclOne));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }

    // Funcion para listar las acl groups del sistema 
    public function listFirewallAction()
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
				$query = "SELECT * FROM nat where namegroup ='$grupo' ORDER BY position_order";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$acl=$stmt->fetchAll();
				//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$queryOne = "SELECT * FROM natone where namegroup ='$grupo' ORDER BY position_order";
				$stmtOne = $db->prepare($queryOne);
				$paramsOne =array();
				$stmtOne->execute($paramsOne);
				$aclOne=$stmtOne->fetchAll();
				return $this->render("@Principal/firewall/listFirewall.html.twig", array("acls"=>$acl,"aclsOne"=>$aclOne));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$grupo=$u->getNameGroup();
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$query = "SELECT * FROM nat where namegroup ='$grupo' ORDER BY position_order";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$acl=$stmt->fetchAll();
				//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$queryOne = "SELECT * FROM natone where namegroup ='$grupo' ORDER BY position_order";
				$stmtOne = $db->prepare($queryOne);
				$paramsOne =array();
				$stmtOne->execute($paramsOne);
				$aclOne=$stmtOne->fetchAll();
				return $this->render("@Principal/firewall/listFirewall.html.twig", array("acls"=>$acl,"aclsOne"=>$aclOne));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }

    public function registerFirewallSuperUserAction(Request $request,$id)
	{
        if(isset($_POST['enviar']))
		{
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$type = $_POST['type'];
			$disabled = $_POST['disabled'];
			$interface = $_POST['interface'];
			$ipprotocol = $_POST['ipprotocol'];
			$proto = $_POST['proto'];
			$res = $_POST['icmptype'];
			$icmptype = implode(",",$res);
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$srcbeginport = $_POST['srcbeginport'];
			$srcbeginport_cust = $_POST['srcbeginport_cust'];
			$srcendport = $_POST['srcendport'];
			$srcendport_cust = $_POST['srcendport_cust'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$dstbeginport = $_POST['dstbeginport'];
			$dstbeginport_cust = $_POST['dstbeginport_cust'];
			$dstendport = $_POST['dstendport'];
			$dstendport_cust = $_POST['dstendport_cust'];
			$log = $_POST['log'];
			$descr = $_POST['descr'];
			$gateway = $_POST['gateway'];
			//$position_order = $_POST['position_order'];
			$query = "INSERT INTO firewallwan(type, disabled, interface, ipprotocol, proto, icmptype, srcnot, srctype, src, srcmask, srcbeginport, srcbeginport_cust, srcendport, srcendport_cust, dstnot, dsttype, dst, dstmask, dstbeginport, dstbeginport_cust, dstendport, dstendport_cust, log, descr, gateway, namegroup) VALUES ('$type','$disabled','$interface', '$ipprotocol', '$proto', '$icmptype', '$srcnot', '$srctype', '$src', '$srcmask', '$srcbeginport', '$srcbeginport_cust','$srcendport','$srcendport_cust', '$dstnot', '$dsttype', '$dst', '$dstmask', '$dstbeginport', '$dstbeginport_cust', '$dstendport', '$dstendport_cust', '$log', '$descr', '$gateway','$id')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupFirewall");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/firewall/registerFirewall.html.twig");
	}

	public function refreshFirewallWanAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$position = $_POST['position'];
		$i=1;
		foreach($position as $k=>$v){
		    $sql = "Update firewallwan SET position_order=".$i." WHERE id=".$v;
		    $stmt = $db->prepare($sql);
			$stmt->execute(array());
			$i++;
		}
		exit();
	}

	public function deleteFirewallWanAction($id)
	{
		// Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$query = "DELETE FROM firewallwan WHERE id = '$id'";
		$stmt = $db->prepare($query);
		$stmt->execute(array());
		// Se validad si la accion de borrar se cumplio
		if($stmt == null)
		{
			// Se notifica al actor que la eliminacion fue correcta 
			$estatus="Problems with the server try later.";
			$this->session->getFlashBag()->add("estatus",$estatus);
		}
		// De lo contrario se notifica al actor
		else
		{
			$estatus="Registry successfully deleted";
			$this->session->getFlashBag()->add("estatus",$estatus);
		}
		// Se redirecciona al listado
		return $this->redirectToRoute("listGroupFirewall");
	}

	public function editFirewallWanAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$querySelect = "SELECT * FROM firewallwan WHERE id = '$id'";
		$stmtSelect = $db->prepare($querySelect);
		$paramsSelect =array();
		$stmtSelect->execute($paramsSelect);
		$listaGrupo=$stmtSelect->fetchAll();
		foreach ($listaGrupo as $value) 
		{
			$array= explode(',',$value['icmptype']);
		}
		if(isset($_POST['enviar']))
		{
			$type = $_POST['type'];
			$disabled = $_POST['disabled'];
			$interface = $_POST['interface'];
			$ipprotocol = $_POST['ipprotocol'];
			$proto = $_POST['proto'];
			$res = $_POST['icmptype'];
			$icmptype = implode(",",$res);
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$srcbeginport = $_POST['srcbeginport'];
			$srcbeginport_cust = $_POST['srcbeginport_cust'];
			$srcendport = $_POST['srcendport'];
			$srcendport_cust = $_POST['srcendport_cust'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$dstbeginport = $_POST['dstbeginport'];
			$dstbeginport_cust = $_POST['dstbeginport_cust'];
			$dstendport = $_POST['dstendport'];
			$dstendport_cust = $_POST['dstendport_cust'];
			$log = $_POST['log'];
			$descr = $_POST['descr'];
			$gateway = $_POST['gateway'];
			$query = "UPDATE firewallwan SET type= '$type', disabled = '$disabled', interface = '$interface', ipprotocol = '$ipprotocol', proto = '$proto', icmptype = '$icmptype', srcnot = '$srcnot', srctype = '$srctype', src = '$src', srcmask = '$srcmask', srcbeginport = '$srcbeginport', srcbeginport_cust = '$srcbeginport_cust', srcendport = '$srcendport', srcendport_cust = '$srcendport_cust', dstnot = '$dstnot', dsttype = '$dstnot', dst = '$dst', dstmask = '$dstmask', dstbeginport = '$dstbeginport', dstbeginport_cust = '$dstbeginport_cust', dstendport = '$dstendport', dstendport_cust = '$dstendport_cust', log = '$log', descr = '$descr', gateway = '$gateway' WHERE id = '$id'";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupFirewall");
		}
		return $this->render("@Principal/firewall/editFirewall.html.twig", array("value"=>$listaGrupo,"value2"=>$array));
	}
}
