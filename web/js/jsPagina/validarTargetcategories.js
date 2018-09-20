function validarTargetcategories() 
{
	// Se recogen las variables del formulario las cuales son respresetadas por ID
	var principalbundle_targetcategories_name, 
		principalbundle_targetcategories_domainList, 
		principalbundle_targetcategories_urlList,
		principalbundle_targetcategories_regularExpression, 
		principalbundle_targetcategories_redirectMode, 
		principalbundle_targetcategories_redirect,
		principalbundle_targetcategories_description, 
		principalbundle_targetcategories_log;
	// Se declaran las variables 
	principalbundle_targetcategories_name = document.getElementById("principalbundle_targetcategories_name").value;
	principalbundle_targetcategories_domainList = document.getElementById("principalbundle_targetcategories_domainList").value;
	principalbundle_targetcategories_urlList = document.getElementById("principalbundle_targetcategories_urlList").value;
	principalbundle_targetcategories_regularExpression = document.getElementById("principalbundle_targetcategories_regularExpression").value;
	principalbundle_targetcategories_redirectMode = document.getElementById("principalbundle_targetcategories_redirectMode").value;
	principalbundle_targetcategories_redirect = document.getElementById("principalbundle_targetcategories_redirect").value;
	principalbundle_targetcategories_description = document.getElementById("principalbundle_targetcategories_description").value;
	principalbundle_targetcategories_log = document.getElementById("principalbundle_targetcategories_log").value;
	// Se realizan las expreciones regulares para validar los formularios 
	// Expresion  regular para validar que el nombre inicie con Mayuscula y que no lleve espacio 
	expresionName = /^[A-Z]\w{1,15}$/;
	// Expresion regular para evaluar el domain list
	expresionDomainList = /^\w/;
	// Expresion regular para evaluar que la ur lleve el simbolo / 
	expresionUrlList = /\w\//;
	// Expresion regular para evaluar que que la expresion regular comienze con el simbolo | y sea texto
	expresionRegularExpression = /^\|+[a-z]/;
	// Expresion regular para evaluar que que la expresion termine con |
	expresionRegularExpression2 = /\|$/;
	// Expresion regular para evaluar que el redireccionamiento sea una url  
	expresionRedirect = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
	// Se evalua que ningun campo este vacio 
	if(principalbundle_targetcategories_name === "" || principalbundle_targetcategories_domainList === "" ||
		principalbundle_targetcategories_urlList ==="" || principalbundle_targetcategories_regularExpression ==="" ||
		principalbundle_targetcategories_redirectMode ==="" || principalbundle_targetcategories_redirect ==="" ||
		principalbundle_targetcategories_description ==="" || principalbundle_targetcategories_log ==="")
	{
		swal({
  				icon: "error",
  				title: "All fields are required."
			});
		return false;;
	}
	// Se evalua que el campo nombre tenga al menos 4 letras
	else if(principalbundle_targetcategories_name.length<4)
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The name can not be less than 4 characters."
			});
		return false;
	}
	// Se evalua que el campo nombre no se mayor de 15 letras 
	else if(principalbundle_targetcategories_name.length>15)
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The name can not be longer than 15 characters."
			});
		return false;
	}
	// Se evalua la exprecion regular
	else if (!expresionName.test(principalbundle_targetcategories_name))
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The first character must be a letter in capital letter.\nThe name does not take spaces."
			});
		return false;
	}
	// Se evalua la exprecion regular
	else if (!expresionDomainList.test(principalbundle_targetcategories_domainList))
	{
		swal({
  				icon: "error",
  				title: "Field domain list",
  				text: "Remember to separate with space."
			});
		return false;
	}
	// Se evalua la exprecion regular
	else if (!expresionUrlList.test(principalbundle_targetcategories_urlList))
	{
		swal({
  				icon: "error",
  				title: "Field URL list",
  				text: "Remember to place /."
			});
		return false;
	}
	// Se evalua la exprecion regular
	else if (!expresionRegularExpression.test(principalbundle_targetcategories_regularExpression))
	{
		swal({
  				icon: "error",
  				title: "Field regular expression",
  				text: "The first character is a |."
			});
		return false;
	}
	// Se evalua la exprecion regular
	else if (!expresionRegularExpression2.test(principalbundle_targetcategories_regularExpression))
	{
		swal({
  				icon: "error",
  				title: "Field regular expression",
  				text: "The last character is a |."
			});
		return false;;
	}
	// Se evalua la exprecion regular
	else if (!expresionRedirect.test(principalbundle_targetcategories_redirect))
	{
		swal({
  				icon: "error",
  				title: "Field redirect",
  				text: "Write a URL."
			});
		return false;
	}
	// Se evalua que la descripcion no sea mayor a 50 caracteres
	else if(principalbundle_targetcategories_description.length>50)
	{
		swal({
  				icon: "error",
  				title: "Field description",
  				text: "The description can not be longer than 50 characters."
			});
		return false;
	}
}