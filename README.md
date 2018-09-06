# Service Dispatcher

#### Docker
```console
docker network create --gateway 10.6.0.1 --subnet 10.6.0.0/24 hub
docker-compose up --build --force-recreate
```

### Service register in hub interface

##### Service to hub ping request
```json
{
  "protocol": "1.0",
  "action": "register",
  "route": "sleep",
  "message": "Register me, I am ready",
  "payload": {
    "load": "90",
    "time": "09-05-2018 10:00:00.111111"
  }
} 

```

##### Hub to service ping response

All done

```json
{
  "protocol": "1.0",
  "action": "registered",
  "route": "sleep",
  "message": "Service registered for work",
  "payload": {
    "time": "09-05-2018 10:00:00.111111"
  }
}
```
Sync and back

```json
{
  "protocol": "1.0",
  "action": "sync_time",
  "route": "sleep",
  "message": "Service need to sync time",
  "payload": {
    "time": "09-05-2018 10:00:00.111111"
  }
}
```

### From client to hub requests interface

##### Call service action

```json
{
  "protocol": "1.0",
  "action": "task",
  "route": "sleep",
  "message": "Sleep",
  "payload": {
    "interval": "10"
  }
}
```
or 

```json
{
  "protocol": "1.0",
  "action": "check",
  "route": "passport",
  "message": "Check client passport",
  "payload": {
    "client_id": "client_id"
  }
}
```
or 

```json
{
  "protocol": "1.0",
  "action": "generate",
  "route": "passport",
  "message": "Generate client passport",
  "payload": {
    "client_id": "client_id"
  }
}
```