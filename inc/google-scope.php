<?php

require_once DIR_PATH . '/vendor/autoload.php';

session_start();

// $outh2_client_id     = '1080281359595-mqk7mtfo36n785t33rfqa4hl0pvfe8b9.apps.googleusercontent.com';
// $outh2_client_secret = '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';

function get_client() {

    $client = new Google_Client( array( 'retry' => array( 'retries' => 2 ) ) );
    $client->setApplicationName( 'Google Calendar PHP Starter Application' );
    //     $client->setClientId( $outh2_client_id );
    //     $client->setClientSecret( $outh2_client_secret );
    //     $client->setRedirectUri();
    $client->setDeveloperKey( 'AIzaSyB_b_M79grEa6gsPJvdeAIkDEVtGKN4meY' );
    $client->setAccessType( 'offline' );
    $client->setScopes( array(
        'https://www.googleapis.com/auth/calendar',
        'https://www.googleapis.com/auth/calendar.events'
    ) );
    //
    //     //For loging out.
    //     if ( isset( $_GET[ 'logout' ] ) ) {
    //         unset( $_SESSION[ 'token' ] );
    //     }
    //
    //     // Step 2: The user accepted your access now you need to exchange it.
    //     if ( isset( $_GET[ 'code'] ) ) {
    //
    //         $client->authenticate($_GET['code']);
    //         $_SESSION[ 'token' ] = $client->getAccessToken();
    //         $redirect = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
    //         header( 'Location: ' . filter_var( $redirect, FILTER_SANITIZE_URL ) );
    //     }
    //
    //     // Step 1:  The user has not authenticated we give them a link to login
    //     if ( ! isset( $_SESSION[ 'token' ] ) ) {
    //
    //         $authUrl = $client->createAuthUrl();
    //
    //         echo '<a href="' . $authUrl . '">Connect Me!</a>';
    //     }
    //
    //     if ( isset( $_SESSION[ 'token' ] ) ) {
    //
    //     	$client->setAccessToken( $_SESSION[ 'token' ] );
    //     	print "LogOut";
    //     }
    //

    return $client;
}
