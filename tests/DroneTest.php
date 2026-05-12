<?php

declare(strict_types=1);

namespace Tests;

use App\Drone;
use PHPUnit\Framework\TestCase;

final class DroneTest extends TestCase
{
    public function testDroneIsCreatedWithDefaultValues(): void
    {
        $drone = new Drone('DR-001');

        $this->assertSame('DR-001', $drone->id());
        $this->assertSame(0, $drone->flightMinutes());
        $this->assertSame(Drone::STATUS_DOCKED, $drone->status());
        $this->assertTrue($drone->isDocked());
    }

    public function testDroneLifecycleTransitions(): void
    {
        $drone = new Drone('DR-001');

        $drone->takeOff();

        $this->assertTrue($drone->isInFlight());

        $drone->addFlightMinutes(20);

        $this->assertSame(20, $drone->flightMinutes());

        $drone->markDocked();

        $this->assertTrue($drone->isDocked());

        $drone->sendToMaintenance();

        $this->assertTrue($drone->isInMaintenance());

        $drone->retire();

        $this->assertTrue($drone->isRetired());
    }

    public function testDroneCannotTakeOffFromMaintenance(): void
    {
        $drone = new Drone(
            'DR-001',
            0,
            Drone::STATUS_MAINTENANCE
        );

        $this->expectException(\RuntimeException::class);

        $drone->takeOff();
    }
}
