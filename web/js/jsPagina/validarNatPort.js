function validarNatPort() 
{
	var dstbeginport_cust2, dstendport_cust2, localip;
	dstbeginport_cust2 = document.getElementById("dstbeginport_cust2").value;
	dstendport_cust2 = document.getElementById("dstendport_cust2").value;
	localip = document.getElementById("localip").value;
	if($("#dstendport option:selected").val() === "")
	{
		swal({
  				icon: "error",
  				title: "Field Destination port ",
  				text: "The field is required."
			});
		return false;
	}
	if($("#dstendport2 option:selected").val() === "")
	{
		swal({
  				icon: "error",
  				title: "Field Destination port ",
  				text: "The field is required."
			});
		return false;
	}
	if(localip === "")
	{
		swal({
  				icon: "error",
  				title: "Field Redirect target IP ",
  				text: "The field is required."
			});
		return false;
	}
	if($("#localbeginport option:selected").val() === "")
	{
		swal({
  				icon: "error",
  				title: "Field Redirect target port ",
  				text: "A valid redirect target port must be specified. It must be a port alias or integer between 1 and 65535"
			});
		return false;
	}
	/*else if(name.length>30)
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
	}*/
}