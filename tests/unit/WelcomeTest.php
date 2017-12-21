<?php

class WelcomeTest extends \PHPUnit_Framework_TestCase
{
    protected $welcome;

    public function setUp()
    {
        $this->welcome = new \App\Controllers\WelcomeController();
    }

    /** @test */
    public function welcome_should_returns_welcome_view()
    {
        $this->welcome->view('welcome', ['stylist'=>['name'=>'sithara', 'address'=>'Galle', 'session_rate'=> '15$', 'net_rate'=> '5 stars'], 'salon'=>['name'=>'ABC salon', 'address'=>'Galle', 'net_rate'=> '5 stars']]);
        $details = $this->welcome->getView();

        $this->assertArrayHasKey('view', $details);
        $this->assertArrayHasKey('data', $details);
        var_dump($details['data']);
        $this->assertEquals($details['view'],'app/views/welcome.view.php');
        $this->assertEquals($details['data'], ['stylist'=>['name'=>'sithara', 'address'=>'Galle', 'session_rate'=> '15$', 'net_rate'=> '5 stars'], 'salon'=>['name'=>'ABC salon', 'address'=>'Galle', 'net_rate'=> '5 stars']]);
    }

}