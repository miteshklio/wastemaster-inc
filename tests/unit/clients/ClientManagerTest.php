<?php

use Mockery as m;
use WasteMaster\v1\Clients\ClientManager;

class ClientManagerTest extends UnitTestCase
{
    protected $clients;
    protected $cities;

    /**
     * @var \WasteMaster\v1\Clients\ClientManager
     */
    protected $manager;

    public function setUp()
    {
        parent::setUp();

        $this->clients = m::mock('\App\Client');
        $this->cities  = m::mock('\App\City');
        $this->manager = new ClientManager($this->clients, $this->cities);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testGetClientSuccess()
    {
        $this->clients->shouldReceive('with->find')
                      ->once()
                      ->andReturn((object)[
                'id' => 1
            ]);

        $this->assertEquals(1, $this->manager->find(1)->id);
    }

    public function testGetThrowsOnFailure()
    {
        $this->setExpectedException('WasteMaster\v1\Clients\ClientNotFound');

        $this->clients->shouldReceive('with->find')
                      ->once()
                      ->andReturn();

        $this->manager->find(1);
    }

    public function testCreateThrowsOnNoData()
    {
        $this->setExpectedException('WasteMaster\v1\Clients\MissingRequiredFields');

        $this->manager->create();
    }

    public function testCreateThrowsWhenClientExistsByAddress()
    {
        $this->setExpectedException('WasteMaster\v1\Clients\ClientExists');

        $this->clients->shouldReceive('where->count')
                      ->once()
                      ->andReturn(1);

        $Client = $this->manager
            ->setCompany('company a')
            ->setAddress('123 Alphabet St')
            ->setCityID(2)
            ->setContactName('contact person')
            ->setContactEmail('foo@example.com')
            ->setAccountNum('abc123')
            ->setHaulerID(3)
            ->setWaste(1, 2, 3)
            ->setRecycling(4,5,6)
            ->create();
    }

    /**
     * @group single
     */
    public function testCreateSuccess()
    {
        $expects = [
            'company' => 'companya',
            'address' => '123AlphabetSt',
            'city_id' => 2,
            'contact_name' => 'contactperson',
            'contact_email' => 'foo@example.com',
            'account_num' => 'abc123',
            'hauler_id' => 3,
            'msw_qty' => 1,
            'msw_yards' => 2,
            'msw_per_week' => 3,
            'rec_qty' => 4,
            'rec_yards' => 5,
            'rec_per_week' => 6,
            'prior_total' => 100,
            'msw_price' => 123,
            'rec_price' => 456,
            'rec_offset' => 20,
            'fuel_surcharge' => 15.2,
            'env_surcharge' => 12.5,
            'recovery_fee' => 5.35,
            'admin_fee' => 10.2,
            'other_fees' => 1.23,
            'net_monthly' => 565,
            'gross_profit' => 434,
            'total' => 949,
            'archived' => 0,
        ];

        $this->clients->shouldReceive('where->count')
                      ->once()
                      ->andReturn(0);
        $this->clients->shouldReceive('create')
                      ->once()
                      ->with(Mockery::subset($expects))
                      ->andReturn((object)[
                'id' => 4
            ]);

        $Client = $this->manager
            ->setCompany('companya')
            ->setAddress('123AlphabetSt')
            ->setCityID(2)
            ->setContactName('contactperson')
            ->setContactEmail('foo@example.com')
            ->setAccountNum('abc123')
            ->setHaulerID(3)
            ->setWaste(1, 2, 3)
            ->setRecycling(4,5,6)
            ->setPriorTotal(100)
            ->setWastePrice(123)
            ->setRecyclePrice(456)
            ->setRecycleOffset(20)
            ->setFuelSurcharge(15.2)
            ->setEnvironmentalSurcharge(12.5)
            ->setRecoveryFee(5.35)
            ->setAdminFee(10.2)
            ->setOtherFees(1.23)
            ->setNet(565)
            ->setGross(434)
            ->setTotal(949)
            ->create();

        $this->assertEquals(4, $Client->id);
    }

    public function testUpdateFailsNotFound()
    {
        $this->setExpectedException('WasteMaster\v1\Clients\ClientNotFound');

        $this->clients->shouldReceive('find')
                      ->once()
                      ->with(12)
                      ->andReturn();

        $this->manager->update(12);
    }

    public function testUpdateFailsNoData()
    {
        $this->setExpectedException('WasteMaster\v1\Clients\NothingToUpdate');

        $this->clients->shouldReceive('find')
                      ->once()
                      ->with(12)
                      ->andReturn($this->clients);

        $this->manager->update(12);
    }

    public function testUpdateSuccess()
    {
        $this->clients->shouldReceive('find')
                      ->once()
                      ->with(12)
                      ->andReturn($this->clients);
        $this->clients->shouldReceive('fill')
                      ->once();
        $this->clients->shouldReceive('save')
                      ->once();

        $response = $this->manager
            ->setCompany('company b')
            ->update(12);

        $this->assertEquals($this->clients, $response);
    }

    public function testDeleteThrowsOnNotFound()
    {
        $this->setExpectedException('WasteMaster\v1\Clients\ClientNotFound');

        $this->clients->shouldReceive('with->find')
                      ->once()
                      ->with(12)
                      ->andReturn();

        $this->manager->delete(12);
    }

    public function testArchiveThrowsOnNotFound()
    {
        $this->setExpectedException('WasteMaster\v1\Clients\ClientNotFound');

        $this->clients->shouldReceive('with->find')
                      ->once()
                      ->with(12)
                      ->andReturn();

        $this->manager->archive(12);
    }
}
