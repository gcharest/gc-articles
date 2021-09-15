include {
  path = find_in_parent_folders()
}

dependencies {
  paths = ["../network", "../hosted-zone", "../load-balancer", "../database", "../ecs"]
}

dependency "network" {
  config_path = "../network"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs = {
    private_subnet_ids           = ""
    sns_lambda_security_group_id = ""
  }
}

dependency "hosted-zone" {
  config_path = "../hosted-zone"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs = {
    zone_id = ""
  }
}

dependency "load-balancer" {
  config_path = "../load-balancer"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs = {
    alb_arn                     = ""
    alb_arn_suffix              = ""
    alb_target_group_arn_suffix = ""
    cloudfront_arn              = ""
    cloudfront_distribution_id  = ""
    cloudfront_waf_web_acl_name = ""
  }
}

dependency "database" {
  config_path = "../database"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs = {
    rds_cluster_id = ""
  }
}

dependency "ecs" {
  config_path = "../ecs"

  mock_outputs_allowed_terraform_commands = ["init", "fmt", "validate", "plan", "show"]
  mock_outputs = {
    ecs_cloudfront_log_group_name = ""
    ecs_cluster_name              = ""
    ecs_service_name              = ""
    efs_id                        = ""
  }
}

inputs = {
  alb_arn         = dependency.load-balancer.outputs.alb_arn
  alb_arn_suffix  = dependency.load-balancer.outputs.alb_arn_suffix
  alb_5xx_maximum = 100

  alb_target_group_arn_suffix              = dependency.load-balancer.outputs.alb_target_group_arn_suffix
  alb_target_response_time_average_maximum = 2
  alb_target_5xx_maximum                   = 100
  alb_target_4xx_maximum                   = 100

  canary_healthcheck_url_eng = "https://platform-ircc.cdssandbox.xyz/"
  canary_healthcheck_url_fra = "https://platform-ircc.cdssandbox.xyz/wp-login.php"

  cloudfront_arn              = dependency.load-balancer.outputs.cloudfront_arn
  cloudfront_distribution_id  = dependency.load-balancer.outputs.cloudfront_distribution_id
  cloudfront_waf_web_acl_name = dependency.load-balancer.outputs.cloudfront_waf_web_acl_name
  cloudfront_5xx_maximum      = 100
  cloudfront_4xx_maximum      = 100

  ecs_cluster_name   = dependency.ecs.outputs.ecs_cluster_name
  ecs_service_name   = dependency.ecs.outputs.ecs_service_name
  ecs_cpu_maximum    = 50
  ecs_memory_maximum = 50

  efs_id                   = dependency.ecs.outputs.efs_id
  efs_burst_credit_balance = "192000000000"
  efs_percent_io_limit     = "95"

  hosted_zone_id = dependency.hosted-zone.outputs.zone_id

  rds_cluster_id                 = dependency.database.outputs.rds_cluster_id
  rds_aurora_replica_lag_maximum = 2000
  rds_cpu_maxiumum               = 80
  rds_freeable_memory_minimum    = 64000000

  sns_lambda_private_subnet_ids = dependency.network.outputs.private_subnet_ids
  sns_lambda_security_group_id  = dependency.network.outputs.sns_lambda_security_group_id

  wordpress_failed_login_maximum = "5"
  wordpress_log_group_name       = dependency.ecs.outputs.ecs_cloudfront_log_group_name
}

terraform {
  source = "../../../aws//alarms"
}