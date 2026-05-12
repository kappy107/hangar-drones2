# NOTES

## Strategia dei test

Ho concentrato la suite di test sui comportamenti principali del dominio applicativo.

Per la classe Drone ho coperto:
- creazione dell'oggetto
- validazione dei parametri
- transizioni di stato
- gestione dei minuti di volo
- operazioni non valide ed eccezioni

Per la classe Hangar ho coperto:
- creazione dell'hangar
- aggiunta dei droni
- gestione della capacità
- flusso di decollo e atterraggio
- gestione dei droni ritirati

I test sono stati scritti in modo da essere deterministici, isolati e leggibili.

## Assunzioni

Ho assunto che:
- i droni ritirati non possano essere reinseriti nell'hangar
- solo i droni docked possano decollare
- i droni entrino sempre in manutenzione dopo l'atterraggio
- la capacità dell'hangar si applichi solo ai droni fisicamente presenti all'interno