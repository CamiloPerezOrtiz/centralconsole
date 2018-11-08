$('#interface').on('change', function() 
{
    $('#dsttype').val(this.value).prop('selected', true);
});