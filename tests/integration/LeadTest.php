<?php

class LeadTest extends IntegrationTestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $model = app('App\User');
        $this->user = $model->find(1);
    }

    public function testCanCreateLead()
    {
        $this->actingAs($this->user)
            ->visit(route('leads::home'))
            ->see('Manage Leads')
            ->click('New Lead')
            ->see('Create New Lead')
            ->type('Company A', 'company')
            ->type('123 That Street', 'address')
            ->select(1, 'service_area_id')
            ->type('Fred Durst', 'contact_name')
            ->type('fred.durst@example.com', 'contact_email')
            ->type('123abc', 'account_num')
            ->select(1, 'hauler_id')
            ->type(1, 'msw_qty')
            ->type(2, 'msw_yards')
            ->type(3, 'msw_per_week')
            ->type(4, 'rec_qty')
            ->type(5, 'rec_yards')
            ->type(6, 'rec_per_week')
            ->type('A grand scheme', 'notes')
            ->type(200, 'monthly_price')
            ->press('submit')
            ->see('Update Lead')
            ->seeInDatabase('leads', [
                'company' => 'Company A',
                'address' => '123 That Street',
                'service_area_id' => 1,
                'contact_name' => 'Fred Durst',
                'contact_email' => 'fred.durst@example.com',
                'account_num' => '123abc',
                'hauler_id' => 1,
                'msw_qty' => 1,
                'msw_yards' => 2,
                'msw_per_week' => 3,
                'rec_qty' => 4,
                'rec_yards' => 5,
                'rec_per_week' => 6,
                'notes' => 'A grand scheme',
                'monthly_price' => 200,
                'archived' => 0,
                'bid_count' => 0
            ]);
    }
}
