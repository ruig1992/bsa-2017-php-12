Binary Studio Academy PHP 2017. Task #12
====

### Цель
Целью данного задания является ознакомление с различными видами тестирования
приложений и инструментами для них.

***

### Формат API-запроса на аренду автомобиля
   Пользователь авторизирован в системе с `user_id`

```
    POST /api/cars/<car_id>/rent HTTP/1.1
    ...
    Accept: application/json
    Content-Type: application/json
    ...

    rented_from=<rented_from>
```
   , где <car_id> - Id автомобиля 
         <rented_from> - Id местоположения, откуда автомобиль будет взят в аренду 


### Формат API-запроса на возврат автомобиля
   Пользователь авторизирован в системе с `user_id`

```
    POST /api/cars/<car_id>/return HTTP/1.1
    ...
    Accept: application/json
    Content-Type: application/json
    ...

    returned_to=<returned_to>
```
   , где <car_id> - Id автомобиля  
         <returned_to> - Id местоположения, куда автомобиль будет возвращен  


### Формат API-ответа на запросы аренды и возврата автомобиля

```
    HTTP/1.1 (200 OK|401 Unauthorized|403 Forbidden|404 Not Found)
    ...
    Content-Type: application/json
    ...

    {
       "id"          : <Id записи>,
       "user_id"     : <Id текущего пользователя, который арендовал автомобиль>,
       "car_id"      : <Id автомобиля, который был арендован>,
       "price"       : <Фиксированная цена аренды>,
       "rented_from" : <Id местоположения, откуда автомобиль был взят в аренду>,
       "rented_at"   : "<дата и время, когда автомобиль поступил в аренду>",

       (следующие два поля - для запроса возврата)

       "returned_to" : <Id местоположения, куда автомобиль был возвращен>,
       "returned_at" : "<дата и время, когда автомобиль был возвращен>"
    }
```

***

### Описание классов

 - `App\Services\Rental\RentalService` - сервис по аренде автомобилей
 - `App\Services\Rental\ReturnService` - сервис по возврату автомобилей из аренды

Также используются:

 - `App\Entity\Rental` - модель "Аренда" (+ миграция `create_rentals_table`)
 - `App\Entity\Location` - модель "Расположение" (+ миграция `create_locations_table`)

 - `App\Managers\Eloquent\RentalManager` - менеджер по работе с `App\Entity\Rental`
 - `App\Managers\Eloquent\LocationManager` - менеджер по работе с `App\Entity\Location`

 - `App\Http\Controllers\CarRentalController` - обработка запросов аренды и возврата в браузере
 - `App\Http\Controllers\Api\CarRentalController` - обработка API-запросов аренды и возврата

 - `App\Services\Rental\Exceptions\...` - обработчики исключений аренды и возврата:

    - `User\UserNotFound` - пользователь не найден
    - `User\UserHasRentedCar` - ошибка новой аренды: пользователь уже арендовал автомобиль
    - `Car\CarNotFound` - автомобиль не найден
    - `Car\CarNotRented` - ошибка возврата: автомобиль не арендован
    - `Car\CarAlreadyRented` - ошибка новой аренды: автомобиль уже арендован

 - `App\Policies\CarRentalPolicy` - сокрытие элементов в браузере для пользователей, 
  которые не могут арендовать/возвращать автомобили.

***

### Запуск тестов

   `./vendor/bin/phpunit`

или

 1. `./vendor/bin/phpunit --testsuite rental_service`
 2. `./vendor/bin/phpunit --testsuite return_service`
 3. `./vendor/bin/phpunit --testsuite rentalreturn_api`

### Описание тестов

`Unit\RentalServiceTest.php` - проверка сервиса аренды автомобилей.

 - `testRentCar()` - проверка аренды автомобиля: проведение аренды, получение корретной сущности `App\Entity\Rental`
 - `testRentWhenUserNotFound()` - проверка аренды пользователем, который не найден (не существует)
 - `testRentWhenCarNotFound()` - проверка аренды автомобиля, который не найден (не существует)
 - `testRentWhenUserHasRentedCar()` - проверка аренды в случае, если у пользователя уже имеется автомобиль
 - `testRentWhenCarAlreadyRented()` - проверка аренды автомобиля, который уже арендован

`Unit\ReturnServiceTest.php` - проверка сервиса возврата автомобилей.

 - `testReturnCar()` - проверка возврата автомобиля: проведение возврата, получение корретной сущности `App\Entity\Rental`
 - `testReturnWhenUserNotFound()` - проверка возврата автомобиля пользователем, который не найден (не существует)
 - `testReturnWhenCarNotFound()` - проверка возврата автомобиля, который не найден (не существует)
 - `testReturnWhenCarNotRentedByAnyUser()` - проверка возврата автомобиля, который не был взят в аренду ни одним из пользователей _(проверка корретности сообщения)_
 - `testReturnWhenCarNotRentedByThisUser()` - тоже самое, только для текущего пользователя

`Feature\RentalApiTest.php` - проверка сервиса аренды через API

 - `testUserUnAuthenticatedRentCar()` - проверка аренды неаутентифицированным пользователем (ошибка 401)
 - `testRentCar()` - проверка аренды в различных случаях, описанных выше для `Unit\RentalServiceTest.php`

`Feature\ReturnApiTest.php` - проверка сервиса возврата через API

 - `testUserUnAuthenticatedReturnCar()` - проверка возврата неаутентифицированным пользователем (ошибка 401)
 - `testReturnCar()` - проверка возврата в различных случаях, описанных выше для `Unit\ReturnServiceTest.php`

