# CulqiCashier for Laravel

Paquete para la integración  de Culqi enfocado en el usuario para el framework Laravel. 


## Instalación 
    composer install emm/culqi-cashier

## Configuración
 ## Correr migraciones
 
 ```
   php artisan migrate
```
 ### Modelo Facturable
 Antes de utilizar el paquete agrega el trait `Facturable` a tu modelo e implementa la función `culqiAntiFraud` con los 
 parámetros que se muestran en este ejemplo.  
 
 ```
    use Emm\CulqiCashier\Facturable;
    
    class User extends Authenticatable
    {
        use Facturable;

        /**
        * @return array
        */
        public function culqiAntiFraud(): array
        {
            return [
                "first_name" => $this->names,
                "last_name" => $this->last_names,
                "email" => $this->email,
                "address" => $this->address,
                "address_city" => $this->city',
                "country_code" => $this->country_code,
                "phone" => $this->mobile_phone,
                "metadata" => [], // opcional
            ];
        }
    }
 ```

   
 ### API Keys
 ```
CULQI_SECRET=
CULQI_PUBLIC=
```

### Configuración de moneda
Por defecto Culqi Cashier utiliza el Sol (PEN) como moneda. Para cambiar la moneda debes asignarlo en `.env`
```
CULQI_CURRENCY=USD
```
## Clientes
### Obteniendo todos los clientes

Puedes obtener todos los clientes, llamando a la función `list` del scope `Customer`. La respuesta sera una instancia de la clase `Illuminate\Support\Collection`
```
use Emm\CulqiCashier\CulqiCashier;

$customers = CulqiCashier::Customer()->list();
```
### Creando un nuevo cliente
La forma más simple es usando el método `createCulqiCustomer`. Recuerda que debes tener implementado `culqiAntiFraud`  
```
$user->createCulqiCustomer()
```
### Actualizando los datos de un cliente

```
 $newData = ['email' => 'newemail@mail.com'];
$user->updateCulqiCustomer($newData)
```
## Métodos de pago

### Cargo 
```
    $description = 'Venta de Prueba';
    $sourceId = request()->get('culqi_token');//ID del objeto Token u objeto Tarjeta que se va usar para realizar el cargo.
    $antifraud = []; //opcional, si quieres reemplazar algunos datos de `culqiAntiFraud`
    $user->charge($amount, $description,$sourceId, $antifraud);
```
### Suscripción
Las suscripciones deben ser configuradas en el [panel de culqi](https://integ-panel.culqi.com/#/login). 

```
 $planId = 'plan_1'; // Nombre del plan que asignaste en el panel de culqi
 $sourceId = request()->get('culqi_token');//ID del objeto Token u objeto Tarjeta que se va usar para realizar el cargo.
 $user->newSubscription($planId', $sourceId);
```

## License

CulqiCashier is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
