$(document).ready(function(){
  $('.btnMenu').click(function(e) {
   e.preventDefault();
   if($('nav').hasClass('viewMenu')) {
     $('nav').removeClass('viewMenu');
   }else {
     $('nav').addClass('viewMenu');
   }

 });


 $("#rdproducto").prop("checked", true);
 $("#rdtipo").prop("checked", true);

 $('nav ul li').click(function() {
   $('nav ul li ul').slideUp();
   $(this).children('ul').slideToggle();
 });
// Modal Agregar
   $('.add_product').click(function(e) {
     e.preventDefault();
     var producto = $(this).attr('product');
     var action = 'infoProducto';
     $.ajax({
       url: 'modal.php',
       type: 'POST',
       async: true,
       data: {action:action,producto:producto},
       success: function(response) {        
         //console.log(response); 
       if (response != 0) {
         var info = JSON.parse(response);
       //  $('#producto_id').val(info.codproducto);
       //  $('.nameProducto').html(info.descripcion);

         $('.bodyModal').html('<form action="" method="post" name="form_add_product" id="form_add_product" onsubmit="event.preventDefault(); sendDataProduct();">'+
           '<h1>Agregar Producto</h1><br>'+
           '<h2 class="nameProducto">'+info.descripcion+'</h2>'+
           '<br>'+
           '<hr>'+
           '<input type="number" name="cantidad" id="txtCantidad" placeholder="Cantidad del Producto" required><br>'+
           '<input type="number" name="precio" id="txtPrecio" placeholder="Precio del Producto" required>'+
           '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required><br>'+
           '<input type="hidden" name="action" value="addProduct" required>'+
           '<div class="alert alertAddProduct"></div>'+
           '<button type="submit" class="btn_new">Agregar</button>'+
           '<a href="#" class="btn_ok closeModal" onclick="closeModal();">Cerrar</a>'+

         '</form>');
       }
       },
       error: function(error) {
         console.log(error);
       }
       });

     $('.modal').fadeIn();

   });
// modal Eliminar producto
$('.del_product').click(function(e) {
 e.preventDefault();
 var producto = $(this).attr('product');
 var action = 'infoProducto';
 $.ajax({
   url: 'modal.php',
   type: 'POST',
   async: true,
   data: {action:action,producto:producto},  
   success: function(response) {
   //  console.log(response);
   if (response != 0) {
     var info = JSON.parse(response);
   //  $('#producto_id').val(info.codproducto);
   //  $('.nameProducto').html(info.descripcion);

     $('.bodyModal').html('<form action="" method="post" name="form_del_product" id="form_del_product" onsubmit="event.preventDefault(); delProduct();">'+
       '<h2 style="color: red; font-size: 18px;">¿Estás seguro de eliminar el Producto</h2>'+
       '<h2 class="nameProducto">'+info.descripcion+'</h2>'+
       '<hr>'+
       '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required><br>'+
       '<input type="hidden" name="action" value="delProduct" required>'+
       '<div class="alert alertAddProduct"></div>'+
       '<input type="submit"  value="Aceptar" class="ok"><br>'+
       '<a href="#" style="text-align: center;" class="btn_cancelar" onclick="closeModal();">Cerrar</a>'+
     '</form>');
   }
   },
   error: function(error) {
     console.log('error');
   }
   });

 $('.modal').fadeIn();

});

$('#search_proveedor').change(function(e) {
 e.preventDefault();
 var sistema = getUrl();
 location.href = sistema+'buscar_productos.php?proveedor='+$(this).val();

});

// activa campos para registrar Cliente
$('.btn_new_cliente').click(function(e) {
 e.preventDefault();
 $('#nom_cliente').removeAttr('disabled');
 $('#tel_cliente').removeAttr('disabled');
 $('#dir_cliente').removeAttr('disabled');
 //$('#dir_cliente').removeAttr('disabled');
 $('#nom_cliente').focus();
 $('#div_registro_cliente').slideDown();

 var action = 'nextIdcliente';
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action},
   success: function(response) {
   //console.log(response);
   var data = $.parseJSON(response);
     $('#dni_cliente').val(data);
     $('#idcliente').val(data);
     $('#dni_cliente').attr('disabled','disabled');
     $('#nom_cliente').focus();
   },
   error: function(error) {

   }
 });

});

// buscar Cliente
$('#dni_cliente').keyup(function(e) {
 e.preventDefault();
 var cl = $(this).val();
 var action = 'searchCliente';
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action,cliente:cl},
   success: function(response) {
     if (response == 0) {
       $('#idcliente').val('');
       $('#nom_cliente').val('');
       $('#tel_cliente').val('');
       $('#dir_cliente').val('');
       // mostar boton agregar
       $('.btn_new_cliente').slideDown();
     
     }else {
       var data = $.parseJSON(response);
       $('#idcliente').val(data.idcliente);
       $('#dni_cliente').val(data.idcliente);
       $('#nom_cliente').val(data.nombre);
       $('#tel_cliente').val(data.telefono);
       $('#dir_cliente').val(data.direccion);
       cl=data.idcliente;
     
       var action = 'searchClienteCredito';
       $.ajax({
         url: 'modal.php',
         type: "POST",
         async: true,
         data: {action:action,cliente:cl},
         success: function(response) {
           var info = $.parseJSON(response);
           $('#divCreditos').html(info.detalle);
         },
         error: function(error) {
     
         }
       });


       // ocultar boton Agregar
       $('.btn_new_cliente').slideUp();
       $('#btnBuscarCliente').slideUp();

       // Bloque campos
     
       $('#nom_cliente').attr('disabled','disabled');
       $('#tel_cliente').attr('disabled','disabled');
       $('#dir_cliente').attr('disabled','disabled');
       // ocultar boto Guardar
       $('#div_registro_cliente').slideUp();
     }
   },
   error: function(error) {

   }
 });

});

// crear cliente = Ventas
$('#form_new_cliente_venta').submit(function(e) {  
 $('#dni_cliente').removeAttr('disabled');
 e.preventDefault();
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: $('#form_new_cliente_venta').serialize(),
   success: function(response) {
     if (response  != 0) {
       // Agregar id a input hidden
       $('#idcliente').val(response);
       $('#dni_cliente').val(response);
       //bloque campos
       $('#dni_cliente').attr('disabled','disabled');
       $('#nom_cliente').attr('disabled','disabled');
       $('#tel_cliente').attr('disabled','disabled');
       $('#dir_cliente').attr('disabled','disabled');
       // ocultar boton Agregar
       $('.btn_new_cliente').slideUp();
       //ocultar boton Guardar
       $('#div_registro_cliente').slideUp();
     }
   },
   error: function(error) {
   }
 });
});

// buscar producto = Ventas
$('#txt_cod_pro').keyup(function(e) {
 e.preventDefault();
 var productos = $(this).val();
 if (productos == "") {
   $('#txt_descripcion').html('-');
   $('#txt_existencia').html('-');
   $('#txt_cant_producto').val('0');
   $('#txt_precio').html('0.00');
   $('#txt_precio_total').html('0.00');

   //Bloquear Cantidad
   $('#txt_cant_producto').attr('disabled', 'disabled');
   // Ocultar Boto Agregar
   $('#add_product_venta').slideUp();
 }

 var action = 'infoProducto';
 if (productos != '') {
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action,producto:productos},
   success: function(response){
    // console.log(response); 
     if(response == 0) {
       $('#txt_descripcion').html('-');
       $('#txt_existencia').html('-');
       $('#txt_cant_producto').val('0');
       $('#txt_precio').html('0.00');
       $('#txt_precio_total').html('0.00');

       //Bloquear Cantidad
       $('#txt_cant_producto').attr('disabled','disabled');
       // Ocultar Boto Agregar
       $('#add_product_venta').slideUp();


     }else{
     
       var info = JSON.parse(response);
       if (info.existencia < 1) {
         $('#txt_descripcion').html(info.descripcion);
         $('#txt_cod_producto').val(info.codproducto);
         $('#txt_existencia').html(info.existencia);
         $('#txt_descuento').html(info.descuento);
       
         if(info.tipo==1)
         { $('#tipo').html("Descuento");
           $('#txt_descuento').html(info.descuento+"%");
         }else  if(info.tipo==2)
         {
           $('#tipo').html("Precio de promocion");
           $('#txt_descuento').html(info.descuento);
         }else
         {
           $('#tipo').html("Descuento");
           $('#txt_descuento').html(0);
         }

         $('#txt_precio').html(info.precio);
         $('#txt_precio_total').html(info.newprecio)


         //Bloquear Cantidad
         $('#txt_cant_producto').attr('disabled', 'disabled');
         // Ocultar Boto Agregar
         $('#add_product_venta').slideUp();
       }else{
         $('#txt_descripcion').html(info.descripcion);
         $('#txt_existencia').html(info.existencia);         
         $('#txt_cod_producto').val(info.codproducto);
         $('#txt_cant_producto').val('1');
         $('#txt_precio').html(info.precio);
         $('#txt_descuento').html(info.descuento);

         $('#txt_cantidad_mayoreo').val(info.cantidad_mayoreo);
         $('#txt_precio_mayoreo').val(info.mayoreo);         
         $('#txt_precio_normal').val(info.precio); 

         if(info.tipo==1)
         { $('#tipo').html("Descuento");
           $('#txt_descuento').html(info.descuento+"%");
         }else  if(info.tipo==2)
         {
           $('#tipo').html("Precio de promocion");
           $('#txt_descuento').html(info.descuento);
         }else
         {
           $('#tipo').html("Descuento");
           $('#txt_descuento').html(0);
         }

         $('#txt_precio').html(info.precio);
         $('#txt_precio_total').html(info.newprecio)
         // Activar Cantidad
         $('#txt_cant_producto').removeAttr('disabled');
         // Mostar boton Agregar
         $('#add_product_venta').slideDown();
       }

     }
   },
   error: function(error) {
   }
 });
 $('#txt_descripcion').html('-');
 $('#txt_existencia').html('-');
 $('#txt_cant_producto').val('0');
 $('#txt_precio').html('0.00');
 $('#txt_precio_total').html('0.00');

 //Bloquear Cantidad
 $('#txt_cant_producto').attr('disabled','disabled');
 // Ocultar Boto Agregar
 $('#add_product_venta').slideUp();

 ////
 }


});




// calcular el Total
$('#txt_cant_producto').keyup(function(e) {
 e.preventDefault();



 var precio_total = $(this).val() * $('#txt_precio').html();
 var existencia = parseInt($('#txt_existencia').html());

  console.log("hola"+parseInt($('#txt_cantidad_mayoreo').val()));
if(parseInt($('#txt_cantidad_mayoreo').val())>0)
{
  if($(this).val() >= parseInt($('#txt_cantidad_mayoreo').val())){
   $('#tipo').html("Precio de mayoreo");
    $('#txt_descuento').html($('#txt_precio_mayoreo').val());
    var precio_total = $(this).val() * $('#txt_precio_mayoreo').val();

    Swal.fire({
     icon: 'info',
     title: '',
     text: '¡Has alcanzado el precio de mayoreo!',
     footer: ''
   });

  }else{
   $('#tipo').html("Descuento");
   $('#txt_descuento').html('');
  }
}
   
  
 $('#txt_precio_total').html(precio_total);
 // Ocultat el boton Agregar si la cantidad es menor que 1
 if (($(this).val() < 1 || isNaN($(this).val())) || ($(this).val() > existencia)){
   $('#add_product_venta').slideUp();
 }else {
   $('#add_product_venta').slideDown();
 }
});


$('#add_product_venta').click(function(e) {
 e.preventDefault(); 
 if ($('#txt_cant_producto').val() > 0) {
   var existencia= parseInt($('#txt_existencia').html());
   var action = 'productoDetalleValida';
   var codproducto = $('#txt_cod_producto').val();   
   var cantidad=$('#txt_cant_producto').val();
   $.ajax({
     url: 'modal.php',
     type: 'POST',
     async: true,
     data: {action:action,producto:codproducto},
     success: function(response) {    
      //console.log(response);
      //console.log((parseInt(response)+parseInt(cantidad))>parseInt(existencia));
      if((parseInt(response)+parseInt(cantidad))>parseInt(existencia))
      {
       Swal.fire({
         icon: 'error',
         title: 'Oops...',
         text: '¡Ya no puede agregar mas productos, revasaria el stock exitente!',
         footer: ''
       });
       return;
     }
      
      
     },
     error: function(error) {
   
     }
   });


   var action = 'addProductoDetalle';
   $.ajax({
     url: 'modal.php',
     type: 'POST',
     async: true,
     data: {action:action,producto:codproducto,cantidad:cantidad},
     success: function(response) {    
       //console.log(response);    
       if (response != 'error') {          
         var info = JSON.parse(response);
        //console.log(response);
         $('#detalle_venta').html(info.detalle);
         $('#detalle_totales').html(info.totales);
         $('#totalmodal').val(  MASK('', (info.totalmodal),'$##,###,##0.00',1));      
         $('#totalFIJO').val(  MASK('', (info.totalmodal),'$##,###,##0.00',1));              
         $('#txt_cod_producto').val('');
         $('#txt_cod_pro').val('');
         $('#txt_descripcion').html('-');
         $('#txt_existencia').html('-');
         $('#txt_cant_producto').val('0');
         $('#txt_precio').html('0.00');
         $('#txt_precio_total').html('0.00');
         $('#tipo').html("Descuento");
         $('#txt_descuento').html('');
         // Bloquear cantidad
         $('#txt_cant_producto').attr('disabled','disabled');
         // Ocultar boton agregar
         $('#add_product_venta').slideUp();
       }else {
         console.log('No hay dato');
       }
       viewProcesar();
     },
     error: function(error) {

     }
   });
 }
});





// anular venta
$('#btn_anular_venta').click(function(e) {
 e.preventDefault();
 var rows = $('#detalle_venta tr').length;
 if (rows > 0) {
   var action = 'anularVenta';
   $.ajax({
     url: 'modal.php',
     type: 'POST',
     async: true,
     data: {action:action},
     success: function(response) {
       if (response != 0) {
         location.reload();
       }
     },
     error: function(error) {

     }
   });
 }
});
// facturar venta
$('#btn_facturar_venta').click(function(e) {
 e.preventDefault();
 var rows = $('#detalle_venta tr').length;  
 var action = 'procesarVenta';
 var codcliente = $('#idcliente').val();
 var tipoventa = $('#tipoven').val();    
 var tipopago = $('#tipopago').val();   
 var referencia = $('#numreferencia').val();  
 
 
 if(tipoventa==2 && codcliente==1)
 {
   Swal.fire({
     icon: 'error',
     title: 'Oops...',
     text: 'Debe especificar el nombre de un cliente , el credito no puede ser a publico en general!',
     footer: ''
   })
   $('#exampleModal').modal('hide');
   $('#idcliente').focus();
   return;
 }


 if( tipoventa==1 )
 { 
   pago = document.getElementById("pagar_con").value;
   fechaven = new Date();
   total=document.getElementById("totalmodal").value;
   console.log(total);
   console.log(pago);
   console.log('entro');

   total = document.getElementById("totalmodal").value.replace('$','');//Quitamos el signo de pesos 
 total=total.replace(',','');//Quitamos el la coma para poder hacer la operacion


 pago = document.getElementById("pagar_con").value.replace('$','');//Quitamos el signo de pesos 
 pago=pago.replace(',','');//Quitamos el la coma para poder hacer la operacion
  console.log(total+'total');
   console.log(pago+'pago');

   if(parseInt(total)>parseInt(pago))
   {
   
      Swal.fire({
        icon: 'error',
        title: 'Oops...',       
        text: 'El pago no debe ser menor al total!',       
        footer: ''
      })
      $('#exampleModal').modal('hide');
      return;
    }
 }
 else
 {
  
   pago = document.getElementById("pagar_conC").value; 
   total=document.getElementById("totalmodal").value;
   fechaven = $('#fechav').val();
   } 
   numcredito = document.getElementById("numcredito").value; 
   console.log(numcredito);
   
   if(tipopago==5)
{
  console.log('pagomixto');
  var pefectivo=$('#pefectivo').val();
  var ptarjeta=$('#ptarjeta').val();
  var ptransferencia=$('#ptransferencia').val();
  var pdeposito = $('#pdeposito').val();;

 
  if(pefectivo==''){pefectivo=0;}
  if(ptarjeta==''){ptarjeta=0;}
  if(ptransferencia==''){ptransferencia=0;}
  if(pdeposito==''){pdeposito=0;}

  var pago=parseFloat(pefectivo)+parseFloat(ptarjeta)+parseFloat(ptransferencia)+parseFloat(pdeposito);
 // total=document.getElementById("totalmodal").value;
 
 total = document.getElementById("totalmodal").value.replace('$','');//Quitamos el signo de pesos 

 total=total.replace(',','');//Quitamos el la coma para poder hacer la operacion
  console.log(total+'total');
   console.log(pago+'pago');
  if(parseFloat(pago)<parseFloat(total))
   {
   
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: 'El pago no debe ser menor al total!',
        footer: ''
      })
      $('#exampleModal').modal('hide');
      return;
    }
}

  


    if(pago<=0 || pago=='')
    {
    
       Swal.fire({
         icon: 'error',
         title: 'Oops...',
         text: 'Debe especificar el monto con el que esta pagando!',
         footer: ''
       })
       $('#exampleModal').modal('hide');
       return;
     }

 if (rows > 0) {
   $.ajax({
     url: 'modal.php',
     type: 'POST',
     async: true,
     data: {action:action,codcliente:codcliente,tipoventa:tipoventa, pago:pago,fechaven:fechaven,tipopago:tipopago,referencia:referencia,numcredito:numcredito,efectivo:pefectivo,tarjeta:ptarjeta,transferencia:ptransferencia,deposito:pdeposito},
     success: function(response) {
     (response); 
     console.log(response);
     if (response != 0) {
     //console.log(response);
       var info = JSON.parse(response);        
       generarPDF(info.codcliente,info.nofactura);
      // location.reload();
     }else {
       console.log('no hay dato');
     }
     },
     error: function(error) {

     }
   });
 }else 
 {
  
   if (numcredito> 0)
   { 
   // console.log("entro");    
   $.ajax({
   url: 'modal.php',
   type: 'POST',
   async: true,
   data: {action:action,codcliente:codcliente,tipoventa:tipoventa,total:total, pago:pago,fechaven:fechaven,tipopago:tipopago,referencia:referencia,numcredito:numcredito,efectivo:pefectivo,tarjeta:ptarjeta,transferencia:ptransferencia,deposito:pdeposito},
   success: function(response) {
     console.log(response);
   if (response != 0) {
     var info = JSON.parse(response);        
     generarPDF(info.codcliente,info.nofactura);
     location.reload();
   }else {
     console.log('no hay dato');
   }
   },
   error: function(error) {

   }
 });
 }

 }
});

//Ver Factura
$('.view_factura').click(function(e) {
 e.preventDefault();

 var codCliente = $(this).attr('cl');
 var noFactura = $(this).attr('f');
 generarPDF(codCliente,noFactura);
});




// Cambiar contraseña
$('.newPass').keyup(function() {
 validaPass();
});

// cambiar contraseña
$('#frmChangePass').submit(function(e){
 e.preventDefault();
 var passActual = $('#actual').val();
 var passNuevo = $('#nueva').val();
 var passconfir = $('#confirmar').val();
 var action = "changePasword";
 if (passNuevo != passconfir) {
   $('.alertChangePass').html('<p style="color:red;">Las contraseñas no Coinciden</p>');
   $('.alertChangePass').slideDown();
   return false;
   }
 if (passNuevo.length < 5) {
 $('.alertChangePass').html('<p style="color:orangered;">Las contraseñas deben contener como mínimo 5 caracteres');
 $('.alertChangePass').slideDown();
 return false;
 }
 $.ajax({
   url: 'modal.php',
   type: 'POST',
   async: true,
   data: {action:action,passActual:passActual,passNuevo:passNuevo},
   success: function(response) {
     if (response != 'error') {
       var info = JSON.parse(response);
       if (info.cod == '00') {
         $('.alertChangePass').html('<p style="color:green;">'+info.msg+'</p>');
         $('#frmChangePass')[0].reset();
       }else {
         $('.alertChangePass').html('<p style="color:green;">'+info.msg+'</p>');
       }
       $('.alertChangePass').slideDown();
     }
   },
   error: function(error) {
   }
 });
});

$(".confirmar").submit(function(e) {
 e.preventDefault();
 Swal.fire({
   title: 'Esta seguro de eliminar?',
   icon: 'warning',
   showCancelButton: true,
   confirmButtonColor: '#3085d6',
   cancelButtonColor: '#d33',
   confirmButtonText: 'SI, Eliminar!'
 }).then((result) => {
   if (result.isConfirmed) {
     this.submit();
   }
 })
})
$(".cancelar").submit(function(e) {
 e.preventDefault();
 Swal.fire({
   title: 'Esta seguro de cancelar?',
   icon: 'warning',
   showCancelButton: true,
   confirmButtonColor: '#3085d6',
   cancelButtonColor: '#d33',
   confirmButtonText: 'SI, Cancelar!'
 }).then((result) => {
   if (result.isConfirmed) {
     this.submit();
   }
 })
})

}); // fin ready

function validaPass() {
 var passNuevo = $('#nueva').val();
 var confirmarPass = $('#confirmar').val();
 if (passNuevo != confirmarPass) {
   $('.alertChangePass').html('<p style="color:red;">Las contraseñas no Coinciden</p>');
   $('.alertChangePass').slideDown();
   return false;
 }
if (passNuevo.length < 5) {
 $('.alertChangePass').html('<p style="color:orangered;">Las contraseñas deben contener como mínimo 5 caracteres');
 $('.alertChangePass').slideDown();
 return false;
}

$('.alertChangePass').html('<p style="color:blue;">Las contraseñas Coinciden.</p>');
$('.alertChangePass').slideDown();
}
function generarPDF(cliente,factura) {
 url = 'factura/generaFactura.php?cl='+cliente+'&f='+factura;
 window.open(url, '_blank');
}

function del_product_detalle(correlativo) {
 var action = 'delProductoDetalle';
 var id_detalle = correlativo;
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action,id_detalle:id_detalle},
   success: function(response) {
     //console.log(response);
       if (response != 0) {
        //console.log(response);
        
      var info = JSON.parse(response);
       $('#detalle_venta').html(info.detalle);
       $('#detalle_totales').html(info.totales);
       $('#totalmodal').val(  MASK('', (info.totalmodal),'$##,###,##0.00',1));
       $('#totalFIJO').val(  MASK('', (info.totalmodal),'$##,###,##0.00',1));              
       $('#txt_cod_producto').val('');
       $('#txt_descripcion').html('-');
       $('#txt_existencia').html('-');
       $('#txt_cant_producto').val('0');
       $('#txt_precio').html('0.00');
       $('#txt_precio_total').html('0.00');

       // Bloquear cantidad
       $('#txt_cant_producto').attr('disabled','disabled');

       // Ocultar boton agregar
       $('#add_product_venta').slideUp();
     }else {
       $('#detalle_venta').html('');
       $('#detalle_totales').html('');


     }
     viewProcesar();
   },
   error: function(error) {
     
   }
 });
}

// mostrar/ ocultar boton Procesar
function viewProcesar() {
 if ($('#detalle_venta tr').length > 0){
   $('#procesarVenta').show();
   $('#btn_anular_venta').show();
 }else {
   $('#procesarVenta').hide();
   $('#btn_anular_venta').hide();
 }
}

function searchForDetalle(id) {
 var action = 'searchForDetalle';
 var user = id;
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action,user:user},
   success: function(response) {   
     //console.log(response); 
     if (response == 0) {       
     }else {
       var info = JSON.parse(response);
       $('#detalle_venta').html(info.detalle);
       $('#detalle_totales').html(info.totales);
       $('#totalmodal').val(MASK('', (info.totalmodal),'$##,###,##0.00',1));  
       $('#totalFIJO').val(  MASK('', (info.totalmodal),'$##,###,##0.00',1));        
     }
     viewProcesar();
   },
   error: function(error) {

   }
 });
}

function getUrl() {
 var loc = window.location;
 var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/')+ 1);
 return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}
// funcion para agregar producto
function sendDataProduct() {
 $('.alertAddProduct').html('');
 $.ajax({
   url: 'modal.php',
   type: 'POST',
   async: true,
   data: $('#form_add_product').serialize(),
   success: function(response) {
     if (producto == 'error') {
       $('.alertAddProduct').html('<p style="color : red;">Error al agregar producto.</p>');

     }else {
       var info = JSON.parse(response);
       $('.row'+info.producto_id+' .celExistencia').html(info.nueva_existencia);
       $('.row'+info.producto_id+' .celPrecio').html(info.nuevo_precio);
       $('#txtCantidad').val('');
       $('#txtPrecio').val('');
       $('.alertAddProduct').html('<p>Producto Agregado Corectamente.</p>');

     }
   },
   error: function(error) {
     console.log(error);

   }
 });

}
// funcion para elimar producto
function delProduct() {
 var pr = $('#producto_id').val();
 $('.alertAddProduct').html('');
 $.ajax({
   url: 'modal.php',
   type: 'POST',
   async: true,
   data: $('#form_del_product').serialize(),
   success: function(response) {

     if (response == 'error') {
       $('.alertAddProduct').html('<p style="color : red;">Error al eliminar producto.</p>');

     }else {

       $('.row'+pr).remove();
       $('#form_del_product .ok').remove();
       $('.alertAddProduct').html('<p>Producto Eliminado Corectamente.</p>');

     }
   },
   error: function(error) {
     console.log(error);

   }
 });

}

/*if (document.getElementById("sales-chart")) {
 const action = "sales";
 $.ajax({
   url: 'chart.php',
   type: 'POST',
   data: {action},
   async: true,
   success: function (response) {
     if (response != 0) {
       var data = JSON.parse(response);
       var nombre = [];
       var cantidad = [];
       for (var i = 0; i < data.length; i++) {
         nombre.push(data[i]['descripcion']);
         cantidad.push(data[i]['existencia']);
       }
       try {
         //Sales chart
         var ctx = document.getElementById("sales-chart");
         if (ctx) {
           ctx.height = 150;
           var myChart = new Chart(ctx, {
             type: 'line',
             data: {
               labels: nombre,
               type: 'line',
               defaultFontFamily: 'Poppins',
               datasets: [{
                 label: "Disponible",
                 data: cantidad,
                 backgroundColor: 'transparent',
                 borderColor: 'rgba(220,53,69,0.75)',
                 borderWidth: 3,
                 pointStyle: 'circle',
                 pointRadius: 5,
                 pointBorderColor: 'transparent',
                 pointBackgroundColor: 'rgba(220,53,69,0.75)',
               }, {
                 label: "Cantidad",
                 data: [0, 2, 3, 4, 5, 6, 7,8,9,10],
                 backgroundColor: 'transparent',
                 borderColor: 'rgba(40,167,69,0.75)',
                 borderWidth: 3,
                 pointStyle: 'circle',
                 pointRadius: 5,
                 pointBorderColor: 'transparent',
                 pointBackgroundColor: 'rgba(40,167,69,0.75)',
               }]
             },
             options: {
               responsive: true,
               tooltips: {
                 mode: 'index',
                 titleFontSize: 12,
                 titleFontColor: '#000',
                 bodyFontColor: '#000',
                 backgroundColor: '#fff',
                 titleFontFamily: 'Poppins',
                 bodyFontFamily: 'Poppins',
                 cornerRadius: 3,
                 intersect: false,
               },
               legend: {
                 display: false,
                 labels: {
                   usePointStyle: true,
                   fontFamily: 'Poppins',
                 },
               },
               scales: {
                 xAxes: [{
                   display: true,
                   gridLines: {
                     display: false,
                     drawBorder: false
                   },
                   scaleLabel: {
                     display: false,
                     labelString: 'Month'
                   },
                   ticks: {
                     fontFamily: "Poppins"
                   }
                 }],
                 yAxes: [{
                   display: true,
                   gridLines: {
                     display: false,
                     drawBorder: false
                   },
                   scaleLabel: {
                     display: true,
                     labelString: 'Cantidad',
                     fontFamily: "Poppins"

                   },
                   ticks: {
                     fontFamily: "Poppins"
                   }
                 }]
               },
               title: {
                 display: false,
                 text: 'Normal Legend'
               }
             }
           });
         }
       } catch (error) {
         console.log(error);
       }
     }
   },
   error: function (error) {
     console.log(error);
   }
 });
}*/

if (document.getElementById("sales-chart")) {
  const action = "sales-chart";
 
  $.ajax({
    url: 'chart.php',
    type: 'POST',
    async: true,
    data: {action},
    success: function (response) {
     //alert(response);
     console.log(response);
     $("#sales-chart").val(response);
     $('#sales-chart').html(response);
    },
    error: function (error) {
      console.log(error);
 
    }
  });
 }



if (document.getElementById("polarChart")) {
 const action = "polarChart";

 $.ajax({
   url: 'chart.php',
   type: 'POST',
   async: true,
   data: {action},
   success: function (response) {
    //alert(response);
    console.log(response);
    $("#polarChart").val(response);
    $('#polarChart').html(response);
   },
   error: function (error) {
     console.log(error);

   }
 });
}


// buscar producto = Ventas
$('#txt_cod_pro').keyup(function(e) {
 e.preventDefault();
 if (e.which == 13) {
     $('#add_product_venta').click();   
} 
});


$('#pagar_con').keyup(function(e) {
 e.preventDefault();
 //if (e.which == 13) {  


   pagar_con = document.getElementById("pagar_con").value.replace('$',''); //Quitamos el signo de pesos 
   total = document.getElementById("totalmodal").value.replace('$','');//Quitamos el signo de pesos 

   total=total.replace(',','');//Quitamos el la coma para poder hacer la operacion
   pagar_con=pagar_con.replace(',','');//Quitamos el la coma para poder hacer la operacion

   const cambio =(parseFloat(pagar_con)-parseFloat(total));
   tipopago= document.getElementById("tipopago").value;
   const tipoventa = document.getElementById("tipoven").value;    

   if(tipopago==1)
   {
     $('#btn_facturar_venta').slideUp();
         if (cambio >= 0 ) {    
            
         document.getElementById("cambio").value=MASK('', (cambio),'$##,###,##0.00',1);
         $('.alertCambio').html('<p style="color : red;"></p>');
         $('#btn_facturar_venta').slideDown();      
         } else {
           $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga debe ser mayor o igual al total!</p>');
         //   document.getElementById("pagar_con").value=pagar_con;        
         //  Swal.fire({
         //   icon: 'error',
         //   title: 'Oops...',
         //   text: 'Error la cantidad con la que paga debe ser mayor o igual al total!',
         //   footer: ''
         // })
        // $('#exampleModal').modal('hide');
          $('#btn_facturar_venta').slideUp();    
           document.getElementById('pagar_con').focus();
           
         }
//   } 
 
}
});




function pagarcon(codcubo)
{
  //if (e.which == 13) {  
 
 
    pagar_con = document.getElementById("pagar_con"+codcubo).value.replace('$',''); //Quitamos el signo de pesos 
    total = document.getElementById("totalmodal"+codcubo).value.replace('$','');//Quitamos el signo de pesos 
 
    total=total.replace(',','');//Quitamos el la coma para poder hacer la operacion
    pagar_con=pagar_con.replace(',','');//Quitamos el la coma para poder hacer la operacion
 
 
    const cambio =(parseFloat(pagar_con)-parseFloat(total));
    tipopago= document.getElementById("tipopago"+codcubo).value;
    const tipoventa = document.getElementById("tipoven"+codcubo).value;    
 
    if(tipopago==1)
    {
      $('#btn_facturar_venta').slideUp();
          if (cambio >= 0 ) {    
             
          document.getElementById("cambio"+codcubo).value=MASK('', (cambio),'$##,###,##0.00',1);
          $('.alertCambio').html('<p style="color : red;"></p>');
          $('#btn_facturar_venta').slideDown();      
          } else {
            $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga debe ser mayor o igual al total!</p>');

           $('#btn_facturar_venta').slideUp();    
            document.getElementById('pagar_con'+codcubo).focus();
            
          }
 }else if (tipopago==2){
 
 
         
      

 }
}
 
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('input[type=text]').forEach( node => node.addEventListener('keypress', e => {
    if(e.keyCode == 13) {
      e.preventDefault();
    }
  }))
});







// GUARDAR NUEVO CORTE DE CAJA
$('#btn_guardarcorte').click(function(e) {
 e.preventDefault();
 var montoinicial = $('#montoinicial').val();

 var action = 'guardarCorte';
 $.ajax({
   url: 'modal.php',
   type: 'POST',
   async: true,
   data: {action:action,montoinicial: montoinicial},
   success: function(response) {
     if(response.includes('ok')==true){
       $('#abrircorte').modal('hide');
       Swal.fire({
         icon: 'success',
         title: 'Hecho!',
         text: 'Se ha registrado con éxito el nuevo corte de caja!',
         footer: ''
       })
       .then(() => {
         location.reload();
       })
       //location.reload();
     }else if(responde == 1){
       $('#abrircorte').modal('hide');
       Swal.fire({
         icon: 'error',
         title: 'Opss',
         text: 'No se puede hacer corte de caja sin registro de ventas.',
         footer: ''
       })
       .then(() => {
         location.reload();
       })
      // $('.alertAddProduct').html(response);
      // $("#abrircorte input").val("");
     }else{
       $('#abrircorte').modal('hide');
       Swal.fire({
         icon: 'error',
         title: 'Opss',
         text: 'Hubo un error, favor de intentarlo nuevamente.',
         footer: ''
       })
       .then(() => {
         location.reload();
       })
     }
     

   },
   error: function(error) {

   }
 });
 
});


jQuery('#abrircorte').on('hidden.bs.modal', function (e) {
 jQuery(this).removeData('bs.modal');
 jQuery(this).find('.alertAddProduct').empty();
});


// CERRAr CORTE DE CAJA
$('#btn_cerrarcorte').click(function(e) {
 e.preventDefault();
 var montoinicial = $('#montoinicial').val();
 var montofinal = $('#montofinal').val();
 var totalventas = $('#totalventas').val();
 var idcorte = $('#idcorte').val();
 var montogral = $('#montogral').val();

 var action = 'cerrarCorte';
 $.ajax({
   url: 'modal.php',
   type: 'POST',
   async: true,
   data: {action:action,montoinicial: montoinicial, montofinal:montofinal,totalventas:totalventas, idcorte:idcorte, montogral:montogral},
   success: function(response) {
     if(response.includes('ok')==true){
       //console.log(response);
       $('#cerrarcorte').modal('hide');
       Swal.fire({
         icon: 'success',
         title: 'Hecho!',
         text: 'Se ha cerrado con éxito el corte de caja!',
         footer: ''
       })
       .then(() => {
         location.reload();
       })
       //window.location='rep_cortecaja.php?idcorte='+idcorte;

     }else if(response.includes('1')){
       $('#cerrarcorte').modal('hide');
       Swal.fire({
         icon: 'error',
         title: 'Opss',
         text: 'No se puede hacer corte de caja sin registro de ventas.',
         footer: ''
       })
       .then(() => {
         location.reload();
       })
     }else{
       $('#cerrarcorte').modal('hide');
       Swal.fire({
         icon: 'error',
         title: 'Opss',
         text: 'Hubo un error, favor de intentarlo nuevamente.',
         footer: ''
       })
       .then(() => {
         location.reload();
       })
     }
     
    
   },
   error: function(error) {

   }
 });
 
});


 
//  $('.modal').on('hidden.bs.modal', function (e) {           
//   location.reload();  
// });

jQuery('#cerrarcorte').on('hidden.bs.modal', function (e) {
 jQuery(this).removeData('bs.modal');
 jQuery(this).find('.alertAddProduct').empty();
});


function abrirModalAbono(numcredito,total,saldo)
{
 
 
 $('#tipoven').val(2); 
 $('#tipoven').change();
 $('#numcredito').val(numcredito); 
 $('#totalmodalC').val( MASK('', (total),'$##,###,##0.00',1));  

  
 $('#saldo').val(MASK('', (saldo),'$##,###,##0.00',1));
 $('#divSaldo').slideDown();   
 $('#ventacontado').slideUp();  
 $('#ventacredito').slideDown();  
 $('#numcreditom').val(numcredito); 

 //$('#totalFIJO').val( MASK('', (total),'$##,###,##0.00',1));    
 $('#totalFIJO').val( MASK('', (saldo),'$##,###,##0.00',1));   
 $('#totalpagomixto').val(saldo);   

 $('#etiquetaPago').html("Saldo Credito");     
 $('#divFechaVencimientomixto').slideUp();
 $('#divFechaVencimiento').slideUp();
 $('#exampleModal').modal('show');


}

//EVALUAMOS QUE TIPO DE PAGO SERÁ
$('#tipopago').on('change', function() {

  var total=$('#totalmodal').val();
  var totalC=$('#totalmodalC').val();
 
  total=total.replace('$','');//Quitamos el signo de pesos 
  total=total.replace(',','');//Quitamos el la coma para poder hacer la operacion

  totalC=totalC.replace('$','');//Quitamos el signo de pesos 
  totalC=totalC.replace(',','');//Quitamos el la coma para poder hacer la operacion


 
  if(this.value=='2') //Evaluamos si es una tarjeta
 {
 
  if ($('#tipoven').val()==1)
      {
       total=parseFloat(total)+parseFloat ((total*5)/100);
       totalC=parseFloat(totalC)+parseFloat((totalC*5)/100);
     
       $('#ventacontado').slideDown();  
      $('#ventacredito').slideUp();   
      $('#referencia').slideDown(); 
      $('#divCambio').slideUp();
      $('#divCambioC').slideUp();
      $('#pagomixto').slideUp();  
      
      
     
          document.getElementById('totalmodal').value=MASK('', (total),'$##,###,##0.00',1);
          document.getElementById('totalmodalC').value=MASK('', (totalC),'$##,###,##0.00',1);
          document.getElementById('pagar_con').value=MASK('', (total),'$##,###,##0.00',1);
          document.getElementById('pagar_conC').value=MASK('', (totalC),'$##,###,##0.00',1);
          document.getElementById('pagar_con').disabled = true;
          document.getElementById('pagar_conC').disabled = true;
        
        var total=$('#totalmodal').val();
        var totalC=$('#totalmodalC').val(); 
      
        $('.alertCambio').html('<p style="color : red;">5% De comisión por pago con Tarjeta</p>'); 
         console.log("entro1");
      }else{
        var totalFijo=$('#totalFIJO').val();
        var totalFijoc=$('#totalFIJOc').val();
        document.getElementById('totalmodalC').value=MASK('', (totalFijo),'$##,###,##0.00',1);
        document.getElementById('totalmodal').value=MASK('', (totalFijo),'$##,###,##0.00',1);
        document.getElementById('pagar_conC').disabled = false;
        $('#ventacontado').slideUp();  
        $('#ventacredito').slideDown();  
        $('#referencia').slideDown(); 
        $('#divCambio').slideUp();
        $('#divCambioC').slideUp();
        $('#pagomixto').slideUp();  
        console.log("entro2");
      }
  

 } 
 else if(this.value=='1')  // su es tipo de pago efectivo
 { 
  console.log("entro5");
   if ($('#tipoven').val()==2)
      {
        console.log("entro3");
        var totalFijo=$('#totalFIJO').val();
        var totalFijoc=$('#totalFIJOc').val();
        $('#ventacontado').slideUp(); 
        $('#ventacredito').slideDown(); 
      }else{
        console.log("entro4");
        $('#ventacontado').slideDown();   
        $('#ventacredito').slideUp();  
      }
    $('#pagomixto').slideUp();   
    $('#referencia').slideUp();       
    $('#divCambio').slideDown();
    $('#divCambioC').slideDown(); 
  

   

   $('.alertCambio').html('<p style="color : red;"></p>');
  
    var totalFijo=$('#totalFIJO').val();
    var totalFijoc=$('#totalFIJOc').val();
 
    //  document.getElementById('totalmodal').value=MASK('', (totalFijo),'$##,###,##0.00',1);
    //  document.getElementById('totalmodalC').value=MASK('', (totalFijoc),'$##,###,##0.00',1);
   
    document.getElementById('pagar_con').value="0.00";
    document.getElementById('pagar_conC').value="0.00";
    document.getElementById('pagar_con').disabled = false;
    document.getElementById('pagar_conC').disabled = false;
   
   document.getElementById('totalmodalC').value=MASK('', (totalFijo),'$##,###,##0.00',1);
   document.getElementById('totalmodal').value=MASK('', (totalFijo),'$##,###,##0.00',1);
   //document.getElementById('totalmodalC').value=MASK('', (totalFijoc),'$##,###,##0.00',1);
     

 }
 
 else if(this.value=='5')  // su es tipo de pago mixto
 {   console.log("entro6");

  if($('#tipoven').val()==1)
  {
    $('#ventacontado').slideUp();    
    $('#ventacredito').slideUp();  
    $('#divFechaVencimientomixto').slideUp();
   $('#divnumcredito').slideUp();
   var totalFijo=$('#totalFIJO').val();
   //var totalFijoc=$('#totalFIJOc').val();
   document.getElementById('totalmodal').value=MASK('', (totalFijo),'$##,###,##0.00',1);
   //document.getElementById('totalmodalC').value=MASK('', (totalFijo),'$##,###,##0.00',1);
   console.log("entro7");
  }
  else
  {     console.log("entro8");
    $('#ventacontado').slideUp();    
   $('#ventacredito').slideUp();      
    
   var totalFijo=$('#totalFIJO').val();
  var totalFijoc=$('#totalFIJOc').val();

  if($('#saldo').val()!=0)
  {
    totalFijoc=$('#saldo').val();
    totalFijo=$('#saldo').val();
  }
   
  // var totalFijo=$('#totalFIJO').val();
  // var totalFijoc=$('#totalFIJOc').val();

   document.getElementById('totalmodal').value=MASK('', (totalFijo),'$##,###,##0.00',1);
   document.getElementById('totalmodalC').value=MASK('', (totalFijo),'$##,###,##0.00',1);

 
  }
  
  $('#pagomixto').slideDown();      
  
  $('#referencia').slideUp();   
  
  // var totalFijo=$('#totalFIJO').val();
  // var totalFijoc=$('#totalFIJOc').val();
 
  document.getElementById('totalpagomixto').value=MASK('', (totalFijo),'$##,###,##0.00',1);

 }
 
 else
 {
  console.log("entro9");
  var totalFijo=$('#totalFIJO').val();
  var totalFijoc=$('#totalFIJOc').val();
  $('.alertCambio').html('<p style="color : red;"></p>');       
  $('#divCambio').slideUp();
  $('#divCambioC').slideUp();
  $('#pagomixto').slideUp();  

  if($('#tipoven').val()==1)
  {
    console.log("entro10");
    $('#ventacredito').slideUp(); 
    $('#ventacontado').slideDown();  

    document.getElementById('pagar_con').value=MASK('', (totalFijo),'$##,###,##0.00',1);
    document.getElementById('pagar_conC').value=MASK('', (totalFijoc),'$##,###,##0.00',1);
    document.getElementById('pagar_con').disabled = true;
    document.getElementById('pagar_conC').disabled = true;

    document.getElementById('totalmodal').value=MASK('', (totalFijo),'$##,###,##0.00',1);
  }
  else
  {  console.log("entro11");
    $('#ventacredito').slideDown(); 
    $('#ventacontado').slideUp();   
      
    document.getElementById('pagar_con').value="0.00";
    document.getElementById('pagar_conC').value="0.00";
    document.getElementById('pagar_con').disabled = false;
    document.getElementById('pagar_conC').disabled = false;
   
   document.getElementById('totalmodalC').value=MASK('', (totalFijo),'$##,###,##0.00',1);
  }
   
  $('#referencia').slideDown();  




  // if($('#tipoven').val()==1)
  // {    
  //   document.getElementById('pagar_con').value=MASK('', (totalFijo),'$##,###,##0.00',1);
  //   document.getElementById('pagar_conC').value=MASK('', (totalFijoc),'$##,###,##0.00',1);
  //   document.getElementById('pagar_con').disabled = true;
  //   document.getElementById('pagar_conC').disabled = true;
  // }
  // else
  // {
  //   document.getElementById('pagar_con').value="0.00";
  //   document.getElementById('pagar_conC').value="0.00";
  //   document.getElementById('pagar_con').disabled = false;
  //   document.getElementById('pagar_conC').disabled = false;

  // }
}

});

function tipopagocubo(codcubo)
{
  
  //EVALUAMOS QUE TIPO DE PAGO SERÁ
  cubocodigo=codcubo;
 valor=   $('#tipopago'+codcubo).val();
 var total=$('#totalmodal'+codcubo).val();
 var totalFIJO=$('#totalFIJO'+codcubo).val();
 total=total.replace('$','');//Quitamos el signo de pesos 
 total=total.replace(',','');//Quitamos el la coma para poder hacer la operacion

 totalFIJO=totalFIJO.replace('$','');//Quitamos el signo de pesos 
 totalFIJO=totalFIJO.replace(',','');//Quitamos el la coma para poder hacer la operacion

 var mesesxadelantar=$('#adelantar'+codcubo).val();
 

  if(valor=='1')
  { 
     if(mesesxadelantar==0)
      {
        renta= totalFIJO;
      }else
      {
        renta=parseFloat(totalFIJO*mesesxadelantar);
      }

    document.getElementById('totalmodal'+codcubo).value=renta;
    $('#referencia'+codcubo).slideUp();       
    $('#divCambio'+codcubo).slideDown();
    $('#divCambioC'+codcubo).slideDown();
    $('#pagomixtocubos'+codcubo).slideUp(); 

    document.getElementById('pagar_con'+codcubo).value="0.00";
    //document.getElementById('pagar_conC'+codcubo).value="0.00";
    $('pagar_con'+codcubo).removeAttr("readonly");
  
 }  
  else{  
    $('#pagomixtocubos'+codcubo).slideUp(); 
    $('#referencia'+codcubo).slideDown(); 
    $('#divCambio'+codcubo).slideUp();
    $('#divCambioC'+codcubo).slideUp();
    
    if(mesesxadelantar==0)
    {
      
      total= totalFIJO;
    }else
    {
      total=parseFloat(totalFIJO*mesesxadelantar);
    }
    
    
  if (valor==2)
  ///var total=$('#totalmodal'+codcubo).val();
  {
 
    total=parseFloat(total)+parseFloat ((total*5)/100);    

    document.getElementById('totalmodal'+codcubo).value=MASK('', (total),'$##,###,##0.00',1);

    document.getElementById('pagar_con'+codcubo).value=MASK('', (total),'$##,###,##0.00',1);
    document.getElementById('totalpagomixtocubos'+codcubo).value=MASK('', (total),'$##,###,##0.00',1);
    $('.alertCambio').html('<p style="color : red;">5% De comisión por pago con Tarjeta</p>'); 
  
    $('#pagomixtocubos'+codcuboo).slideUp();
   

  } else if (valor==5)
  ///var total=$('#totalmodal'+codcubo).val();
  {
    $('#referencia'+codcubo).slideUp(); 
    $('#pagomixtocubos'+codcubo).slideDown(); 
    document.getElementById('totalpagomixtocubos'+codcubo).value=MASK('', (total),'$##,###,##0.00',1);
  }
  else
  {
    $('#pagomixtocubos'+codcubo).slideUp();
  
    document.getElementById('totalmodal'+codcubo).value=MASK('', (total),'$##,###,##0.00',1);
    document.getElementById('pagar_con'+codcubo).value=MASK('', (total),'$##,###,##0.00',1);
    document.getElementById('totalpagomixtocubos'+codcubo).value=MASK('', (total),'$##,###,##0.00',1);
    $('.alertCambio').html('<p style="color : red;"></p>'); 
  
  }
 


  
  
  //  if ($('#tipoven'+codcubo).val()==1)
  // {
  //   document.getElementById('pagar_con'+codcubo).value=total;
   
  
  //   $('pagar_con'+codcubo).attr("readonly","readonly");
  //   }
  }

}



// ingresar abono
$('#form_new_abono_creditos').submit(function(e) {  
  e.preventDefault();
  $.ajax({
    url: 'modal.php',
    type: "POST",
    async: true,
    data: $('#form_new_abono_creditos').serialize(),
    success: function(response) {
     //console.log(response);
     //  // Agregar id a inp
      if (response  != 0) {
      
         location.reload();
         $('#abrirAbonos').click();  
      }
    },
    error: function(error) {
    }
  });
});

function MASK(form, n, mask, format) {
 if (format == "undefined") format = false;
 if (format || NUM(n)) {
   dec = 0, point = 0;
   x = mask.indexOf(".")+1;
   if (x) { dec = mask.length - x; }

   if (dec) {
     n = NUM(n, dec)+"";
     x = n.indexOf(".")+1;
     if (x) { point = n.length - x; } else { n += "."; }
   } else {
     n = NUM(n, 0)+"";
   } 
   for (var x = point; x < dec ; x++) {
     n += "0";
   }
   x = n.length, y = mask.length, XMASK = "";
   while ( x || y ) {
     if ( x ) {
       while ( y && "#0.".indexOf(mask.charAt(y-1)) == -1 ) {
         if ( n.charAt(x-1) != "-")
           XMASK = mask.charAt(y-1) + XMASK;
         y--;
       }
       XMASK = n.charAt(x-1) + XMASK, x--;
     } else if ( y && "$0".indexOf(mask.charAt(y-1))+1 ) {
       XMASK = mask.charAt(y-1) + XMASK;
     }
     if ( y ) { y-- }
   }
 } else {
    XMASK="";
 }
 if (form) { 
   form.value = XMASK;
   if (NUM(n)<0) {
     form.style.color="#FF0000";
   } else {
     //form.style.color="#000000";
   }
 }
 return XMASK;
}
function NUM(s, dec) {
 for (var s = s+"", num = "", x = 0 ; x < s.length ; x++) {
   c = s.charAt(x);
   if (".-+/*".indexOf(c)+1 || c != " " && !isNaN(c)) { num+=c; }
 }
 if (isNaN(num)) { num = eval(num); }
 if (num == "")  { num=0; } else { num = parseFloat(num); }
 if (dec != undefined) {
   r=.5; if (num<0) r=-r;
   e=Math.pow(10, (dec>0) ? dec : 0 );
   return parseInt(num*e+r) / e;
 } else {
   return num;
 }
}


// buscar producto para ajuste de inventario
$('#cod_pro').keyup(function(e) {
 e.preventDefault();
 var productos = $(this).val();


 if (productos == "") {
   $('#txt_descripcion').html('-');
   $('#txt_existencia').html('-');
   $('#txt_cant_producto').val('0');
   $('#txt_precio').html('0.00');
   $('#txt_precio_total').html('0.00');

   //Bloquear Cantidad
   $('#txt_cant_producto').attr('disabled', 'disabled');
   // Ocultar Boto Agregar
   $('#add_product_venta').slideUp();
 }

 var action = 'infoProducto';
 if (productos != '') {
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action,producto:productos},
   success: function(response){
     if(response == 0) {
       $('#txt_descripcion').html('-');
       $('#txt_existencia').html('-');
       $('#txt_cant_producto').val('0');
       $('#txt_precio').html('0.00');
       $('#txt_precio_total').html('0.00');

       //Bloquear Cantidad
       $('#txt_cant_producto').attr('disabled','disabled');
       // Ocultar Boto Agregar
       $('#add_product_venta').slideUp();


     }else{
       var info = JSON.parse(response);
       if (info.existencia < 1) {
         $('#name').val(info.descripcion);
         $('#cantidad').val(info.existencia);         
         //Bloquear Cantidad
         $('#txt_cant_producto').attr('disabled', 'disabled');
         // Ocultar Boto Agregar
         $('#add_product_venta').slideUp();
       }else{
         $('#name').val(info.descripcion);
         $('#cantidad').val(info.existencia);
         // Activar Cantidad
         $('#txt_cant_producto').removeAttr('disabled');
         // Mostar boton Agregar
         $('#add_product_venta').slideDown();
       }

     }
   },
   error: function(error) {
   }
 });

 //Bloquear Cantidad
 $('#txt_cant_producto').attr('disabled','disabled');
 // Ocultar Boto Agregar
 $('#add_product_venta').slideUp();

 ////
 }


});

// AJUSTE DE INVENTARIO
$('#btn_guardarajuste').click(function(e) {
 e.preventDefault();
 var cod_pro = $('#cod_pro').val();
 var name = $('#name').val();
 var cantidad = $('#cantidad').val();
 var agregar = $('#agregar').val();

 var action = 'guardarAjuste';

 if(cantidad == 0 && agregar < 0){
   $('#ajusteinventario').modal('hide');
   Swal.fire({
     icon: 'error',
     title: 'Opss',
     text: 'No puedes hacer salidas de un producto sin stock!',
     footer: ''
   })
   .then(() => {
     location.reload();
   })
  
 }else{

   $.ajax({
     url: 'modal.php',
     type: 'POST',
     async: true,
     data: {action:action,cod_pro:cod_pro, name:name,cantidad:cantidad, agregar:agregar},
     success: function(response) {
       
       if(response.includes('ok')==true){
         //console.log(response);
         $('#ajusteinventario').modal('hide');
         Swal.fire({
           icon: 'success',
           title: 'Hecho!',
           text: 'Se ha registrado con éxito el ajuste!',
           footer: ''
         })
         .then(() => {
           location.reload();
         })
         
       
         //window.location='ajuste_inventario.php';
         
         

       }else{
         $('#ajusteinventario').modal('hide');
         
         Swal.fire({
           icon: 'error',
           title: 'Opss',
           text: 'Hubo un error, favor de intentarlo de nuevo.',
           footer: ''
         })
         .then(() => {
           location.reload();
         })
       }
       
     
     },
     error: function(error) {

     }
   });
}
 
});

// canidad del producto
$('#txt_cant_producto').keyup(function() {
 
 if(  parseInt($('#txt_cant_producto').val())> parseInt($('#txt_existencia').html()))
 {
   Swal.fire({
     icon: 'error',
     title: 'Oops...',
     text: 'La cantidad de producto no puede ser mayor a la cantidad en existencia.',
     footer: ''
   })
  $('#txt_cant_producto').val("");
 }
 var existencia= parseInt($('#txt_existencia').html());
 var action = 'productoDetalleValida';
 var codproducto = $('#txt_cod_producto').val();   
 var cantidad=$('#txt_cant_producto').val();
 $.ajax({
   url: 'modal.php',
   type: 'POST',
   async: true,
   data: {action:action,producto:codproducto},
   success: function(response) {  
     //console.log(response);
     //console.log(parseInt(response)+parseInt(cantidad))  ;
    if((parseInt(response)+parseInt(cantidad))>parseInt(existencia))
    {
     Swal.fire({
       icon: 'error',
       title: 'Oops...',
       text: '¡Ya no puede agegar mas productos, revasaria el stock exitente!',
       footer: ''
     });
     return;
   }
    
    
   },
   error: function(error) {
 
   }
 });



});





jQuery('#ajusteinventario').on('hidden.bs.modal', function (e) {
 jQuery(this).removeData('bs.modal');
 //jQuery(this).find('.alertAddProduct').empty();
});

$('#subtotal').keyup(function(e) {
 e.preventDefault();
 var suma = 0;
 var subtotal = $('#subtotal').val();
 if(subtotal == ''){
   subtotal = 0;
 }
 var res = (parseFloat(subtotal) *.16);
 /*if(iva == ''){
   iva = 0;
 }else{
   iva = iva;
 }*/
 $('#iva').val(res);
 var iva = $('#iva').val();
 suma = (parseFloat(subtotal) + parseFloat(iva));
 $('#total').val(suma);
});

$('#iva').keyup(function(e) {
 e.preventDefault();
 var suma = 0;
 var subtotal = $('#subtotal').val();
 if(subtotal == ''){
   subtotal = 0;
 }
 var iva = $('#iva').val();
 if(iva == ''){
   iva = 0;
 }
 suma = (parseFloat(subtotal) + parseFloat(iva));
 $('#total').val(suma);
});

/*function calcular_total(){
 var suma = 0;
 var subtotal = $('#subtotal').val();
 var iva = $('#iva').val();
 suma = (parseFloat(subtotal) + parseFloat(iva));
 document.getElementById("total").value=MASK('', (suma),'$##,###,##0.00',1);
 //$('#total').val(suma);
 
}*/





function seleccionarCliente(dnicliente)
{

 cl=dnicliente;  
 var action = 'searchCliente';
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action,cliente:cl},
   success: function(response) {
     if (response == 0) {
       $('#idcliente').val('');
       $('#nom_cliente').val('');
       $('#tel_cliente').val('');
       $('#dir_cliente').val('');
       // mostar boton agregar
       $('.btn_new_cliente').slideDown();
     }else {
       var data = $.parseJSON(response);
       $('#dni_cliente').val(data.dni);
       $('#idcliente').val(data.idcliente);
       $('#nom_cliente').val(data.nombre);
       $('#tel_cliente').val(data.telefono);
       $('#dir_cliente').val(data.direccion);
       cl=data.idcliente;
     
       var action = 'searchClienteCredito';
       $.ajax({
         url: 'modal.php',
         type: "POST",
         async: true,
         data: {action:action,cliente:cl},
         success: function(response) {
           var info = $.parseJSON(response);
           $('#divCreditos').html(info.detalle);
         },
         error: function(error) {
     
         }
       });


       // ocultar boton Agregar
       $('.btn_new_cliente').slideUp();
       $('#btnBuscarCliente').slideUp();
       // Bloque campos
       $('#nom_cliente').attr('disabled','disabled');
       $('#tel_cliente').attr('disabled','disabled');
       $('#dir_cliente').attr('disabled','disabled');
       // ocultar boto Guardar
       $('#div_registro_cliente').slideUp();
       $('#modalBusquedacliente').modal('hide');
     }

   },
   error: function(error) {

   }
 });





}


$('#rand').click(function(e) {
 e.preventDefault();

 let cadena = 0;
 let n1 = random(2, 9) + '';;
 let n2 = random(2, 9) + '';;
 let n3 = random(2, 9) + '';;
 let n4 = random(2, 9) + '';;
 let n5 = random(2, 9) + '';;
 let n6 = random(2, 9) + '';;
 let n7 = random(2, 9) + '';;
 let n8 = random(2, 9) + '';;

cadena = n1+n2+n3+n4+n5+n6+n7+n8;
 $('#data').val(cadena);

});

function random(min, max) {
 return Math.floor(Math.random() * (max - min + 1) + min);
}

$('input[type=radio][name=clasificacion]').change(function() {
 var idclasificacion = $(this).val();
 console.log(idclasificacion);
if(idclasificacion==1)
{ 
 $('#labelcodigo').html("Codigo del Cubo");
console.log("");
 action='cargocubo';
$.ajax({
 url: "cargar_cubos.php",
 type: "post",
 success: function(data){
     $('#cubo').html(data+"\n");
 }
});


$('#cubo').slideDown();
 $('#prod').slideUp();  
 $("#codigo").attr("placeholder", "Ingrese el codigo del cubo");

}else if(idclasificacion==3)
{ 
 $('#labelcodigo').html("Codigo del Cubo");
console.log("");
 action='cargocubo';
$.ajax({
 url: "cargar_cubosdisponibles.php",
 type: "post",
 success: function(data){
     $('#cubo').html(data+"\n");
 }
});


$('#cubo').slideDown();
 $('#prod').slideUp();  
 $("#codigo").attr("placeholder", "Ingrese el codigo del cubo");

}

else{
 $('#cubo').slideUp();
 $('#prod').slideDown(); 
 $('#labelcodigo').html("Codigo del Producto");
 $("#codigo").attr("placeholder", "Ingrese el codigo del producto");
}
});


$('input[type=radio][name=tipo]').change(function() {
 var tipo = $(this).val();

if(tipo==1)
{ 
 $('#labelpromocion').html("Porcentaje");
 $("#promocion").attr("placeholder", "Ingrese el porcentaje");

}

else{

 $('#labelpromocion').html("Precio");
 $("#promocion").attr("placeholder", "Ingrese el precio");
}
});

$('#cubop').change(function(e) {
 e.preventDefault();
  $('#nom').val('');
 $('#numsig').val('');

 var idcubo = $('#cubop').val();
 $('#codigo').val(' ');
 
 var action = 'nomenclatura';

 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action, idcubo: idcubo},
   success: function(response) {
     //console.log(response);
     document.getElementById('nom').value = response; 
     
   },
   error: function(error) {
   }
 });

 var action = 'signumero';
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action, idcubo: idcubo},
   success: function(response) {
     //console.log(response);
      document.getElementById('numsig').value = response;
     
      var nomenclatura = $('#nom').val();
      var numsig = $('#numsig').val();
      $('#codigo').val(nomenclatura+'-'+numsig);
   },
   error: function(error) {
   }
 });

 
 //validar que no tenga arrendatario el cubo
 var action = 'buscarArrendatario';
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action, idcubo: idcubo},
   success: function(response) {
     if(response.trim() == '-1'){
       Swal.fire({
         icon: 'error',
         title: 'Oops...',
         text: '¡No es posible agregar productos a este cubo por que no esta asociado a un arrendador!',
         footer: ''
       });
       $('#guardarProductobtn').attr('disabled', 'disabled');
     }

     
     
   },
   error: function(error) {
   }
 });

 
 


});

function seleccionarProducto(idproducto)
{
 console.log("entro");
 $('#codigopro').val(idproducto);
 $('#modalBusquedaProducto').modal('hide');
}


$('#add_product_salida').click(function(e) {
 e.preventDefault(); 

 if ($('#txt_cant_producto').val() > 0) {
   var existencia= parseInt($('#txt_existencia').html());
   var action = 'productoDetalleValidaSalida';
   var codproducto = $('#txt_cod_producto').val();   
   var cantidad=$('#txt_cant_producto').val();
   $.ajax({
     url: 'modal.php',
     type: 'POST',
     async: true,
     data: {action:action,producto:codproducto},
     success: function(response) {    
       
      //console.log(response);
      //console.log((parseInt(response)+parseInt(cantidad))>parseInt(existencia));
      if((parseInt(response)+parseInt(cantidad))>parseInt(existencia))
      {
       Swal.fire({
         icon: 'error',
         title: 'Oops...',
         text: '¡Ya no puede agregar mas productos, revasaria el stock exitente!',
         footer: ''
       });
       return;
     }
      
      
     },
     error: function(error) {
   
     }
   });


   var action = 'addProductoSalida';
   $.ajax({
     url: 'modal.php',
     type: 'POST',
     async: true,
     data: {action:action,producto:codproducto,cantidad:cantidad},
     success: function(response) {    
        
       //alert(response);
       if (response != 'error') {   

         var info = JSON.parse(response);

         if(info.detalle == '0'){
           Swal.fire({
             icon: 'error',
             title: 'Opss',
             text: 'No puedes agregar productos de diferentes cubos en una misma salida!.',
             footer: ''
           });
         }else{
        
           $('#detalle_venta_salida').html(info.detalle);
           // Ocultar boton agregar
           $('#add_product_salida').slideUp();
         }
       }else {
         console.log('No hay dato');
       }
       viewProcesar();
     },
     error: function(error) {

     }
   });
 }
});

// buscar producto = Ventas
$('#cod_pro').keyup(function(e) {
 e.preventDefault();
 
 var productos = $(this).val();
 if (productos == "") {
   $('#txt_descripcion').html('-');
   $('#txt_existencia').html('-');
   $('#txt_cant_producto').val('0');
  
   //Bloquear Cantidad
   $('#txt_cant_producto').attr('disabled', 'disabled');
   // Ocultar Boto Agregar
   $('#add_product_salida').slideUp();
 }

 var action = 'infoProducto2';
 if (productos != '') {
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action,producto:productos},
   success: function(response){
     console.log(response); 
     
     if(response == 0) {
    
       $('#txt_descripcion').html('-');
       $('#txt_existencia').html('-');
       $('#txt_cant_producto').val('0');
      
       //Bloquear Cantidad
       $('#txt_cant_producto').attr('disabled','disabled');
       // Ocultar Boto Agregar
       $('#add_product_salida').slideUp();
     }else{
       
       var info = JSON.parse(response);
       
       if (info.existencia < 1) {
       
         $('#txt_descripcion').html(info.descripcion);
         $('#txt_cod_producto').val(info.codproducto);
         $('#txt_existencia').html(info.existencia);
         
         //Bloquear Cantidad
         $('#txt_cant_producto').attr('disabled', 'disabled');
         // Ocultar Boto Agregar
         $('#add_product_salida').slideUp();
       }else{
         
         $('#txt_descripcion').html(info.descripcion);
         $('#txt_existencia').html(info.existencia);         
         $('#txt_cod_producto').val(info.codproducto);
         
        
         // Activar Cantidad
         $('#txt_cant_producto').removeAttr('disabled');
         // Mostar boton Agregar
         $('#add_product_salida').slideDown();
       }

     }
   },
   error: function(error) {
   }
 });

 }


});

// mostrar/ ocultar boton Procesar
function viewProcesar() {
 if ($('#detalle_venta_salida tr').length > 0){
   $('#procesarVentaSalida').show();
   $('#btn_anular_venta_salida').show();
 }else {
   $('#procesarVentaSalida').hide();
   $('#btn_anular_venta_salida').hide();
 }
}

/*
$('#procesarVentaSalida').click(function(e) {
 e.preventDefault();
 var rows = $('#detalle_venta_salida tr').length;  
 if (rows > 0) {
   window.location.href = 'salidas_pdf.php';
 }
});*/


function eliminar_salida(correlativo) {
 var action = 'delProductoDetalleSalida';
 var id_detalle = correlativo;
 $.ajax({
   url: 'modal.php',
   type: "POST",
   async: true,
   data: {action:action,id_detalle:id_detalle},
   success: function(response) {
    // console.log(response);
       if (response != 0) {
       
        
       var info = JSON.parse(response);
       $('#detalle_venta_salida').html(info.detalle);
       
       // Bloquear cantidad
       $('#txt_cant_producto').attr('disabled','disabled');

       // Ocultar boton agregar
       $('#add_product_venta').slideUp();
     }else {
       $('#detalle_venta_salida').html('');
     

     }
     viewProcesar();
   },
   error: function(error) {
     
   }
 });
}

$('#btn_anular_venta_salida').click(function(e) {
 e.preventDefault();
 var rows = $('#detalle_venta_salida tr').length;  
 var action = 'eliminarSalidas';
 
 if(rows == 0){
   Swal.fire({
     icon: 'error',
     title: 'Opss',
     text: 'No hay registros que anular!.',
     footer: ''
   });
 }else{
   $.ajax({
     url: 'modal.php',
     type: "POST",
     async: true,
     data: {action:action},
     success: function(response) {
     //alert(response);
       if (response.includes('ok')==true) {
         Swal.fire({
           icon: 'success',
           title: 'Hecho!',
           text: 'Se ha anulado la salida de productos con éxito!',
           footer: ''
         });
         $('#detalle_venta_salida').html('');
      

       }else {
         Swal.fire({
           icon: 'error',
           title: 'Opss',
           text: 'Ocurrio un error, intente nuevamente!.',
           footer: ''
         });
         $('#detalle_venta_salida').html('');
       
 
       }
       viewProcesar();
     },
     error: function(error) {
       
     }
   });
 }
 
});


function adelantarMeses(codcubo)
{
  var total=$('#totalmodal'+codcubo).val();
  var totalFIJO=$('#totalFIJO'+codcubo).val();
  var mesesxadelantar=$('#adelantar'+codcubo).val();
  total=total.replace('$','');//Quitamos el la coma para poder hacer la operacion
  total=total.replace(',','');//Quitamos el la coma para poder hacer la operacion
  totalFIJO=totalFIJO.replace('$','');//Quitamos el la coma para poder hacer la operacion
  totalFIJO=totalFIJO.replace(',','');//Quitamos el la coma para poder hacer la operacion
  console.log(total);
  if($('#tipopago'+codcubo).val()==2)
  {
    if(mesesxadelantar==0)
    {
      renta= total;
    }else
    {
      renta=parseFloat(total*mesesxadelantar);
    }
  }else
  {
    if(mesesxadelantar==0)
    {
      renta= totalFIJO;
    }else
    {
      renta=parseFloat(totalFIJO*mesesxadelantar);
    }
  }


 document.getElementById('totalmodal'+codcubo).value=MASK('', (renta),'$##,###,##0.00',1);
 document.getElementById('totalpagomixtocubos'+codcubo).value=MASK('', (renta),'$##,###,##0.00',1);
 if($('#tipopago'+codcubo).val()!=1){
 document.getElementById('pagar_con'+codcubo).value=MASK('', (renta),'$##,###,##0.00',1);
 document.getElementById('totalpagomixtocubos'+codcubo).value=MASK('', (renta),'$##,###,##0.00',1);
 }else
 {
  document.getElementById('pagar_con'+codcubo).value=MASK('', (0),'$##,###,##0.00',1);
  document.getElementById('totalpagomixtocubos'+codcubo).value=MASK('', (renta),'$##,###,##0.00',1);
 }




}

 $("#pefectivo").keyup(function () {
  

  var totalFijo=$('#totalFIJO').val();
  totalFijo=totalFijo.replace('$','');//Quitamos el la coma para poder hacer la operacion
  totalFijo=totalFijo.replace(',','');//Quitamos el la coma para poder hacer la operacion 
  
  var pefectivo = $(this).val();
  var ptarjeta=$('#ptarjeta').val();
  var ptransferencia=$('#ptransferencia').val();
  var pdeposito=$('#pdeposito').val();

  if(pefectivo==''){pefectivo=0;}
  if(ptarjeta==''){ptarjeta=0;}
  if(ptransferencia==''){ptransferencia=0;}
  if(pdeposito==''){pdeposito=0;}

  var nuevovalor=parseFloat(totalFijo)-parseFloat(pefectivo)-parseFloat(ptarjeta)-parseFloat(ptransferencia)-parseFloat(pdeposito);
  var suma=parseFloat(pefectivo)+parseFloat(ptarjeta)+parseFloat(ptransferencia)+parseFloat(pdeposito);
  
  
  if(suma>totalFijo)
  {   
    $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga no debe ser mayor al total!</p>');
   
    $(this).val('');   
    // $('#ptarjeta').val('');
    // $('#ptransferencia').val('');
    // $('#pdeposito').val('');
    $("#saldopmixto").val(0);
  }else{
    $('.alertCambio').html('<p style="color : red;"></p>');
  $("#saldopmixto").val(nuevovalor);
  }
});

$("#ptarjeta").keyup(function () {
 
  
  var totalFijo=$('#totalFIJO').val();
  totalFijo=totalFijo.replace('$','');//Quitamos el la coma para poder hacer la operacion
  totalFijo=totalFijo.replace(',','');//Quitamos el la coma para poder hacer la operacion 
  
 
  var pefectivo=$('#pefectivo').val();
  var ptarjeta = $(this).val();
  var ptransferencia=$('#ptransferencia').val();
  var pdeposito=$('#pdeposito').val();

  if(pefectivo==''){pefectivo=0;}
  if(ptarjeta==''){ptarjeta=0;}
  if(ptransferencia==''){ptransferencia=0;}
  if(pdeposito==''){pdeposito=0;}
  
  var nuevovalor=parseFloat(totalFijo)-parseFloat(pefectivo)-parseFloat(ptarjeta)-parseFloat(ptransferencia)-parseFloat(pdeposito);
  var suma=parseFloat(pefectivo)+parseFloat(ptarjeta)+parseFloat(ptransferencia)+parseFloat(pdeposito);

  if(suma>totalFijo)
  {   
    $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga no debe ser mayor al total!</p>');
   
    // $('#pefectivo').val('');
    $(this).val('');   
    // $('#ptransferencia').val('');
    // $('#pdeposito').val('');
    $("#saldopmixto").val(0);

  }else{
    $('.alertCambio').html('<p style="color : red;"></p>');
  $("#saldopmixto").val(nuevovalor);
  }
});

$("#ptransferencia").keyup(function () {
 
  
  var totalFijo=$('#totalFIJO').val();
  totalFijo=totalFijo.replace('$','');//Quitamos el la coma para poder hacer la operacion
  totalFijo=totalFijo.replace(',','');//Quitamos el la coma para poder hacer la operacion 

 
  var pefectivo=$('#pefectivo').val();
  var ptarjeta=$('#ptarjeta').val();
  var ptransferencia = $(this).val();
  var pdeposito=$('#pdeposito').val();

  if(pefectivo==''){pefectivo=0;}
  if(ptarjeta==''){ptarjeta=0;}
  if(ptransferencia==''){ptransferencia=0;}
  if(pdeposito==''){pdeposito=0;}
  

  var nuevovalor=parseFloat(totalFijo)-parseFloat(pefectivo)-parseFloat(ptarjeta)-parseFloat(ptransferencia)-parseFloat(pdeposito);
  var suma=parseFloat(pefectivo)+parseFloat(ptarjeta)+parseFloat(ptransferencia)+parseFloat(pdeposito);

  if(suma>totalFijo)
  {   
    $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga no debe ser mayor al total!</p>');
    //  $('#pefectivo').val('');
    //  $('#ptarjeta').val('');
    //  $('#pdeposito').val('');
     $(this).val('');
     $("#saldopmixto").val(0);
  }else{
    $('.alertCambio').html('<p style="color : red;"></p>');
  $("#saldopmixto").val(nuevovalor);
  }
});

$("#pdeposito").keyup(function () {
 
  
  var totalFijo=$('#totalFIJO').val();
  totalFijo=totalFijo.replace('$','');//Quitamos el la coma para poder hacer la operacion
  totalFijo=totalFijo.replace(',','');//Quitamos el la coma para poder hacer la operacion 

  

  var pefectivo=$('#pefectivo').val();
  var ptarjeta=$('#ptarjeta').val();
  var ptransferencia=$('#ptransferencia').val();
  var pdeposito = $(this).val();

  if(pefectivo==''){pefectivo=0;}
  if(ptarjeta==''){ptarjeta=0;}
  if(ptransferencia==''){ptransferencia=0;}
  if(pdeposito==''){pdeposito=0;}

  var nuevovalor=parseFloat(totalFijo)-parseFloat(pefectivo)-parseFloat(ptarjeta)-parseFloat(ptransferencia)-parseFloat(pdeposito);
  var suma=parseFloat(pefectivo)+parseFloat(ptarjeta)+parseFloat(ptransferencia)+parseFloat(pdeposito);
console.log(suma);
console.log('entrnonos');
console.log(totalFijo);
  if(suma>totalFijo)
  {   
    $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga no debe ser mayor al total!</p>');

    //  $('#pefectivo').val('');
    //  $('#ptarjeta').val('');
    //  $('#ptransferencia').val('');
     $(this).val('');
     $("#saldopmixto").val(0);
  }else{
    $('.alertCambio').html('<p style="color : red;"></p>');
  $("#saldopmixto").val(nuevovalor);
  }
});

// $('[name="pagom[]"]').click(function() {
  
//   if( $(this).is(':checked') ){
    
//     if($(this).val()==1)
//     {
//       $("#pefectivo").show();
//     }
//     else if($(this).val()==2)
//     {
//       $("#ptarjeta").show();
//       $('#etiquetat').val('');
//       $('#etiquetatarjeta').show();
//       $('#etiquetat').show();
//     }
//     else if($(this).val()==3)
//     {
//       $("#ptransferencia").show();
//     }
//     else if($(this).val()==4)
//     {
//       $("#pdeposito").show();
//     }

  //}
//   if( $(this).is(':unchecked') ){
      
   
//     if($(this).val()==1)
//     {
//       $('#pefectivo').val('');
//       $("#pefectivo").hide();
//     }
//     else if($(this).val()==2)
//     { 
//       $('#ptarjeta').val('');
//       $('#ptarjeta').hide();     
//       $('#etiquetatarjeta').hide();
//       $('#etiquetat').hide();
//     }
//     else if($(this).val()==3)
//     { 
//       $('#ptransferencia').val('');
//       $('#ptransferencia').hide();
      
//     }
//     else 
//     { $('#pdeposito').val('');
//     $('#pdeposito').hide();
//     }

//   }

// });


$('[name="pagomv[]"]').click(function() {
  
  if( $(this).is(':checked') ){
    
    if($(this).val()==1)
    {
      $("#pefectivo").show();
    }
    else if($(this).val()==2)
    {
      $("#ptarjeta").show();
      $('#etiquetat').val('');
      $('#etiquetatarjeta').show();
      $('#etiquetat').show();
    }
    else if($(this).val()==3)
    {
      $("#ptransferencia").show();
    }
    else if($(this).val()==4)
    {
      $("#pdeposito").show();
    }

  }
  if( $(this).is(':unchecked') ){
      
    
    if($(this).val()==1)
    {
      $('#pefectivo').val('');
      $("#pefectivo").hide();
    }
    else if($(this).val()==2)
    { 
      $('#ptarjeta').val('');
      $('#ptarjeta').hide();     
      $('#etiquetatarjeta').hide();
      $('#etiquetat').hide();
    }
    else if($(this).val()==3)
    { 
      $('#ptransferencia').val('');
      $('#ptransferencia').hide();
      
    }
    else 
    { $('#pdeposito').val('');
    $('#pdeposito').hide();
    }

  }

});
// $('#ptarjeta').keyup(function(e) {
//   e.preventDefault();
//   valor=$('#ptarjeta').val();
//   total=parseFloat(valor)+parseFloat ((valor*5)/100);
//   $('#etiquetatarjeta').html("$"+total);
//   //$('#etiquetatarjeta').html('Hola yo voy dentro del label!');

// });




 $(document).ready(function() {
 
 
   // Comprobar cuando cambia un checkbox
    $('input[type=checkbox]').on('change', function() {
    
        if ($(this).is(':checked') ) {
          console.log('ebtro1');
        seleccion=$(this).prop("id");    
        console.log(seleccion);
       
        if (seleccion.includes("pagomcubos")==true)
        { console.log('ebtro');
        idcubo= seleccion.substring(10,  (seleccion.length-2));  
        console.log(idcubo);
        $('[name="'+seleccion+'"]').click(function() {    
          console.log('ok');
            if( $(this).is(':checked') ){
              console.log($(this)+'this');
              console.log('selecionado1');
                  if($(this).val()==1)
                  {
                    $("#pefectivoc"+idcubo).show();
                  }
                   else if($(this).val()==2)
                  {
                    $("#ptarjetac"+idcubo).show();
                    $('#etiquetat'+idcubo).val('');
                    $('#etiquetatarjetac'+idcubo).show();
                    $('#etiquetat'+idcubo).show();
                  }
                  else if($(this).val()==3)
                  {
                    $("#ptransferenciac"+idcubo).show();
                  }
                  else if($(this).val()==4)
                  {
                    $("#pdepositoc"+idcubo).show();
                  }
                  
              
            }
            if( $(this).is(':unchecked') ){
                    
              console.log('deseleccionado');
                  if($(this).val()==1)
                  {
                    $('#pefectivoc'+idcubo).val('');
                    $("#pefectivoc"+idcubo).hide();
                  }
                  else if($(this).val()==2)
                  { 
                    $('#ptarjetac'+idcubo).val('');
                    $('#ptarjetac'+idcubo).hide();     
                    $('#etiquetatarjeta'+idcubo).hide();
                    $('#etiquetat'+idcubo).hide();
                  }
                  else if($(this).val()==3)
                  { 
                    $('#ptransferenciac'+idcubo).val('');
                    $('#ptransferenciac'+idcubo).hide();
                    
                  }
                  else 
                  { $('#pdepositoc'+idcubo).val('');
                  $('#pdepositoc'+idcubo).hide();
                  }
            }

          });
    }
  }
    else{

      $('[name="'+seleccion+'"]').click(function() {    
        if( $(this).is(':checked') ){
              if($(this).val()==1)
              { 
                $("#pefectivo").show();
              }
               else if($(this).val()==2)
              {
                $("#ptarjeta").show();
                $('#etiquetat').val('');
                $('#etiquetatarjeta').show();
                $('#etiquetat').show();
              }
              else if($(this).val()==3)
              {
                $("#ptransferencia").show();
              }
              else if($(this).val()==4)
              {
                $("#pdeposito").show();
              }
          
        }
        if( $(this).is(':unchecked') ){
                
              
              if($(this).val()==1)
              {
                $('#pefectivo').val('');
                $("#pefectivo").hide();
              }
              else if($(this).val()==2)
              { 
                $('#ptarjeta').val('');
                $('#ptarjeta').hide();     
                $('#etiquetatarjeta').hide();
                $('#etiquetat').hide();
              }
              else if($(this).val()==3)
              { 
                $('#ptransferencia').val('');
                $('#ptransferencia').hide();
                
              }
              else 
              { $('#pdeposito').val('');
              $('#pdeposito').hide();
              }
        }

      });





    }
   });


 });

//  $("#pefectivo").keyup(function () {

//   var totalFijo=$('#totalFIJO').val();
//   totalFijo=totalFijo.replace('$','');//Quitamos el la coma para poder hacer la operacion
//   totalFijo=totalFijo.replace(',','');//Quitamos el la coma para poder hacer la operacion 
  
//   var pefectivo = $(this).val();
//   var ptarjeta=$('#ptarjeta').val();
//   var ptransferencia=$('#ptransferencia').val();
//   var pdeposito=$('#pdeposito').val();

//   if(pefectivo==''){pefectivo=0;}
//   if(ptarjeta==''){ptarjeta=0;}
//   if(ptransferencia==''){ptransferencia=0;}
//   if(pdeposito==''){pdeposito=0;}

//   var nuevovalor=parseFloat(totalFijo)-parseFloat(pefectivo)-parseFloat(ptarjeta)-parseFloat(ptransferencia)-parseFloat(pdeposito);
//   var suma=parseFloat(pefectivo)+parseFloat(ptarjeta)+parseFloat(ptransferencia)+parseFloat(pdeposito);
//   console.log(suma);
//   if(suma>totalFijo)
//   {   
//     $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga no debe ser mayor al total!</p>');
   
//     $(this).val('');   
//     // $('#ptarjeta').val('');
//     // $('#ptransferencia').val('');
//     // $('#pdeposito').val('');
//     $("#saldopmixto").val(0);
//   }else{
//     $('.alertCambio').html('<p style="color : red;"></p>');
//   $("#saldopmixto").val(nuevovalor);
//   }
// });

function functionefectivo(cubocodigo) {

  var totalFijo=$('#totalFIJO'+cubocodigo).val();
  totalFijo=totalFijo.replace('$','');//Quitamos el la coma para poder hacer la operacion
  totalFijo=totalFijo.replace(',','');//Quitamos el la coma para poder hacer la operacion 
  
  var pefectivo = $('#pefectivoc'+cubocodigo).val();//$(this).val();
  var ptarjeta=$('#ptarjetac'+cubocodigo).val();
  var ptransferencia=$('#ptransferenciac'+cubocodigo).val();
  var pdeposito=$('#pdepositoc'+cubocodigo).val();

  if(pefectivo==''){pefectivo=0;}
  if(ptarjeta==''){ptarjeta=0;}
  if(ptransferencia==''){ptransferencia=0;}
  if(pdeposito==''){pdeposito=0;}

  var nuevovalor=parseFloat(totalFijo)-parseFloat(pefectivo)-parseFloat(ptarjeta)-parseFloat(ptransferencia)-parseFloat(pdeposito);
  var suma=parseFloat(pefectivo)+parseFloat(ptarjeta)+parseFloat(ptransferencia)+parseFloat(pdeposito);
  if(suma>totalFijo)
  {   
    $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga no debe ser mayor al total!</p>');
    $('#pagar_con'+cubocodigo).val('');
    $(this).val('');   
    //$("#saldopmixto"+cubocodigo).val(0);
  }else{
    $('.alertCambio').html('<p style="color : red;"></p>');
    $('#pagar_con'+cubocodigo).val(suma);
  //$("#saldopmixto"+cubocodigo).val(nuevovalor);
  }
}


function functiontarjeta(cubocodigo) {
  
  var totalFijo=$('#totalFIJO'+cubocodigo).val();
  totalFijo=totalFijo.replace('$','');//Quitamos el la coma para poder hacer la operacion
  totalFijo=totalFijo.replace(',','');//Quitamos el la coma para poder hacer la operacion 
   
  var pefectivo=$('#pefectivoc'+cubocodigo).val();
  var ptarjeta = $('#ptarjetac'+cubocodigo).val();
  var ptransferencia=$('#ptransferenciac'+cubocodigo).val();
  var pdeposito=$('#pdepositoc'+cubocodigo).val();

  if(pefectivo==''){pefectivo=0;}
  if(ptarjeta==''){ptarjeta=0;}
  if(ptransferencia==''){ptransferencia=0;}
  if(pdeposito==''){pdeposito=0;}
  
  var nuevovalor=parseFloat(totalFijo)-parseFloat(pefectivo)-parseFloat(ptarjeta)-parseFloat(ptransferencia)-parseFloat(pdeposito);
  var suma=parseFloat(pefectivo)+parseFloat(ptarjeta)+parseFloat(ptransferencia)+parseFloat(pdeposito);

  if(suma>totalFijo)
  {  $('#pagar_con'+cubocodigo).val(''); 
    $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga no debe ser mayor al total!</p>');
   
    // $('#pefectivo').val('');
    $(this).val('');   
    // $('#ptransferencia').val('');
    // $('#pdeposito').val('');
   // $("#saldopmixto").val(0);

  }else{
    $('.alertCambio').html('<p style="color : red;"></p>');
    $('#pagar_con'+cubocodigo).val(suma);
   // $("#saldopmixto").val(nuevovalor);
  }
}

function functiontransferencia(cubocodigo)
 {
  var totalFijo=$('#totalFIJO'+cubocodigo).val();
  totalFijo=totalFijo.replace('$','');//Quitamos el la coma para poder hacer la operacion
  totalFijo=totalFijo.replace(',','');//Quitamos el la coma para poder hacer la operacion 

  var pefectivo=$('#pefectivoc'+cubocodigo).val();
  var ptarjeta=$('#ptarjetac'+cubocodigo).val();
  var ptransferencia = $('#ptransferenciac'+cubocodigo).val(); //$(this).val();
  var pdeposito=$('#pdepositoc'+cubocodigo).val();

  if(pefectivo==''){pefectivo=0;}
  if(ptarjeta==''){ptarjeta=0;}
  if(ptransferencia==''){ptransferencia=0;}
  if(pdeposito==''){pdeposito=0;}
  

  var nuevovalor=parseFloat(totalFijo)-parseFloat(pefectivo)-parseFloat(ptarjeta)-parseFloat(ptransferencia)-parseFloat(pdeposito);
  var suma=parseFloat(pefectivo)+parseFloat(ptarjeta)+parseFloat(ptransferencia)+parseFloat(pdeposito);

  if(suma>totalFijo)
  {   
    $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga no debe ser mayor al total!</p>');
    //  $('#pefectivo').val('');
    //  $('#ptarjeta').val('');
    //  $('#pdeposito').val('');
     $(this).val('');
     $('#pagar_con'+cubocodigo).val('');
    // $("#saldopmixto").val(0);
  }else{
    $('.alertCambio').html('<p style="color : red;"></p>');
    $('#pagar_con'+cubocodigo).val(suma);
    //$("#saldopmixto").val(nuevovalor);
  }
}


function functiondeposito(cubocodigo)
{
  var totalFijo=$('#totalFIJO'+cubocodigo).val();
  totalFijo=totalFijo.replace('$','');//Quitamos el la coma para poder hacer la operacion
  totalFijo=totalFijo.replace(',','');//Quitamos el la coma para poder hacer la operacion 

  

  var pefectivo=$('#pefectivoc'+cubocodigo).val();
  var ptarjeta=$('#ptarjetac'+cubocodigo).val();
  var ptransferencia=$('#ptransferenciac'+cubocodigo).val();
  var pdeposito = $('#pdepositoc'+cubocodigo).val();

  if(pefectivo==''){pefectivo=0;}
  if(ptarjeta==''){ptarjeta=0;}
  if(ptransferencia==''){ptransferencia=0;}
  if(pdeposito==''){pdeposito=0;}

  var nuevovalor=parseFloat(totalFijo)-parseFloat(pefectivo)-parseFloat(ptarjeta)-parseFloat(ptransferencia)-parseFloat(pdeposito);
  var suma=parseFloat(pefectivo)+parseFloat(ptarjeta)+parseFloat(ptransferencia)+parseFloat(pdeposito);

  if(suma>totalFijo)
  {   
    $('.alertCambio').html('<p style="color : red;">Error la cantidad con la que paga no debe ser mayor al total!</p>');
    $('#pagar_con'+cubocodigo).val('');
    //  $('#pefectivo').val('');
    //  $('#ptarjeta').val('');
    //  $('#ptransferencia').val('');
     $(this).val('');
     //$("#saldopmixto").val(0);
  }else{
    $('.alertCambio').html('<p style="color : red;"></p>');
    $('#pagar_con'+cubocodigo).val(suma);
    //$("#saldopmixto").val(nuevovalor);
  }
}