$( function() 
{
    $("#srctype").change( function() 
    {
        if ($(this).val() === "single" || $(this).val() === "network") 
        {
            $("#src").prop("readonly", false);
            if($("#srctype option:selected").val() === "any" || $("#srctype option:selected").val() === "pppoe" || $("#srctype option:selected").val() === "l2tp" || $("#srctype option:selected").val() === "wan" || $("#srctype option:selected").val() === "wanip" || $("#srctype option:selected").val() === "lan" || $("#srctype option:selected").val() === "lanip") 
            {
               $('#src[type="text"]').val('');
               $("#srcmask").val('32');
            }
            $("#srcmask").prop("hidden", true);
        } 
        else 
        {
            $("#src").prop("readonly", true);
            if($("#srctype option:selected").val() === "any" || $("#srctype option:selected").val() === "pppoe" || $("#srctype option:selected").val() === "l2tp" || $("#srctype option:selected").val() === "wan" || $("#srctype option:selected").val() === "wanip" || $("#srctype option:selected").val() === "lan" || $("#srctype option:selected").val() === "lanip") 
            {
               $('#src[type="text"]').val('');
               $("#srcmask").val('32');
            }
            $("#srcmask").prop("hidden", false);
        }
        if ($(this).val() === "network") 
        {
            $("#srcmask").prop("hidden", false);
            $("#srcmask").val('32');
        } 
        else 
        {
            $("#srcmask").prop("hidden", true);
            $("#srcmask").val('32');
        }
    });
});

if($("#srctype option:selected").val() == "network") 
{
   $("#src").prop("readonly", false);
   $("#srcmask").prop("hidden", false);
}
else if ($("#srctype option:selected").val() == "single")
{
    $("#src").prop("readonly", false);
    $("#srcmask").prop("hidden", true);
}
else
{
    $("#src").prop("readonly", true);
    $("#srcmask").prop("hidden", true);
}