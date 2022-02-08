<?php

// cek token
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $tokenInDatabase = 'sBODUz5AMlsA2JSvtITSjBWDIhcrfumgZQ1nKvyc3IUQ+fZnvlFO+2+MyWrsxkqp5cjck2MbPAw18bkb1wwkw==';

    // check token if match
    if ($token == $tokenInDatabase) {
        echo 'Token match!';

        // check if token expired
        if (time() - $tokenInDatabase < (60 * 60 * 24)) {
        } else {
            // delete token in database
            // ...


            echo 'Token kedaluarsa anjg';
        }
    } else {
        echo 'Token salah anjg';
    }
} else {
    echo 'Token tidak ada anjg <hr>';
}
