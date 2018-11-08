function protocolOnChange(sel) 
{
    if (sel.value=="tcp" || sel.value=="udp" || sel.value=="tcp/udp")
    {
        divC = document.getElementById("portRange");
        divC.style.display="block";
    }
    else
    {
        divC = document.getElementById("portRange");
        divC.style.display="none";
    }
}