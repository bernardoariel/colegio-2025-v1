/*=============================================
FECHA EN EL DATAPICKER
=============================================*/
$('#datepicker_inicio').datepicker({dataFormat:'d/m/Y'}).val()
$('#datepicker_fin').datepicker({dataFormat:'d/m/Y'}).val()


$("#bsq_comprobantes").on("click",function(){


	let fechaInicio = $("#datepicker_inicio").val()
	let fechaFin = $("#datepicker_fin").val()
	window.location = `index.php?ruta=comprobantes-cantidad&fechaInicio=${fechaInicio}&fechaFin=${fechaFin}`;
    $("#datepicker_inicio").val(fechaInicio)
    $("#datepicker_fin").val(fechaFin)
})
