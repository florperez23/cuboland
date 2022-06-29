
function generar(){
    //alert('entro');
    var data = $("#data").val();
    //var nombre = $("#nombre").val();
    $("#imagen").html('<img src="barcode\\barcode.php?text='+data+'&size=90&codetype=Code39&print=true"/>');
    $.post( "guardarImagen.php", { filepath: "codigosGenerados/"+data+".png", text:data});
    $("#data").val('');
   /////////////////////////////////////////// $("#nombre").val();
}
// //generar_barcode").click(function() {
                //     var data = $("#data").val();
                //     $("#imagen").html('<img src="barcode\\barcode.php?text='+data+'&size=90&codetype=Code39&print=true"/>');
                //     $("#data").val('');
                // });

// funcion para imprmir codigo de barras en pdf
function imprmir() {
  /*var  pr = $('#producto_id').val();
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
  });*/

}