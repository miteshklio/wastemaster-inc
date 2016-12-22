<?php

use App\Bid;
use Mockery as m;
use WasteMaster\v1\Bids\BidManager;

class BidManagerTest extends UnitTestCase
{
    protected $bids;

    /**
     * @var \WasteMaster\v1\Bids\BidManager
     */
    protected $manager;

    public function setUp()
    {
        parent::setUp();

        $this->bids = m::mock('\App\Bid');
        $this->manager = new BidManager($this->bids);
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testGetBidSuccess()
    {
        $this->bids->shouldReceive('with->find')
                      ->once()
                      ->andReturn((object)[
                'id' => 1
            ]);

        $this->assertEquals(1, $this->manager->find(1)->id);
    }

    public function testGetThrowsOnFailure()
    {
        $this->setExpectedException('WasteMaster\v1\Bids\BidNotFound');

        $this->bids->shouldReceive('with->find')
                      ->once()
                      ->andReturn();

        $this->manager->find(1);
    }

    public function testCreateThrowsOnNoData()
    {
        $this->setExpectedException('WasteMaster\v1\Bids\MissingRequiredFields');

        $this->manager->create();
    }

    public function testCreateThrowsWhenBidExistsByAddress()
    {
        $this->setExpectedException('WasteMaster\v1\Bids\BidExists');

        $this->bids->shouldReceive('where->count')
                      ->once()
                      ->andReturn(1);

        $bid = $this->manager
            ->setHaulerID(3)
            ->setHaulerEmail('foo@example.com')
            ->setLeadID(13)
            ->setStatus(Bid::STATUS_LIVE)
            ->setWaste(1, 2, 3)
            ->setRecycling(4,5,6)
            ->setWastePrice(132)
            ->setRecyclePrice(423)
            ->setNet(543)
            ->create();
    }

    /**
     * @group single
     */
    public function testCreateSuccess()
    {
        $expects = [
            'hauler_id' => 3,
            'hauler_email' => 'foo@example.com',
            'lead_id' => 13,
            'status' => Bid::STATUS_LIVE,
            'notes' => 'Schnotes!',
            'msw_qty' => 1,
            'msw_yards' => 2,
            'msw_per_week' => 3,
            'rec_qty' => 4,
            'rec_yards' => 5,
            'rec_per_week' => 6,
            'msw_price' => 123,
            'rec_price' => 456,
            'rec_offset' => 20,
            'fuel_surcharge' => 15.2,
            'env_surcharge' => 12.5,
            'recovery_fee' => 5.35,
            'admin_fee' => 10.2,
            'other_fees' => 1.23,
            'net_monthly' => 565,
        ];

        $this->bids->shouldReceive('where->count')
                      ->once()
                      ->andReturn(0);
        $this->bids->shouldReceive('create')
                      ->once()
                      ->with(Mockery::subset($expects))
                      ->andReturn((object)[
                'id' => 4
            ]);

        $Bid = $this->manager
            ->setHaulerID(3)
            ->setHaulerEmail('foo@example.com')
            ->setLeadID(13)
            ->setStatus(Bid::STATUS_LIVE)
            ->setNotes('Schnotes!')
            ->setWaste(1, 2, 3)
            ->setRecycling(4,5,6)
            ->setWastePrice(123)
            ->setRecyclePrice(456)
            ->setRecycleOffset(20)
            ->setFuelSurcharge(15.2)
            ->setEnvironmentalSurcharge(12.5)
            ->setRecoveryFee(5.35)
            ->setAdminFee(10.2)
            ->setOtherFees(1.23)
            ->setNet(565)
            ->create();

        $this->assertEquals(4, $Bid->id);
    }

    public function testUpdateFailsNotFound()
    {
        $this->setExpectedException('WasteMaster\v1\Bids\BidNotFound');

        $this->bids->shouldReceive('find')
                      ->once()
                      ->with(12)
                      ->andReturn();

        $this->manager->update(12);
    }

    public function testUpdateFailsNoData()
    {
        $this->setExpectedException('WasteMaster\v1\Bids\NothingToUpdate');

        $this->bids->shouldReceive('find')
                      ->once()
                      ->with(12)
                      ->andReturn($this->bids);

        $this->manager->update(12);
    }

    public function testUpdateSuccess()
    {
        $this->bids->shouldReceive('find')
                      ->once()
                      ->with(12)
                      ->andReturn($this->bids);
        $this->bids->shouldReceive('fill')
                      ->once();
        $this->bids->shouldReceive('save')
                      ->once();

        $response = $this->manager
            ->setStatus(Bid::STATUS_ACCEPTED)
            ->update(12);

        $this->assertEquals($this->bids, $response);
    }

    public function testDeleteThrowsOnNotFound()
    {
        $this->setExpectedException('WasteMaster\v1\Bids\BidNotFound');

        $this->bids->shouldReceive('with->find')
                      ->once()
                      ->with(12)
                      ->andReturn();

        $this->manager->delete(12);
    }

}
