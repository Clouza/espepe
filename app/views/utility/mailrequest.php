<?php

// cek token
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    $tokenInDatabase = 'L3nKjw9RuHYyz16uqAoGEWTI1O6dFzQ09c3fqmOja7wi92t3PREBDukBIvCj5G4rJD/1oQmskIwYDtgC3R5RnA==';

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
