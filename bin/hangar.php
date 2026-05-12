<?php

declare(strict_types=1);

use App\Drone;
use App\Hangar;

require __DIR__ . '/../vendor/autoload.php';

$hangar = new Hangar(capacity: 4);

foreach (['D1', 'D2', 'D3'] as $id) {
    $hangar->addDrone(new Drone($id));
}

echo "Hangar CLI\n";
echo "Slots capacity: {$hangar->capacity()}\n";
echo "Rule: every landing drone goes to maintenance.\n";
echo "Docked: {$hangar->dockedCount()}\n\n";

$d1 = $hangar->launchDrone();
$d2 = $hangar->launchDrone();

echo "Launched drones: {$d1->id()}, {$d2->id()}\n";
echo "Docked now: {$hangar->dockedCount()}\n";
echo "In flight now: {$hangar->inFlightCount()}\n\n";

$hangar->landDrone($d1, 240);
$hangar->landDrone($d2, 90);

echo "Landed drones with flights (240min, 90min)\n";
echo "Docked: {$hangar->dockedCount()}\n";
echo "Maintenance: {$hangar->maintenanceCount()}\n\n";

// Retire D2 while it is in maintenance
$hangar->retireDrone($d2->id());
echo "Retired {$d2->id()}\n";
echo "Docked: {$hangar->dockedCount()}\n";
echo "Maintenance: {$hangar->maintenanceCount()}\n";
echo "Retired: {$hangar->retiredCount()}\n";
echo "Slots in use: {$hangar->insideCount()} / {$hangar->capacity()}\n\n";

$hangar->releaseFromMaintenance($d1->id());
echo "Released {$d1->id()} from maintenance\n";
echo "Docked: {$hangar->dockedCount()}\n";
echo "Maintenance: {$hangar->maintenanceCount()}\n";
echo "Docked IDs: " . implode(', ', $hangar->dockedDroneIds()) . "\n";
if ($hangar->retiredCount() > 0) {
    echo "Retired IDs: " . implode(', ', $hangar->retiredDroneIds()) . "\n";
}
