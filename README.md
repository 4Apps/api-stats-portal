![logo](resources/logo_50.png)

# Api Stats Portal

Portal to show off api stats collected using various collection [Libraries](###statistics-collection-libraries).
Portal uses MongoDB as data store.

## Installation

### To test things out locally, run these commands:

1. `python3 -m pip install -r requirements.txt`
2. `fab docker.install`
3. `docker compose exec develop bash` and `php -f /srv/sites/web/src/Application/Public/index.php -- --query /console/create-test-data`
4. Open localhost:5700 in browser.

### Deployment

_In progress_

## Statistics collection libraries

1. [python](https://github.com/4Apps/api-stats-python)
2. [php](https://github.com/4Apps/api-stats-php)
3. Ask me if you need another language implementation
