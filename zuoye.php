<?php
/**
 * Created by PhpStorm.
 * User: 22750
 * Date: 2017/6/29
 * Time: 21:26
 */
    create database db_keyu;
    create table tb_emp(
        id int(9) auto_increment not null,
        name varchar(40) not null,
        age tinyint(3) not null comment 'age',
        sex tinyint(1) unsigned not null comment 'sex:1-man;2-woman',
        email varcahr(20) not null,
        intro varchar(500) not null,
);

    alter table tb_emp add unique key email(`email`);
    alter table tb_emp add primary key  id(`id`);
    alter table tb_emp add key name(`name`);

set global slow_query_log=1;
