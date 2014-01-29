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
        /************************************************************************************/
        /********************       OPCION FACTURAR        **********************************/
        /************************************************************************************/
        
        // Variables
        var facturar;
        facturar=$("#Facturar");
        facturar.click(seccionFacturar);
        //Inicializacion de las opciones
        seccionFacturar(); 
        facturar.addClass('active');
        $("input[type|='submit']").click(function (event) 
        {
            event.preventDefault();
        });

            /************************************************************************************/
            /********************       SUBOPCION FACTURAR        *******************************/
            /************************************************************************************/

            var addProducto;
            addProducto = $("#addProducto");
            addProducto.click(function () 
                {
                    agregarProducto();
                });
            var newPDF;
            newPDF = $("#generarPDF");
            newPDF.click(generarPDF);

        /************************************************************************************/
        /********************       OPCION INVENTARIO      **********************************/
        /************************************************************************************/
        
        // Variables
        var inventario;
        //Inicializacion de las opciones
        inventario=$("#Inventario");
        inventario.click(menuInventario);
        //activadorEventosInventario();

        /************************************************************************************/
        /********************       OPCION COTIZAR         **********************************/
        /************************************************************************************/
        
        // Variables
        var cotizar;
        //Inicializacion de las opciones
        cotizar=$("#Cotizar");
        cotizar.click(menuCotizar);
        //activadorEventosCotizar();

            /************************************************************************************/
            /********************       SUBOPCION COTIZAR         *******************************/
            /************************************************************************************/
            var addCotizar;
            addCotizar = $("#addCotizar");
            addCotizar.click(agregarProductoCotizado);
            var newPDF_Cotizar;
            newPDF_Cotizar = $("#generarPDF_Cotizar");
            //newPDF_Cotizar.click(generarPDF_Cotizar);

        /************************************************************************************/
        /********************       OPCION REPORTE         **********************************/
        /************************************************************************************/
        
        // Variables
        var reporte;
        //Inicializacion de las opciones
        reporte=$("#Reporte");
        reporte.click(menuReporte);
        //activadorEventosreporte();
}
    //**********************************************************************************
    //**********************************************************************************
    //*************                                                *********************
    //*************  FUNCION QUE ACTIVA LOS EVENTOS PRINCIPALES    *********************
    //*************  DE LA SECCION FACTURAR                        *********************
    //*************                                                *********************
    //**********************************************************************************
    //**********************************************************************************



    //**********************************************************************
    //**********************************************************************
    //*************                                    *********************
    //*************  FUNCIONES QUE CARGAN LOS          *********************
    //*************  COMPONENTES DEL MENU VERTICAL     *********************
    //*************                                    *********************
    //**********************************************************************
    //**********************************************************************

    function menuInventario () 
    {
        $(".contenedor").load("subsecciones/inventario.php");
        $('a').removeClass('active');
        $('#Inventario').addClass('active');
    }

    function menuCotizar () 
    {
        $('a').removeClass('active');
        $('#Cotizar').addClass('active');
        seccionCotizar();
    }

    function menuReporte () 
    {
        var codigoHTML = "<p>Hola Reporte</p>";
        $(".contenedor").html(codigoHTML);
        $('a').removeClass('active');
        $('#Reporte').addClass('active');
    }

    //**********************************************************************************
    //**********************************************************************************
    //*************                                                *********************
    //*************  FUNCIONES QUE MODIFICAN LA INTERFAZ GRAFICA   *********************
    //*************  SEGUN LAS OPCIONES DE LOS BOTONES             *********************
    //*************                                                *********************
    //**********************************************************************************
    //**********************************************************************************

    function seccionCotizar () 
    {
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
                            "<tfoot border='0'>"+
                                "<form action='../controlador/cotizar.php' method='post'>"+
                                    "<td><input type='text' name = 'ref'><img src='../multimedia/img/lupa.png' width='20px' height='20px' class='lupa'></td>"+
                                    "<td colspan='2'>Cantidad<input type='number' name='cantidad' min='0' max='1000' value='0'></td>"+
                                    "<td><button id='addCotizar'>Agregar</button></td>"+
                                    "<td><a href='#coti-pdf' id='generarPDF_Cotizar'>Generar Recibo</a></td>"+
                                "</form>"+
                            "</tfoot>"+
                        "</table>";
        $(".contenedor").html(codigoHTML);
        $('#find').dialog({ autoOpen: false });
        $(".lupa").css("cursor","pointer").click(dialogoLupa);
        agregarProductoCotizado();
    }

    function seccionFacturar () 
    {
        $('a').removeClass('active');
        $('#Facturar').addClass('active');
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
                            "<tfoot border='0'>"+
                                "<form action='../controlador/factura.php' method='post'>"+
                                    "<td><input type='text' name = 'ref'><img src='../multimedia/img/lupa.png' width='20px' height='20px' class='lupa'></td>"+
                                    "<td colspan='2'>Cantidad<input type='number' name='cantidad' min='0' max='1000' value='0'></td>"+
                                    "<td><input type='submit' id='addProducto' value='Agregar'></td>"+
                                    "<td><a href='#fact-pdf' id='generarPDF'>Generar Recibo</a></td>"+
                                "</form>"+
                            "</tfoot>"+
                        "</table>";
        $(".contenedor").html(codigoHTML);
        $('#find').dialog({ autoOpen: false });
        $(".lupa").css("cursor","pointer").click(dialogoLupa);
        agregarProducto();
    }
/*
    function agregarProducto() 
    {
        $("table").append("<tbody></tbody>");
        $.ajax({
                type: 'POST',
                url:$("form").attr('action'),
                data: $("form").serialize(),
                beforeSend:cargando,
                success: function(data)
                {
                    if (data!= 0) 
                    {
                        $("tbody").html(data);
                    }
                    else
                    {
                        $("tbody").html("<td id='cargando' colspan='5' align='center' border='0'><h3>Ingrese los Productos Segun Su referencia</td></h3>");
                        $("#cargando").css("border","0");
                    }
                }
            });
        $(":input:first").focus();
        $("input[type|='text'],input[type|='number']").val("");
    }
    */

    function agregarProductoCotizado() 
    {
        $("table").append("<tbody></tbody>");
        $.ajax({
                type: 'POST',
                url:$("form").attr('action'),
                data: $("form").serialize(),
                beforeSend:cargando,
                success: function(data)
                {
                    if (data!= 0) 
                    {
                        $("tbody").html(data);
                    }
                    else
                    {
                        $("tbody").html("<td id='cargando' colspan='5' align='center' border='0'><h3>Ingrese los Productos A Cotizar</td></h3>");
                        $("#cargando").css("border","0");
                    }
                }
            });
        $(":input:first").focus();
        $("input[type|='text'],input[type|='number']").val("");
    }

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

    function actualizarCantidad () 
    {
        ref_dada = $("input[name|='ref']").val();
        cantidad_nueva = $("input[name|='cantidad']").val();

        $.ajax({
            type: 'POST',
            url: '../controlador/actualizar.php',
            data: {ref : ref_dada, cantidad : cantidad_nueva},
        });
    }

    function cargando () 
    {
        $("tbody").append( "<td colspan='5' id='cargando' align='center'><img src='../multimedia/img/load.gif' /></td>" );
        $("#cargando").css("border","0");
    }

    function generarPDF () 
    {
        window.open("subsecciones/factura_pdf.php", "Factura De Venta","fullscreen=yes");
        $("tbody").html("<td colspan='5'><h2>Exito Al Generar La Factura</td></h2>")
    }