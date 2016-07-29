
function discount(total, quantity, dis)
{
    p = dis.split('%');
    if (p.length > 1)
    {
        t = (total / 100) * p[0];
        total = (total - t);
    }
    else
    {
        total = (total - dis);
    }

    return total;
}

function totals()
{
    totalsex = 0;
    totalsinc = 0;
    
    //alert('totals');
    $('.total').each(function(i, obj) {
        
  //      alert(i+':'+obj);
        
        subtotal = $(obj).val();
      //  alert(subtotal);
        
        totalsex += totalsex + subtotal;
    //    alert(subtotal+','+totalsex);
    
    });
    
    tax = ((totalsex / 100) * 21);
    totalsinc = tax + totalsex; 
    
    $('#totalsex').val(totalsex);
    $('#totalsinc').val(totalsinc);
    
}

$(document).ready(function () {

    function getName(name)
    {
        return $('input[name="' + name + '"]').val();
    }

    function setName(name, value)
    {
        return  $('input[name="' + name + '"]').val(value);
    }

    $('.price').change(function () {
        name = $(this).attr('name');
        arr = name.split('_');
        price = getName('price_' + arr[1]);
        quantity = getName('quantity_' + arr[1]);
        total = price * quantity;
        discountvar = getName('discount_' + arr[1]);

        setName('total_' + arr[1], discount(total, quantity, discountvar));
        
        totals();
    });
    $('.quantity').change(function () {
        name = $(this).attr('name');
        arr = name.split('_');
        price = getName('price_' + arr[1]);
        quantity = getName('quantity_' + arr[1]);
        total = price * quantity;
        discountvar = getName('discount_' + arr[1]);
alert('here2');
        setName('total_' + arr[1], discount(total, quantity, discountvar));
        alert('here2');
        totals();
    });

    $('.discount').change(function () {
        discountvar = $(this).val();
        name = $(this).attr('name');
        arr = name.split('_');

        total = getName('total_' + arr[1]);

        setName('total_' + arr[1], discount(total, getName('quantity_' + arr[1]), discountvar));
        
        totals();
    });




    function calculateLine()
    {

    }

    function calculateTotal()
    {

    }


});