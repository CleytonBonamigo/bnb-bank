# BNB Bank
Project developed using Laravel 10.x, VueJS 3, TailwindCSS and TypeScript.

## Installing
### Docker
```bash
# This script will mount containers, run everything that needs
bash start.sh
```

To visit, just open in your browser:
```
http://localhost:8001
```

### Users
| Username   | Password | Description |
| :---------- | ------- | :---------------------------------- |
| `admin` | `password` | admin user |

To create a normal user, you should use registration :).

### Tests
Tests were made with Pest and it runs with ```bash start.sh```, but if you want to run it manually:
```bash
docker exec -it bnb-bank-php bash
./vendor/bin/pest
```