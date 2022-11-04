<?php

/**
 * ExchangeController.
 *
 * PHP version 7.4
 *
 * Classe responsável pelo processamento e cálculo das conversões.
 *
 * @category Class
 * @package  Controller
 * @author   Cino Campra <gino@gcampra.com.br>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/apiki/back-end-challenge
 */

namespace App\Controller;

/** 
 * Class ExchangeController
 *
 * @category Class
 * @package  Controller
 * @author   Cino Campra <gino@gcampra.com.br>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     https://github.com/apiki/back-end-challenge
 */
class ExchangeController
{

    private $_requestMethod;
    private $_amount;
    private $_from;
    private $_to;
    private $_rate;

    /** 
     * Cria a instancia
     * 
     * @param string $requestMethod Verbo da API
     * @param int    $amount        Valor a ser convertido
     * @param string $from          Moeda a ser convetida
     * @param string $to            Moeda para qual será
     *                              convetida
     * @param int    $rate          Taxa de conversão a ser
     *                              usada
     */
    public function __construct($requestMethod, $amount, $from, $to, $rate)
    {
        $this->_requestMethod = $requestMethod;
        $this->_amount = $amount;
        $this->_from = $from;
        $this->_to = $to;
        $this->_rate = $rate;
    }

    /** 
     * Processa a requisição da API
     * 
     * @return json 
     */
    public function processRequest()
    {
        if ($this->_requestMethod === 'GET') {
            switch (true) {
            case ($this->_amount === '' || !is_numeric($this->_amount)):
                $response = $this->_badRequestResponse();
                break;
            case ($this->_rate === '' || !is_numeric($this->_rate)):
                $response = $this->_badRequestResponse();
                break;
            case ($this->_from === 'BRL' && $this->_to === 'USD'):
                $response = $this->_calculateBrlUsd($this->_amount, $this->_rate);
                break;
            case ($this->_from === 'BRL' && $this->_to === 'EUR'):
                $response = $this->_calculateBrlEur($this->_amount, $this->_rate);
                break;
            case ($this->_from === 'USD' && $this->_to === 'BRL'):
                $response = $this->_calculateUsdBrl($this->_amount, $this->_rate);
                break;
            case ($this->_from === 'EUR' && $this->_to === 'BRL'):
                $response = $this->_calculateEurBrl($this->_amount, $this->_rate);
                break;
            default:
                $response = $this->_badRequestResponse();
                break;
            }
        } else {
            $response = $this->_badRequestResponse();
        }
        header($response['status_code_header']);
        echo $response['body'];
        return;
    }

    /** 
     * Converte Real para Dolar
     * 
     * @param int $amount Valor a ser convertido
     * @param int $rate   Taxa de conversão
     *                    a ser usada
     * 
     * @return json 
     */
    private function _calculateBrlUsd($amount, $rate)
    {
        $result = array();
        $result = ['valorConvertido' => $amount * $rate, 'simboloMoeda' => '$'];
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }


    /** 
     * Converte Real para Euro
     * 
     * @param int $amount Valor a ser convertido
     * @param int $rate   Taxa de conversão
     *                    a ser usada
     * 
     * @return json 
     */
    private function _calculateBrlEur($amount, $rate)
    {
        $result = array();
        $result = ['valorConvertido' => $amount * $rate, 'simboloMoeda' => '€'];
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }


    /** 
     * Converte Dolar para Real 
     * 
     * @param int $amount Valor a ser convertido
     * @param int $rate   Taxa de conversão
     *                    a ser usada
     * 
     * @return json 
     */
    private function _calculateUsdBrl($amount, $rate)
    {
        $result = array();
        $result = ['valorConvertido' => $amount * $rate, 'simboloMoeda' => 'R$'];
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }


    /** 
     * Converte Euro para Real
     * 
     * @param int $amount Valor a ser convertido
     * @param int $rate   Taxa de conversão
     *                    a ser usada
     * 
     * @return json 
     */
    private function _calculateEurBrl($amount, $rate)
    {
        $result = array();
        $result = ['valorConvertido' => $amount * $rate, 'simboloMoeda' => 'R$'];
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    /** 
     * Retorna Bad Request Exception
     * 
     * @return Exception
     */
    private function _badRequestResponse()
    {
        $result = array('message' => 'HTTP/1.1 400 Bad Request');
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode($result);
        return $response;
    }
}
