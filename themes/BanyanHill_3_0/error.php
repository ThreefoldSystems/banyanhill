<?php

$status = $_SERVER['REDIRECT_STATUS'];
$codes = array(
//	400 => 'Bad Request',
//	401 => 'Unauthorized',
//	402 => 'Payment Required',
//	403 => array('403 Forbidden', 'The server has refused to fulfill your request.'),
//	404 => array('404 Not Found', 'The document/file requested was not found on this server.'),
//	405 => array('405 Method Not Allowed', 'The method specified in the Request-Line is not allowed for the specified resource.'),
//	408 => array('408 Request Timeout', 'Your browser failed to send a request in the time allowed by the server.'),
//	500 => array('500 Internal Server Error', 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
//	502 => array('502 Bad Gateway', 'The server received an invalid response from the upstream server while trying to fulfill the request.'),
//	503 => 'Service Unavailable',
//	504 => array('504 Gateway Timeout', 'The upstream server failed to send a request in the time allowed by the server.'),
//	505 => 'HTTP Version Not Supported',
//	506 => 'Variant Also Negotiates',
//	507 => 'Insufficient Storage',
//	509 => 'Bandwidth Limit Exceeded',
//	510 => 'Not Extended'
 	100 => 'Continue',
    101 => 'Switching Protocols',
    102 => 'Processing',
    103 => 'Checkpoint',
    200 => 'OK',
    201 => 'Created',
    202 => 'Accepted',
    203 => 'Non-Authoritative Information',
    204 => 'No Content',
    205 => 'Reset Content',
    206 => 'Partial Content',
    207 => 'Multi-Status',
    300 => 'Multiple Choices',
    301 => 'Moved Permanently',
    302 => 'Found',
    303 => 'See Other',
    304 => 'Not Modified',
    305 => 'Use Proxy',
    306 => 'Switch Proxy',
    307 => 'Temporary Redirect',
    400 => 'Bad Request',
    401 => 'Unauthorized',
    402 => 'Payment Required',
    403 => 'Forbidden',
    404 => 'Not Found',
    405 => 'Method Not Allowed',
    406 => 'Not Acceptable',
    407 => 'Proxy Authentication Required',
    408 => 'Request Timeout',
    409 => 'Conflict',
    410 => 'Gone',
    411 => 'Length Required',
    412 => 'Precondition Failed',
    413 => 'Request Entity Too Large',
    414 => 'Request-URI Too Long',
    415 => 'Unsupported Media Type',
    416 => 'Requested Range Not Satisfiable',
    417 => 'Expectation Failed',
    418 => 'I\'m a teapot',
    422 => 'Unprocessable Entity',
    423 => 'Locked',
    424 => 'Failed Dependency',
    425 => 'Unordered Collection',
    426 => 'Upgrade Required',
    449 => 'Retry With',
    450 => 'Blocked by Windows Parental Controls',
    500 => 'Internal Server Error',
    501 => 'Not Implemented',
    502 => 'Bad Gateway',
    503 => 'Service Unavailable',
    504 => 'Gateway Timeout',
    505 => 'HTTP Version Not Supported',
    506 => 'Variant Also Negotiates',
    507 => 'Insufficient Storage',
    509 => 'Bandwidth Limit Exceeded',
    510 => 'Not Extended'	
);

$title = $codes[$status][0];
$message = $codes[$status][1];
if ($title == false || strlen($status) != 3) {
       $message = 'Please supply a valid status code.';
}
// Insert headers here
echo '<h1>'.$title.'</h1>
<p>'.$message.'</p>';
// Insert footer here

?>