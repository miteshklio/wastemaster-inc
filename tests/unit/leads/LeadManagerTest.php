<?php

use Mockery as m;

class LeadManagerTest extends UnitTestCase
{
    protected $leads;
    protected $cities;

    /**
     * @var \WasteMaster\v1\Leads\LeadManager
     */
    protected $manager;

    public function setUp()
    {
        parent::setUp();

        $this->leads = m::mock('\App\Lead');
        $this->cities = m::mock('\App\City');
        $this->manager = new \WasteMaster\v1\Leads\LeadManager($this->leads, $this->cities);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testGetLeadSuccess()
    {
        $this->leads->shouldReceive('with->find')
            ->once()
            ->andReturn((object)[
                'id' => 1
            ]);

        $this->assertEquals(1, $this->manager->find(1)->id);
    }

    public function testGetThrowsOnFailure()
    {
        $this->setExpectedException('WasteMaster\v1\Leads\LeadNotFound');

        $this->leads->shouldReceive('with->find')
            ->once()
            ->andReturn();

        $this->manager->find(1);
    }

    public function testCreateThrowsOnNoData()
    {
        $this->setExpectedException('WasteMaster\v1\Leads\MissingRequiredFields');

        $this->manager->create();
    }

    public function testCreateThrowsWhenLeadExistsByAddress()
    {
        $this->setExpectedException('WasteMaster\v1\Leads\LeadExists');

        $this->leads->shouldReceive('where->count')
            ->once()
            ->andReturn(1);

        $lead = $this->manager
            ->setCompany('company a')
            ->setAddress('123 Alphabet St')
            ->setCityID(2)
            ->setContactName('contact person')
            ->setContactEmail('foo@example.com')
            ->setAccountNum('abc123')
            ->setHaulerID(3)
            ->setWaste(1, 2, 3)
            ->setRecycling(4,5,6)
            ->setMonthlyPrice(123)
            ->create();
    }

    public function testCreateSuccess()
    {
        $this->leads->shouldReceive('where->count')
                    ->once()
                    ->andReturn(0);
        $this->leads->shouldReceive('create')
            ->once()
            ->andReturn((object)[
                'id' => 4
            ]);

        $lead = $this->manager
            ->setCompany('company a')
            ->setAddress('123 Alphabet St')
            ->setCityID(2)
            ->setContactName('contact person')
            ->setContactEmail('foo@example.com')
            ->setAccountNum('abc123')
            ->setHaulerID(3)
            ->setWaste(1, 2, 3)
            ->setRecycling(4,5,6)
            ->setMonthlyPrice(123)
            ->create();

        $this->assertEquals(4, $lead->id);
    }

    public function testUpdateFailsNotFound()
    {
        $this->setExpectedException('WasteMaster\v1\Leads\LeadNotFound');

        $this->leads->shouldReceive('find')
            ->once()
            ->with(12)
            ->andReturn();

        $this->manager->update(12);
    }

    public function testUpdateFailsNoData()
    {
        $this->setExpectedException('WasteMaster\v1\Leads\NothingToUpdate');

        $this->leads->shouldReceive('find')
                    ->once()
                    ->with(12)
                    ->andReturn($this->leads);

        $this->manager->update(12);
    }

    public function testUpdateSuccess()
    {
        $this->leads->shouldReceive('find')
            ->once()
            ->with(12)
            ->andReturn($this->leads);
        $this->leads->shouldReceive('fill')
            ->once();
        $this->leads->shouldReceive('save')
            ->once();

        $response = $this->manager
            ->setCompany('company b')
            ->update(12);

        $this->assertEquals($this->leads, $response);
    }

    public function testDeleteThrowsOnNotFound()
    {
        $this->setExpectedException('WasteMaster\v1\Leads\LeadNotFound');

        $this->leads->shouldReceive('with->find')
                    ->once()
                    ->with(12)
                    ->andReturn();

        $this->manager->delete(12);
    }

    public function testArchiveThrowsOnNotFound()
    {
        $this->setExpectedException('WasteMaster\v1\Leads\LeadNotFound');

        $this->leads->shouldReceive('with->find')
                    ->once()
                    ->with(12)
                    ->andReturn();

        $this->manager->archive(12);
    }
}
