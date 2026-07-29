# Databázové migrace

Migrace se spouštějí proti zálohované PostgreSQL databázi v číselném pořadí.
Nejsou součástí `schema.sql`, protože ten soubor obsahuje historický seed a není
bezpečný pro opakované použití v produkci.

## Task 0.2

```bash
psql "$DB_DSN" -v ON_ERROR_STOP=1 -f migrations/202607290001_checkout_snapshot_up.sql
```

Před spuštěním vytvořte ověřenou databázovou zálohu. Rollback souborem
`202607290001_checkout_snapshot_down.sql` je přípustný pouze před vytvořením
prvního snapshotu; po reálných objednávkách obnovujte databázi ze zálohy.

## Task 0.3

Před spuštěním nové grantové migrace nejprve proveďte dry-run report starých
tokenů, potom zálohu a teprve následně migraci. Staré raw tokeny se záměrně
invalidují a nejsou migrovány.

```bash
psql "$DB_DSN" -v ON_ERROR_STOP=1 -f migrations/202607290002_legacy_download_token_report.sql
psql "$DB_DSN" -v ON_ERROR_STOP=1 -f migrations/202607290002_order_access_and_download_grants_up.sql
```

## Task 0.4

Nejdříve vytvořte a ověřte PostgreSQL backup. Preflight report je pouze pro
čtení; duplicity Stripe identifikátorů musí být vyřešeny vědomě před migrací.

```bash
psql "$DB_DSN" -v ON_ERROR_STOP=1 -f migrations/202607290003_preflight_report.sql
psql "$DB_DSN" -v ON_ERROR_STOP=1 -f migrations/202607290003_payments_and_inventory_up.sql
```

Migrace staré objednávky automaticky neoznačuje jako zaplacené. Používá stav
`legacy_unknown`, přidává unikátní vazbu snapshot–objednávka, Stripe event
ledger, skladové pohyby a digitální entitlementy. Kontrola kladného množství je
`NOT VALID`: platí pro nové řádky, ale nezablokuje migraci kvůli historickému
nekonzistentnímu řádku, který vypíše preflight.

Down migraci používejte pouze před prvním novým payment eventem. Po reálné
platbě by odstranila auditní důkazy; bezpečný rollback je obnova celého
PostgreSQL backupu a předchozí verze aplikace v jednom servisním okně.
