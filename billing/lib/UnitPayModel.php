<?php

class UnitPayModel
{
    private $mysqli;

    static function getInstance()
    {
        return new self();
    }

    private function __construct()
    {
        $this->mysqli = @new mysqli (
            Config::DB_HOST, Config::DB_USER, Config::DB_PASS, Config::DB_NAME
        );
        /* �������� ����������� */
        if (mysqli_connect_errno()) {
            throw new Exception('�� ������� ������������ � ��');
        }
    }
    
    // ������� ������������� ��� ��������� ������ ������ �������
    function get_id($tbl_dt)
    {   // ����������� �� ��������
        $result = $this->mysqli->query("SELECT id FROM ".$tbl_dt." ORDER BY `id` DESC");    
        $myrow = $result->fetch_array(MYSQLI_ASSOC);
        $num_rows = $result->num_rows;

        // ���� ���������� ������� ��������� � ��������� id, �����...
        if ($num_rows==$myrow['id'])
        { // ...������ ����� ������ ������� � ������� � ���������� ��������� id,
         // �������� �� ������� ��� ��������
           return $myrow['id'] + 1;
        } else { // ����������� �� �����������, �����...
            $result1 = $this->mysqli->query("SELECT id FROM ".$tbl_dt." ORDER BY `id` ASC");    
            $myrow1 = $result1->fetch_array(MYSQLI_ASSOC);
            $i = 1;
            do // ...���� "������" id � ��������� ������ �� ����� id
            {
                if ($i == $myrow1['id']) {
                    $i++;
                    continue;
                } else {
                    return $i;
                }
            }
            while ($myrow1 = $result1->fetch_array(MYSQLI_ASSOC));
        }
    } // get_id
    
    function getPriceById($itemId)
    {
        $query = '
                SELECT price FROM
                    data
                WHERE
                    id = "'.$this->mysqli->real_escape_string($itemId).'"
                LIMIT 1
            ';
            
        $result = $this->mysqli->query($query);
        return $result->fetch_object();
    }

    function createPayment($unitpayId, $account, $sum, $itemsCount)
    {
        $query = '
            INSERT INTO
                unitpay_payments (id, unitpayId, account, sum, itemsCount, dateCreate, status)
            VALUES
                (
                    "'.$this->get_id('unitpay_payments').'",
                    "'.$this->mysqli->real_escape_string($unitpayId).'",
                    "'.$this->mysqli->real_escape_string($account).'",
                    "'.$this->mysqli->real_escape_string($sum).'",
                    "'.$this->mysqli->real_escape_string($itemsCount).'",
                    NOW(),
                    0
                )
        ';

        return $this->mysqli->query($query);
    }

    function getPaymentByUnitpayId($unitpayId)
    {
        $query = '
                SELECT * FROM
                    unitpay_payments
                WHERE
                    unitpayId = "'.$this->mysqli->real_escape_string($unitpayId).'"
                LIMIT 1
            ';
            
        $result = $this->mysqli->query($query);
        return $result->fetch_object();
    }

    function confirmPaymentByUnitpayId($unitpayId)
    {
        $query = '
                UPDATE
                    unitpay_payments
                SET
                    status = 1,
                    dateComplete = NOW()
                WHERE
                    unitpayId = "'.$this->mysqli->real_escape_string($unitpayId).'"
                LIMIT 1
            ';
        return $this->mysqli->query($query);
    }
    
    function getAccountByName($account)
    {
        $sql = "
            SELECT
                *
            FROM
               ".Config::TABLE_ACCOUNT."
            WHERE
               ".Config::TABLE_ACCOUNT_NAME." = '".$this->mysqli->real_escape_string($account)."'
            LIMIT 1
         ";
         
        $result = $this->mysqli
            ->query($sql);
            
        return $result->fetch_object();
    }
    
    function donateForAccount($account, $count)
    {
        $query = "
            UPDATE
                ".Config::TABLE_ACCOUNT."
            SET
                ".Config::TABLE_ACCOUNT_DONATE." = ".Config::TABLE_ACCOUNT_DONATE." + ".$this->mysqli->real_escape_string($count)."
            WHERE
                ".Config::TABLE_ACCOUNT_NAME." = '".$this->mysqli->real_escape_string($account)."'
        ";
        
        return $this->mysqli->query($query);
    }
}