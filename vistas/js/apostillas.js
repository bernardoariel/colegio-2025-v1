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

      success:function({idventa,haya,id,folio,nombre,descripcion,intervino,importe}){
        // console.log(haya)
        $("#idVentaA").val(idventa);
        $("#idHaya").val(haya);
        $("#idApostillaVenta").val(id);
        $("#nroFolio").val(folio);
        $("#idNombreApostilla").val(nombre);
        $("#idDescripcionApostilla").val(descripcion);
        $("#intervinoApostilla").val(intervino);
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
$(".tablasApostillas").on("click", ".btnImprimirApostilla", function(){
    let idApostilla = $(this).attr("idApostilla");
    window.open(`extensiones/fpdf/pdf/apostilla.php?id=${idApostilla}` ,"APOSTILLA "+idApostilla,1,2);

})
