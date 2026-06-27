# PrintCraft POD Platform 🎨

Kompletna Print on Demand e-commerce platforma.

## Pokretanje

```bash
cd php-pod
php -S localhost:8080 index.php
```

Otvori browser: **http://localhost:8080**

## Admin pristup
- URL: http://localhost:8080/admin
- Email: `admin@pod.ba`
- Lozinka: `admin123`

## Funkcionalnosti

### Kupci
- Katalog proizvoda po kategorijama (odjeća, kućni dekor, knjige, aksesoari)
- Pretraga i filtriranje
- Upload vlastitog dizajna na proizvod
- Odabir veličine i boje
- Shopping cart
- Checkout sa dostavom i načinom plaćanja
- Registracija / prijava
- Pregled narudžbi
- Ocjenjivanje proizvoda

### Admin Panel
- Dashboard sa statistikama (prihod, narudžbe, korisnici)
- CRUD upravljanje proizvodima + upload slike
- Upravljanje narudžbama + promjena statusa
- Lista korisnika

## Tehničke info
- PHP 8.0+ (bez frameworka)
- SQLite (nema MySQL setup-a)
- Bootstrap 5 UI
- Vanilla JS
