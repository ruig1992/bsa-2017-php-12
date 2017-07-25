Binary Studio Academy PHP 2017
====

### Домашнее задание #12

#### Цель
Целью данного задания является ознакомление с различными видами тестирования
приложений и инструментами для них.

***

#### Установка

Предполагается, что у вас уже есть приложение с прошлых заданий, поэтому следует
использовать его.

#### Задания

Реализовать возможность аренды и возврата автомобилей в текущем приложении.
Аренда возможна только для зарегистрированных пользователей, авторизованых в приложении.
Для хранения арендованных автомобилей вам может понадобиться дополнительная таблица
типа ```rentals``` или ```bookings```.

Для заданий 1 и 2 рекомендуется использовать PHPUnit + любые средства для создания mock- объектов.

Для задания 3 рекомендуется наследование класса ```TestCase``` из фреймворка, т.к. он уже содержит встроенный
HTTP-клиент.

##### Задание 1
Реализовать класс ```RentalService```, который будет заниматься арендой автомобилей,
и unit-test ```RentalServiceTest``` для его проверки.

Для того, чтобы арендовать автомобиль необходима следующая информация:

- ```car_id``` - ID автомобиля
- ```user_id``` - ID текущего пользователя
- ```rented_from``` - место (адрес), откуда машина была взята в аренду
- ```rented_at``` - время (DATETIME), когда машина поступила в аренду. Проставляется автоматически
- ```price``` - фиксированная стоимость. Некая константа или значение из конфигурационного файла.

Правила аренды

- Пользователь не может арендовать автомобиль, если за ним уже числится хотя бы один,
который он не вернул.

- Нельзя арендовать несуществующий (удаленный) автомобиль

- Нельзя арендовать арендованный кем-то автомобиль

- Несуществующий пользователь не может арендовать

##### Задание 2
Реализовать класс ```ReturnService```, который будет отвечать за возврат арендованных автомобилей,
и unit-test ```ReturnServiceTest```, чтобы его проверить.

Для того, чтобы вернуть арендованный автомобиль, необходима следующая информация:

- ```car_id``` - ID автомобиля
- ```user_id``` - ID текущего пользователя
- ```returned_to``` - место (адрес), куда машину вернули
- ```returned_at``` - время (DATETIME), когда машину вернули. Проставляется автоматически

Правила возврата

- Нельзя вернуть не существующий(удаленный) автомобиль

- Нельзя вернуть автомобиль, который арендовал другой пользователь

- Несуществующий пользователь не может вернуть автомобиль

#### Задание 3
Реализовать REST-api для аренды и возврата автомобилей. Например, в виде следующих маршрутов

```/cars/rent``` - POST

```/cars/return``` - POST.

Форматы запроса и ответа вы проектируете сами и описываете в файле README.

Необходимо реализовать функциональные тесты ```RentalApiTest``` и ```ReturnApiTest``` в виде
"запрос-ответ".

Пример, [Laravel Testing](https://laravel.com/docs/5.4/http-tests).

Тестовые данные можно подготовить, используя библиотеку [Faker](https://github.com/fzaninotto/Faker).


#### Проверка

Поскольку тесты для данного задания будете писать вы сами, инструкции по запуску лучше предоставить
в README к репозиторию.

#### Приём решений

В идеале разместить ваше решение в отдельном репозитории или форке текущего на Github или Bitbucket
и прислать ссылку на него.
