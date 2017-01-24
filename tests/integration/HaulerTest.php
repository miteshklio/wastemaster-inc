<?php

class HaulerTest extends IntegrationTestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $model = app('App\User');
        $this->user = $model->find(1);
    }

//    public function insertLead()
//    {
//        \DB::table('leads')->insert([
//            'company' => 'Company A',
//            'address' => '123 That Street',
//            'service_area_id' => 1,
//            'contact_name' => 'Fred Durst',
//            'contact_email' => 'fred.durst@example.com',
//            'account_num' => '123abc',
//            'hauler_id' => 1,
//            'msw_qty' => 1,
//            'msw_yards' => 2,
//            'msw_per_week' => 3,
//            'rec_qty' => 4,
//            'rec_yards' => 5,
//            'rec_per_week' => 6,
//            'notes' => 'A grand scheme',
//            'monthly_price' => 200,
//            'archived' => 0,
//            'bid_count' => 0,
//            'status' => \App\Lead::NEW
//        ]);
//
//        return \DB::table('leads')
//            ->where('company', 'Company A')
//            ->first();
//    }


    public function testCanCreateHauler()
    {
        $this->actingAs($this->user)
            ->visit(route('haulers::home'))
            ->see('Manage Haulers')
            ->click('New Hauler')
            ->see('Create New Hauler')
            ->type('New Hauler A', 'name')
            ->select(1, 'service_area_id')
            ->click('recycle')
            ->click('waste')
            ->type('foo@example.com', 'emails')
            ->press('Create Hauler')
            ->see('Update Lead')
            ->seeInDatabase('leads', [
                'company' => 'New Hauler A',
                'service_area_id' => 1,
            ]);
    }

//    public function testCanEditHauler()
//    {
//        $lead = $this->insertLead();
//
//        $this->actingAs($this->user)
//            ->visit(route('leads::show', ['id' => $lead->id]))
//            ->see('Update Lead')
//            ->select(2, 'service_area_id')
//            ->press('submit')
//            ->see('Update Lead')
//            ->seeInDatabase('leads', [
//                'company' => 'Company A',
//                'service_area_id' => 2
//            ]);
//    }

}
