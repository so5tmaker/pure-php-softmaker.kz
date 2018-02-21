<?php

class Config
{ 
    // Ваш секретный ключ (из настроек проекта в личном кабинете unitpay.ru )
    const SECRET_KEY = '36aae2c635e8de16c6e58e26d255e230';
    // Стоимость товара в руб.
    const ITEM_PRICE = 33;

    // Таблица начисления товара, например `users`
    const TABLE_ACCOUNT = 'users';
    // Название поля из таблицы начисления товара по которому производится поиск аккаунта/счета, например `email`
    const TABLE_ACCOUNT_NAME = 'id';
    // Название поля из таблицы начисления товара которое будет увеличено на колличево оплаченого товара, например `sum`, `donate`
    const TABLE_ACCOUNT_DONATE= '';

    // Параметры соединения с бд
    // Хост
    const DB_HOST = 'mysql677.cp.idhost.kz';
    // Имя пользователя
    const DB_USER = 'u1088065_root';
    // Пароль
    const DB_PASS = ']budetr';
    // Название базы
    const DB_NAME = 'db1088065_db';
}