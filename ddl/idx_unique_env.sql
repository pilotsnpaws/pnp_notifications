ALTER TABLE `pnp_xport`.`pnp_users` 
ADD UNIQUE INDEX `idx_unique_env` (`user_id` ASC, `source_server` ASC, `source_database` ASC);
