<?php

namespace go1\monolith\scripts;

# Remove containers
passthru('docker rm monolith_consumer_1');
passthru('docker rm monolith_es_1');
passthru('docker rm monolith_memcached_1');
passthru('docker rm monolith_minio_1');
passthru('docker rm monolith_neo4j_1');
passthru('docker rm monolith_queue_1');
passthru('docker rm monolith_ui_1');
passthru('docker rm monolith_web_1');
passthru('docker rm monolith_website_1');
passthru('docker rm monolith_wkhtmltopdf_1');
passthru('docker rm monolith_worker_1');

# Remove images
passthru('docker rmi monolith_consumer');
passthru('docker rmi monolith_worker');
passthru('docker rmi monolith_web');
