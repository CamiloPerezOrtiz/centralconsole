function validarTargetcategories() 
{
	// Se recogen las variables del formulario las cuales son respresetadas por ID
	var name, domainList, urlList, regularExpression, redirect, description;
	// Se declaran las variables 
	name = document.getElementById("name").value;
	domainList = document.getElementById("domainList").value;
	urlList = document.getElementById("urlList").value;
	regularExpression = document.getElementById("regularExpression").value;
	redirect = document.getElementById("redirect").value;
	description = document.getElementById("description").value;
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
	if(name === "" || domainList === "" || urlList ==="" || regularExpression ==="" ||redirect ==="" || description ==="")
	{
		swal({
  				icon: "error",
  				title: "All fields are required."
			});
		return false;;
	}
	// Se evalua que el campo nombre tenga al menos 4 letras
	else if(name.length<4)
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The name can not be less than 4 characters."
			});
		return false;
	}
	// Se evalua que el campo nombre no se mayor de 15 letras 
	else if(name.length>50)
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The name can not be longer than 15 characters."
			});
		return false;
	}
	// Se evalua la exprecion regular
	else if (!expresionName.test(name))
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The first character must be a letter in capital letter.\nThe name does not take spaces."
			});
		return false;
	}
	// Se evalua la exprecion regular
	else if (!expresionDomainList.test(domainList))
	{
		swal({
  				icon: "error",
  				title: "Field domain list",
  				text: "Remember to separate with space."
			});
		return false;
	}
	// Se evalua la exprecion regular
	else if (!expresionUrlList.test(urlList))
	{
		swal({
  				icon: "error",
  				title: "Field URL list",
  				text: "Remember to place /."
			});
		return false;
	}
	// Se evalua la exprecion regular
	else if (!expresionRegularExpression.test(regularExpression))
	{
		swal({
  				icon: "error",
  				title: "Field regular expression",
  				text: "The first character is a |."
			});
		return false;
	}
	// Se evalua la exprecion regular
	else if (!expresionRegularExpression2.test(regularExpression))
	{
		swal({
  				icon: "error",
  				title: "Field regular expression",
  				text: "The last character is a |."
			});
		return false;;
	}
	// Se evalua la exprecion regular
	else if (!expresionRedirect.test(redirect))
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