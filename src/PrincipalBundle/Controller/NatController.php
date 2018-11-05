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
				$query = "SELECT * FROM nat ORDER BY position_order";
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
			$query = "INSERT INTO nat(disabled, nordr, interface, proto, srcnot, srctype, src, srcmask, srcbeginport, dstbeginport_cust, srcendport, dstendport_cust, dstnot, dsttype, dst, dstmask, dstendport, dstbeginport_cust2, dstendport2, dstendport_cust2, localip, localbeginport, localbeginport_cust, descr, nosync, natreflection, associated_rule_id, namegroup) VALUES ('$disabled','$nordr','$interface', '$proto', '$srcnot', '$srctype', '$src', '$srcmask', '$srcbeginport', '$dstbeginport_cust', '$srcendport', '$dstendport_cust','$dstnot','$dsttype', '$dst', '$dstmask', '$dstendport', '$dstbeginport_cust2', '$dstendport2', '$dstendport_cust2', '$localip', '$localbeginport', '$localbeginport_cust', '$descr', '$nosync', '$natreflection', '$associated_rule_id','$id')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupNat");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/nat/registerNat.html.twig");
	}

	public function editNatAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$querySelect = "SELECT * FROM nat WHERE id = '$id'";
		$stmtSelect = $db->prepare($querySelect);
		$paramsSelect =array();
		$stmtSelect->execute($paramsSelect);
		$listaGrupo=$stmtSelect->fetchAll();
		if(isset($_POST['enviar']))
		{
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

			$query = "UPDATE nat SET disabled = '$disabled', nordr = '$nordr', interface = '$interface', proto = '$proto', srcnot = '$srcnot', 
				srctype = '$srctype', src = '$src', srcmask = '$srcmask', srcbeginport = '$srcbeginport', dstbeginport_cust = '$dstbeginport_cust',
				srcendport = '$srcendport', dstendport_cust = '$dstendport_cust', dstnot = '$dstnot', dsttype = '$dsttype', dst = '$dst',
				dstmask = '$dstmask', dstendport = '$dstendport', dstbeginport_cust2 = '$dstbeginport_cust2', dstendport2 = '$dstendport2', 
				dstendport_cust2 = '$dstendport_cust2', localip = '$localip', localbeginport = '$localbeginport', localbeginport_cust = '$localbeginport_cust',
				descr = '$descr', nosync = '$nosync', natreflection = '$natreflection', associated_rule_id = '$associated_rule_id' WHERE id = '$id'";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupNat");
		}
		return $this->render("@Principal/nat/editNat.html.twig", array("value"=>$listaGrupo));
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

	public function createXMLNatAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();
    	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
		$query = "SELECT * FROM nat WHERE namegroup = '$id'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		// Se alamacena la consulta en una variable 
		$formato=$stmt->fetchAll();
		// Se crea un nuevo documento XML con la version 
	    $contenido = "<?xml version='1.0'?>\n";
	    // Se crear el nombre de la etiqueta
		$contenido .= "<nat>\n";
		// Se realiza un ciclo para llenar las demas etiquetas del archivo xml 
		foreach ($formato as $formatos) 
		{
			$contenido .= "\t\t\t<rule>\n";
			$contenido .= "\t\t\t\t<source>\n";
			# Campo type source #
			if($formatos['srctype'] === "any")
			{
				$contenido .= "\t\t\t\t\t<any></any>\n";
			}
			if($formatos['srctype'] === "single")
			{
				$contenido .= "\t\t\t\t\t<address>" . $formatos['src'] . "</address>\n";
			}
			if($formatos['srctype'] === "network")
			{
				$contenido .= "\t\t\t\t\t<address>" . $formatos['src'] . "/" . $formatos['srcmask'] . "</address>\n";
			}
			if($formatos['srctype'] === "pppoe")
			{
				$contenido .= "\t\t\t\t\t<network>pppoe</network>\n";
			}
			if($formatos['srctype'] === "l2tp")
			{
				$contenido .= "\t\t\t\t\t<network>l2tp</network>\n";
			}
			if($formatos['srctype'] === "wan")
			{
				$contenido .= "\t\t\t\t\t<network>wan</network>\n";
			}
			if($formatos['srctype'] === "wanip")
			{
				$contenido .= "\t\t\t\t\t<network>wanip</network>\n";
			}
			if($formatos['srctype'] === "lan")
			{
				$contenido .= "\t\t\t\t\t<network>lan</network>\n";
			}
			if($formatos['srctype'] === "lanip")
			{
				$contenido .= "\t\t\t\t\t<network>lanip</network>\n";
			}
			# Campo invert match #
			if($formatos['srcnot'] === "no")
			{
				# No se coloca nada #
			}
			else
			{
				$contenido .= "\t\t\t\t\t<not></not>\n";
			}
			$contenido .= "\t\t\t\t</source>\n";
		    $contenido .= "\t\t\t\t<protocol>" . $formatos['proto'] . "</protocol>\n";
		    $contenido .= "\t\t\t\t<target>" . $formatos['localip'] . "</target>\n";
		    $contenido .= "\t\t\t\t<interface>" . $formatos['interface'] . "</interface>\n";
		    $contenido .= "\t\t\t</rule>\n";
		}
		// Se termina el nombre de la etiqueta 
		$contenido .= "</nat>";
		// Se crea o actualiza el archivo 
		$archivo = fopen('conf.xml', 'w');
		// Se abre el archivo y se ingresa la informacion almacenada en la variable 
		fwrite($archivo, $contenido);
		// Se cierra el archivo 
		fclose($archivo); 
		# Mover el archivo a la carpeta #
		$archivoConfig = 'conf.xml';
		$destinoConfig = "Groups/$id/config.xml";
	   	if (!copy($archivoConfig, $destinoConfig)) 
	   	{
		    echo "Error al copiar $archivoConfig...\n";
		}
		unlink("conf.xml");
		$estatus="The configuration has been saved";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("listGroupNat");
	}
}
