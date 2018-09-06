# Service Dispatcher


#### Docker
```console
docker network create --gateway 10.6.0.1 --subnet 10.6.0.0/24 hub
docker-compose up --build
```

#### Ping interface

#### Service to hub ping request
```json
{
  "protocol": "1.0",
  "action": "register",
  "message": "Register me, I am ready",
  "payload": {
    "route": "sleep",
    "load": "90",
    "time": "09-05-2018 10:00:00.111111"
  }
} 
```
```json
{"protocol":"1.0","action":"register","message":"Register me, I am ready","payload":{"route":"sleep","load":"90","time":"09-07-2018 10:00:00.111111"}}
```
#### Hub to service ping response

##### All done

```json
{
  "protocol": "1.0",
  "action": "registered",
  "message": "Service registered for work",
  "payload": {
    "time": "09-05-2018 10:00:00.111111"
  }
}
```

##### Sync and back

```json
{
  "protocol": "1.0",
  "action": "sync_time",
  "message": "Service need to sync time",
  "payload": {
    "time": "09-05-2018 10:00:00.111111"
  }
}
```

