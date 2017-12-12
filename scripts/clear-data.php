<?php

namespace go1\monolith\scripts;

passthru('sudo rm -rf .data/mysql');
passthru('sudo rm -rf .data/neo4j');
passthru('sudo rm -rf .data/elasticsearch');
passthru('sudo rm -rf .data/scormengine');
passthru('sudo rm -rf .data/minio && git checkout -- .data/minio');
