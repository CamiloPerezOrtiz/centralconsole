<?php

namespace PrincipalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
# Se importa el componente request de Symfony esto permite hacer peticiones
use Symfony\Component\HttpFoundation\Request;
# se importa el componete de session de Symfony esto permite declarar sessiones
use Symfony\Component\HttpFoundation\Session\Session;
# Componente para ejecutar procesos, se utiliza en este caso para ejecuatr codigo python 
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AliasesController extends Controller
{
	# Se declara la variable session 
	private $session;

	# Se realiza el constructor 
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
	        	return $this->redirectToRoute("listAliases");
	        }
	        if($role == "ROLE_USER")
	        {
	        	return $this->redirectToRoute("listAliases");
	        }
	    }
		return $this->redirectToRoute("dashboard");
	}

	public function listAliasesSuperUserAction($id)
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
				$querySelect = "SELECT * FROM aliases WHERE namegroup = '$id'";
				$stmtSelect = $db->prepare($querySelect);
				$paramsSelect =array();
				$stmtSelect->execute($paramsSelect);
				$listaGrupo=$stmtSelect->fetchAll();
				return $this->render("@Principal/aliases/listAliases.html.twig", array("grupo"=>$listaGrupo));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
	}

	public function listAliasesAction()
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
	        if($role == "ROLE_ADMIN")
	        {	        	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
				$querySelect = "SELECT * FROM aliases WHERE namegroup = '$grupo'";
				$stmtSelect = $db->prepare($querySelect);
				$paramsSelect =array();
				$stmtSelect->execute($paramsSelect);
				$listaGrupo=$stmtSelect->fetchAll();
				return $this->render("@Principal/aliases/listAliases.html.twig", array("grupo"=>$listaGrupo));
	        }
	        if($role == "ROLE_USER")
	        {
	        	$querySelect = "SELECT * FROM aliases WHERE namegroup = '$grupo'";
				$stmtSelect = $db->prepare($querySelect);
				$paramsSelect =array();
				$stmtSelect->execute($paramsSelect);
				$listaGrupo=$stmtSelect->fetchAll();
				return $this->render("@Principal/aliases/listAliases.html.twig", array("grupo"=>$listaGrupo));
	        }
	    }
		// Regresa un arreglo con la informacion obtendia de la base de datos
	    return $this->redirectToRoute("dashboard");
	}

	public function registerAliasesSuperUserAction(Request $request, $id)
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
			$query = ("SELECT name FROM aliases WHERE name = '$name' AND namegroup = '$id'");
			$stmt = $db->prepare($query);
			$params =array();
			$stmt->execute($params);
			$resultado=$stmt->fetchAll();
			if(count($resultado)==0)
			{
				$queryAliases = "INSERT INTO aliases(name, description, status, ip, descriptionhost, namegroup) 
						VALUES ('$name','$description','$status','$res1','$res2', '$id')";
				$stmt = $db->prepare($queryAliases);
				$stmt->execute(array());
				return $this->redirectToRoute("listGroupAliases");
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
        return $this->render("@Principal/aliases/registerAliases.html.twig");
	}

	public function registerAliasesAction()
	{
        if(isset($_POST['enviar']))
		{
			$authenticationUtils = $this->get("security.authentication_utils");
			$error = $authenticationUtils->getLastAuthenticationError();
			$lastUsername = $authenticationUtils->getLastUsername();
			$u = $this->getUser();
			$grupo=$u->getNameGroup();
			$em = $this->getDoctrine()->getEntityManager();
			$db = $em->getConnection();
			$name = $_POST['name'];
			$description = $_POST['description'];
			$status = $_POST['status'];
			$value1 = $_POST['input'];
			$value2 = $_POST['input2'];
			$res1 = implode(" ",$value1);
			$res2 = implode(" ||",$value2);
			$query = ("SELECT name FROM aliases WHERE name = '$name' AND namegroup = '$grupo'");
			$stmt = $db->prepare($query);
			$params =array();
			$stmt->execute($params);
			$resultado=$stmt->fetchAll();
			if(count($resultado)==0)
			{
				$queryAliases = "INSERT INTO aliases(name, description, status, ip, descriptionhost, namegroup) 
						VALUES ('$name','$description','$status','$res1','$res2', '$grupo')";
				$stmt = $db->prepare($queryAliases);
				$stmt->execute(array());
				return $this->redirectToRoute("listGroupAliases");
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
			return $this->redirectToRoute("listGroupAliases");
		}
		return $this->render("@Principal/aliases/editAliases.html.twig", array("value"=>$array1,"value2"=>$array2,"value3"=>$listaGrupo));
	}

	public function deleteAliasesAction($id)
	{
		// Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();
		$query = "DELETE FROM aliases WHERE id = '$id'";
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
		return $this->redirectToRoute("listGroupAliases");
	}

	// Funcion para crear XML de target categories
	public function createXMLAliasesAction($id)
	{
		//Variables declaradas para mandar a llamar al asistente de base de datos doctrine
		$em = $this->getDoctrine()->getEntityManager();
        $db = $em->getConnection();
    	//Query para seleccionar los datos de id, ip, cliente de la tabla txtip solamente del cliente que fue seleccionado
		$query = "SELECT * FROM aliases WHERE namegroup = '$id'";
		$stmt = $db->prepare($query);
		$params =array();
		$stmt->execute($params);
		// Se alamacena la consulta en una variable 
		$formato=$stmt->fetchAll();
		// Se crea un nuevo documento XML con la version 
	    $contenido = "<?xml version='1.0'?>\n";
	    // Se crear el nombre de la etiqueta
		$contenido .= "\t<aliases>\n";
		// Se realiza un ciclo para llenar las demas etiquetas del archivo xml 
		foreach ($formato as $formatos) 
		{
			$contenido .= "\t\t<alias>\n";
		    $contenido .= "\t\t\t<name>" . $formatos['name'] . "</name>\n";
		    $contenido .= "\t\t\t<type>" . $formatos['status'] . "</type>\n";
		    $contenido .= "\t\t\t<address>" . $formatos['ip'] . "</address>\n";
		    $contenido .= "\t\t\t<descr>" . $formatos['description'] . "</descr>\n";
		    $contenido .= "\t\t\t<detail>" . $formatos['descriptionhost'] . "</detail>\n";
		    $contenido .= "\t\t</alias>\n";
		}
		// Se termina el nombre de la etiqueta 
		$contenido .= "\t</aliases>";
		// Se crea o actualiza el archivo 
		$archivo = fopen("conf.xml", 'w');
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
		return $this->redirectToRoute("listGroupAliases");
	}

	// funcion para correr el script aplicar cambios en target categories
	public function aplicateXMLAliasesAction($id)
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
	        	$archivo = fopen("change_to_do.txt", 'w');
				// Se abre el archivo y se ingresa la informacion almacenada en la variable 
				fwrite($archivo, "aliases.py");
				fwrite ($archivo, "\n". PHP_EOL);
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
				fwrite($archivo, "aliases.py");
				fwrite ($archivo, "\n". PHP_EOL);
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