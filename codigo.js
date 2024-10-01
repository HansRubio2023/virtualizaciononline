$(document).ready(function(){
    $("#guardar-sesiones").click(function(){
        alert("prueba");

    });
    toastr.options = {
	//primeras opciones
  "closeButton": false,//aparecera una X para cerrar
  "debug": false,
  "newestOnTop": false, //notificaciones mas nuevas van en la parte superior
  "progressBar": false,// barra de progreso hasta que se oculta la  notificacion
  "preventDuplicates": true, //para prevenir mensajes duplicador
  "onclick": null,

  "positionClass": "toast-top-right", 
  /*posicion de la notificacion
  toast-bottom-left, toast-bottom-right,
  toast-top-full-width, toast-top-center*/

  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}
});