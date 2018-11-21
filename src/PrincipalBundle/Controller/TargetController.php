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
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

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
	        	return $this->redirectToRoute("listTarget");
	        }
	        if($role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("listTarget");
	        }
	    }
		return $this->redirectToRoute("dashboard");
	}
	
	// Funcion para listar las Target categories del sistema 
	public function listTargetSuperUserAction($id)
    {
		$authenticationUtils = $this->get("security.authentication_utils");
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();
		$u = $this->getUser();
		$grupo=$u->getNameGroup();
		if($u != null)
		{
			//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
			$em = $this->getDoctrine()->getEntityManager();
	        $db = $em->getConnection();

	        $role=$u->getRole();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$query = "SELECT * FROM target WHERE namegroup = '$id'";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$target=$stmt->fetchAll();
				return $this->render("@Principal/target/listTarget.html.twig", array("targets"=>$target));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
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
	        if($role == "ROLE_ADMIN")
	        {
	        	$grupo=$u->getNameGroup();
	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$query = "SELECT * FROM target where namegroup ='$grupo'";
				$stmt = $db->prepare($query);
				$params =array();
				$stmt->execute($params);
				$target=$stmt->fetchAll();
				return $this->render("@Principal/target/listTarget.html.twig", array("targets"=>$target));
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
				return $this->render("@Principal/target/listTarget.html.twig", array("targets"=>$target));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
    }

	// Funcion para el registro de un nuevo target categorie
    public function registerTargetSuperUserAction(Request $request, $id)
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
			$em = $this->getDoctrine()->getEntityManager();
	        $RAW_QUERY = 'SELECT name FROM target where name = :name and namegroup = :status ;';
	        $statement = $em->getConnection()->prepare($RAW_QUERY);
	        $statement->bindValue('status', $id);
	        $statement->bindValue('name', $form->get("name")->getData());
	        $statement->execute();
	        $resultado = $statement->fetchAll();

			if(count($resultado)==0)
			{					
				// Se manada llamr al asistende de base de datos
				$em = $this->getDoctrine()->getEntityManager();
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
					return $this->redirectToRoute("listGroupTarget");
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
                		alert("The name you are trying to register already exists try again.");
                		window.history.go(-1);
             		 </script>
             		';
             	exit;
			}
		}
		// Se renderiza el formulario para que el actor lo llene los campos solicitados
		return $this->render("@Principal/target/registerTarget.html.twig",array("form"=>$form->createView()));
    }

    // Funcion para el registro de un nuevo target categorie
    public function registerTargetAction(Request $request)
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
			//$authenticationUtils = $this->get("security.authentication_utils");
			//$error = $authenticationUtils->getLastAuthenticationError();
			//$lastUsername = $authenticationUtils->getLastUsername();
			$u = $this->getUser();
			$grupo=$u->getNameGroup();

			$em = $this->getDoctrine()->getEntityManager();
	        $RAW_QUERY = 'SELECT name FROM target where name = :name and namegroup = :status ;';
	        $statement = $em->getConnection()->prepare($RAW_QUERY);
	        $statement->bindValue('status', $grupo);
	        $statement->bindValue('name', $form->get("name")->getData());
	        $statement->execute();
	        $resultado = $statement->fetchAll();

			if(count($resultado)==0)
			{					
				// Se manada llamr al asistende de base de datos
				$em = $this->getDoctrine()->getEntityManager();
				// Se guarda la informacion en la base de datos 
				$video->setNameGroup($grupo);
				$em->persist($video);
				$flush=$em->flush();
				// Se validad si se inserto los datos correctamente 
				if($flush == null)
				{
					// Se notifica al actor que su registro fue correcto
					$estatus="Target Categorie successfully registered";
					$this->session->getFlashBag()->add("estatus",$estatus);
					// Se redirecciona al listado
					return $this->redirectToRoute("listGroupTarget");
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
                		alert("The name you are trying to register already exists try again.");
                		window.history.go(-1);
             		 </script>
             		';
             	exit;
			}
		}
		// Se renderiza el formulario para que el actor lo llene los campos solicitados
		return $this->render("@Principal/target/registerTarget.html.twig",array("form"=>$form->createView()));
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
				return $this->redirectToRoute("listGroupTarget");
			}
			// De lo contrario se notifica al actor
			else
			{
				$estatus="Problems with the server try later.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
		}
		// Se renderiza el formulario para que el actor lo llene los campos solicitados
		return $this->render("@Principal/target/editTarget.html.twig",array("form"=>$form->createView()));
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
		return $this->redirectToRoute("listGroupTarget");
	}

	// Funcion para crear XML de target categories
	public function createXMLTargetAction($id)
	{
		//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();
    	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
		$query = "SELECT t.id, t.name, t.domainlist, t.urllist, t.regularexpression, t.redirectmode, t.redirect, t.description, l.description AS log
					FROM target AS t, log AS l
						WHERE t.log_id = l.id
						AND t.namegroup = '$id'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		// Se alamacena la consulta en una variable 
		$formato=$stmt->fetchAll();
		// Se crea un nuevo documento XML con la version 
	    $contenido = "<?xml version='1.0'?>\n";
	    // Se crear el nombre de la etiqueta
		$contenido .= "\t<squidguarddest>\n";
		// Se realiza un ciclo para llenar las demas etiquetas del archivo xml 
		foreach ($formato as $formatos) 
		{
			$contenido .= "\t\t\t<config>\n";
		    $contenido .= "\t\t\t\t<name>" . $formatos['name'] . "</name>\n";
		    $contenido .= "\t\t\t\t<domains>" . $formatos['domainlist'] . "</domains>\n";
		    $contenido .= "\t\t\t\t<urls>" . $formatos['urllist'] . "</urls>\n";
		    $contenido .= "\t\t\t\t<expressions>" . $formatos['regularexpression'] . "</expressions>\n";
		    $contenido .= "\t\t\t\t<redirect_mode>" . $formatos['redirectmode'] . "</redirect_mode>\n";
		    $contenido .= "\t\t\t\t<redirect>" . $formatos['redirect'] . "</redirect>\n";
		    $contenido .= "\t\t\t\t<description>" . $formatos['description'] . "</description>\n";
		    $contenido .= "\t\t\t\t<enablelog></enablelog>\n";
		    $contenido .= "\t\t\t</config>\n";
		}
		// Se termina el nombre de la etiqueta 
		$contenido .= "\t</squidguarddest>";
		// Se crea o actualiza el archivo 
		$archivo = fopen('conf.xml', 'w');
		// Se abre el archivo y se ingresa la informacion almacenada en la variable 
		fwrite($archivo, $contenido);
		// Se cierra el archivo 
		fclose($archivo); 
		# Mover el archivo a la carpeta #
		$archivoConfig = 'conf.xml';
		$destinoConfig = "Groups/$id/conf.xml";
	   	if (!copy($archivoConfig, $destinoConfig)) 
	   	{
		    echo "Error al copiar $archivoConfig...\n";
		}
		unlink("conf.xml");
		$estatus="The configuration has been saved";
		$this->session->getFlashBag()->add("estatus",$estatus);
		return $this->redirectToRoute("listGroupTarget");     
	}

	// funcion para correr el script aplicar cambios en target categories
	public function aplicateXMLTargetAction($id)
	{
		$u = $this->getUser();
		if($u != null)
		{
			//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
			$em = $this->getDoctrine()->getEntityManager();
	        $db = $em->getConnection();

	        $role=$u->getRole();
	        if($role == "ROLE_SUPERUSER")
	        {
	        	$archivo = fopen("change_to_do.txt", 'w');
				// Se abre el archivo y se ingresa la informacion almacenada en la variable 
				fwrite($archivo, "targetcategories.py");
				fwrite ($archivo, "\n");
				// Se cierra el archivo 
				fclose($archivo); 
				# Mover el archivo a la carpeta #
				$archivoConfig = 'change_to_do.txt';
				$destinoConfig = "centralizedConsole/change_to_do.txt";
			   	if (!copy($archivoConfig, $destinoConfig)) 
			   	{
				   echo "Error al copiar $archivoConfig...\n";
				}

				$archivoConfig = "Groups/$id/conf.xml";
				$destinoConfig = "centralizedConsole/conf.xml";
			   	if (!copy($archivoConfig, $destinoConfig)) 
			   	{
				    echo "Error al copiar $archivoConfig...\n";
				}
	        	return $this->redirectToRoute('listGroup');
	        }
	        if($role == "ROLE_ADMIN")
	        {	 
	        	$archivo = fopen("change_to_do.txt", 'w');
				// Se abre el archivo y se ingresa la informacion almacenada en la variable 
				fwrite($archivo, "targetcategories.py");
				fwrite ($archivo, "\n");
				// Se cierra el archivo 
				fclose($archivo); 
				# Mover el archivo a la carpeta #
				$archivoConfig = 'change_to_do.txt';
				$destinoConfig = "centralizedConsole/change_to_do.txt";
			   	if (!copy($archivoConfig, $destinoConfig)) 
			   	{
				   echo "Error al copiar $archivoConfig...\n";
				}

				$archivoConfig = "Groups/$id/conf.xml";
				$destinoConfig = "centralizedConsole/conf.xml";
			   	if (!copy($archivoConfig, $destinoConfig)) 
			   	{
				    echo "Error al copiar $archivoConfig...\n";
				}      	
				return $this->redirectToRoute('listGroupIp');
			}
		}
		return $this->redirectToRoute('dashboard');
	}
}
