$( function() 
{
    $("#dsttype").change( function() 
    {
        if ($(this).val() === "single" || $(this).val() === "network") 
        {
            $("#dst").prop("readonly", false);
            $('input[type="text"]').val('');
            $("#dstmask").prop("hidden", true);
        } 
        else 
        {
            $("#dst").prop("readonly", true);
            $('input[type="text"]').val('');
            $("#dstmask").prop("hidden", false);
        }
        if ($(this).val() === "network") 
        {
            $("#dstmask").prop("hidden", false);
        } 
        else 
        {
            $("#dstmask").prop("hidden", true);
        }
    });
});

if($("#dsttype option:selected").val() !== "") 
{
   $("#dst").prop("readonly", true);
   $("#dstmask").prop("hidden", true);
}