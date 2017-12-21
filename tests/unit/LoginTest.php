<?php

class LoginTest extends \PHPUnit_Framework_TestCase
{
    protected $login;

    public function setUp()
    {
        $this->login = new \App\Controllers\AccountController();
    }

    /** @test */
    public function login_to_my_account()
    {
        $userData = ['firstName' => 'sithara', 'lastName' => 'sewwanthi', 'email' => 'sewsith@gmail.com', 'password' => md5(1234), 'user_role' => '3'];
        $this->login->setUser($userData);
        $user = $this->login->getUser();

        $this->login->login('sewsith@gmail.com', 1234);

        $details = $this->login->getView();

        $this->assertArrayHasKey('view', $details);
        $this->assertArrayHasKey('data', $details);
//        var_dump($details['data']);


//        **************if erros******
//        $this->assertEquals($details['view'],'app/views/activatedLogin.view.php');
//
////        ****************If there is no user matching above details****************
//        $this->assertEquals($details['data'],["errors"=>"Incorrect Email or Password"]);

//        **********login success************

        //************if user_role=3***********
        $this->assertEquals($details['view'],'app/views/bothUsers.view.php');

        //************if user_role=1***********
//        $this->assertEquals($details['view'],'app/views/salon.view.php');
////
//        //************if user_role=2***********
//        $this->assertEquals($details['view'],'app/views/stylist.view.php');
    }
}