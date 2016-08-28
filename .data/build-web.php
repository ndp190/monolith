<?php

namespace at\labs;

return function ($pwd, $home) {
  $node = "docker run -it --rm -w='/data' -v $pwd/web/ui:/data -v '$home/.ssh/id_rsa:/private-key' go1com/ci-nodejs";
  passthru("$node npm install");
  passthru("$node bash -c 'eval $(ssh-agent -s) && ssh-add /private-key && mkdir -p ~/.ssh && echo -e \"Host *\\n\\tStrictHostKeyChecking no\\n\\n\" > ~/.ssh/config && bower install --allow-root'");
  passthru("$node grunt install");
  passthru("$node grunt build --force");
  passthru("$node grunt set-env:compose");
};
