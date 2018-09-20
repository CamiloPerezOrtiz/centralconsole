function validarLogin() 
{
	var username, password;
	username = document.getElementById("username").value;
	password = document.getElementById("password").value;
	expresion = /\w+@\w+\.+[a-z]/;
	if(username === "" || password === "")
	{
		alert("All fields are required.");
		return false;
	}
	else if(username.length>25)
	{
		alert("The name can not be longer than 15 characters.");
		return false;
	}
	else if (!expresion.test(username))
	{
		alert("Please enter an email.");
		return false;
	}
}