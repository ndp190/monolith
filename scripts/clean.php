<?php

namespace go1\monolith;

passthru('docker rm monolith_ui_1');
passthru('docker rm monolith_website_1');
passthru('docker rm monolith_worker_1');
passthru('docker rm monolith_consumer_1');
passthru('docker rm monolith_web_1');

passthru('docker rmi monolith_consumer');
passthru('docker rmi monolith_worker');
passthru('docker rmi monolith_web');
