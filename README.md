# folkcert-api
REST API using PHP Symfony

## Concerts
### `/concert`

```
GET /concert
```
```
*HTTP 200 OK*
[
  {
    'id' : 1,
    'name' : 'En vivo Racing',
    'description': 'Los Redondos en vivo, estadio Racing Club 1998',
    'location': 'Estadio Racing Club',
    'date': '1998-12-19',
    'artist': {
        'id: 1,
        'name': 'Patricio Rey y sus Redonditos de Ricota',
        'picture': 'yogz789ern76r.png'
    },
    'genre': {
        'id': 1,
        'name': 'Rock',
        'logo': 'rock.png'
    },
    'links': [
        {
            'id': 1,
            'linkCode' 'PzhEUOpkRD4',
            'type': {
                'id': 1,
                'name': 'YouTube',
                'logo': 'fa-youtube',
                'baseUrl': 'https://www.youtube.com/watch?v='
            }
        }
    ]
  }
]

```
```
POST /concert
{
    'name' : 'En vivo Huracan',
    'description': 'Los Redondos en vivo, estadio Huracan 1994',
    'location': 'Estadio Tomas Duco',
    'date': '1994-12-17',
    'artist': {
        'id: 1,
    },
    'genre': {
        'id': 1,
    },
    'links': [
        {
            'linkCode' 'Misjc2JMKKY',
            'type': {
                'id': 1,
            }
        }
    ]
}
```
```
*HTTP 200 OK*
{
    'id' : 2,
    'name' : 'En vivo Huracan',
    'description': 'Los Redondos en vivo, estadio Huracan 1994',
    'location': 'Estadio Tomas Duco',
    'date': '1994-12-17',
    'artist': {
        'id: 1,
        'name': 'Patricio Rey y sus Redonditos de Ricota',
        'picture': 'yogz789ern76r.png'
    },
    'genre': {
        'id': 1,
        'name': 'Rock',
        'logo': 'rock.png'
    },
    'links': [
        {
            'id': 2,
            'linkCode' 'Misjc2JMKKY',
            'type': {
                'id': 1,
                'name': 'YouTube',
                'logo': 'fa-youtube',
                'baseUrl': 'https://www.youtube.com/watch?v='
            }
        }
    ]
}


```



