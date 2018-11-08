$( function() 
{
    $("#id_categoria").change( function() 
    {
        if ($(this).val() === "single" || $(this).val() === "network") 
        {
            $("#id_input").prop("readonly", false);
            $('input[type="text"]').val('');
            $("#srcmask").prop("hidden", true);
        } 
        else 
        {
            $("#id_input").prop("readonly", true);
            $('input[type="text"]').val('');
            $("#srcmask").prop("hidden", false);
        }
        if ($(this).val() === "network") 
        {
            $("#srcmask").prop("hidden", false);
        } 
        else 
        {
            $("#srcmask").prop("hidden", true);
        }
    });
});

if($("#id_categoria option:selected").val() !== "") 
{
   $("#id_input").prop("readonly", true);
   $("#srcmask").prop("hidden", true);
}