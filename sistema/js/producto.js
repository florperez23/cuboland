$(document).ready(function(){
   $('.btnMenu').click(function(e) {
    e.preventDefault();
    if($('nav').hasClass('viewMenu')) {
      $('nav').removeClass('viewMenu');
    }else {
      $('nav').addClass('viewMenu');
    }
  });

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
        
          $('#txt_precio').html(info.precio);
          $('#txt_precio_total').html(info.precio);
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
          $('#txt_precio_total').html(info.precio);
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
        if (response != 'error') {
          var info = JSON.parse(response);
          $('#detalle_venta').html(info.detalle);
          $('#detalle_totales').html(info.totales);
          $('#totalmodal').val(  MASK('', (info.totalmodal),'$##,###,##0.00',1));             
          $('#txt_cod_producto').val('');
          $('#txt_cod_pro').val('');
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
  }
  else
  {
    pago = document.getElementById("pagar_conC").value; 
    total=document.getElementById("totalmodal").value;
    fechaven = $('#fechav').val();
    } 
    numcredito = document.getElementById("numcredito").value; 
   
 
  if (rows > 0) {
    $.ajax({
      url: 'modal.php',
      type: 'POST',
      async: true,
      data: {action:action,codcliente:codcliente,tipoventa:tipoventa, pago:pago,fechaven:fechaven,tipopago:tipopago,referencia:referencia,numcredito:numcredito},
      success: function(response) {
      (response); 
      console.log(response);
      if (response != 0) {
        console.log(response);
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
  }else 
  {
    if (numcredito> 0)
    {     
    $.ajax({
    url: 'modal.php',
    type: 'POST',
    async: true,
    data: {action:action,codcliente:codcliente,tipoventa:tipoventa,total:total, pago:pago,fechaven:fechaven,tipopago:tipopago,referencia:referencia,numcredito:numcredito},
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
        if (response != 0) {
        
         
        var info = JSON.parse(response);
        $('#detalle_venta').html(info.detalle);
        $('#detalle_totales').html(info.totales);
        $('#totalmodal').val(  MASK('', (info.totalmodal),'$##,###,##0.00',1));          
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
      if (response == 0) {
        console.log(response);
      }else {
        var info = JSON.parse(response);
        $('#detalle_venta').html(info.detalle);
        $('#detalle_totales').html(info.totales);
        $('#totalmodal').val(MASK('', (info.totalmodal),'$##,###,##0.00',1));          
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

if (document.getElementById("sales-chart")) {
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
}
if (document.getElementById("polarChart")) {
  const action = "polarChart";
  $('.alertAddProduct').html('');
  $.ajax({
    url: 'chart.php',
    type: 'POST',
    async: true,
    data: {action},
    success: function (response) {
      if (response != 0) {
        var data = JSON.parse(response);
        var nombre = [];
        var cantidad = [];
        for (var i = 0; i < data.length; i++) {
          nombre.push(data[i]['descripcion']);
          cantidad.push(data[i]['cantidad']);
        }
      }
      try {

        // polar chart
        var ctx = document.getElementById("polarChart");
        if (ctx) {
          ctx.height = 200;
          var myChart = new Chart(ctx, {
            type: 'polarArea',
            data: {
              datasets: [{
                data: cantidad,
                backgroundColor: [
                  "rgb(0, 123, 255)",
                  "rgb(255, 0, 0)",
                  "rgb(0, 255, 0)",
                  "rgb(0,0,0)",
                  "rgb(0, 0, 255)"
                ]

              }],
              labels: nombre
            },
            options: {
              legend: {
                position: 'top',
                labels: {
                  fontFamily: 'Poppins'
                }

              },
              responsive: true
            }
          });
        }

      } catch (error) {
        console.log(error);
      }
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
  if (e.which == 13) {  

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
            document.getElementById("pagar_con").value=pagar_con;        
           Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: 'Error la cantidad a pagar debe ser mayor o igual al total!',
            footer: ''
          })
          $('#exampleModal').modal('hide');
           $('#btn_facturar_venta').slideUp();    
            document.getElementById('pagar_con').focus();
            
          }
    } 
  
}
});



//EVALUAMOS QUE TIPO DE VENTA SERÁ
$('#tipoven').on('change', function() {
  if(this.value=='1')
  { 
    $('#ventacredito').slideUp();
    $('#ventacontado').slideDown();   
 }
  else{  
   $('#ventacontado').slideUp();
   $('#ventacredito').slideDown();
   const total=$('#totalmodal').val();
   $('#totalmodalC').val(total);

   numcredito = document.getElementById("numcredito").value; 
  
   if(numcredito==0)
   { 
    $('#divSaldo').slideUp();  
    $('#divcredito').slideUp();  
   }else{
    $('#divSaldo').slideDown();  
    $('#divcredito').slideDown();
   
   }
    
  }
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
        console.log(response);
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

jQuery('#cerrarcorte').on('hidden.bs.modal', function (e) {
  jQuery(this).removeData('bs.modal');
  jQuery(this).find('.alertAddProduct').empty();
});


function abrirModalAbono(numcredito,total,saldo)
{
  ;
  $('#tipoven').val(2); 
  $('#tipoven').change();
  $('#numcredito').val(numcredito); 
  $('#totalmodalC').val( MASK('', (total),'$##,###,##0.00',1));  
  $('#saldo').val(MASK('', (saldo),'$##,###,##0.00',1));
  $('#divSaldo').slideDown();   
  $('#divFechaVencimiento').slideUp();     
  $('#exampleModal').modal('show');
        
}

//EVALUAMOS QUE TIPO DE PAGO SERÁ
$('#tipopago').on('change', function() {
 
  if(this.value=='1')
  { 
    $('#referencia').slideUp();       
    $('#divCambio').slideDown();
    $('#divCambioC').slideDown();
    document.getElementById('pagar_con').value="0.00";
    document.getElementById('pagar_conC').value="0.00";
    document.getElementById('pagar_con').disabled = false;
    document.getElementById('pagar_con').disabled = false;
    
 }  
  else{  
  
  $('#referencia').slideDown(); 
  $('#divCambio').slideUp();
  $('#divCambioC').slideUp();
  var total=$('#totalmodal').val();
  var totalC=$('#totalmodalC').val();
   console.log(total);  
   if$('#tipoven').val()==1
  {
    document.getElementById('pagar_con').value=total;
    document.getElementById('pagar_conC').value=totalC;
    document.getElementById('pagar_con').disabled = true;
    document.getElementById('pagar_con').disabled = true;
    }
  }
 });


 // ingresar abono
 $('#form_new_abono_creditos').submit(function(e) {  
   e.preventDefault();
   $.ajax({
     url: 'modal.php',
     type: "POST",
     async: true,
     data: $('#form_new_abono_creditos').serialize(),
     success: function(response) {
      console.log(response);
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
          console.log(response);
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
      console.log(response);
      console.log(parseInt(response)+parseInt(cantidad))  ;
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
  var iva = $('#iva').val();
  if(iva == ''){
    iva = 0;
  }
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