<?php
/**
 * Global Error Handler for Sikumbang
 * Logs errors to file and displays a friendly message to users in production.
 */

// Set error logging path
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../error_log.txt');

// Custom Exception Handler
function customExceptionHandler($exception) {
    $message = "[" . date("Y-m-d H:i:s") . "] Uncaught Exception: " . $exception->getMessage() . 
               " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n" .
               "Stack trace: " . $exception->getTraceAsString() . "\n";
    
    error_log($message);

    // If we are in a CLI environment, just print
    if (php_sapi_name() === 'cli') {
        echo "An error occurred. Check logs.\n";
        return;
    }

    // Redirect to friendly error page
    if (!headers_sent()) {
        header("HTTP/1.1 500 Internal Server Error");
        // Check relative path to 500.html
        if (file_exists(__DIR__ . '/../500.html')) {
            readfile(__DIR__ . '/../500.html');
        } else {
            echo "<h1>Terjadi Kesalahan Sistem</h1><p>Maaf, telah terjadi kesalahan internal. Tim teknis telah diberitahu.</p>";
        }
        exit();
    }
}

// Custom Error Handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return false;
    }

    $message = "[" . date("Y-m-d H:i:s") . "] Error ($errno): $errstr in $errfile on line $errline\n";
    error_log($message);

    // Don't execute PHP internal error handler
    return true; 
}

// Shutdown Function (to catch Fatal Errors)
function shutdownHandler() {
    $error = error_get_last();
    if ($error !== NULL && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
        $message = "[" . date("Y-m-d H:i:s") . "] Fatal Error: " . $error['message'] . 
                   " in " . $error['file'] . " on line " . $error['line'] . "\n";
        error_log($message);

        if (!headers_sent()) {
            header("HTTP/1.1 500 Internal Server Error");
            if (file_exists(__DIR__ . '/../500.html')) {
                readfile(__DIR__ . '/../500.html');
            } else {
                echo "<h1>Sistem Sedang Sibuk</h1><p>Mohon maaf, terjadi kesalahan fatal. Silakan coba beberapa saat lagi.</p>";
            }
        }
    }
}

set_exception_handler('customExceptionHandler');
set_error_handler('customErrorHandler');
register_shutdown_function('shutdownHandler');
?>
