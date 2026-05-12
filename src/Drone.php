<?php

declare(strict_types=1);

namespace App;

final class Drone
{
    public const STATUS_DOCKED = 'docked';
    public const STATUS_IN_FLIGHT = 'in_flight';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_RETIRED = 'retired';

    private string $id;
    private int $flightMinutes;
    private string $status;

    public function __construct(string $id, int $flightMinutes = 0, string $status = self::STATUS_DOCKED)
    {
        $id = trim($id);
        if ($id === '') {
            throw new \InvalidArgumentException('id must be a non-empty string');
        }
        if ($flightMinutes < 0) {
            throw new \InvalidArgumentException('flightMinutes must be >= 0');
        }
        $validStatuses = [
            self::STATUS_DOCKED,
            self::STATUS_IN_FLIGHT,
            self::STATUS_MAINTENANCE,
            self::STATUS_RETIRED,
        ];
        if (!in_array($status, $validStatuses, true)) {
            throw new \InvalidArgumentException('invalid status');
        }

        $this->id = $id;
        $this->flightMinutes = $flightMinutes;
        $this->status = $status;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function flightMinutes(): int
    {
        return $this->flightMinutes;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function isDocked(): bool
    {
        return $this->status === self::STATUS_DOCKED;
    }

    public function isInFlight(): bool
    {
        return $this->status === self::STATUS_IN_FLIGHT;
    }

    public function isInMaintenance(): bool
    {
        return $this->status === self::STATUS_MAINTENANCE;
    }

    public function isRetired(): bool
    {
        return $this->status === self::STATUS_RETIRED;
    }

    /**
     * The drone leaves the hangar for a flight.
     */
    public function takeOff(): void
    {
        if (!$this->isDocked()) {
            throw new \RuntimeException("Drone {$this->id} cannot take off from status {$this->status}");
        }

        $this->status = self::STATUS_IN_FLIGHT;
    }

    /**
     * The drone is considered docked only when it is in flight.
     */
    public function markDocked(): void
    {
        if (!$this->isInFlight()) {
            throw new \RuntimeException("Drone {$this->id} cannot be set to docked from status {$this->status}");
        }

        $this->status = self::STATUS_DOCKED;
    }

    /**
     * Send the drone to maintenance. Only allowed while docked.
     */
    public function sendToMaintenance(): void
    {
        if (!$this->isDocked()) {
            throw new \RuntimeException("Drone {$this->id} cannot enter maintenance from status {$this->status}");
        }

        $this->status = self::STATUS_MAINTENANCE;
    }

    public function returnFromMaintenance(): void
    {
        if (!$this->isInMaintenance()) {
            throw new \RuntimeException("Drone {$this->id} is not in maintenance");
        }

        $this->status = self::STATUS_DOCKED;
    }

    /**
     * Permanently retire the drone. Only allowed while in maintenance.
     * A retired drone cannot transition to any other status.
     */
    public function retire(): void
    {
        if (!$this->isInMaintenance()) {
            throw new \RuntimeException("Drone {$this->id} cannot be retired from status {$this->status}");
        }

        $this->status = self::STATUS_RETIRED;
    }

    /**
     * Add minutes for a flight. Only valid while in flight.
     */
    public function addFlightMinutes(int $flightMinutes): void
    {
        if ($flightMinutes < 0) {
            throw new \InvalidArgumentException('flightMinutes must be >= 0');
        }
        if (!$this->isInFlight()) {
            throw new \RuntimeException("Drone {$this->id} is not in flight");
        }

        $this->flightMinutes += $flightMinutes;
    }
}
