# Solr+Php Docker Test

<a href="https://solr.apache.org/" target="_blank"><img src="data:image/png;base64,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" width="400"></a>

# Local requirements

- docker
- docker-compose

```
cp .env.example .env
docker-compose up -d
docker-compose ps
 Name                Command               State                                      Ports                                    
-------------------------------------------------------------------------------------------------------------------------------
Name               Command               State                         Ports                       
---------------------------------------------------------------------------------------------------
nginx   /docker-entrypoint.sh ngin ...   Up      0.0.0.0:8001->80/tcp,:::8001->80/tcp              
php     docker-php-entrypoint php-fpm    Up      0.0.0.0:8002->8000/tcp,:::8002->8000/tcp, 9000/tcp
solr    docker-entrypoint.sh solr- ...   Up      0.0.0.0:8983->8983/tcp,:::8983->8983/tcp 
```

### Seed Solr with some dummy data

```bash
./generate_data.sh
Starting data generation for 10000 documents...
Batch size: 1000
Total batches: 10

Progress: 0% (0 / 10000 documents)

Committing all documents to Solr...

✓ Successfully inserted 10000 documents!
✓ Time taken: 1m 14s

Verify with: curl 'http://localhost:8983/solr/mycore/select?q=*:*&rows=0'

```

### Solr UI
http://localhost:8983

### Search UI
http://localhost:8001/

### Search queries
<a href="http://localhost:8983/solr/mycore/select?q=price:[1000 TO 2000] OR a:a&wt=json&indent=true&fl=*, score">q=price:[1000 TO 2000] OR a:a&wt=json&indent=true&fl=*, score</a>

<a href="http://localhost:8983/solr/mycore/select?q=comments:perfect*">q=comments:perfect*</a>

