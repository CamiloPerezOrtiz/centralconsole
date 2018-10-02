function validarRegistroAdministrador() 
{
	var principalbundle_usuario_name, principalbundle_usuario_email, principalbundle_usuario_password;
	principalbundle_usuario_name = document.getElementById("principalbundle_usuario_name").value;
	principalbundle_usuario_email = document.getElementById("principalbundle_usuario_email").value;
	principalbundle_usuario_password = document.getElementById("principalbundle_usuario_password").value;
	expresion = /\w+@\w+\.+[a-z]/;
	if(principalbundle_usuario_name === "" || principalbundle_usuario_email === "" || principalbundle_usuario_password === "")
	{
		swal({
  				icon: "error",
  				title: "All fields are required."
			});
		return false;
	}
	else if(principalbundle_usuario_name.length>15)
	{
		swal({
  				icon: "error",
  				title: "The name can not be longer than 15 characters."
			});
		return false;
	}
	else if(principalbundle_usuario_email.length>50)
	{
		swal({
  				icon: "error",
  				title: "The email can not be longer than 50 characters."
			});
		return false;
	}
	else if (!expresion.test(principalbundle_usuario_email))
	{
		swal({
  				icon: "error",
  				title: "Please enter an email."
			});
		return false;
	}
}