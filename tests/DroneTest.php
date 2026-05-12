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

    public function testDroneCanBeCreatedWithCustomValues(): void
    {
        $drone = new Drone(
            'DR-002',
            45,
            Drone::STATUS_IN_FLIGHT
        );

        $this->assertSame('DR-002', $drone->id());
        $this->assertSame(45, $drone->flightMinutes());
        $this->assertSame(
            Drone::STATUS_IN_FLIGHT,
            $drone->status()
        );
    }

    public function testDroneCannotBeCreatedWithEmptyId(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Drone('');
    }

    public function testDroneCannotBeCreatedWithNegativeFlightMinutes(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Drone('DR-001', -1);
    }

    public function testDroneCannotBeCreatedWithInvalidStatus(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new Drone('DR-001', 0, 'invalid_status');
    }

    public function testDroneCanTakeOffWhenDocked(): void
    {
        $drone = new Drone('DR-001');

        $drone->takeOff();

        $this->assertTrue($drone->isInFlight());
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

    public function testDroneCanAddFlightMinutesWhileFlying(): void
    {
        $drone = new Drone('DR-001');

        $drone->takeOff();
        $drone->addFlightMinutes(20);

        $this->assertSame(20, $drone->flightMinutes());
    }

    public function testDroneCannotAddFlightMinutesWhenNotFlying(): void
    {
        $drone = new Drone('DR-001');

        $this->expectException(\RuntimeException::class);

        $drone->addFlightMinutes(10);
    }

    public function testDroneLifecycleTransitions(): void
    {
        $drone = new Drone('DR-001');

        $drone->takeOff();

        $this->assertTrue($drone->isInFlight());

        $drone->markDocked();

        $this->assertTrue($drone->isDocked());

        $drone->sendToMaintenance();

        $this->assertTrue($drone->isInMaintenance());

        $drone->retire();

        $this->assertTrue($drone->isRetired());
    }
}