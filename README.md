# shaving-shop

[![Build Status](https://travis-ci.org/SamelVhatargh/shaving-shop.svg?branch=master)](https://travis-ci.org/SamelVhatargh/shaving-shop)

Дипломный проект по курсу Экстремальное программирование

## Установка
Ставим пакеты
```
php composer.phar install
```

Запускаем веб-сервер
```
php -S localhost:8234 -t public
```
Тесты
```
./vendor/bin/phpunit
```

## Прогресс
Пока что можно смотреть только информацию о текущей подписке и историю платежей. Возможность смены или отемны подписки не реализована. По конкретной дате можно тестить добавив гет-параметр test_date  к запросу: `http://localhost:8234/history?test_date=2018-02-05`