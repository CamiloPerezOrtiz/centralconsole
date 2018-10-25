function validarAliases() 
{
	var name, description, add;
	name = document.getElementById("name").value;
	description = document.getElementById("description").value;
	add = document.getElementById("add").value;
	if(name === "" || description === "" || add === "")
	{
		swal({
  				icon: "error",
  				title: "All fields are required."
			});
		return false;
	}
	else if(name.length>30)
	{
		swal({
  				icon: "error",
  				title: "The name can not be longer than 30 characters."
			});
		return false;
	}
	else if(description.length>50)
	{
		swal({
  				icon: "error",
  				title: "The description can not be longer than 50 characters."
			});
		return false;
	}
}