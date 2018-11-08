$( function() 
{
    $("#localbeginport").change( function() 
    {
        if ($(this).val() === "") 
        {
            $("#localbeginport_cust").prop("readonly", false);
        } 
        else 
        {
            $("#localbeginport_cust").prop("readonly", true);
        }
    });
});

if($("#localbeginport option:selected").val() !== "") 
{
   $("#localbeginport_cust").prop("readonly", true);
}