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
				$query = "SELECT * FROM nat WHERE namegroup = '$id' ORDER BY position_order";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$acl=$stmt->fetchAll();
				//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$queryOne = "SELECT * FROM natone WHERE namegroup = '$id' ORDER BY position_order";
				$stmtOne = $db->prepare($queryOne);
				$paramsOne =array();
				$stmtOne->execute($paramsOne);
				$aclOne=$stmtOne->fetchAll();
				return $this->render("@Principal/nat/listNat.html.twig", array("acls"=>$acl,"aclsOne"=>$aclOne));
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
				return $this->render("@Principal/nat/listNat.html.twig", array("acls"=>$acl,"aclsOne"=>$aclOne));
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
				return $this->render("@Principal/nat/listNat.html.twig", array("acls"=>$acl,"aclsOne"=>$aclOne));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }

    public function registerNatSuperUserAction(Request $request,$id)
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

	public function registerNatAction()
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

	public function deleteNatAction($id)
	{
		// Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$query = "DELETE FROM nat WHERE id = '$id'";
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
		return $this->redirectToRoute("listGroupNat");
	}

	public function registerNatOneSuperUserAction(Request $request, $id)
	{
        if(isset($_POST['enviar']))
		{
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$disabled = $_POST['disabled'];
			$nobinat = $_POST['nobinat'];
			$interface = $_POST['interface'];
			$external = $_POST['external'];
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$descr = $_POST['descr'];
			$natreflection = $_POST['natreflection'];
			$query = "INSERT INTO natone(disabled, nobinat, interface, external, srcnot, srctype, src, srcmask, dstnot, dsttype, dst, dstmask, descr, natreflection, namegroup) VALUES ('$disabled','$nobinat','$interface', '$external', '$srcnot', '$srctype', '$src', '$srcmask', '$dstnot', '$dsttype', '$dst', '$dstmask', '$descr', '$natreflection', '$id')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupNat");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/nat/registerNatOne.html.twig");
	}

	public function registerNatOneAction()
	{
        if(isset($_POST['enviar']))
		{
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$disabled = $_POST['disabled'];
			$nobinat = $_POST['nobinat'];
			$interface = $_POST['interface'];
			$external = $_POST['external'];
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$descr = $_POST['descr'];
			$natreflection = $_POST['natreflection'];
			$query = "INSERT INTO natone(disabled, nobinat, interface, external, srcnot, srctype, src, srcmask, dstnot, dsttype, dst, dstmask, descr, natreflection, namegroup) VALUES ('$disabled','$nobinat','$interface', '$external', '$srcnot', '$srctype', '$src', '$srcmask', '$dstnot', '$dsttype', '$dst', '$dstmask', '$descr', '$natreflection', '$id')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupNat");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/nat/registerNatOne.html.twig");
	}

	public function editNatOneAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$querySelect = "SELECT * FROM natone WHERE id = '$id'";
		$stmtSelect = $db->prepare($querySelect);
		$paramsSelect =array();
		$stmtSelect->execute($paramsSelect);
		$natone=$stmtSelect->fetchAll();
		if(isset($_POST['enviar']))
		{
			$disabled = $_POST['disabled'];
			$nobinat = $_POST['nobinat'];
			$interface = $_POST['interface'];
			$external = $_POST['external'];
			$srcnot = $_POST['srcnot'];
			$srctype = $_POST['srctype'];
			$src = $_POST['src'];
			$srcmask = $_POST['srcmask'];
			$dstnot = $_POST['dstnot'];
			$dsttype = $_POST['dsttype'];
			$dst = $_POST['dst'];
			$dstmask = $_POST['dstmask'];
			$descr = $_POST['descr'];

			$query = "UPDATE natone SET disabled = '$disabled', nobinat = '$nobinat', interface = '$interface', external = '$external', srcnot = '$srcnot', 
				srctype = '$srctype', src = '$src', srcmask = '$srcmask', dstnot = '$dstnot', dsttype = '$dsttype', dst = '$dst',
				dstmask = '$dstmask', descr = '$descr' WHERE id = '$id'";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listGroupNat");
		}
		return $this->render("@Principal/nat/editNatOne.html.twig", array("natones"=>$natone));
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
		exit();
	}

	public function refreshOneAction()
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$position = $_POST['position'];
		$i=1;
		foreach($position as $k=>$v){
		    $sql = "Update natone SET position_order=".$i." WHERE id=".$v;
		    $stmt = $db->prepare($sql);
			$stmt->execute(array());
			$i++;
		}
		exit();
	}

	public function deleteNatOneAction($id)
	{
		// Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$query = "DELETE FROM natone WHERE id = '$id'";
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
		return $this->redirectToRoute("listGroupNat");
	}

	public function createXMLNatAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();
    	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
		$query = "SELECT * FROM nat WHERE namegroup = '$id' ORDER BY position_order";
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
			if($formatos['disabled'] === "yes")
			{
				$contenido .= "\t\t\t\t<disabled></disabled>\n";
			}

			if($formatos['nordr'] === "yes")
			{
				$contenido .= "\t\t\t\t<nordr></nordr>\n";
			}

			$contenido .= "\t\t\t\t<source>\n";
				# Campo Source type #
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
					$contenido .= "\t\t\t\t\t<network>" . $formatos['src'] . "/". $formatos['srcmask'] . "</network>\n";
				}

				if($formatos['srctype'] === "pppoe")
				{
					$contenido .= "\t\t\t\t\t<network>pppoe</network>\n";
				}

				if($formatos['srctype'] === "l2tp")
				{
					$contenido .= "\t\t\t\t\t<network>pppoe</network>\n";
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

				# Campo Source Invert match. #
				if($formatos['srcnot'] === "yes")
				{
					$contenido .= "\t\t\t\t\t<not></not>\n";
				}
				# Campo Source port range cuando los dos campos Custom son iguales. #
				if($formatos['proto'] === "tcp" or $formatos['proto'] === "udp" or $formatos['proto'] === "tcp/udp" )
				{
					if($formatos['srcbeginport'] === "")
					{
						if($formatos['dstbeginport_cust'] === $formatos['dstendport_cust'])
						{
							$contenido .= "\t\t\t\t\t<port>" . $formatos['dstbeginport_cust'] . "</port>\n";
						}
						else
						{
							$contenido .= "\t\t\t\t\t<port>" . $formatos['dstbeginport_cust'] . "-" . $formatos['dstendport_cust'] . "</port>\n";
						}
					}

					if($formatos['srcbeginport'] === "5999")
					{
						$contenido .= "\t\t\t\t\t<port>5999</port>\n";
					}

					if($formatos['srcbeginport'] === "53")
					{
						$contenido .= "\t\t\t\t\t<port>53</port>\n";
					}

					if($formatos['srcbeginport'] === "21")
					{
						$contenido .= "\t\t\t\t\t<port>21</port>\n";
					}

					if($formatos['srcbeginport'] === "3000")
					{
						$contenido .= "\t\t\t\t\t<port>3000</port>\n";
					}

					if($formatos['srcbeginport'] === "80")
					{
						$contenido .= "\t\t\t\t\t<port>80</port>\n";
					}

					if($formatos['srcbeginport'] === "443")
					{
						$contenido .= "\t\t\t\t\t<port>443</port>\n";
					}

					if($formatos['srcbeginport'] === "5190")
					{
						$contenido .= "\t\t\t\t\t<port>5190</port>\n";
					}

					if($formatos['srcbeginport'] === "113")
					{
						$contenido .= "\t\t\t\t\t<port>113</port>\n";
					}

					if($formatos['srcbeginport'] === "993")
					{
						$contenido .= "\t\t\t\t\t<port>993</port>\n";
					}

					if($formatos['srcbeginport'] === "4500")
					{
						$contenido .= "\t\t\t\t\t<port>4500</port>\n";
					}

					if($formatos['srcbeginport'] === "500")
					{
						$contenido .= "\t\t\t\t\t<port>500</port>\n";
					}

					if($formatos['srcbeginport'] === "1701")
					{
						$contenido .= "\t\t\t\t\t<port>1701</port>\n";
					}

					if($formatos['srcbeginport'] === "389")
					{
						$contenido .= "\t\t\t\t\t<port>389</port>\n";
					}

					if($formatos['srcbeginport'] === "1755")
					{
						$contenido .= "\t\t\t\t\t<port>1755</port>\n";
					}

					if($formatos['srcbeginport'] === "7000")
					{
						$contenido .= "\t\t\t\t\t<port>7000</port>\n";
					}

					if($formatos['srcbeginport'] === "445")
					{
						$contenido .= "\t\t\t\t\t<port>445</port>\n";
					}

					if($formatos['srcbeginport'] === "3389")
					{
						$contenido .= "\t\t\t\t\t<port>3389</port>\n";
					}

					if($formatos['srcbeginport'] === "1512")
					{
						$contenido .= "\t\t\t\t\t<port>1512</port>\n";
					}

					if($formatos['srcbeginport'] === "1863")
					{
						$contenido .= "\t\t\t\t\t<port>1863</port>\n";
					}

					if($formatos['srcbeginport'] === "119")
					{
						$contenido .= "\t\t\t\t\t<port>119</port>\n";
					}

					if($formatos['srcbeginport'] === "123")
					{
						$contenido .= "\t\t\t\t\t<port>123</port>\n";
					}

					if($formatos['srcbeginport'] === "138")
					{
						$contenido .= "\t\t\t\t\t<port>138</port>\n";
					}

					if($formatos['srcbeginport'] === "137")
					{
						$contenido .= "\t\t\t\t\t<port>137</port>\n";
					}

					if($formatos['srcbeginport'] === "139")
					{
						$contenido .= "\t\t\t\t\t<port>139</port>\n";
					}

					if($formatos['srcbeginport'] === "1194")
					{
						$contenido .= "\t\t\t\t\t<port>1194</port>\n";
					}

					if($formatos['srcbeginport'] === "110")
					{
						$contenido .= "\t\t\t\t\t<port>110</port>\n";
					}

					if($formatos['srcbeginport'] === "995")
					{
						$contenido .= "\t\t\t\t\t<port>995</port>\n";
					}

					if($formatos['srcbeginport'] === "1723")
					{
						$contenido .= "\t\t\t\t\t<port>1723</port>\n";
					}

					if($formatos['srcbeginport'] === "1812")
					{
						$contenido .= "\t\t\t\t\t<port>1812</port>\n";
					}

					if($formatos['srcbeginport'] === "1813")
					{
						$contenido .= "\t\t\t\t\t<port>1813</port>\n";
					}

					if($formatos['srcbeginport'] === "5004")
					{
						$contenido .= "\t\t\t\t\t<port>5004</port>\n";
					}

					if($formatos['srcbeginport'] === "5060")
					{
						$contenido .= "\t\t\t\t\t<port>5060</port>\n";
					}

					if($formatos['srcbeginport'] === "25")
					{
						$contenido .= "\t\t\t\t\t<port>25</port>\n";
					}

					if($formatos['srcbeginport'] === "465")
					{
						$contenido .= "\t\t\t\t\t<port>465</port>\n";
					}

					if($formatos['srcbeginport'] === "161")
					{
						$contenido .= "\t\t\t\t\t<port>161</port>\n";
					}

					if($formatos['srcbeginport'] === "162")
					{
						$contenido .= "\t\t\t\t\t<port>162</port>\n";
					}

					if($formatos['srcbeginport'] === "22")
					{
						$contenido .= "\t\t\t\t\t<port>22</port>\n";
					}

					if($formatos['srcbeginport'] === "3478")
					{
						$contenido .= "\t\t\t\t\t<port>3278</port>\n";
					}

					if($formatos['srcbeginport'] === "587")
					{
						$contenido .= "\t\t\t\t\t<port>587</port>\n";
					}

					if($formatos['srcbeginport'] === "3544")
					{
						$contenido .= "\t\t\t\t\t<port>3544</port>\n";
					}

					if($formatos['srcbeginport'] === "23")
					{
						$contenido .= "\t\t\t\t\t<port>23</port>\n";
					}

					if($formatos['srcbeginport'] === "69")
					{
						$contenido .= "\t\t\t\t\t<port>69</port>\n";
					}

					if($formatos['srcbeginport'] === "5900")
					{
						$contenido .= "\t\t\t\t\t<port>5900</port>\n";
					}
				}

			$contenido .= "\t\t\t\t</source>\n";

			$contenido .= "\t\t\t\t<destination>\n";
				# Campo destination type #
				if($formatos['dsttype'] === "any")
				{
					$contenido .= "\t\t\t\t\t<any></any>\n";
				}

				if($formatos['dsttype'] === "single")
				{
					$contenido .= "\t\t\t\t\t<address>" . $formatos['dst'] . "</address>\n";
				}

				if($formatos['dsttype'] === "network")
				{
					$contenido .= "\t\t\t\t\t<network>" . $formatos['dst'] . "/". $formatos['dstmask'] . "</network>\n";
				}

				if($formatos['dsttype'] === "pppoe")
				{
					$contenido .= "\t\t\t\t\t<network>pppoe</network>\n";
				}

				if($formatos['dsttype'] === "l2tp")
				{
					$contenido .= "\t\t\t\t\t<network>pppoe</network>\n";
				}

				if($formatos['dsttype'] === "wan")
				{
					$contenido .= "\t\t\t\t\t<network>wan</network>\n";
				}

				if($formatos['dsttype'] === "wanip")
				{
					$contenido .= "\t\t\t\t\t<network>wanip</network>\n";
				}

				if($formatos['dsttype'] === "lan")
				{
					$contenido .= "\t\t\t\t\t<network>lan</network>\n";
				}

				if($formatos['dsttype'] === "lanip")
				{
					$contenido .= "\t\t\t\t\t<network>lanip</network>\n";
				}
				# Campo destination Invert match. #
				if($formatos['dstnot'] === "yes")
				{
					$contenido .= "\t\t\t\t\t<not></not>\n";
				}
				# Campo destination port range cuando los dos campos Custom son iguales. #
				if($formatos['proto'] === "tcp" or $formatos['proto'] === "udp" or $formatos['proto'] === "tcp/udp" )
				{
					if($formatos['dstendport'] === "")
					{
						if($formatos['dstbeginport_cust2'] === $formatos['dstendport_cust2'])
						{
							$contenido .= "\t\t\t\t\t<port>" . $formatos['dstbeginport_cust2'] . "</port>\n";
						}
						else
						{
							$contenido .= "\t\t\t\t\t<port>" . $formatos['dstbeginport_cust2'] . "-" . $formatos['dstendport_cust2'] . "</port>\n";
						}
					}

					if($formatos['dstendport'] === "5999")
					{
						$contenido .= "\t\t\t\t\t<port>5999</port>\n";
					}

					if($formatos['dstendport'] === "53")
					{
						$contenido .= "\t\t\t\t\t<port>53</port>\n";
					}

					if($formatos['dstendport'] === "21")
					{
						$contenido .= "\t\t\t\t\t<port>21</port>\n";
					}

					if($formatos['dstendport'] === "3000")
					{
						$contenido .= "\t\t\t\t\t<port>3000</port>\n";
					}

					if($formatos['dstendport'] === "80")
					{
						$contenido .= "\t\t\t\t\t<port>80</port>\n";
					}

					if($formatos['dstendport'] === "443")
					{
						$contenido .= "\t\t\t\t\t<port>443</port>\n";
					}

					if($formatos['dstendport'] === "5190")
					{
						$contenido .= "\t\t\t\t\t<port>5190</port>\n";
					}

					if($formatos['dstendport'] === "113")
					{
						$contenido .= "\t\t\t\t\t<port>113</port>\n";
					}

					if($formatos['dstendport'] === "993")
					{
						$contenido .= "\t\t\t\t\t<port>993</port>\n";
					}

					if($formatos['dstendport'] === "4500")
					{
						$contenido .= "\t\t\t\t\t<port>4500</port>\n";
					}

					if($formatos['dstendport'] === "500")
					{
						$contenido .= "\t\t\t\t\t<port>500</port>\n";
					}

					if($formatos['dstendport'] === "1701")
					{
						$contenido .= "\t\t\t\t\t<port>1701</port>\n";
					}

					if($formatos['dstendport'] === "389")
					{
						$contenido .= "\t\t\t\t\t<port>389</port>\n";
					}

					if($formatos['dstendport'] === "1755")
					{
						$contenido .= "\t\t\t\t\t<port>1755</port>\n";
					}

					if($formatos['dstendport'] === "7000")
					{
						$contenido .= "\t\t\t\t\t<port>7000</port>\n";
					}

					if($formatos['dstendport'] === "445")
					{
						$contenido .= "\t\t\t\t\t<port>445</port>\n";
					}

					if($formatos['dstendport'] === "3389")
					{
						$contenido .= "\t\t\t\t\t<port>3389</port>\n";
					}

					if($formatos['dstendport'] === "1512")
					{
						$contenido .= "\t\t\t\t\t<port>1512</port>\n";
					}

					if($formatos['dstendport'] === "1863")
					{
						$contenido .= "\t\t\t\t\t<port>1863</port>\n";
					}

					if($formatos['dstendport'] === "119")
					{
						$contenido .= "\t\t\t\t\t<port>119</port>\n";
					}

					if($formatos['dstendport'] === "123")
					{
						$contenido .= "\t\t\t\t\t<port>123</port>\n";
					}

					if($formatos['dstendport'] === "138")
					{
						$contenido .= "\t\t\t\t\t<port>138</port>\n";
					}

					if($formatos['dstendport'] === "137")
					{
						$contenido .= "\t\t\t\t\t<port>137</port>\n";
					}

					if($formatos['dstendport'] === "139")
					{
						$contenido .= "\t\t\t\t\t<port>139</port>\n";
					}

					if($formatos['dstendport'] === "1194")
					{
						$contenido .= "\t\t\t\t\t<port>1194</port>\n";
					}

					if($formatos['dstendport'] === "110")
					{
						$contenido .= "\t\t\t\t\t<port>110</port>\n";
					}

					if($formatos['dstendport'] === "995")
					{
						$contenido .= "\t\t\t\t\t<port>995</port>\n";
					}

					if($formatos['dstendport'] === "1723")
					{
						$contenido .= "\t\t\t\t\t<port>1723</port>\n";
					}

					if($formatos['dstendport'] === "1812")
					{
						$contenido .= "\t\t\t\t\t<port>1812</port>\n";
					}

					if($formatos['dstendport'] === "1813")
					{
						$contenido .= "\t\t\t\t\t<port>1813</port>\n";
					}

					if($formatos['dstendport'] === "5004")
					{
						$contenido .= "\t\t\t\t\t<port>5004</port>\n";
					}

					if($formatos['dstendport'] === "5060")
					{
						$contenido .= "\t\t\t\t\t<port>5060</port>\n";
					}

					if($formatos['dstendport'] === "25")
					{
						$contenido .= "\t\t\t\t\t<port>25</port>\n";
					}

					if($formatos['dstendport'] === "465")
					{
						$contenido .= "\t\t\t\t\t<port>465</port>\n";
					}

					if($formatos['dstendport'] === "161")
					{
						$contenido .= "\t\t\t\t\t<port>161</port>\n";
					}

					if($formatos['dstendport'] === "162")
					{
						$contenido .= "\t\t\t\t\t<port>162</port>\n";
					}

					if($formatos['dstendport'] === "22")
					{
						$contenido .= "\t\t\t\t\t<port>22</port>\n";
					}

					if($formatos['dstendport'] === "3478")
					{
						$contenido .= "\t\t\t\t\t<port>3278</port>\n";
					}

					if($formatos['dstendport'] === "587")
					{
						$contenido .= "\t\t\t\t\t<port>587</port>\n";
					}

					if($formatos['dstendport'] === "3544")
					{
						$contenido .= "\t\t\t\t\t<port>3544</port>\n";
					}

					if($formatos['dstendport'] === "23")
					{
						$contenido .= "\t\t\t\t\t<port>23</port>\n";
					}

					if($formatos['dstendport'] === "69")
					{
						$contenido .= "\t\t\t\t\t<port>69</port>\n";
					}

					if($formatos['dstendport'] === "5900")
					{
						$contenido .= "\t\t\t\t\t<port>5900</port>\n";
					}
				}
			$contenido .= "\t\t\t\t</destination>\n";

		    $contenido .= "\t\t\t\t<protocol>" . $formatos['proto'] . "</protocol>\n";
		    # No RDR (NOT) esta activo #
		    if($formatos['nordr'] === "no")
			{
				# Redirect target IP #
			    $contenido .= "\t\t\t\t<target>" . $formatos['localip'] . "</target>\n";
			    # Redirect target port #
			    if($formatos['localbeginport'] === "")
				{
					$contenido .= "\t\t\t\t<local-port>" . $formatos['localbeginport_cust'] . "</local-port>\n";
				}

				if($formatos['localbeginport'] === "5999")
				{
					$contenido .= "\t\t\t\t<local-port>5999</local-port>\n";
				}

				if($formatos['localbeginport'] === "53")
				{
					$contenido .= "\t\t\t\t<local-port>53</local-port>\n";
				}

				if($formatos['localbeginport'] === "21")
				{
					$contenido .= "\t\t\t\t<local-port>21</local-port>\n";
				}

				if($formatos['localbeginport'] === "3000")
				{
					$contenido .= "\t\t\t\t<local-port>3000</local-port>\n";
				}

				if($formatos['localbeginport'] === "80")
				{
					$contenido .= "\t\t\t\t<local-port>80</local-port>\n";
				}

				if($formatos['localbeginport'] === "443")
				{
					$contenido .= "\t\t\t\t<local-port>443</local-port>\n";
				}

				if($formatos['localbeginport'] === "5190")
				{
					$contenido .= "\t\t\t\t<local-port>5190</local-port>\n";
				}

				if($formatos['localbeginport'] === "113")
				{
					$contenido .= "\t\t\t\t<local-port>113</local-port>\n";
				}

				if($formatos['localbeginport'] === "143")
				{
					$contenido .= "\t\t\t\t<local-port>143</local-port>\n";
				}

				if($formatos['localbeginport'] === "993")
				{
					$contenido .= "\t\t\t\t<local-port>993</local-port>\n";
				}

				if($formatos['localbeginport'] === "4500")
				{
					$contenido .= "\t\t\t\t<local-port>4500</local-port>\n";
				}

				if($formatos['localbeginport'] === "500")
				{
					$contenido .= "\t\t\t\t<local-port>500</local-port>\n";
				}

				if($formatos['localbeginport'] === "1701")
				{
					$contenido .= "\t\t\t\t<local-port>1701</local-port>\n";
				}

				if($formatos['localbeginport'] === "389")
				{
					$contenido .= "\t\t\t\t<local-port>389</local-port>\n";
				}

				if($formatos['localbeginport'] === "1755")
				{
					$contenido .= "\t\t\t\t<local-port>1755</local-port>\n";
				}

				if($formatos['localbeginport'] === "7000")
				{
					$contenido .= "\t\t\t\t<local-port>7000</local-port>\n";
				}

				if($formatos['localbeginport'] === "445")
				{
					$contenido .= "\t\t\t\t<local-port>445</local-port>\n";
				}

				if($formatos['localbeginport'] === "3389")
				{
					$contenido .= "\t\t\t\t<local-port>3389</local-port>\n";
				}

				if($formatos['localbeginport'] === "1512")
				{
					$contenido .= "\t\t\t\t<local-port>1512</local-port>\n";
				}

				if($formatos['localbeginport'] === "1863")
				{
					$contenido .= "\t\t\t\t<local-port>1863</local-port>\n";
				}

				if($formatos['localbeginport'] === "119")
				{
					$contenido .= "\t\t\t\t<local-port>119</local-port>\n";
				}

				if($formatos['localbeginport'] === "123")
				{
					$contenido .= "\t\t\t\t<local-port>123</local-port>\n";
				}

				if($formatos['localbeginport'] === "138")
				{
					$contenido .= "\t\t\t\t<local-port>138</local-port>\n";
				}

				if($formatos['localbeginport'] === "137")
				{
					$contenido .= "\t\t\t\t<local-port>137</local-port>\n";
				}

				if($formatos['localbeginport'] === "139")
				{
					$contenido .= "\t\t\t\t<local-port>139</local-port>\n";
				}

				if($formatos['localbeginport'] === "1194")
				{
					$contenido .= "\t\t\t\t<local-port>1194</local-port>\n";
				}

				if($formatos['localbeginport'] === "110")
				{
					$contenido .= "\t\t\t\t<local-port>110</local-port>\n";
				}

				if($formatos['localbeginport'] === "995")
				{
					$contenido .= "\t\t\t\t<local-port>995</local-port>\n";
				}

				if($formatos['localbeginport'] === "1723")
				{
					$contenido .= "\t\t\t\t<local-port>1723</local-port>\n";
				}

				if($formatos['localbeginport'] === "1812")
				{
					$contenido .= "\t\t\t\t<local-port>1812</local-port>\n";
				}

				if($formatos['localbeginport'] === "1813")
				{
					$contenido .= "\t\t\t\t<local-port>1813</local-port>\n";
				}

				if($formatos['localbeginport'] === "5004")
				{
					$contenido .= "\t\t\t\t<local-port>5004</local-port>\n";
				}

				if($formatos['localbeginport'] === "5060")
				{
					$contenido .= "\t\t\t\t<local-port>5060</local-port>\n";
				}

				if($formatos['localbeginport'] === "25")
				{
					$contenido .= "\t\t\t\t<local-port>25</local-port>\n";
				}

				if($formatos['localbeginport'] === "465")
				{
					$contenido .= "\t\t\t\t<local-port>465</local-port>\n";
				}

				if($formatos['localbeginport'] === "161")
				{
					$contenido .= "\t\t\t\t<local-port>161</local-port>\n";
				}

				if($formatos['localbeginport'] === "162")
				{
					$contenido .= "\t\t\t\t<local-port>162</local-port>\n";
				}

				if($formatos['localbeginport'] === "22")
				{
					$contenido .= "\t\t\t\t<local-port>22</local-port>\n";
				}

				if($formatos['localbeginport'] === "3478")
				{
					$contenido .= "\t\t\t\t<local-port>3478</local-port>\n";
				}

				if($formatos['localbeginport'] === "587")
				{
					$contenido .= "\t\t\t\t<local-port>587</local-port>\n";
				}

				if($formatos['localbeginport'] === "3544")
				{
					$contenido .= "\t\t\t\t<local-port>3544</local-port>\n";
				}

				if($formatos['localbeginport'] === "23")
				{
					$contenido .= "\t\t\t\t<local-port>23</local-port>\n";
				}

				if($formatos['localbeginport'] === "69")
				{
					$contenido .= "\t\t\t\t<local-port>69</local-port>\n";
				}

				if($formatos['localbeginport'] === "5900")
				{
					$contenido .= "\t\t\t\t<local-port>5900</local-port>\n";
				}
			}
			# Interface #
		    $contenido .= "\t\t\t\t<interface>" . $formatos['interface'] . "</interface>\n";
		    # Description #
		    $contenido .= "\t\t\t\t<descr>" . $formatos['descr'] . "</descr>\n";
		    # Filter rule association #
		    if($formatos['associated_rule_id'] === "")
			{
		    	$contenido .= "\t\t\t\t<associated-rule-id></associated-rule-id>\n";
		    }

		    if($formatos['associated_rule_id'] === "")
			{
		    	$contenido .= "\t\t\t\t<associated-rule-id>pass</associated-rule-id>\n";
		    }

		    # No XMLRPC Sync #
		    if($formatos['nosync'] === "yes")
			{
				$contenido .= "\t\t\t\t<nosync></nosync>\n";
			}
			# NAT reflection #
			if($formatos['natreflection'] === "enable")
			{
				$contenido .= "\t\t\t\t\t<natreflection>enable</natreflection>\n";
			}

			if($formatos['natreflection'] === "purenat")
			{
				$contenido .= "\t\t\t\t\t<natreflection>purenat</natreflection>\n";
			}

			if($formatos['natreflection'] === "disable")
			{
				$contenido .= "\t\t\t\t\t<natreflection>disable</natreflection>\n";
			}

		    $contenido .= "\t\t\t</rule>\n";
		}
		
		$queryOne = "SELECT * FROM natone WHERE namegroup = '$id' ORDER BY position_order";
		$stmtOne = $db->prepare($queryOne);
		$paramsOne =array();
		$stmtOne->execute($paramsOne);
		// Se alamacena la consulta en una variable 
		$formatoOne=$stmtOne->fetchAll();
		// Se realiza un ciclo para llenar las demas etiquetas del archivo xml 
		foreach ($formatoOne as $formatos) 
		{
			$contenido .= "\t\t\t<onetoone>\n";
			# No BINAT (NOT) #
			if($formatos['nobinat'] === "yes")
			{
				$contenido .= "\t\t\t\t<nobinat></nobinat>\n";
			}
			# Disabled #
			if($formatos['disabled'] === "yes")
			{
				$contenido .= "\t\t\t\t<disabled></disabled>\n";
			}
			# External subnet IP #
			$contenido .= "\t\t\t\t<external>" . $formatos['external'] . "</external>\n";
			# Description #
			$contenido .= "\t\t\t\t<descr>" . $formatos['descr'] . "</descr>\n";
			# Interface #
			$contenido .= "\t\t\t\t<interface>" . $formatos['interface'] . "</interface>\n";
			# Internal IP #
			$contenido .= "\t\t\t\t<source>\n";
				# Type Address/mask #
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
					$contenido .= "\t\t\t\t<network>lanip</network>\n";
				}
				# Not Invert the sense of the match #
				if($formatos['srcnot'] === "yes")
				{
					$contenido .= "\t\t\t\t\t<not></not>\n";
				}
			$contenido .= "\t\t\t\t</source>\n";
			# Destination #
			$contenido .= "\t\t\t\t<source>\n";
				# Type Address/mask #
				if($formatos['dsttype'] === "any")
				{
					$contenido .= "\t\t\t\t\t<any></any>\n";
				}
				if($formatos['dsttype'] === "single")
				{
					$contenido .= "\t\t\t\t\t<address>" . $formatos['dst'] . "</address>\n";
				}
				if($formatos['dsttype'] === "network")
				{
					$contenido .= "\t\t\t\t\t<address>" . $formatos['dst'] . "/" . $formatos['dstmask'] . "</address>\n";
				}
				if($formatos['dsttype'] === "pppoe")
				{
					$contenido .= "\t\t\t\t\t<network>pppoe</network>\n";
				}
				if($formatos['dsttype'] === "l2tp")
				{
					$contenido .= "\t\t\t\t\t<network>l2tp</network>\n";
				}
				if($formatos['dsttype'] === "wan")
				{
					$contenido .= "\t\t\t\t\t<network>wan</network>\n";
				}
				if($formatos['dsttype'] === "wanip")
				{
					$contenido .= "\t\t\t\t\t<network>wanip</network>\n";
				}
				if($formatos['dsttype'] === "lan")
				{
					$contenido .= "\t\t\t\t\t<network>lan</network>\n";
				}
				if($formatos['dsttype'] === "lanip")
				{
					$contenido .= "\t\t\t\t<network>lanip</network>\n";
				}
				# Not Invert the sense of the match #
				if($formatos['dstnot'] === "yes")
				{
					$contenido .= "\t\t\t\t\t<not></not>\n";
				}
			$contenido .= "\t\t\t\t</source>\n";
			$contenido .= "\t\t\t</onetoone>\n";
		}

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
