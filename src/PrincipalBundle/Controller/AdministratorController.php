<?php

################################################################
# This Code Has Been Developed By Camilo Perez                 #
################################################################

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request; # Se importa el componente request de Symfony esto permite hacer peticiones #
use PrincipalBundle\Entity\Administrator; # Se importa la entidad de la carpeta Entity que contiene los datos necesarios #
use PrincipalBundle\Form\AdministratorType; # Se importa el formulario de la carpeta Form #
use Symfony\Component\HttpFoundation\Session\Session; # se importa el componete de session de Symfony esto permite declarar sessiones en el sistema #

class AdministratorController extends Controller
{
	#Se declara la variable session #
	private $session; 

	# Se realiza el constructor # 
	public function __construct()
	{
		$this->session = new Session();
	}

	# Funcion para mostrar el dashboard del administrador #
	public function dashboardAction()
	{
		return $this->render('@Principal/administrator/dashboard.html.twig');
	}

	# Funcion para iniciar sesion #
	public function loginAction()
	{
		$authenticationUtils = $this->get("security.authentication_utils");
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		$u = $this->getUser();
		if($u != null)
		{
			$role = $u->getRole();
			if($role == "ROLE_SUPERUSER" or  $role == "ROLE_ADMIN")
			{
				return $this->redirectToRoute("dashboard");
			}
			else
			{
				return $this->render('@Principal/Default/index.html.twig', array('error'=> $error,));
			}
		}
		else
		{
			return $this->render('@Principal/Default/index.html.twig', array('error'=> $error,));
		}
		return $this->render('@Principal/Default/index.html.twig', array('error'=> $error,));
	}

	# Funcion para agregar un el actor super usuario #
	public function registerAdministratorAction(Request $request)
	{
		# Se declara un nuevo usuario #
		$superUser =  new Administrator();
		# Se manda a llamar el formulario #
		$form = $this->createForm(AdministratorType::class,$superUser);
		$form->remove('role');
		$form->handleRequest($request);
		# Se valida si el boton de enviar fue presionado y valido #
		if($form->isSubmitted() && $form->isValid())
		{
			# Se manada llamr al asistende de base de datos #
			$em = $this->getDoctrine()->getEntityManager(); 
			# Se realiza una consulta previa para saber si el correo que se esta intentando registrar ya exite #
			$query = $em->createQuery("SELECT u FROM PrincipalBundle:Administrator u WHERE u.email = :email")->setParameter("email",$form->get("email")->getData());
			$resultado = $query->getResult();
			# Se realiza una consulta para saber si el nombre ya esta registrado #
			$query2 = $em->createQuery("SELECT u FROM PrincipalBundle:Administrator u WHERE u.name = :name")->setParameter("name",$form->get("name")->getData());
			$resultado2 = $query2->getResult();
			# Si el correo no exite se manda a insertar el usuario con el rol de administrador de lo contrario regresa al formulario #
			if(count($resultado)==0 && count($resultado2)==0)
			{
				$superUser->setRole("ROLE_SUPERUSER");
				$superUser->setNameGroup("Null");
				$factory = $this->get("security.encoder_factory");
				$encoder = $factory->getEncoder($superUser);
				$p = $encoder->encodePassword($form->get("password")->getData(),$superUser->getSalt());
				$superUser->setPassword($p);
				$em->persist($superUser);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="Successfully registration";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("listAdministrator");
				}
				else
				{
					$estatus="Problems with the server try later.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
			}
			else
			{
				$estatus="The name or email you are trying to register already exists try again.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		return $this->render("@Principal/administrator/registerAdministrator.html.twig",array("form"=>$form->createView()));
	}

	# Funcion para agregar un nuevo administrador o usuario #
	public function registerUserAdministratorAction(Request $request, $id)
	{
		# Se declara un nuevo usuario #
		$administrator =  new Administrator();
		# Se manda a llamar el formulario #
		$form = $this->createForm(AdministratorType::class,$administrator);
		$form->handleRequest($request);
		# Se valida si el boton de enviar fue presionado y valido #
		if($form->isSubmitted() && $form->isValid())
		{
			# Se manada llamr al asistende de base de datos #
			$em = $this->getDoctrine()->getEntityManager();
			# Se realiza una consulta previa para saber si el correo que se esta intentando registrar ya exite #
			$query = $em->createQuery("SELECT u FROM PrincipalBundle:Administrator u WHERE u.email = :email")->setParameter("email",$form->get("email")->getData());
			$resultado = $query->getResult();
			# Se realiza una consulta para saber si el nombre ya esta registrado #
			$query2 = $em->createQuery("SELECT u FROM PrincipalBundle:Administrator u WHERE u.name = :name")->setParameter("name",$form->get("name")->getData());
			$resultado2 = $query2->getResult();
			# Si el correo no exite se manda a insertar el usuario con el rol de administrador de lo contrario regresa al formulario #
			if(count($resultado)==0 && count($resultado2)==0)
			{
				$administrator->setNameGroup($id);
				$factory = $this->get("security.encoder_factory");
				$encoder = $factory->getEncoder($administrator);
				$p = $encoder->encodePassword($form->get("password")->getData(),$administrator->getSalt());
				$administrator->setPassword($p);
				$em->persist($administrator);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="Successfully registration";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("listAdministrator");
				}
				else
				{
					$estatus="Problems with the server try later.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
			}
			else
			{
				$estatus="The name or email you are trying to register already exists try again.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		return $this->render("@Principal/administrator/registerUserAdministrator.html.twig",array("form"=>$form->createView()));
	}

	# Funcion para mostrar la lista de administradores #
	public function listAdministratorAction()
	{
		$authenticationUtils = $this->get("security.authentication_utils");
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		$u = $this->getUser();
		if($u != null)
		{
			# Variables declaradas para mandar a llamar al asistente de base de datos doctrine #
			$em = $this->getDoctrine()->getEntityManager();
	        $db = $em->getConnection();

	        $role=$u->getRole();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	# Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado #
				$query = "SELECT * FROM administrator";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$administrador=$stmt->fetchAll();
				return $this->render('@Principal/administrator/listAdministrator.html.twig', array("administradores"=>$administrador));
	        }
	        if($role == "ROLE_ADMIN")
	        {
	        	$grupo=$u->getNameGroup();
	        	# Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado #
				$query = "SELECT * FROM administrator where role = 'ROLE_ADMIN' AND namegroup ='$grupo' UNION SELECT * FROM administrator where role = 'ROLE_USER' and namegroup ='$grupo'";
				# $query = "SELECT * FROM administrator where namegroup = '$grupo' and role = 'ROLE_ADMIN' OR role = 'ROLE_USER'"; #
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$administrador=$stmt->fetchAll();
				return $this->render('@Principal/administrator/listAdministrator.html.twig', array("administradores"=>$administrador));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$grupo=$u->getNameGroup();
	        	# Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado #
				$query = "SELECT * FROM administrator where role = 'ROLE_USER' AND namegroup ='$grupo'";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$administrador=$stmt->fetchAll();
				return $this->render('@Principal/administrator/listAdministrator.html.twig', array("administradores"=>$administrador));
	        }
	    }
		# Regresa un arreglo con la informacion obtendia de la base de datos #
	    return $this->redirectToRoute("dashboard");
	}

	# Funcion para editar al actor super usuario #
	public function editAdministratorUserAction(Request $request,$id)
	{
		# Variables declaradas para mandar a llamar al asistente de base de datos doctrine #
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:Administrator")->find($id);
		$form = $this->createForm(AdministratorType::class,$formato);
		$form->handleRequest($request);
		if($form->isSubmitted())
		{
			if($form->isValid())
			{
				# Se obtiene los datos los cuales se van a editar #
				$em = $this->getDoctrine()->getEntityManager();
				$formato->setName($form->get("name")->getData());
				$formato->setEmail($form->get("email")->getData());
				$formato->setRole($form->get("role")->getData());
				$formato->setPassword($form->get("password")->getData());
				# Encrypta la contraseña #
				$factory = $this->get("security.encoder_factory");
				$encoder = $factory->getEncoder($formato);
				$p = $encoder->encodePassword($form->get("password")->getData(),$formato->getSalt());
				$formato->setPassword($p);
			    # Termino de encryptar la contraseña #
				$em->persist($formato);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="Successfully updated registration";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("listAdministrator");
				}
				else
				{
					$estatus="Problems with the server try later.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
			}
		}
		return $this->render("@Principal/administrator/editAdministratorUser.html.twig",array("form"=>$form->createView()));
	}

	# funcion para ediatar al actor administrador o usuario #
	public function editAdministratorAction(Request $request,$id)
	{
		# Variables declaradas para mandar a llamar al asistente de base de datos doctrine #
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:Administrator")->find($id);
		$form = $this->createForm(AdministratorType::class,$formato);
		$form->handleRequest($request);
		if($form->isSubmitted())
		{
			if($form->isValid())
			{
				# Se obtiene los datos los cuales se van a editar #
				$em = $this->getDoctrine()->getEntityManager();
				$formato->setRole("ROLE_SUPERUSER");
				$formato->setName($form->get("name")->getData());
				$formato->setEmail($form->get("email")->getData());
				$formato->setPassword($form->get("password")->getData());
				# Encrypta la contraseña #
				$factory = $this->get("security.encoder_factory");
				$encoder = $factory->getEncoder($formato);
				$p = $encoder->encodePassword($form->get("password")->getData(),$formato->getSalt());
				$formato->setPassword($p);
				# Termino de encryptar la contraseña #
				$em->persist($formato);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="Successfully updated registration";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("listAdministrator");
				}
				else
				{
					$estatus="Problems with the server try later.";
					$this->session->getFlashBag()->add("estatus",$estatus);
				}
			}
		}
		return $this->render("@Principal/administrator/editAdministrator.html.twig",array("form"=>$form->createView()));
	}

	# Funcion para eliminar cualquier actor #
	public function deleteAdministratorAction($id)
	{
		# Variables declaradas para mandar a llamar al asistente de base de datos doctrine #
		$em = $this->getDoctrine()->getEntityManager();
		$formato = $em->getRepository("PrincipalBundle:Administrator")->find($id);
		$em->remove($formato);
		$flush=$em->flush();
		if($flush == null)
		{
			$estatus="Successfully delete registration";
		}
		else
		{
			$estatus="Problems with the server try later.";
		}
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("listAdministrator");
	}
}
