# Installation

1. Copy php_dio.dll into PHP extension path or download [here](http://pecl.php.net/package/dio/0.0.7/) 
2. Change $portName with your port (ex : "com3:")
3. Change $dataToSend with your request
4. Done !


# About DIO Direct I/O functions

The DIO functions have not been bundled with PHP for years,  but you can download the [DIO Package here](http://pecl.php.net/package/dio) (it's still in beta). The following article contains instructions on how to install and use it: How do I control a serial port using PHP? The required php_dio.dll file is available for PHP up to version 5.6, though the PHP manual says that the DLL is "Currently unavailable" (which is true if you’re using PHP 7).