function showContent() 
{
    element = document.getElementById("divocultar");
    check = document.getElementById("nordr");
    if (check.checked) 
    {
        element.style.display='none';
    }
    else 
    {
        element.style.display='block';
    }
}

function muestra_oculta(id){
    if (document.getElementById)
    {
        var el = document.getElementById(id);
        el.style.display = (el.style.display == "none") ? "block" : "none";
    }
}
window.onload = function()
{
    muestra_oculta("contenido");
};