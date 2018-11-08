$( function() 
{
    $("#srcbeginport").change( function() 
    {
        if ($(this).val() === "") {
            $("#dstbeginport_cust").prop("readonly", false);
        } 
        else 
        {
            $("#dstbeginport_cust").prop("readonly", true);
        }
    });
});

$('#srcbeginport').on('change', function() 
{
    $('#srcendport').val(this.value).prop('selected', true);
    if($("#srcendport option:selected").val() == "") 
    {
        $("#dstendport_cust").prop("readonly", false);
    }
    else
    {
        $("#dstendport_cust").prop("readonly", true);
    }
});

if($("#srcbeginport option:selected").val() !== "") 
{
   $("#dstbeginport_cust").prop("readonly", true);
}

$( function() 
{
    $("#dstendport").change( function() 
    {
        if ($(this).val() === "") 
        {
            $("#dstbeginport_cust2").prop("readonly", false);
        } 
        else 
        {
            $("#dstbeginport_cust2").prop("readonly", true);
        }
    });
});

$( function() 
{
    $("#srcendport").change( function() 
    {
        if ($(this).val() === "") 
        {
            $("#dstendport_cust").prop("readonly", false);
        } 
        else 
        {
            $("#dstendport_cust").prop("readonly", true);
        }
    });
});

if($("#srcendport option:selected").val() !== "") 
{
   $("#dstendport_cust").prop("readonly", true);
}