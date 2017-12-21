<?php

class ActivateUserTest extends \PHPUnit_Framework_TestCase
{
    protected $activate;

    public function setUp()
    {
        $this->activate = new \App\Controllers\AccountController();
    }

    /** @test */
    public function activateUser_if_user_exists()
    {
        $userData=['firstName'=>'sithara', 'lastName'=>'sewwanthi', 'email'=>'sewsith@gmail.com', 'password'=>md5(1234), 'user_role'=>'1'];
        $this->activate->setUser($userData);
        $user =  $this->activate->getUser();
        var_dump($user);

        $this->activate->activateUser('sewsith@gmail.com', 1234, 'bff435cf2cfaf786871f54bdec2ea31a');

        $details = $this->activate->getView();

        $this->assertArrayHasKey('view', $details);
        $this->assertArrayHasKey('data', $details);
//        var_dump($details['data']);



//        **********if User not exist in the database**************
//        $this->assertEquals($details['view'],'app/views/activatedLogin.view.php');



//        ****************If there is no user matching above details****************
//        $this->assertEquals($details['data'],["errors"=>"Sorry, we cant activate your account. Something went wrong!!!"]);



//        **************password or email incorrect*******************
//        $this->assertEquals($details['data'],["errors"=>"Incorrect Email or Password"]);



//        if usr exist and details matched

//        //************if user_role=3***********
//        $this->assertEquals($details['view'],'app/views/bothUsers.view.php');

        //************if user_role=1***********
        $this->assertEquals($details['view'],'app/views/salon.view.php');
//
//        //************if user_role=2***********
//        $this->assertEquals($details['view'],'app/views/stylist.view.php');
    }
}