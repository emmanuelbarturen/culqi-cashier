<html>
<head>
    <meta charset="UTF-8">
    <title>Compra</title>
</head>
<body>
<div>
    <label>
        <span>endpoint</span>
        <input type="text" value="/api/culqi/payment" id="endpoint">
    </label>
</div>
//Aquí creamos el formulario de captura de la tarjeta
//agregar tarjetas de prueba aqui
<form action="" method="POST" id="culqi-card-form">
    <div>
        <label>
            <span>Correo Electrónico</span>
            <input type="text" size="50" data-culqi="card[email]" id="card[email]" value="emmanuelbarturen@gmail.com">
        </label>
    </div>
    <div>
        <label>
            <span>Número de tarjeta</span>
            <input type="text" size="20" data-culqi="card[number]"  id="card[number]" value="4111111111111111">
        </label>
    </div>
    <div>
        <label>
            <span>CVV</span>
            <input type="text" size="4" data-culqi="card[cvv]" id="card[cvv]" value="123">
        </label>
    </div>
    <div>
        <label>
            <span>Fecha expiración (MM/YYYY)</span>
            <input type="text" size="2" data-culqi="card[exp_month]" id="card[exp_month]" value="09">
        </label>
        <span>/</span>
        <input type="text" size="4" data-culqi="card[exp_year]" id="card[exp_year]" value="2020">
    </div>
    <button id="buyButton" type="button">Pagar</button>
</form>
<footer class="footer">
    <div class="container">
        <p class="text-muted">Una implementación de Culqi en Laravel</p>
    </div>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<script src="https://checkout.culqi.com/v2"></script>
<!-- Configurando el checkout-->
<script>
    Culqi.publicKey = '{{env('CULQI_PUBLIC')}}';
    Culqi.init();
    var checkTarjeta = false;
    $('#buyButton').on('click', function (e) {
        // Abre el formulario con las opciones de Culqi.settings
        e.preventDefault();
        Culqi.createToken();
    });

    function culqi() {
        if(Culqi.token) { // ¡Token creado exitosamente!
            // Get the token ID:
            var token = Culqi.token.id;
            $.ajax({
                url:'/api/expoalimentaria/buy-virtual-code',
                method:'POST',
                data:{_token:'{{csrf_token()}}',culqiToken:token}
            }).done(function(response){
                if(response.status === 'error'){
                    var msg = JSON.parse(response.msg);
                    alert(msg.merchant_message)
                }else{
                    alert('yeah!');
                }
                console.log(response);
                alert('done');
            });

        }else{ // ¡Hubo algún problema!
            // Mostramos JSON de objeto error en consola
            console.log(Culqi.error);
            alert(Culqi.error.mensaje);
        }
    }
</script>
</body>
</html>
