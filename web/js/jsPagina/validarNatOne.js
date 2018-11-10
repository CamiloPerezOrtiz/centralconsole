function validarNatOne() 
{
	var external;
	external = document.getElementById("external").value;
	if(external === "" )
	{
		swal({
  				icon: "error",
  				title: "Field External subnet ip ",
  				text: "The field External subnet is required."
			});
		return false;
	}
	if($("#srctype option:selected").val() === "single" || $("#srctype option:selected").val() === "network")
	{
		if($("#src").val() === "")
		{
			swal({
	  				icon: "error",
	  				title: "Field address ",
	  				text: "The field address is required."
				});
			return false;
		}
	}
	if($("#dsttype option:selected").val() === "single" || $("#dsttype option:selected").val() === "network")
	{
		if($("#dst").val() === "")
		{
			swal({
	  				icon: "error",
	  				title: "Field Destination ",
	  				text: "The field Destination is required."
				});
			return false;
		}
	}
	if($("#srcmask option:selected").val() === "")
	{
		swal({
  				icon: "error",
  				title: "Field Destination mask",
  				text: "The field Destination mask is required."
			});
		return false;
	}
}