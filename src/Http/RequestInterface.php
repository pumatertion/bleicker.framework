<?php
namespace Bleicker\Framework\Http;

use Bleicker\Request\MainRequestInterface;
use Symfony\Component\HttpFoundation\RequestInterfaceHttp;

/**
 * Class Request
 *
 * @package Bleicker\Framework\Http
 */
interface RequestInterface extends RequestInterfaceHttp, MainRequestInterface, \Bleicker\Request\RequestInterface {

}