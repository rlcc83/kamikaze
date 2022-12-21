# Proyecto Kamikaze

El objetivo de este proyecto es obtener la mayor rentabilidad a partir de un conjunto de proyectos disponibles, teniendo en cuenta que solo se puede ejecutar un proyecto a la vez.

## Instalación

### Requisitos previos:
Tener instalado Docker y Docker compose:
https://www.docker.com/

Clonar el repositorio:
```bash
git clone git@github.com:rlcc83/kamikaze.git
```
Entrar en la carpeta kamikaze:
```cd kamikaze```

Iniciar los contenedores y el proyecto:
```bash
make init
```

## Funcionamiento:

- Tenemos dos puntos de entrada:
    - Command: ```make compatibility-leads``` (es un alias de: docker-compose --file docker/docker-compose.yml -p kamikaze exec docker-php-fpm bin/console app:compatibility-leads)
    - URL: http://localhost:8080/calculate

- Al entrar en cualquier Entry point, se llama al servicio Services/GetWinnerLead, que implementa la interface CompatibilityLeads para forzar a implementar el método execute
    - Se llama a LeadRepository
        - Busca todos los leads/proyectos entre unas fechas dadas
    - Se llama a CompatibilityLeadsByDate enviándole los leads anteriores compatibles (son compatibles los que no se solapan en fechas con el mismo)
        - Para cada uno de los proyectos recibidos, busca los leads que son compatibles consigo mismo. Devuelve un array donde cada lead tiene sus leads compatibles.
        - Después, para cada uno de los arrays del punto anterior, comprueba que los leads son compatibles entre ellos.
            - Esto devuelve un array que contiene arrays de leads compatibles entre ellos.
        - Se utiliza un interface por si se quiere cambiar la estrategia. En este caso es por fechas, pero podría ser por cualquier otro motivo. Cambiándole la inyección sería suficiente para aplicar la nueva estrategia.
    - Por último se llama LeadWinnerExpensiveResolver enviándole los arrays que son compatibles entre sí y la estrategia a utilizar (pudiendo ser el proyecto más rentable o el menos rentable). Este servicio es el encargado de devolver un único resultado, por defecto, el que de mayor beneficio. Se implementa la interface LeadWinnerResolverInterface que obliga a implementar el método resolve y se hace un compiler pass con Services/LeadWinner, donde se chequea la estrategia y se devuelve el resultado.

### Test:
- Se crean test funcionales para comprobar el ciclo completo de la aplicación
- Con el comando ```make test``` se genera una bbdd, cargan los fixtures y se ejecutan los tests
- Con el comando ```make test-phpunit``` se ejecutan los test sin cargar los fixtures

### Code Styling:
- Se hace uso de phpcs
- Para ejecutarlo ```make phpcs```

### Otra información:
- Adicionalmente, se crea una mejora a modo de ejemplo, otro calculador para saber el proyecto con menor beneficio:
    - URL: http://localhost:8080/calculate-cheaper
- En el archivo Makefile se insertan varios comandos útiles para el día a día
