function validarAclgroups() 
{
	var principalbundle_aclgroups_disabled, 
		principalbundle_aclgroups_name, 
		id_input,
		status,
		principalbundle_aclgroups_allowIp,
		principalbundle_aclgroups_redirectMode, 
		principalbundle_aclgroups_redirect, 
		principalbundle_aclgroups_safeSearch,
		principalbundle_aclgroups_rewrite,
		principalbundle_aclgroups_rewriteTime, 
		principalbundle_aclgroups_description,
		principalbundle_aclgroups_log;
	principalbundle_aclgroups_disabled = document.getElementById("principalbundle_aclgroups_disabled").value;
	principalbundle_aclgroups_name = document.getElementById("principalbundle_aclgroups_name").value;
	id_input = document.getElementById("id_input").value;
	status = document.getElementById("status").value;
	principalbundle_aclgroups_allowIp = document.getElementById("principalbundle_aclgroups_allowIp").value;
	principalbundle_aclgroups_redirectMode = document.getElementById("principalbundle_aclgroups_redirectMode").value;
	principalbundle_aclgroups_redirect = document.getElementById("principalbundle_aclgroups_redirect").value;
	principalbundle_aclgroups_safeSearch = document.getElementById("principalbundle_aclgroups_safeSearch").value;
	principalbundle_aclgroups_rewrite = document.getElementById("principalbundle_aclgroups_rewrite").value;
	principalbundle_aclgroups_rewriteTime = document.getElementById("principalbundle_aclgroups_rewriteTime").value;
	principalbundle_aclgroups_description = document.getElementById("principalbundle_aclgroups_description").value;
	principalbundle_aclgroups_log = document.getElementById("principalbundle_aclgroups_log").value;
	
	expresion = /\w+@\w+\.+[a-z]/;
	expresionName = /^[A-Z]\w{1,15}$/;
	//Expresion que valida una ip
	expresionClient = /^(?!0)(?!.*\.$)((1?\d?\d|25[0-5]|2[0-4]\d)(\.|$)){4}$/;
	expresionRedirect = /^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/;
	
	if(principalbundle_aclgroups_disabled === "" || principalbundle_aclgroups_name === "" || id_input === "" ||
		principalbundle_aclgroups_allowIp ==="" || principalbundle_aclgroups_redirectMode ==="" ||
		principalbundle_aclgroups_redirect ==="" || principalbundle_aclgroups_safeSearch ==="" ||
		principalbundle_aclgroups_rewrite ==="" || principalbundle_aclgroups_rewriteTime ==="" ||
		principalbundle_aclgroups_description === "" || principalbundle_aclgroups_log === "")
	{
		swal({
  				icon: "error",
  				title: "All fields are required."
			});
		return false;
	}
	else if(principalbundle_aclgroups_name.length<4)
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The name can not be less than 4 characters."
			});
		return false;
	}
	else if(principalbundle_aclgroups_name.length>15)
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The name can not be longer than 15 characters."
			});
		return false;
	}
	else if (!expresionName.test(principalbundle_aclgroups_name))
	{
		swal({
  				icon: "error",
  				title: "Field name",
  				text: "The first character must be a letter in capital letter.\nThe name does not take spaces."
			});
		return false;
	}
	else if (!expresionRedirect.test(principalbundle_aclgroups_redirect))
	{
		swal({
  				icon: "error",
  				title: "Field redirect",
  				text: "Escribe una URL."
			});
		return false;
	}
	else if(principalbundle_aclgroups_description.length>40)
	{
		swal({
  				icon: "error",
  				title: "Field description",
  				text: "The description can not be longer than 50 characters."
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
	else if (status === "subnet")
	{
		if(!expresionClient.test(id_input))
		{
			swal({
	  				icon: "error",
	  				title: "Field client",
	  				text: "Enter an ip.\nExample: 192.168.1.0/255.255.255.0"
				});
			return false;
		}
	}
	else if (status === "ipRange")
	{
		if(!expresionClient.test(id_input))
		{
			swal({
	  				icon: "error",
	  				title: "Field client",
	  				text: "Enter an ip.\nExample: 192.168.1.1-192.168.1.10 "
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
	else if (status === "ldapSearch")
	{
		if(!expresionRedirect.test(id_input))
		{
			swal({
  				icon: "error",
  				title: "Field client",
  				text: "Write a URL.\nExample: ldap://192.168.0.100/DC=domain"
			});
			return false;
		}
	}
}