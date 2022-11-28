<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agenda</title>
    <!--Css-->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/datatables.min.css">
    <link rel="stylesheet" href="css/bootstrap-clockpicker.css">
    <link rel="stylesheet" href="fullcalendar/main.css">
    <!--JS-->
    <script type="text/javascript" src="js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="js/popper.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/datatables.min.js"></script>
    <script type="text/javascript" src="js/bootstrap-clockpicker.js"></script>
    <script type="text/javascript" src="js/moment-with-locales.js"></script>
    <script type="text/javascript" src="fullcalendar/main.js"></script>
</head>
<body>
    <div class="container-fluid">
        <section class="content-header">
            <h1>Agenda
                <small>Panel de control</small>
            </h1>
        </section>
        <div class="row">
            <div class="col-10">
                <div id="Calendario1" style="border: 1px solid #000; padding: 2px;"></div>
            </div>
            <div class="col-2">
                <div id="external-events" style="margin-bottom: 1em; height: 350px; border: 1px solid #000; overflow: auto; padding: 1em;">
                <h4 class="text-center"> Eventos predefinidos</h4>
            <div id="listaeventospredefinidos">
                <?php
                require("conexion.php");
                $conexion = regresarConexion();
                $datos = mysqli_query($conexion, "SELECT id,titulo,horainicio,horafin,colortexto,colorfondo FROM eventospredefinidos");
                $ep = mysqli_fetch_all($datos, MYSQLI_ASSOC);

                foreach ($ep as $fila) {
                    echo"<div class='fc-event' data-titulo='$fila[titulo]' data-horafin='$fila[horafin]' data-horainicio='$fila[horainicio]' data-colorfondo='$fila[colorfondo]' data-colortexto='$fila[colortexto]' style='border-color:$fila[colorfondo];color:$fila[colortexto];background-color:$fila[colorfondo];margin:10px'>
                    $fila[titulo][" . substr($fila['horainicio'],0,5) . " a " .substr($fila['horafin'],0,5) . "] </div>";
                }
                ?>

            </div>
            <hr>
            <div class="" style="text-align: center;">
            <button class="btn btn-success" type="button" id="BotonEventosPredefinidos" name="button">Administrar eventos predefinidos</button>
        </div>
        </div>
    </div>

    <div id="Calendario1" style="border: 1px solid #000; padding: 2px;"></div>
    
    <!--Formularioeventos-->
    <div class="modal fade" id="FormularioEventos" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="Id">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="">Titulo del Evento:</label>
                            <input type="text" id="Titulo" class="form-control" name="" value="">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Fecha de inicio:</label>
                            <div class="input-group" data-autoclose="true">
                                <input type="date" id="FechaInicio" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-md-6" id="TituloHoraInicio">
                            <label for="">Hora de inicio:</label>
                            <div class="input-group clockpicker" data-autoclose="true">
                                <input type="text"id="HoraInicio" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="">Fecha de fin:</label>
                            <div class="input-group" data-autoclose="true">
                                <input type="date" id="FechaFin" class="form-control">
                            </div>
                        </div>
                        <div class="form-group col-md-6" id="TituloHoraFin">
                            <label for="">Hora de fin:</label>
                            <div class="input-group clockpicker" data-autoclose="true">
                                <input type="text"id="HoraFin" class="form-control" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <label for="">Descripcion:</label>
                        <textarea id="Descripcion" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-row">
                        <label for="">Color de fondo:</label>
                        <input type="color" value="#3788D8" id="ColorFondo" class="form-control" style="height: 36px;">
                    </div>
                    <div class="form-row">
                        <label for="">Color texto:</label>
                        <input type="color" value="#ffffff" id="ColorTexto" class="form-control" style="height: 36px;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="BotonAgregar" class="btn btn-success">Agregar</button>
                    <button type="button" id="BotonModificar" class="btn btn-success">Modificar</button>
                    <button type="button" id="BotonBorrar" class="btn btn-success">Borrar</button>
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
<script>
    $('.clockpicker'),clockpicker();

    let calendario1 = new FullCalendar.Calendar(document.getElementById('Calendario1'),{ 
        events: 'datoseventos.php?accion=listar',
        dateClick: function(info){
            limpiarFormulario()
            $('#BotonAgregar').show();
            $('#BotonModificar').hide();
            $('#BotonBorrar').hide();

            if (info.allDay) {
                $('#FechaInicio').val(info.dateStr);
                $('#FechaFin').val(info.dateStr);
            } else {
                let fechaHora = info.dateStr.split("T");
                $('FechaInicio').val(fechaHora[0]);
                $('FechaFin').val(fechaHora[0]);
                $('HoraInicio').val(fechaHora[1].subtring(0,5));
            }
           $("#FormularioEventos").modal('show');
        }
    });
    calendario1.render();

    function agregarRegistro(registro){
        $.ajax({
            type: 'POST',
            url: 'datoseventos.php?accion=agregar',
            data: registro,
            success: function(msg){
                calendario1.fetchEvents();
            }
        },
        error: function(error){
            alert("Hubo un error al agregar el evento:" + error);
        })
    }

    function limpiarFormulario(){
        $('#Id').val('');
        $('#Titulo').val('');
        $('#Descripcion').val('');
        $('#FechaFin').val('');
        $('#FechaInicio').val('');
        $('#HoraInicio').val('');~
        $('#HoraFin').val('');
        $('#ColorFondo').val('#3788D8');
        $('#ColortEXTO').val('#ffffff');
    }
    </script>

</body>
</html>