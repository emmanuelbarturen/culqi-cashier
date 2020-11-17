<html>
<head>
    <meta charset="UTF-8">
    <title>Compra</title>
</head>
<body>
<div>
    <label>
        <span>endpoint</span>
        <input type="text" value="{{request()->get('url','/api/v1/culqi/payment')}}" id="endpoint">
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
            <input type="text" size="20" data-culqi="card[number]" id="card[number]" value="4111111111111111">
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
        <input type="text" size="4" data-culqi="card[exp_year]" id="card[exp_year]" value="2025">
    </div>
    <button onclick="payment()" type="button">Pagar</button>
</form>
<footer class="footer">
    <div class="container">
        <p class="text-muted">Una implementación de Culqi en Laravel</p>
    </div>
</footer>
<script src="https://checkout.culqi.com/v2"></script>
<script>
    Culqi.publicKey = '{{env('CULQI_PUBLIC')}}';
    Culqi.init();
    var checkTarjeta = false;

    function payment() {
        Culqi.createToken();
    }

    function culqi() {
        if (Culqi.token) { // ¡Token creado exitosamente!
            const token = Culqi.token.id;   // Get the token ID:
            const url = document.getElementById('endpoint').value;
            const data = new FormData();
            data.append('_token', '{{csrf_token()}}');
            data.append('culqi_token', token);

            fetch(url, {
                method: 'POST',
                body: data
            }).then(function (response) {
                if (response.ok) {
                    return response.text()
                } else {
                    throw "Error en la llamada Ajax";
                }

            }).then(function (texto) {
                console.log(texto);
            }).catch(function (err) {
                console.log(err);
            });

        } else { // ¡Hubo algún problema!
            // Mostramos JSON de objeto error en consola
            console.log(Culqi.error);
            alert(Culqi.error.mensaje);
        }
    }
</script>
</body>
</html>
