# Hangar &amp; Drones (CLI) — starter Prova 2

Mini-progetto didattico in PHP (solo CLI) che simula un piccolo **hangar** con un numero finito di **slot di docking (slots)** e dei **droni** che escono in volo e rientrano.

Questa versione è la **base di riferimento per la Prova 2**: il difetto della Prova 1 è già corretto e la funzionalità di dismissione (`retire`) è già implementata.

## Oggetti

- `Drone`: id, minuti di volo accumulati, stato (`docked`, `in_flight`, `maintenance`, `retired`).
- `Hangar`: un numero fisso di slot, pool di droni in docking, pool maintenance, id in volo, id ritirati.

Regola di business semplificata: **ogni drone che rientra all'hangar entra sempre in `maintenance`** (ispezione post-volo/diagnostica). Dalla maintenance può uscire per tornare in docking oppure essere **ritirato** (retired): una volta ritirato non può più cambiare stato e libera lo slot.

## Requisiti

- PHP 8.1+
- Composer

## Setup

```bash
composer install
```

## Esecuzione CLI

```bash
php bin/hangar.php
```

## Test

```bash
composer test
```

La cartella `tests/` è vuota: popolarla è l'oggetto di questa prova.

## Lint (PSR-12)

```bash
composer lint
composer lint:fix
```

## Documento d'esame

Il documento `PROVA_2.md` (fornito separatamente dal docente) descrive le attività da svolgere.
