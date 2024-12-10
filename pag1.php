<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
//<![CDATA[
var watchId;
/* Controlamos los tiempos de espera mínimo y máximo de nuestra geolocalización respecto a la petición anterior */
var PositionOptions = {
    timeout: 5000,
    maximumAge: 60000,
    enableHighAccurace: true // busca la mejor forma de geolocalización (GPS, tiangulación, ...)
};
/* Utiliza la geolocalalización solamente cuando se solicita.
Con PositionOptions aseguramos que la posición no corresponde a caché */
function initiate_geolocation() {
  if (navigator.geolocation) {
    browserSupportFlag = true;
    var watchId = navigator.geolocation.getCurrentPosition(successCallback, errorCallback, PositionOptions);
  } else {
    document.getElementById("mensaje").innerHTML = "Lo sentimos pero el API de Geolocalización de HTM5 no está disponible para su navegador";
  }
}
/* Reitera la geolocalización hasta que la detenemos */
function watch_geolocation() {
  if (navigator.geolocation) {
    browserSupportFlag = true;  // Para optimizarlo en los navegadores (mis dudas con IE)
    var watchId = navigator.geolocation.watchPosition(successCallback, errorCallback);
  } else {
    document.getElementById("mensaje").innerHTML = "Lo sentimos pero el API de Geolocalización de HTM5 no está disponible para su navegador";
  }
}
/* Detenemos la geolocalización reiterada */
function clear_watch_geolocation() {
  if (navigator.geolocation) {
    navigator.geolocation.clearWatch(watchId);
  } else {
    document.getElementById("mensaje").innerHTML = "Lo sentimos pero el API de Geolocalización de HTM5 no está disponible para su navegador";
  }
}
 
function successCallback(pos) {
  var timestamp = document.getElementById('timestamp');
  var date = new Date(pos.timestamp);
  /* Hacemos legible la fecha a nuestro léxico. 
  timestamp nos daría una lectura como ésta: Wed Jun 18 2014 09:46:21 GMT+0200  */
  var mes = date.getMonth() + 1;
  if (mes < 10) {
    mes = "0" + mes
  }
  var dia = date.getDate();
  if (dia < 10) {
    dia = "0" + dia
  }
  var anyo = date.getFullYear();
  var hora = date.getHours();
  if (hora < 10) {
    hora = "0" + hora
  }
  var minuto = date.getMinutes();
  if (minuto < 10) {
    minuto = "0" + minuto
  }
  var segundo = date.getSeconds();
  if (segundo < 10) {
    segundo = "0" + segundo
  }
  var latitude = document.getElementById('latitude');
  latitude.innerHTML = pos.coords.latitude.toFixed(6);  // Limito decimales de coordenadas a 6 
  var longitude = document.getElementById('longitude');
  longitude.innerHTML = pos.coords.longitude.toFixed(6);
  };
/* Posibles errores que se pueden producir en la geolocalización */
function errorCallback(error) {
  var appErrMessage = null;
  if (error.core == error.PERMISSION_DENIED) {
    appErrMessage = "El usuario no ha concedido los privilegios de geolocalización"
  } else if (error.core == error.POSITION_UNAVAILABLE) {
    appErrMessage = "Posicion no disponible"
  } else if (error.core == error.TIMEOUT) {
    appErrMessage = "Demasiado tiempo intentando obtener la localización del usuario."
  } else if (error.core == error.UNKNOWN) {
    appErrMessage = "Error desconocido"
  } else {
    appErrMessage = "Error insesperado"
  }
  document.getElementById("mensaje").innerHTML = appErrMessage
};
//]]>
</script>
<?php

if (strlen($_POST["logina"])<=0 or strlen($_POST["clavea"])<=0)
   {
	    echo '<script>alert("Ingrese Usuario o Password")</script>';
        echo "<script>location.href='movil.php'</script>";
   }
else
   {
   		echo '<br>
		Latitud: <span style="color:#FF00AA;" id="latitude"></span>
		<br>
		Longitud: <span style="color:#FF00AA;" id="longitude"></span>
		<br>';
		echo '<script>initiate_geolocation();</script>';
		
		
	   }
	
 
     

?>

