# Instalacion de dependencias
`` composer install ``

# Inicializacion del servidor
``  symfony server:start ``

# Creacion de base de datos
` php bin/console doctrine:database:create `

# Creacion de tablas
`  php bin/console doctrine:migrations:migrate `