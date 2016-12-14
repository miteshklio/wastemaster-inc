<?php

use WasteMaster\v1\Haulers\HaulerManager;
use Mockery as m;

class HaulerManagerTest extends UnitTestCase
{
    protected $haulers;
    protected $manager;

    public function setUp()
    {
        $this->haulers = m::mock('\App\Hauler');

        $this->manager = new HaulerManager($this->haulers);

        parent::setUp();
    }

    public function tearDown()
    {
        m::close();
        parent::tearDown();
    }

    public function testGetHaulerSuccess()
    {
        $this->haulers->shouldReceive('find')
            ->once()
            ->andReturn((object)[
                'id' => 1
            ]);

        $hauler = $this->manager->find(1);
        $this->assertEquals(1, $hauler->id);
    }

    public function testGetUserThrowsOnFailure()
    {
        $this->setExpectedException('WasteMaster\v1\Haulers\HaulerNotFound');

        $this->haulers->shouldReceive('find')
            ->once()
            ->andReturn();

        $this->manager->find(1);
    }

    public function testParseEmailsReturnsArray()
    {
        $expected = [
            'foo@example.com',
            'bar@example.com'
        ];

        $this->assertEquals($expected, $this->manager->parseEmails($expected));
    }

    public function testParseEmailsThrowsOnInvalidType()
    {
        $this->setExpectedException('WasteMaster\v1\Haulers\InvalidEmails');

        $this->manager->parseEmails(new stdClass());
    }

    public function testParseEmailsParsesString()
    {
        $emails = 'foo@example.com, bar@example.com ';
        $expected = [
            'foo@example.com',
            'bar@example.com'
        ];

        $this->assertEquals($expected, $this->manager->parseEmails($emails));
    }

    public function testCreateThrowsWithMissingFields()
    {
        $this->setExpectedException('WasteMaster\v1\Haulers\MissingRequiredFields');

        $this->manager->create();
    }

    public function testCreateSuccess()
    {
        $fields = [
            'name'        => 'name',
            'city_id'     => 13,
            'svc_recycle' => 1,
            'svc_waste'   => 0,
            'emails'      => serialize(['foo@example.com', 'bar@example.com'])
        ];

        $this->haulers->shouldReceive('create')
            ->once()
            ->with($fields)
            ->andReturn((object)[
                'id' => 2
            ]);

        $hauler = $this->manager->setName('name')
            ->setCityID(13)
            ->setRecycling(true)
            ->setWaste(false)
            ->setEmails('foo@example.com,bar@example.com')
            ->create();

        $this->assertEquals(2, $hauler->id);
    }

    public function testUpdateFailsNotFound()
    {
        $this->setExpectedException('WasteMaster\v1\Haulers\HaulerNotFound');

        $this->haulers->shouldReceive('find')
            ->once()
            ->andReturn();

        $this->manager->update(3, []);
    }

    /**
     * @group single
     */
    public function testUpdateSuccess()
    {
        $this->haulers->shouldReceive('find')
            ->andReturn($this->haulers);
        $this->haulers->shouldReceive('fill')
            ->once()
            ->andReturn($this->haulers);
        $this->haulers->shouldReceive('save')
            ->once();

        $hauler = $this->manager->update(3, ['emails' => 'foo@example.com, bar@example.com']);

        $this->assertTrue($hauler instanceof Mockery_0_App_Hauler);
    }

    public function testDeleteSuccess()
    {
        $this->haulers->shouldReceive('find')
            ->once()
            ->andReturn($this->haulers);
        $this->haulers->shouldReceive('delete')
            ->once()
            ->andReturn(true);

        $this->assertTrue($this->manager->delete(1));
    }


}
