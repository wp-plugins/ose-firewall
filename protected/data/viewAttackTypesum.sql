CREATE  VIEW `#__osefirewall_attacktypesum` AS select `acl`.`id` AS `aclid`,`acl`.`datetime` AS `datetime`,`acl`.`status` AS `status`,`acl`.`country_code` AS `country_code`,`attacktype`.`id` AS `attacktypeid`,`attacktype`.`name` AS `name`,`attacktype`.`tag` AS `tag` from (((`#__osefirewall_acl` `acl` left join `#__osefirewall_detected` `detected` on((`acl`.`id` = `detected`.`acl_id`))) left join `#__osefirewall_detattacktype` `detattacktype` on((`detected`.`detattacktype_id` = `detattacktype`.`id`))) left join `#__osefirewall_attacktype` `attacktype` on((`attacktype`.`id` = `detattacktype`.`attacktypeid`)));