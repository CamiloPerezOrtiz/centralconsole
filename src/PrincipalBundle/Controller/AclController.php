<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
// Se importa el componente request de Symfony esto permite hacer peticiones
use Symfony\Component\HttpFoundation\Request;
// Se importa la entidad Aclgroups de la carpeta Entidad
use PrincipalBundle\Entity\Acl;
// Se importa el formulario AclgroupsType de la carpeta Form
use PrincipalBundle\Form\AclType;
// se importa el componete de session de Symfony esto permite declarar sessiones
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;


class AclController extends Controller
{
	// Se declara la variable session 
	private $session;

	// Se realiza el constructor 
	public function __construct()
	{
		$this->session = new Session();
	}

	public function listGroupAclAction()
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
				return $this->render("@Principal/acl/listGroup.html.twig", array("grupo"=>$listaGrupo));
	        }
	        if($role == "ROLE_ADMIN")
	        {
	        	return $this->redirectToRoute("listAcl");
	        }
	        if($role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("listAcl");
	        }
	    }
		return $this->redirectToRoute("dashboard");
	}

	// Funcion para listar las acl groups del sistema 
    public function listAclSuperUserAction($id)
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
				return $this->render("@Principal/acl/listAcl.html.twig", array("acls"=>$acl));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }

    // Funcion para listar las acl groups del sistema 
    public function listAclAction()
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
				return $this->render("@Principal/acl/listAcl.html.twig", array("acls"=>$acl));
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
				return $this->render("@Principal/acl/listAcl.html.twig", array("acls"=>$acl));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }


	// Funcion para el registro de un nuevo acl group 
	public function registerAclSuperUserAction(Request $request, $id)
    {
    	// Se declara una nuevo acl group
        $video = new Acl();
        // Se manda a llamar el formulario
        $form=$this->createForm(AclType::class,$video);
        // Se obtiene la informacion del formulario 
		$form->handleRequest($request);
		// Se valida si el boton de enviar fue presionado y si es valido 
		if($form->isSubmitted() && $form->isValid())
		{
			$em = $this->getDoctrine()->getEntityManager();
	        $RAW_QUERY = 'SELECT name FROM acl where name = :name and namegroup = :status ;';
	        $statement = $em->getConnection()->prepare($RAW_QUERY);
	        $statement->bindValue('status', $id);
	        $statement->bindValue('name', $form->get("name")->getData());
	        $statement->execute();
	        $resultado = $statement->fetchAll();
			if(count($resultado)==0)
			{
				$em = $this->getDoctrine()->getEntityManager();
		        $RAW_QUERY2 = 'SELECT client FROM acl where client = :client and namegroup = :status ;';
		        $statement2 = $em->getConnection()->prepare($RAW_QUERY2);
		        $statement2->bindValue('status', $id);
		        $statement2->bindValue('client', $form->get("client")->getData());
		        $statement2->execute();
		        $resultado2 = $statement2->fetchAll();
				if(count($resultado2)==0)
				{
					// Se manada llamr al asistende de base de datos
					$em = $this->getDoctrine()->getEntityManager();
					// Se guarda la informacion en la base de datos 
					$video->setNameGroup($id);
					$video->setTime('');
					$video->setTargetRule('all [ all]');
					$em->persist($video);
					$flush=$em->flush();
					// Se validad si se inserto los datos correctamente
					if($flush == null)
					{
						// Se notifica al actor que su registro fue correcto
						$estatus="Acl group successfully registered";
						$this->session->getFlashBag()->add("estatus",$estatus);
						// Se redirecciona al listado
						return $this->redirectToRoute("listGroupAcl");
					}
					// De lo contrario se notifica al actor
					else
					{
						$estatus="Problems with the server try later.";
						$this->session->getFlashBag()->add("estatus",$estatus);
					}		
				}
				else
				{
					echo '<script> 
	                		alert("The client(Ip) you are trying to register already exists try again.");
	                		window.history.go(-1);
	             		 </script>
	             		';
	             	exit;
				}	
			}
			else
			{
				echo '<script> 
                		alert("The name you are trying to register already exists try again.");
                		window.history.go(-1);
             		 </script>
             		';
             	exit;
			}
		}
		// Se renderiza el formulario para que el actor lo llene los campos solicitados
		return $this->render("@Principal/acl/registerAcl.html.twig",array("form"=>$form->createView()));
    }

    // Funcion para el registro de un nuevo acl group 
	public function registerAclAction(Request $request)
    {
    	// Se declara una nuevo acl group
        $video = new Acl();
        // Se manda a llamar el formulario
        $form=$this->createForm(AclType::class,$video);
        // Se obtiene la informacion del formulario 
		$form->handleRequest($request);
		// Se valida si el boton de enviar fue presionado y si es valido 
		if($form->isSubmitted() && $form->isValid())
		{
			$u = $this->getUser();
			$grupo=$u->getNameGroup();

			$em = $this->getDoctrine()->getEntityManager();
	        $RAW_QUERY = 'SELECT name FROM acl where name = :name and namegroup = :status ;';
	        $statement = $em->getConnection()->prepare($RAW_QUERY);
	        $statement->bindValue('status', $grupo);
	        $statement->bindValue('name', $form->get("name")->getData());
	        $statement->execute();
	        $resultado = $statement->fetchAll();

			if(count($resultado)==0)
			{		
				$em = $this->getDoctrine()->getEntityManager();
		        $RAW_QUERY2 = 'SELECT client FROM acl where client = :client and namegroup = :status ;';
		        $statement2 = $em->getConnection()->prepare($RAW_QUERY2);
		        $statement2->bindValue('status', $grupo);
		        $statement2->bindValue('client', $form->get("client")->getData());
		        $statement2->execute();
		        $resultado2 = $statement2->fetchAll();
				if(count($resultado2)==0)
				{			
					// Se manada llamr al asistende de base de datos
					$em = $this->getDoctrine()->getEntityManager();
					// Se guarda la informacion en la base de datos 
					$video->setNameGroup($id);
					$video->setTime('');
					$video->setTargetRule('all [ all]');
					$em->persist($video);
					$flush=$em->flush();
					// Se validad si se inserto los datos correctamente
					if($flush == null)
					{
						// Se notifica al actor que su registro fue correcto
						$estatus="Acl group successfully registered";
						$this->session->getFlashBag()->add("estatus",$estatus);
						// Se redirecciona al listado
						return $this->redirectToRoute("listGroupAcl");
					}
					// De lo contrario se notifica al actor
					else
					{
						$estatus="Problems with the server try later.";
						$this->session->getFlashBag()->add("estatus",$estatus);
					}
				}
				else
				{
					echo '<script> 
	                		alert("The client(Ip) you are trying to register already exists try again.");
	                		window.history.go(-1);
	             		 </script>
	             		';
	             	exit;
				}	
			}
			else
			{
				echo '<script> 
                		alert("The name you are trying to register already exists try again.");
                		window.history.go(-1);
             		 </script>
             		';
             	exit;
			}
		}
		// Se renderiza el formulario para que el actor lo llene los campos solicitados
		return $this->render("@Principal/acl/registerAcl.html.twig",array("form"=>$form->createView()));
    }

    // Funcion para editar a un acl groups
    public function editAclAction(Request $request,$id)
	{
		// Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		// Se obtiene el repositorio de Aclgroups solo del dato del id el cual fue solicitado en el formulario 
		$formato = $em->getRepository("PrincipalBundle:Acl")->find($id);
		// Se manda a llamar el formulario
		$form = $this->createForm(AclType::class,$formato);
		// Se obtiene la informacion del formulario 
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid())
		{
			// Se manda a llamar al asistente de base de datos doctrine 
			$em = $this->getDoctrine()->getEntityManager();
			//Se obtiene los datos los cuales se van a editar
			$formato->setDisabled($form->get("disabled")->getData());
			$formato->setName($form->get("name")->getData());
			$formato->setClient($form->get("client")->getData());
			$formato->setAllowIp($form->get("allowIp")->getData());
			$formato->setRedirectMode($form->get("redirectMode")->getData());
			$formato->setRedirect($form->get("redirect")->getData());
			$formato->setSafeSearch($form->get("safeSearch")->getData());
			$formato->setRewrite($form->get("rewrite")->getData());
			$formato->setRewriteTime($form->get("rewriteTime")->getData());
			$formato->setDescription($form->get("description")->getData());
			$formato->setLog($form->get("log")->getData());
			// Se guarda la informacion en la base de datos 
			$em->persist($formato);
			$flush=$em->flush();
			// Se validad si se inserto los datos correctamente  
			if($flush == null)
			{
				// Se notifica al actor que su actualizacion fue correcto
				$estatus="Alc group successfully updated";
				$this->session->getFlashBag()->add("estatus",$estatus);
				// Se redirecciona al listado
				return $this->redirectToRoute("listGroupAcl");
			}
			// De lo contrario se notifica al actor
			else
			{
				$estatus="Problems with the server try later.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		// Se renderiza el formulario para que el actor lo llene los campos solicitados
		return $this->render("@Principal/acl/editAcl.html.twig",array("form"=>$form->createView()));
	}

	// Funcion para eliminar un target categori 
	public function deleteAclAction($id)
	{
		// Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		// Se obtiene el repositorio de Aclgroup solo del dato del id el cual fue solicitado en el formulario
		$formato = $em->getRepository("PrincipalBundle:Acl")->find($id);
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
		return $this->redirectToRoute("listGroupAcl");
	}

	// Funcion para crear XML de target categories
	public function createXMLAclAction($id)
	{
		$em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();
    	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
		$query = "SELECT ac.disabled, ac.name, ac.client, ac.time, ac.targetrule,
						ac.allowip, ac.redirectmode, ac.redirect, ac.safesearch,
						ac.rewrite, ac.rewritetime, ac.description, l.description AS log
					FROM acl AS ac, log AS l 
					WHERE ac.acl_id = l.id
					AND namegroup = '$id'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		// Se alamacena la consulta en una variable 
		$formato=$stmt->fetchAll();
		// Se crea un nuevo documento XML con la version 
	    $contenido = "<?xml version='1.0'?>\n";
	    // Se crear el nombre de la etiqueta
		$contenido .= "<squidguardacl>\n";
		// Se realiza un ciclo para llenar las demas etiquetas del archivo xml 
		foreach ($formato as $formatos) 
		{
			$contenido .= "\t\t\t<config>\n";
		    $contenido .= "\t\t\t\t<disabled>" . $formatos['disabled'] . "</disabled>\n";
		    $contenido .= "\t\t\t\t<name>" . $formatos['name'] . "</name>\n";
		    $contenido .= "\t\t\t\t<source>" . $formatos['client'] . "</source>\n";
		    $contenido .= "\t\t\t\t<time></time>\n";
		    $contenido .= "\t\t\t\t<dest>" . $formatos['targetrule'] . "</dest>\n";
		    $contenido .= "\t\t\t\t<notallowingip></notallowingip>\n";
		    $contenido .= "\t\t\t\t<redirect_mode>" . $formatos['redirectmode'] . "</redirect_mode>\n";
		    $contenido .= "\t\t\t\t<redirect>" . $formatos['redirect'] . "</redirect>\n";
		    $contenido .= "\t\t\t\t<safesearch>" . $formatos['safesearch'] . "</safesearch>\n";
		    $contenido .= "\t\t\t\t<rewrite>" . $formatos['rewrite'] . "</rewrite>\n";
		    $contenido .= "\t\t\t\t<overrewrite>" . $formatos['rewritetime'] . "</overrewrite>\n";
		    $contenido .= "\t\t\t\t<description>" . $formatos['description'] . "</description>\n";
		    $contenido .= "\t\t\t\t<enablelog>" . $formatos['log'] . "</enablelog>\n";
		    $contenido .= "\t\t\t</config>\n";
		}
		// Se termina el nombre de la etiqueta 
		$contenido .= "</squidguardacl>";
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
		return $this->redirectToRoute("listGroupAcl");
	}

	// funcion para correr el script aplicar cambios en target categories
	public function aplicateXMLAclAction($id)
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
	        	return $this->redirectToRoute('listGroup');
	        }
	        if($role == "ROLE_ADMIN")
	        {	        	
				return $this->redirectToRoute('listGroupIp');
			}
		}
		return $this->redirectToRoute('dashboard');
	}
}
