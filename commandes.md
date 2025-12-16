# Executions

## 1- Executer migration ( creation des tables dans la base de donnée )
```bash
php artisan migrate
ou
php artisan migrate:fresh --seed
```

## 2- Executer seeder

### a- Executer un seul seeder
```bash
php artisan db:seed --class=CategoriesSeeder
```

### b- Executer tout les seeders
```bash
php artisan migrate:fresh --seed
```



# Création

## Création d'une nouvelle migration
```bash
php artisan make:migration liste
```

## Création d'un nouveau seeder:
```bash
php artisan make:seeder CategoriesSeeder
php artisan db:seed --class=CategoriesSeeder
```

## Création d'un modèle et d'un controller
```bash
php artisan make:model Listes
php artisan make:controller ListesController
```

## Création d'un nouveaux fichier de test Unitaire
```bash
php artisan make:test MonModelTest --unit
```


# Tests

## Test pint
```bash
vendor/bin/pint --test
```

correction :

```bash
vendor/bin/pint
```

## PHP Unitaire Tests
```bash
php artisan test
ou
php artisan tinker
```