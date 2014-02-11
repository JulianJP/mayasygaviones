//***********************************************************************************
//***********************************************************************************
//*******    RUTINA QUE SE EJECUTA AL CARGARSE LA PAGINA WEB     ********************
//***********************************************************************************
//***********************************************************************************

var pagina;
pagina=$(document);
pagina.ready(inicializar);

//**********************************************************************************
//**********************************************************************************
//*************                                                *********************
//*************  FUNCION QUE INICIALIZA LAS OPERACIONES        *********************
//*************  PRINCIPALES EN JQUERY                         *********************
//*************                                                *********************
//**********************************************************************************
//**********************************************************************************

function inicializar()
{
    //Creamos El Dialogo
     $('#find').dialog({ autoOpen: false });

    /************************************************************************************/
    /********************       OPCION INVENTARIO      **********************************/
    /************************************************************************************/

    //Variables
    var inventario;
    //Inicializar Opciones
    inventario = $("#Inventario");
    inventario.click(menuInventario);
    //Inicio Activadores
    seccionInventario();
    inventario.addClass('active');
    activarEventosInventario();

    /************************************************************************************/
    /********************       OPCION COTIZAR         **********************************/
    /************************************************************************************/

    //Variables
    var cotizar;
    //Inicializar Opciones
    cotizar = $("#Cotizar");
    cotizar.click(menuCotizar);
    //Inicio Activadores
    activarEventosCotizar();            

    /************************************************************************************/
    /********************       OPCION REPORTE         **********************************/
    /************************************************************************************/

    //Variables
    var reporte;
    //Inicializar Opciones
    reporte = $("#Reporte");
    reporte.click(menuReporte);
    //Inicio Activadores
    activarEventosReporte();
}

//**********************************************************************************
//**********************************************************************************
//*************                                                *********************
//*************  FUNCIONES QUE CREA Y ACTIVA CUADRO FLOTANTE   *********************
//*************  DEL DIALOGO DE BUSQUEDA                       *********************
//*************                                                *********************
//**********************************************************************************
//**********************************************************************************

function dialogoLupa () 
{
    $('#find').dialog("open");
    $('.buscador').keypress(lupa);
    $('#find').dialog({ minWidth: 380 , title: "Buscar Referencia", buttons: [ { text: "Aceptar", click: function() { $( this ).dialog( "close" ); adicionarFind (); }} ] });
}

function lupa () 
{
    $.ajax({
            type: 'POST',
            url: $("form[class|='buscador']").attr('action'),
            data: $("form[class|='buscador']").serialize(),
            success: function(data)
            {
                $("#encontrado").html(data);
            }
        });
}

function adicionarFind () 
{
    var ref_find = $('input:radio[name=seleccionado]:checked').val();
    var cantidad = $('input:hidden[id='+ref_find+']').val();
    $("input[name|='ref']").val(ref_find);
    $("input[name|='cantidad']").attr("max",cantidad);
    $("input[name|='descripcion']").val("");
    $('#encontrado').html("<center></center>");
}

//**********************************************************************************
//**********************************************************************************
//*************                                                *********************
//*************  FUNCIONES QUE ACTIVA LOS EVENTOS PRINCIPALES  *********************
//*************  DE LOS SUBMENUS                               *********************
//*************                                                *********************
//**********************************************************************************
//**********************************************************************************

function activarEventosInventario () 
{
      
}

function activarEventosCotizar () 
{
    //variables
    var generarCotizacion;
    //Asignacion de variables
    generarCotizacion  = $("#generarPDF_Cotizar");
    //Asignacion de Funciones al momento de hacer click
    generarCotizacion.click(generarCotizacionPDF);  
}

function activarEventosReporte () 
{
    
}

//**********************************************************************************
//**********************************************************************************
//*************                                                *********************
//*************  FUNCIONES QUE MODIFICAN EL MENU AGREGANDO     *********************
//*************  LA CLASE ACTIVO A LA OPCION Y QUITANDOLA      *********************
//*************  A TODO EL MENU                                *********************
//*************                                                *********************
//**********************************************************************************
//**********************************************************************************


function menuInventario () 
{
    $("a").removeClass('active');
    $(this).addClass('active');
    seccionInventario();
}

function menuCotizar () 
{
    
    $("a").removeClass('active');
    $(this).addClass('active');
    seccionCotizar();
}

function menuReporte () 
{
    $("a").removeClass('active');
    $(this).addClass('active');
    seccionReporte();
}

//**********************************************************************************
//**********************************************************************************
//*************                                                *********************
//*************  FUNCIONES QUE MODIFICAN LA INTERFAZ GRAFICA   *********************
//*************  SEGUN LAS OPCIONES DEL MENU                   *********************
//*************                                                *********************
//**********************************************************************************
//**********************************************************************************


function seccionInventario () 
{
    var codigoHTML= "<table border='3px'>"+
                        "<thead>"+
                            "<tr>"+
                                "<th>Ref</th>"+
                                "<th>Detalle</th>"+
                                "<th>Cantidad</th>"+
                                "<th>Valor Unitario</th>"+
                                "<th>Subtotal</th>"+
                            "</tr>"+
                        "</thead>"+
                        "<tbody></tbody>"+
                    "<?table>";
    $(".contenedor").html(codigoHTML);
    $.post('../controlador/inventario.php', function(data) {
        $("tbody").html(data);
    });    
    activarEventosInventario();
}

function seccionCotizar () 
{
    activarEventosCotizar();
    var codigoHTML= "<table border='2px'>"+
                        "<thead>"+
                            "<tr>"+
                              "<th>Ref</th>"+
                              "<th>Detalle</th>"+
                              "<th>Cantidad</th>"+
                              "<th>Valor Unitario</th>"+
                              "<th>Subtotal</th>"+
                            "</tr>"+
                        "</thead>"+
                        "<tbody></tbody>"+
                        "<tfoot border='0'>"+
                            "<form action='../controlador/cotizar.php' method='post' id='formulario_cotizar'>"+
                                "<td><input type='text' name = 'ref'><img src='../multimedia/img/lupa.png' width='20px' height='20px' class='lupa'></td>"+
                                "<td colspan='2'>Cantidad<input type='number' name='cantidad' min='0' max='1000' value='0'></td>"+
                                "<td><input type='submit' value='Agregar' id='addCotizar'></td>"+
                                "<td><button id='generarPDF_Cotizar'>Generar Cotizacion</button></td>"+
                            "</form>"+
                        "</tfoot>"+
                    "</table>";
    $(".contenedor").html(codigoHTML);
    $(".lupa").css("cursor","pointer").click(dialogoLupa);
    activarEventosFacturar();
    $("#formulario_cotizar").submit(function(event) {
        event.preventDefault();
        addProducto_BD();
    });
    addProducto_BD();

}

function seccionReporte () 
{
    var codigoHTML= "<span>Como Desea Realizar su consulta :</span><br>"+
                    "<input type='radio' name='reporte' value='fecha'><label>Por Fecha</label><br>"+
                    "<input type='radio' name='reporte' value='referencia'><label>Por Referencia</label>";
    $(".contenedor").html(codigoHTML);
    activarEventosReporte();
}

//**********************************************************************************
//**********************************************************************************
//*************                                                *********************
//*************  FUNCIONES QUE MODIFICAN LA INTERFAZ GRAFICA   *********************
//*************  SEGUN LAS CONSULTAS HECHAS A LOS              *********************
//*************  CONTROLADORES Y SUS VALORES DEVUELTOS         *********************
//*************                                                *********************
//**********************************************************************************
//**********************************************************************************

function addProducto_BD () 
{
    $.ajax({
                type: 'POST',
                url:$("form").attr("action"),
                data:$("form").serialize(),
                beforeSend: cargando(),
                success: function(data)
                {
                    if (data!= 0) 
                    {
                        $("tbody").html(data);
                    }
                    else
                    {
                        $("tbody").html("<td colspan='5' align='center' border='0'><h3>Ingrese los Productos Segun Su referencia</td></h3>");
                        $("#cargando").css("border","0");
                    }
                }
            });
}

//**********************************************************************************
//**********************************************************************************
//*************                                                *********************
//*************  FUNCIONES QUE ACTUALIZA LA CANTIDAD DE STOCK  *********************
//*************  DE UNA REFERENCIA DADA; EN LA TABLA           *********************
//*************  INVENTARIOS                                   *********************
//*************                                                *********************
//**********************************************************************************
//**********************************************************************************

function actualizarCantidad () 
{
    $.ajax({
        type: 'POST',
        url: '../controlador/actualizar.php',
        data: $("form").serialize(),
        success: console.log("Realizado con Exito"),
        error: console.log("No funciono error"),
    });
    $(":input:first").focus();
    $("input[type|='text'],input[type|='number']").val("");
}

//**********************************************************************************
//**********************************************************************************
//*************                                                *********************
//*************  FUNCIONES QUE AGREGA UN TERCERO AL FACTURA    *********************
//*************                                                *********************
//**********************************************************************************
//**********************************************************************************

function addTercero ()
{
    
}

//**********************************************************************************
//**********************************************************************************
//*************                                                *********************
//*************  FUNCIONES QUE GENERA LOS PDF'S                *********************
//*************                                                *********************
//**********************************************************************************
//**********************************************************************************

function generarReciboPDF () 
{
    window.open("subsecciones/factura_pdf.php", "Factura De Venta","fullscreen=yes");
    $("tbody").html("<td colspan='5'><h2>Exito Al Generar La Factura</td></h2>");
}

function generarCotizacionPDF () 
{
    window.open("subsecciones/cotizar_pdf.php", "Cotizacion","fullscreen=yes");
    $("tbody").html("<td colspan='5'><h2>Exito Al Generar La Cotizacion</td></h2>");
}

//**********************************************************************************
//**********************************************************************************
//*************                                                *********************
//*************  FUNCIONES UN CODIGO HTML EN EL CUAL SE        *********************
//*************  CARGA UNA IMAGEN .GIF QUE MUESTRA UN          *********************
//*************  CARGANDO                                      *********************
//*************                                                *********************
//**********************************************************************************
//**********************************************************************************

function cargando () 
{
    $("tbody").append( "<td colspan='5' id='cargando' align='center'><img src='../multimedia/img/load.gif' /></td>" );
    $("#cargando").css("border","0");
}

