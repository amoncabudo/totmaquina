<?php

return [
    "db" => [
        "user" => Emeset\Env::get("user", "root"),
        "pass" => Emeset\Env::get("pass", "12345"),
        "db" => Emeset\Env::get("db", "totmaquina"),
        "host" => Emeset\Env::get("host", "mariadb"),
    ],
];
