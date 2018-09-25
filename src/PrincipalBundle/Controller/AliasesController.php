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
        $user = new AliasesName();

        $form = $this->createForm(AliasesNameType::class, $user);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
        	$em = $this->getDoctrine()->getManager();
        	$query = $em->createQuery("SELECT u FROM PrincipalBundle:AliasesName u WHERE u.name = :name")->setParameter("name",$form->get("name")->getData());
			// Se guarda en una variable el resultado de la consulta
			$resultado = $query->getResult();
			if(count($resultado) == 0)
			{
				$user->setNameGroup($id);
            	$em->persist($user);
            	$flush=$em->flush();
            	// Se validad si se inserto los datos correctamente
				if($flush == null)
				{
					// Se notifica al actor que su registro fue correcto
					$estatus="Aliases successfully registered";
					$this->session->getFlashBag()->add("estatus",$estatus);
					// Se redirecciona al listado
					return $this->redirectToRoute("listAliases");
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
				$estatus="The name of the aliases group you are trying to register already exists try again.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
        }
        // Se renderiza el formulario para que el actor lo llene los campos solicitados
        return $this->render('@Principal/aliases/registerAliases.html.twig', 
        [
            'form' => $form->createView()    
        ]);
	}

	public function editAliasesAction(Request $request, $id)
	{
		$em = $this->getDoctrine()->getManager();
        /**
         * @var $user AliasesName
         */
        $user = $em->getRepository('PrincipalBundle:AliasesName')->findOneBy(['id' => $id]);
        $orignalExp = new ArrayCollection();
        foreach ($user->getExp() as $exp) {
            $orignalExp->add($exp);
        }
        $form = $this->createForm(AliasesNameType::class, $user);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            foreach ($orignalExp as $exp) 
            {
                if ($user->getExp()->contains($exp) === false) 
                {
                    $em->remove($exp);
                }
            }
            $em->persist($user);
            $flush=$em->flush();
            if($flush == null)
			{
				// Se notifica al actor que su registro fue correcto
				$estatus="Aliases successfully registered";
				$this->session->getFlashBag()->add("estatus",$estatus);
				// Se redirecciona al listado
				return $this->redirectToRoute("listAliases");
			}
			// De lo contrario se notifica al actor
			else
			{
				$estatus="Problems with the server try later.";
				$this->session->getFlashBag()->add("estatus",$estatus);
			}
        }
        // replace this example code with whatever you need
        return $this->render('@Principal/aliases/editAliases.html.twig', [
            'form' => $form->createView()    
        ]);
	}

	public function listAliasesAction()
	{
	    $em = $this->getDoctrine()->getEntityManager();
		$db = $em->getConnection();

		//Query para borrar la tabla txtip de la base de datos
		$querySelect = "select an.name, ad.ipport
	from aliases_name as an, aliases_description as ad
	where an.id = ad.aliasname_id
	and an.name in(select name from aliases_name)";
		$stmtSelect = $db->prepare($querySelect);
		$paramsSelect =array();
		$stmtSelect->execute($paramsSelect);
		$listaGrupo=$stmtSelect->fetchAll();
		foreach ($listaGrupo as $key) 
		{
			echo $key['ipport'] . " ";
			echo "<br>";
		}
		die();
		//return $this->render("@Principal/aliases/listAliases.html.twig", array("acls2"=>$acl2));
	}
}
