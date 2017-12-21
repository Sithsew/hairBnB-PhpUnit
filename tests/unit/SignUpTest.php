<?php

class SignUpTest extends \PHPUnit_Framework_TestCase
{
    protected $signUp;

    public function setUp()
    {
        $this->signUp = new \App\Controllers\AccountController;
    }

    public function test_i_can_get_the_view_and_data()
    {
        $this->signUp->view('login','firstnameError', 'lastname');
        $details = $this->signUp->getView();

        $this->assertArrayHasKey('view', $details);
        $this->assertArrayHasKey('data', $details);

        $this->assertEquals($details['view'],'app/views/login.view.php');
        $this->assertEquals($details['data'],'firstnameError', 'lastname');

    }

    /** @test */
    public function create_account_by_correctly_filling_text_fields()
    {
        $this->signUp->signUp('sithara', 'sewwanthi', 'sewsith@gmail.com', '1234', 1, 1, 'sithra', 'sewdi', 0);
        $details = $this->signUp->getView();

        $this->assertArrayHasKey('view', $details);
        $this->assertArrayHasKey('data', $details);

//        var_dump($details['data']);

        $user = $this->signUp->getUser();

        $this->assertEquals($user,['id'=>1, 'firstName'=>'sithara', 'lastName'=>'sewwanthi', 'email'=>'sewsith@gmail.com',"password"=>"81dc9bdb52d04dc20036dbd8313ed055", "user_role"=>3, "temp_password"=> "bff435cf2cfaf786871f54bdec2ea31a" ]);
        $this->assertEquals($details['view'],'app/views/welcome.view.php');

//        **********************Email already exists********************
//        $this->assertEquals($details['view'],'app/views/signUpPage.view.php');
//        $this->assertEquals($details['data'], ["errors"=>["emailErr"=> "Email already exist."]]);

    }


    /** @test */
    public function try_create_account_with_empty_fields()
    {
        $this->signUp->signUp('', '', '', '','', '', 'sithra', 'sewdi', '');
        $details = $this->signUp->getView();

        $this->assertArrayHasKey('view', $details);
        $this->assertArrayHasKey('data', $details);

//        var_dump($details['data']);

        $this->assertEquals($details['view'],'app/views/signUpPage.view.php');
        $this->assertEquals($details['data'], ["errors"=>["firstnameErr"=> "First Name is required ","lastnameErr"=> "Last Name is required ", "emailErr"=> "Email is required ", "passwrdErr" =>"Password is required ", "acc_typeErr"=>"Account Type Required" ]]);

    }

    /** @test */
    public function try_to_create_account_with_empty_business_name_fields_acording_to_account_type()
    {
        $this->signUp->signUp('', '', '', '','', '1', 'j', '', '');
        $details = $this->signUp->getView();

        $this->assertArrayHasKey('view', $details);
        $this->assertArrayHasKey('data', $details);

//        var_dump($details['data']);

        $this->assertEquals($details['view'],'app/views/signUpPage.view.php');
        $this->assertEquals($details['data'], ["errors"=>["firstnameErr"=> "First Name is required ","lastnameErr"=> "Last Name is required ", "emailErr"=> "Email is required ", "passwrdErr" =>"Password is required ", "acc_typeErr"=> "Fill Your Business Name or Account Type Correctly"]]);

    }

    /** @test */
    public function try_create_account_with_validation()
    {
        $this->signUp->signUp('1h2', '12', 's@f', 'g','1', '3', 'asdfghjkl1234567890asdfghjklqwertysasfdqwertjkliuytreswdasqwertfa', 'asdfghjkl1234567890asdfghjklqwertysasfdqwertjkliuytreswdasqwertfa', '');
        $details = $this->signUp->getView();

        $this->assertArrayHasKey('view', $details);
        $this->assertArrayHasKey('data', $details);

//        var_dump($details['data']);

        $this->assertEquals($details['view'],'app/views/signUpPage.view.php');
        $this->assertEquals($details['data'], ["errors"=>["firstnameErr"=> "Only letters and white space allowed","lastnameErr"=> "Only letters and white space allowed", "emailErr"=> "Invalid email format", "passwrdErr" =>"Password must have atleast 4 characters and Not More than 64 characters", "salonErr"=> "Your salon Business Name is too long","stylistErr"=> "Your Business Name is too long" ]]);

    }
}