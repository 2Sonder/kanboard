if ('addEventListener' in window) {
    window.addEventListener('load', function () {
        document.body.className = document.body.className.replace(/\bis-loading\b/, '');
    });
    document.body.className += (navigator.userAgent.match(/(MSIE|rv:11\.0)/) ? ' is-ie' : '');
}



$('#enable-financial-columns').change(function(){
    if(this.checked)
        $('.financial').fadeIn('slow');
    else
        $('.financial').fadeOut('slow');

});


$('#test').click(function () {
    alert('clicck');
});

//DeleteInvoiceLine
$('.DeleteInvoiceLine').click(function () {
    $('#invoiceLine_'+$(this).attr('id')).remove();
    $('#linecount').val($('#linecount').val()-1);
});
