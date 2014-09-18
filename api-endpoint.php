<?php

require 'vendor/autoload.php';  //Include SlimFramework

$app = new \Slim\Slim();    //Create a new SlimFramework application

$data = file_get_contents("php://input");
$_PARAMS = json_decode($data);

// Define our API routes

//New Account - User has used app for the first time, create an account for them
$app->post('/accounts', function () {
    global $_PARAMS;

    $account_id = Validator::int($_PARAMS->account_id) ? $_PARAMS->account_id : NULL;
    $email      = Validator::email($_PARAMS->email) ? $_PARAMS->email : NULL;

    if( isset($account_id) && isset($email) ) {
        $response = array(
            'account_id'=> $account_id,
            'status'    => 'approved',
            'error'     => false,
            'msg'       => 'Account created'
        );
    }else{
        $response = array(
            'account_id'=> 0,
            'status'    => 'rejected',
            'error'     => true,
            'msg'       => 'Missing/invalid account_id or email'
        );
    }

    $json_response = json_encode($response);
    echo $json_response;

});

//Turn domain on - User enabled the app on a domain
$app->post('/domains', function () {
    global $_PARAMS;

    $account_id     = Validator::int($_PARAMS->account_id) ? $_PARAMS->account_id : NULL;
    $domain_id      = Validator::int($_PARAMS->domain_id) ? $_PARAMS->domain_id : NULL;
    $domain_name    = Validator::domain($_PARAMS->domain_name) ? $_PARAMS->domain_name : NULL;

    if( isset($_PARAMS->domain_options) ){
        //Validate possible domain options
    }

    if( isset($account_id) && isset($domain_id ) && isset($domain_name) ) {
        $response = array(
            'account_id'=> $account_id,
            'domain_id' => $domain_id,
            'status'    => 'approved',
            'error'     => false,
            'msg'       => 'Domain approved'
        );
    }else{
        $response = array(
            'account_id'=> 0,
            'domain_id' => 0,
            'status'    => 'rejected',
            'error'     => true,
            'msg'       => 'Missing/invalid account_id, domain_id or domain_name'
        );
    }

    $json_response = json_encode($response);
    echo $json_response;

});

//Turn domain off - User disabled the app for a domain
$app->delete('/domains/:id', function ($id) {
    global $_PARAMS;

    $account_id     = Validator::int($_PARAMS->account_id) ? $_PARAMS->account_id : NULL;
    $domain_id      = Validator::int($id) ? $id : NULL;

    if( isset($account_id) && isset($domain_id ) ) {
        $response = array(
            'account_id'=> $account_id,
            'domain_id' => $domain_id,
            'status'    => 'deleted',
            'error'     => false,
            'msg'       => 'Domain deleted'
        );
    }else{
        $response = array(
            'account_id'=> 0,
            'domain_id' => 0,
            'status'    => 'rejected',
            'error'     => true,
            'msg'       => 'Missing/invalid account_id or domain_id'
        );
    }

    $json_response = json_encode($response);
    echo $json_response;

});

//Change subscription - Modify the existing subscription for a domain (or add one if one does not exist)
$app->post('/subscriptions', function () {
    global $_PARAMS;
    
    $domain_id   = Validator::int($_PARAMS->domain_id) ? $_PARAMS->domain_id : NULL;
    $sub_plan    = Validator::text($_PARAMS->sub_plan) ? $_PARAMS->sub_plan : NULL;


    if( isset($domain_id ) && isset($sub_plan) ) {
        $response = array(
            'domain_id' => $domain_id,
            'status'    => 'updated',
            'error'     => false,
            'msg'       => 'Domain approved'
        );
    }else if( isset($domain_id ) ) {    //Delete the domain's subscription when an empty sub_plan is passed over
        $response = array(
            'domain_id' => $domain_id,
            'status'    => 'updated',
            'error'     => false,
            'msg'       => 'Domain subscription removed'
        );
    }else{
        $response = array(
            'domain_id' => 0,
            'status'    => 'rejected',
            'error'     => true,
            'msg'       => 'Missing/invalid account_id'
        );
    }

    $json_response = json_encode($response);
    echo $json_response;

});

$app->get('/', function(){
    echo "End point not found";
});

$app->run();


class Validator {
    static function int($int)       { return $int !== NULL ? (filter_var($int, FILTER_VALIDATE_INT) !== FALSE) : FALSE; }
    static function email($email)   { return $email !== NULL ? (filter_var($email, FILTER_VALIDATE_EMAIL) !== FALSE) : FALSE; }
    static function domain($domain) { return $domain !== NULL ? preg_match("/^([a-zA-Z0-9][-a-zA-Z0-9]*\.)+[-a-zA-Z0-9]{2,20}\$/",$domain) : FALSE; }
    static function text($text)     { return $text !== NULL ? preg_match("/^[\PC]+\$/u",$text)  : FALSE; }
}



