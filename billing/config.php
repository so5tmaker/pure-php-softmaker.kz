<?php

class Config
{ 
    // ��� ��������� ���� (�� �������� ������� � ������ �������� unitpay.ru )
    const SECRET_KEY = '36aae2c635e8de16c6e58e26d255e230';
    // ��������� ������ � ���.
    const ITEM_PRICE = 33;

    // ������� ���������� ������, �������� `users`
    const TABLE_ACCOUNT = 'users';
    // �������� ���� �� ������� ���������� ������ �� �������� ������������ ����� ��������/�����, �������� `email`
    const TABLE_ACCOUNT_NAME = 'id';
    // �������� ���� �� ������� ���������� ������ ������� ����� ��������� �� ��������� ���������� ������, �������� `sum`, `donate`
    const TABLE_ACCOUNT_DONATE= '';

    // ��������� ���������� � ��
    // ����
    const DB_HOST = 'mysql677.cp.idhost.kz';
    // ��� ������������
    const DB_USER = 'u1088065_root';
    // ������
    const DB_PASS = ']budetr';
    // �������� ����
    const DB_NAME = 'db1088065_db';
}