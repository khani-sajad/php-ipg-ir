<?php

/*
*                        _oo0oo_
*                       o8888888o
*                       88" . "88
*                       (| -_- |)
*                       0\  =  /0
*                     ___/`---'\___
*                   .' \\|     |// '.
*                  / \\|||  :  |||// \
*                 / _||||| -:- |||||- \
*                |   | \\\  -  /// |   |
*                | \_|  ''\---/''  |_/ |
*                \  .-\__  '-'  ___/-. /
*              ___'. .'  /--.--\  `. .'___
*           ."" '<  `.___\_<|>_/___.' >' "".
*          | | :  `- \`.;`\ _ /`;.`/ - ` : | |
*          \  \ `_.   \_ __\ /__ _/   .-` /  /
*      =====`-.____`.___ \_____/___.-`___.-'=====
*                        `=---='
* 
* 
*      ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
* 
*                Buddha Bless This Code
*                    To be Bug Free
* 
*  Created by nevercom at 7/20/16 6:48 PM
*/

namespace IPG\Contract;
abstract class AbstractIPGDatabaseManager {
    const VERIFIED    = 1;
    const SETTLED     = 2;
    const REVERSED    = 3;
    const IN_PROGRESS = 4;

    /**
     * This class handles all the database interaction supporting the Payment process
     *
     * @param string $username MySQL username
     * @param string $password MySQL password
     * @param string $db       Database name
     * @param string $host     MySQL database host address
     * @param string $port     MySQL database port
     * @param string $charset  Character Set for the database connection
     *
     * @throws \Exception if any error occur in connecting to database, an exception is thrown explaining the issue.
     */
    public abstract function __construct($username, $password, $db, $host = 'localhost', $port = '3306',
                                         $charset = 'utf8');

    /**
     * Stores a new transaction to the database, and returns PayID, which is used afterwards as a reference to this
     * transaction
     *
     * @param int    $transactionId Generated Transaction ID
     * @param string $bankName      IPG Class name
     * @param int    $amount        Payment amount (Rials)
     *
     * @return int PayID
     */
    public abstract function saveTransaction($transactionId, $bankName, $amount);

    /**
     * Updates the transaction info, will update Reference ID or Status
     *
     * @param int    $payId       Payment ID (Generated by {@link saveTransaction} method)
     * @param string $refId       Reference ID (Returned from IPG)
     * @param string $authorityId Authority ID (a parameter other than refrence ID which could be used in respective
     *                            ban BackOffice
     * @param int    $status      Transaction status. could be
     *                            <ul>
     *                            <li>if less than 1: Transaction <b>NOT completed</b> yet</li>
     *                            <li>{@link VERIFIED}</li>
     *                            <li>{@link SETTLED}</li>
     *                            <li>{@link REVERSED}</li>
     *                            </ul>
     *
     * @return bool
     */
    public abstract function updateTransaction($payId, $refId = NULL, $authorityId = NULL, $status = NULL);

    /**
     * Retrieves GateWay associated with given Payment ID
     *
     * @param int $payId Payment ID
     *
     * @return string Gateway Name
     */
    public abstract function getPaymentGateway($payId);

    /**
     * Returns transaction Status
     *
     * @param int $payId Payment ID
     *
     * @return int Transaction Status, Could be:
     *         <ul>
     *             <li>if less than 1: Transaction <b>NOT completed</b> yet</li>
     *             <li>{@link VERIFIED}</li>
     *             <li>{@link SETTLED}</li>
     *             <li>{@link REVERSED}</li>
     *         </ul>
     */
    public abstract function getTransactionStatus($payId);

    /**
     * @param string $referenceId
     *
     * @return boolean
     */
    public abstract function isReferenceIdUnique($referenceId);

    /**
     * Returns transaction Id of an specific Pay ID
     *
     * @param int $payId Payment ID
     *
     * @return int Transaction ID
     */
    public abstract function getTransactionId($payId);

    /**
     * Returns Transaction Amount
     *
     * @param int $payId Payment ID
     *
     * @return int Amount (Rials)
     */
    public abstract function getTransactionAmount($payId);

    /**
     * Logs a method call with its input to the database
     *
     * @param int    $paymentId  Payment ID
     * @param string $methodName Method Name
     * @param array  $input      Method input values
     *
     * @return int Insertd Item ID
     */
    public abstract function logMethodCall($paymentId, $methodName, $input);

    /**
     * Logs the return value of a method call
     *
     * @param int   $id
     * @param array $output
     * @param int   $statusCode
     *
     * @return bool
     */
    public abstract function logMethodResponse($id, $output, $statusCode = 0);

    /**
     * You can control logging of method calls to database with this method
     *
     * @param boolean $enabled if logging method calls to database should be enabled
     *
     */
    public abstract function setLoggingEnabled($enabled);

}