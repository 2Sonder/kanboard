Environment Variables
=====================

Environment variables maybe useful when Kanboard is deployed as container (Docker).

| Variable      | Description                                                                                                                     |
|---------------|---------------------------------------------------------------------------------------------------------------------------------|
| DATABASE_URL  | `[database type]://[username]:[password]@[host]:[port]/[database name]`, example: `postgres://foo:foo@myserver:5432/kanboard`   |
| DEBUG         | Enable/Disable debug mode: "true" or "false"                                                                                    |
| LOG_DRIVER    | Logging driver: stdout, stderr, file or syslog                                                                                  |
