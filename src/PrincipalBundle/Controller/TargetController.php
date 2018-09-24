<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// Se importa la entidad Targetcategories de la carpeta Entidad
use PrincipalBundle\Entity\Target;
// Se importa el formulario TargetType de la carpeta Form
use PrincipalBundle\Form\TargetType;
// Se importa el componente request de Symfony esto permite hacer peticiones
use Symfony\Component\HttpFoundation\Request;
// se importa el componete de session de Symfony esto permite declarar sessiones
use Symfony\Component\HttpFoundation\Session\Session;

class TargetController extends Controller
{
	// Se declara la variable session 
	private $session;

	// Se realiza el constructor 
	public function __construct()
	{
		$this->session = new Session();
	}

	public function listGroupTargetAction()
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
				return $this->render("@Principal/target/listGroup.html.twig", array("grupo"=>$listaGrupo));
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
				return $this->render("@Principal/target/listGroup.html.twig", array("grupo"=>$listaGrupo));
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
				return $this->render("@Principal/target/listGroup.html.twig", array("grupo"=>$listaGrupo));
	        }
	    }
		return $this->redirectToRoute("dashboard");
	}

	// Funcion para listar las Target categories del sistema 
	public function listTargetAction()
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
				$query = "SELECT * FROM target";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$target=$stmt->fetchAll();
				return $this->render("@Principal/target/listaTargetcategories.html.twig", array("targets"=>$target));
	        }
	        if($role == "ROLE_ADMIN")
	        {
	        	$grupo=$u->getNameGroup();
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$query = "SELECT * FROM target where namegroup ='$grupo'";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$target=$stmt->fetchAll();
				return $this->render("@Principal/target/listaTargetcategories.html.twig", array("targets"=>$target));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$grupo=$u->getNameGroup();
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$query = "SELECT * FROM target where namegroup ='$grupo'";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$target=$stmt->fetchAll();
				return $this->render("@Principal/target/listaTargetcategories.html.twig", array("targets"=>$target));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }

	// Funcion para el registro de un nuevo target categorie
    public function registerTargetAction(Request $request, $id)
    {
    	// Se declara una nueva categoria
        $video = new Target(); 
        // Se manda a llamar el formulario
        $form=$this->createForm(TargetType::class,$video);
        // Se obtiene la informacion del formulario 
		$form->handleRequest($request);
		// Se valida si el boton de enviar fue presionado y si es valido 
		if($form->isSubmitted() && $form->isValid())
		{
			// Se manada llamr al asistende de base de datos
			$em = $this->getDoctrine()->getEntityManager();
			// Se realiza una consulta previa para saber si el nombre que se esta intentando registrar ya exite
			$query = $em->createQuery("SELECT u FROM PrincipalBundle:Target u WHERE u.name = :name")->setParameter("name",$form->get("name")->getData());
			// Se guarda en una variable el resultado de la consulta
			$resultado = $query->getResult();
			// Si el nombre no existe en la base de datos se procede a guardar la demas informacion del formulario
			if(count($resultado) == 0)
			{
				// Se guarda la informacion en la base de datos 
				$video->setNameGroup($id);
				$em->persist($video);
				$flush=$em->flush();
				// Se validad si se inserto los datos correctamente 
				if($flush == null)
				{
					// Se notifica al actor que su registro fue correcto
					$estatus="Target Categorie successfully registered";
					$this->session->getFlashBag()->add("estatus",$estatus);
					// Se redirecciona al listado
					return $this->redirectToRoute("listTarget");
				}
				// De lo contrario se notifica al actor
				else
				{
					$estatus="Problems with the server try later.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
			}
			// De lo contrario regresa al formulario para corregir el error
			else
			{
				$estatus="The name of the target categories you are trying to register already exists try again.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		// Se renderiza el formulario para que el actor lo llene los campos solicitados
		return $this->render("@Principal/target/registroTargetcategories.html.twig",array("form"=>$form->createView()));
    }

    // Funcion para editar a un target categories  
	public function editTargetAction(Request $request,$id)
	{
		// Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		// Se obtiene el repositorio de Targetcategories solo del dato del id el cual fue solicitado en el formulario 
		$formato = $em->getRepository("PrincipalBundle:Target")->find($id);
		// Se manda a llamar el formulario
		$form = $this->createForm(TargetType::class,$formato);
		// Se obtiene la informacion del formulario 
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			// Se manda a llamar al asistente de base de datos doctrine 
			$em = $this->getDoctrine()->getEntityManager();
			//Se obtiene los datos los cuales se van a editar
			$formato->setName($form->get("name")->getData());
			$formato->setDomainList($form->get("domainList")->getData());
			$formato->setUrlList($form->get("urlList")->getData());
			$formato->setRegularExpression($form->get("regularExpression")->getData());
			$formato->setRedirectMode($form->get("redirectMode")->getData());
			$formato->setRedirect($form->get("redirect")->getData());
			$formato->setDescription($form->get("description")->getData());
			$formato->setNameGroup($form->get("nameGroup")->getData());
			$formato->setLog($form->get("log")->getData());// Se guarda la informacion en la base de datos 
			$em->persist($formato);
			$flush=$em->flush();
			// Se validad si se inserto los datos correctamente  
			if($flush == null)
			{
				// Se notifica al actor que su actualizacion fue correcto
				$estatus="Target categorie successfully updated";
				$this->session->getFlashBag()->add("estatus",$estatus);
				// Se redirecciona al listado
				return $this->redirectToRoute("listTarget");
			}
			// De lo contrario se notifica al actor
			else
			{
				$estatus="Problems with the server try later.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		// Se renderiza el formulario para que el actor lo llene los campos solicitados
		return $this->render("@Principal/target/editarTargetcategories.html.twig",array("form"=>$form->createView()));
	}

	// Funcion para eliminar un target categori 
	public function deleteTargetAction($id)
	{
		// Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		// Se obtiene el repositorio de Targetcategories solo del dato del id el cual fue solicitado en el formulario
		$formato = $em->getRepository("PrincipalBundle:Target")->find($id);
		// Se elinana los datos solicitados en la base de datos 
		$em->remove($formato);
		$flush=$em->flush();
		// Se validad si la accion de borrar se cumplio
		if($flush == null)
		{
			// Se notifica al actor que la eliminacion fue correcta 
			$estatus="Registry successfully deleted";
			$this->session->getFlashBag()->add("estatus",$estatus);
		}
		// De lo contrario se notifica al actor
		else
		{
			$estatus="Problems with the server try later.";
			$this->session->getFlashBag()->add("estatus",$estatus);
		}
		// Se redirecciona al listado
		return $this->redirectToRoute("listTarget");
	}
}
