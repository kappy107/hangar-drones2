<?php

declare(strict_types=1);

namespace Tests;

use App\Drone;
use App\Hangar;
use PHPUnit\Framework\TestCase;

final class HangarTest extends TestCase
{
    public function testHangarIsCreatedWithCorrectCapacity(): void
    {
        $hangar = new Hangar(5);

        $this->assertSame(5, $hangar->capacity());
        $this->assertTrue($hangar->hasFreeSlot());
    }

    public function testHangarCannotBeCreatedWithInvalidCapacity(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Hangar(0);
    }

    public function testHangarCanAddDockedDrone(): void
    {
        $hangar = new Hangar(2);
        $drone = new Drone('DR-001');

        $hangar->addDrone($drone);

        $this->assertSame(1, $hangar->dockedCount());
        $this->assertSame(['DR-001'], $hangar->dockedDroneIds());
    }

    public function testHangarCannotAddRetiredDrone(): void
    {
        $hangar = new Hangar(2);

        $drone = new Drone('DR-001');

        $drone->sendToMaintenance();
        $drone->retire();

        $this->expectException(\RuntimeException::class);

        $hangar->addDrone($drone);
    }

    public function testHangarCannotAddDroneWhenFull(): void
    {
        $hangar = new Hangar(1);

        $firstDrone = new Drone('DR-001');
        $secondDrone = new Drone('DR-002');

        $hangar->addDrone($firstDrone);

        $this->expectException(\RuntimeException::class);

        $hangar->addDrone($secondDrone);
    }

    public function testHangarCanLaunchDrone(): void
    {
        $hangar = new Hangar(2);

        $drone = new Drone('DR-001');

        $hangar->addDrone($drone);

        $launchedDrone = $hangar->launchDrone();

        $this->assertSame('DR-001', $launchedDrone->id());
        $this->assertTrue($launchedDrone->isInFlight());
        $this->assertSame(1, $hangar->inFlightCount());
    }

    public function testHangarCanLandDrone(): void
    {
        $hangar = new Hangar(2);

        $drone = new Drone('DR-001');

        $hangar->addDrone($drone);

        $launchedDrone = $hangar->launchDrone();

        $hangar->landDrone($launchedDrone, 30);

        $this->assertSame(30, $launchedDrone->flightMinutes());
        $this->assertTrue($launchedDrone->isInMaintenance());
        $this->assertSame(1, $hangar->maintenanceCount());
    }
}
