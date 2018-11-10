$( function() 
{
    $("#dsttype").change( function() 
    {
        if ($(this).val() === "single" || $(this).val() === "network") 
        {
            $("#dst").prop("readonly", false);
            if($("#dsttype option:selected").val() === "any" || $("#dsttype option:selected").val() === "pppoe" || $("#dsttype option:selected").val() === "l2tp" || $("#dsttype option:selected").val() === "wan" || $("#dsttype option:selected").val() === "wanip" || $("#dsttype option:selected").val() === "lan" || $("#dsttype option:selected").val() === "lanip") 
            {
               $('#dst[type="text"]').val('');
               $("#dstmask").val('32');
            }
            $("#dstmask").prop("hidden", true);
        } 
        else 
        {
            $("#dst").prop("readonly", true);
            if($("#dsttype option:selected").val() === "any" || $("#dsttype option:selected").val() === "pppoe" || $("#dsttype option:selected").val() === "l2tp" || $("#dsttype option:selected").val() === "wan" || $("#dsttype option:selected").val() === "wanip" || $("#dsttype option:selected").val() === "lan" || $("#dsttype option:selected").val() === "lanip") 
            {
               $('#dst[type="text"]').val('');
               $("#dstmask").val('32');
            }
            $("#dstmask").prop("hidden", false);
        }
        if ($(this).val() === "network") 
        {
            $("#dstmask").prop("hidden", false);
            $("#srcmask").val('32');
        } 
        else 
        {
            $("#dstmask").prop("hidden", true);
            $("#srcmask").val('32');
        }
    });
});
// Valores de los input al cargar la pagina 
if($("#dsttype option:selected").val() == "network") 
{
   $("#dst").prop("readonly", false);
   $("#dstmask").prop("hidden", false);
}
else if ($("#dsttype option:selected").val() == "single")
{
    $("#dst").prop("readonly", false);
    $("#dstmask").prop("hidden", true);
}
else
{
    $("#dst").prop("readonly", true);
    $("#dstmask").prop("hidden", true);
}