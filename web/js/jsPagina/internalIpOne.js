$( function() 
{
    $("#srctype").change( function() 
    {
        if ($(this).val() === "single" || $(this).val() === "network") 
        {
            $("#src").prop("readonly", false);
            $('#src[type="text"]').val('');
            $("#srcmask").prop("hidden", true);
        } 
        else 
        {
            $("#src").prop("readonly", true);
            $('#src[type="text"]').val('');
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

if($("#srctype option:selected").val() !== "") 
{
   $("#src").prop("readonly", true);
   $("#srcmask").prop("hidden", true);
}