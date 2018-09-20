<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// Se importa el componente request de Symfony esto permite hacer peticiones
use Symfony\Component\HttpFoundation\Request;
// Se importa la entidad Usuario de la carpeta Entidad
use PrincipalBundle\Entity\Users;
// Se importa el formulario UsuarioType de la carpeta Form
use PrincipalBundle\Form\UsersType;
// se importa el componete de session de Symfony esto permite declarar sessiones
use Symfony\Component\HttpFoundation\Session\Session;

class UsersController extends Controller
{
	// Se declara la variable session
	private $session;

	// Se realiza el constructor 
	public function __construct()
	{
		$this->session = new Session();
	}

	// Funcion para agregar un nuevo administrador
	public function registerUserAction(Request $request, $id)
	{
		// Se declara un nuevo usuario
		$User =  new Users();
		// Se manda a llamar el formulario
		$form = $this->createForm(UsersType::class,$User);
		$form->handleRequest($request);
		// Se valida si el boton de enviar fue presionado y valido
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();//Se manada llamr al asistende de base de datos
			//Se realiza una consulta previa para saber si el correo que se esta intentando registrar ya exite
			$query = $em->createQuery("SELECT u FROM PrincipalBundle:Users u WHERE u.email = :email")->setParameter("email",$form->get("email")->getData());
			$resultado = $query->getResult();
			//Se realiza una consulta para saber si el nombre ya esta registrado
			$query2 = $em->createQuery("SELECT u FROM PrincipalBundle:Users u WHERE u.name = :name")->setParameter("name",$form->get("name")->getData());
			$resultado2 = $query2->getResult();
			//si el correo no exite se manda a insertar el usuario con el rol de administrador de lo contrario regresa al formulario
			if(count($resultado)==0 && count($resultado2)==0)
			{
				$User->setGroup($id);
				$factory = $this->get("security.encoder_factory");
				$encoder = $factory->getEncoder($User);
				$p = $encoder->encodePassword($form->get("password")->getData(),$User->getSalt());
				$User->setPassword($p);
				$em->persist($User);
				$flush=$em->flush();
				if($flush == null)
				{
					$estatus="User successfully registered";
					$this->session->getFlashBag()->add("estatus",$estatus);
					return $this->redirectToRoute("listUser");
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
		return $this->render("@Principal/user/registerUser.html.twig",array("form"=>$form->createView()));
	}

	//Funcion para mostrar la lista de usuerios
	public function listUserAction()
	{
		//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$administrador=$em->getRepository("PrincipalBundle:Administrator")->findAll();
	    return $this->render('@Principal/user/listUser.html.twig', array("usuarios"=>$usuario));
	}
}
