<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// Se importa el componente request de Symfony esto permite hacer peticiones
use Symfony\Component\HttpFoundation\Request;
// se importa el componete de session de Symfony esto permite declarar sessiones
use Symfony\Component\HttpFoundation\Session\Session;
// Se importa la entidad Aclgroups de la carpeta Entidad
use PrincipalBundle\Entity\AliasesName;
use PrincipalBundle\Entity\AliasesDescription;
// Se importa el formulario AliasesType de la carpeta Form
use PrincipalBundle\Form\AliasesNameType;
use Doctrine\Common\Collections\ArrayCollection;

class AliasesController extends Controller
{
	// Se declara la variable session 
	private $session;

	// Se realiza el constructor 
	public function __construct()
	{
		$this->session = new Session();
	}

	public function listGroupAliasesAction()
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
				return $this->render("@Principal/aliases/listGroup.html.twig", array("grupo"=>$listaGrupo));
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
				return $this->render("@Principal/aliases/listGroup.html.twig", array("grupo"=>$listaGrupo));
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
				return $this->render("@Principal/aliases/listGroup.html.twig", array("grupo"=>$listaGrupo));
	        }
	    }
		return $this->redirectToRoute("dashboard");
	}

	public function registerAliasesAction(Request $request, $id)
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
			$query = "INSERT INTO aliases(name, description, status, ip, descriptionhost, namegroup) 
						VALUES ('$name','$description','$status','$res1','$res2', '$id')";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listAliases");
		}
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render("@Principal/aliases/registerAliases.html.twig");
	}

	public function editAliasesAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$querySelect = "SELECT * FROM aliases WHERE id = '$id'";
		$stmtSelect = $db->prepare($querySelect);
		$paramsSelect =array();
		$stmtSelect->execute($paramsSelect);
		$listaGrupo=$stmtSelect->fetchAll();
		foreach ($listaGrupo as $value) 
		{
			$array1= explode(' ',$value['ip']);
			$array2 = explode(" ||",$value['descriptionhost']);
		}
		if(isset($_POST['enviar']))
		{
			$name = $_POST['name'];
			$description = $_POST['description'];
			$status = $_POST['status'];
			$value1 = $_POST['input'];
			$value2 = $_POST['input2'];
			$res1 = implode(" ",$value1);
			$res2 = implode(" ||",$value2);

			$query = "UPDATE aliases SET name = '$name', description = '$description', status = '$status', ip = '$res1', descriptionhost = '$res2' WHERE id = '$id'";
			$stmt = $db->prepare($query);
			$stmt->execute(array());
			return $this->redirectToRoute("listAliases");
		}
		return $this->render("@Principal/aliases/editAliases.html.twig", array("value"=>$array1,"value2"=>$array2,"value3"=>$listaGrupo));
	}

	public function listAliasesAction()
	{
	    $em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$query = "SELECT * FROM aliases";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		$res=$stmt->fetchAll();
		return $this->render("@Principal/aliases/listAliases.html.twig", array("ress"=>$res, "ress"=>$res));
	}
}