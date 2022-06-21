/*=============================================
MOSTRAR LA APOSTILLA
=============================================*/
$(".tablasApostillas").on("click", ".btnApostilla", function(){

	let idApostilla = $(this).attr("idApostilla");
    console.log('idApostilla', idApostilla)

    var datos = new FormData();
    datos.append("idApostilla", idApostilla);

    $.ajax({

      url:"ajax/apostilla.ajax.php",
      method: "POST",
      data: datos,
      cache: false,
      contentType: false,
      processData: false,
      dataType:"json",

      success:function({id,folio,descripcion,nombre,importe,idventa}){
        $("#idVentaA").val(idventa);
        $("#idApostillaVenta").val(id);
        $("#nroFolio").val(folio);
        $("#idNombreApostilla").val(nombre);
        $("#idDescripcionApostilla").val(descripcion);
		$("#idImporteApostilla").val(importe);
	
	  }

  	})

})
/*=============================================
HACER FOCO EN EL NOMBRE DEL COMPROBANTE
=============================================*/
$('#modalAgregarApostilla').on('shown.bs.modal', function () {
    
    if($("#idNombreApostilla").val().length==0){

        $("#idNombreApostilla").focus();

    }else{

        $("#idNombreApostilla").focus();
        $("#idNombreApostilla").select();

    }

})

$('#btnIrHistoria').on('click', ()=>{
    // history.back();
    window.location = "ventas";
})