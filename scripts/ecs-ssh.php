<?php

namespace go1\monolith\scripts;

/**
 * Get service IP
 *
 * Command: php ecs-ssh.php $cluster $serviceName
 * Example: php ecs-ssh.php staging exim-dev
 *
 * Requirements
 * - aws cli http://docs.aws.amazon.com/cli/latest/userguide/installing.html
 * - Configure aws http://docs.aws.amazon.com/cli/latest/userguide/cli-chap-getting-started.html
 */

use RuntimeException;

if (empty($argv[1])) {
    throw new RuntimeException("Missing cluster name");
}

if (empty($argv[2])) {
    throw new RuntimeException("Missing service name");
}

$cluster = $argv[1];
$service = $argv[2];
$service = "ecscompose-service-$service";
$tasks = shell_exec("aws ecs list-tasks --cluster=$cluster --service-name=$service");
$tasks = json_decode($tasks, true);
$taskName = $tasks['taskArns'][0];
$taskName = explode("/", $taskName)[1];
if (empty($taskName)) {
    throw new RuntimeException("Failed to find task name");
}
$task = shell_exec("aws ecs describe-tasks --cluster=$cluster --tasks=$taskName");
$task = json_decode($task, true);
$task = $task['tasks'][0]['containerInstanceArn'];
$instanceId = explode("/", $task)[1];
if (empty($instanceId)) {
    throw new RuntimeException("Failed to find container instance id.");
}

$instance = shell_exec("aws ecs describe-container-instances --cluster=$cluster --container-instances=$instanceId");
$instance = json_decode($instance, true);
$instanceId = $instance['containerInstances'][0]['ec2InstanceId'];
if (empty($instanceId)) {
    throw new RuntimeException("Failed to find instance id.");
}

$instance = shell_exec("aws ec2 describe-instances --instance-ids=$instanceId");
$instance = json_decode($instance, true);
$ip = $instance['Reservations'][0]['Instances'][0]['PublicDnsName'];
if (empty($ip)) {
    throw new RuntimeException("Failed to find public DNS.");
}

$cmd = 'ssh -i ~/.ssh/go1.pem ec2-user@' . $ip;
echo "{$cmd}\n";
passthru($cmd);
