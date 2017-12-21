<?php

class SearchTest extends \PHPUnit_Framework_TestCase
{
    protected $search;

    public function setUp()
    {
        $this->search = new \App\Controllers\WelcomeController();
    }

    /** @test */
    public function searchView_should_returns_search_view()
    {
        $this->search->view('advancedSearch', []);
        $details = $this->search->getView();
        $this->assertEquals($details['view'],'app/views/advancedSearch.view.php');
    }

    public function search_should_returns_search_results()
    {
        $this->search->view('advancedSearch/search', ['stylist'=>['name'=>'sithara', 'address'=>'Galle', 'session_rate'=> '15$', 'net_rate'=> '5 stars']]);
        $details = $this->search->getView();

        $this->assertArrayHasKey('view', $details);
        $this->assertArrayHasKey('data', $details);

        var_dump($details['data']);

        $this->assertEquals($details['view'],'app/views/advancedSearch.view.php');
        $this->assertEquals($details['data'], ['stylist'=>['name'=>'sithara', 'address'=>'Galle', 'session_rate'=> '15$', 'net_rate'=> '5 stars']]);
    }}