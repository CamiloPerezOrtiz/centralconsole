$( function() 
{
    $("#dstendport2").change( function() 
    {
        if ($(this).val() === "") 
        {
            $("#dstendport_cust2").prop("readonly", false);
        } 
        else 
        {
            $("#dstendport_cust2").prop("readonly", true);
        }
    });
});

$('#dstendport').on('change', function() 
{
  	$('#dstendport2').val(this.value).prop('selected', true);
  	if($("#dstendport2 option:selected").val() == "") 
  	{
       $("#dstendport_cust2").prop("readonly", false);
    }
    else
    {
         $("#dstendport_cust2").prop("readonly", true);
    }
});

if($("#dstendport option:selected").val() !== "") 
{
    $("#dstbeginport_cust2").prop("readonly", true);
}

if($("#dstendport2 option:selected").val() !== "") 
{
	$("#dstendport_cust2").prop("readonly", true);
}