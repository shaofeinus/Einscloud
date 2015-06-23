<?php

session_id("user");
session_start();
echo session_id();
echo "<pre>", print_r($_SESSION, 1), "</pre>";
session_write_close();