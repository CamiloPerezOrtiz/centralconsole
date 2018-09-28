function validarAclgroups() 
{
	var name, id_input, status, redirect, description;
	name = document.getElementById("name").value;
	id_input = document.getElementById("id_input").value;
	status = document.getElementById("status").value;
	redirect = document.getElementById("redirect").value;
	description = document.getElementById("description").value;
	
	expresionName = /^[A-Z]\w{1,15}$/;
	//Expresion que valida una ip
	expresionClient = /^(?!0)(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d)(\.|$)){4}$/;
	expresionRedirect = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
	
	if(name === "" || id_input === "" || redirect ==="" || description === "")
	{
		swal({
  				icon: "error",
  				title: "All fields are required."
			});
		return false;
	}
	else if(name.length<4)
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The name can not be less than 4 characters."
			});
		return false;
	}
	else if(name.length>15)
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The name can not be longer than 50 characters."
			});
		return false;
	}
	else if (!expresionName.test(name))
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The first character must be a letter in capital letter.\nThe name does not take spaces."
			});
		return false;
	}
	else if (!expresionRedirect.test(redirect))
	{
		swal({
  				icon: "error",
  				title: "Field redirect",
  				text: "Escribe una URL."
			});
		return false;
	}
	else if(description.length>40)
	{
		swal({
  				icon: "error",
  				title: "Field description",
  				text: "The description can not be longer than 50 characters."
			});
		return false;
	}
	else if (status === "1") 
	{
		swal({
  				icon: "error",
  				title: "Field Client (source)",
  				text: "Choose a method to complete the form."
			});
		return false;
	}
	else if (status === "ip") 
	{
		if(!expresionClient.test(id_input))
		{
			swal({
	  				icon: "error",
	  				title: "Field client",
	  				text: "Enter an ip.\nExample: 192.168.0.1"
				});
			return false;
		}
	}
	else if (status === "domain")
	{
		if(!expresionRedirect.test(id_input))
		{
			swal({
  				icon: "error",
  				title: "Field client",
  				text: "Write a URL.\nExample: foo.bar"
			});
			return false;
		}
	}
}