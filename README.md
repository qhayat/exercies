# Requirements
    - Docker
    - git
    - nothing running on port 8080 and 3307

## build and up docker: 
- build: `docker-compose build` *(or `docker compose build` for more recent docker version)*
- up: `docker-compose up -d` *(`docker compose up -d`)*
  
## enter in docker:

`docker-compose exec app_exam bash` *(`docker compose exec app_exam bash`)*

## Install project:

`make install`

## Usage

then go to [http://localhost:8080](http://localhost:8080)

## Documentations

rest doc: [http://localhost:8080/doc](http://localhost:8080/doc)

graphiql doc: [http://localhost:8080/graphiql](http://localhost:8080/graphiql)
