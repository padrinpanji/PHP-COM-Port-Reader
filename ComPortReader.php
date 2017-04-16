<?php

//-- settings --//

//brainboxes serial ports
//on 'nix start with cu.usbserial-
//on windows starts with com : must be lower case in windows and end with a colon
$portName = 'com8:';
$baudRate = 9600;
$bits = 8;
$spotBit = 1;
$dataToSend = "17"; //Echo-test

header( 'Content-type: text/plain; charset=utf-8' ); 
?>
Serial Port Test
================
<?php


function echoFlush($string)
{
	echo $string . "\n";
	flush();
	ob_flush();
}

function debug_script($string)
{
	echo '<pre>'; print_r($string); echo '</pre>';
}

if(!extension_loaded('dio'))
{
	echoFlush( "PHP Direct IO does not appear to be installed for more info see: http://www.php.net/manual/en/book.dio.php" );
	exit;
}

try 
{
	//the serial port resource
	$bbSerialPort;
	
	echoFlush(  "Connecting to serial port: {$portName}" );
	
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
	{ 
		$bbSerialPort = dio_open($portName, O_RDWR );
		//we're on windows configure com port from command line
		exec("mode {$portName} baud={$baudRate} data={$bits} stop={$spotBit} parity=n xon=on");
	} 
	else //'nix
	{
		$bbSerialPort = dio_open($portName, O_RDWR | O_NOCTTY | O_NONBLOCK );
		dio_fcntl($bbSerialPort, F_SETFL, O_SYNC);
		//we're on 'nix configure com from php direct io function
		dio_tcsetattr($bbSerialPort, array(
			'baud' => $baudRate,
			'bits' => $bits,
			'stop'  => $spotBit,
			'parity' => 0
		));
	}
	
	if(!$bbSerialPort)
	{
		echoFlush( "Could not open Serial port {$portName} ");
		exit;
	}
	
	// send data
	
	echoFlush( "Writing to serial port data: \"{$dataToSend}\"" );
	$bytesSent = dio_write($bbSerialPort, $dataToSend );
	echoFlush( "Sent: {$bytesSent} bytes" );
	
	date_default_timezone_set ("Asia/Jakarta");
    
    #custom datetime
    $delay = 10;
    $end_time = strtotime(date("Y-m-d H:i:s", time() + $delay)); //10 seconds
	echoFlush(  "Waiting for {$delay} seconds to recieve data on serial port" );
    
	
	while (strtotime(date("Y-m-d H:i:s")) < $end_time) {
	
		$data = dio_read($bbSerialPort, 256); //this is a blocking call
		if ($data) {
			echoFlush(  "Data Recieved: ". $data );
		}
	}
	
	echoFlush(  "Closing Port" );
	
	dio_close($bbSerialPort);

} 
catch (Exception $e) 
{
	echoFlush(  $e->getMessage() );
	exit(1);
} 

?>