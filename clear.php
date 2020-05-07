<?php
\System\Main\Core::init();
\System\Main\Core::getInstance()->login(null);
var_dump(\System\Main\Core::getInstance()->getUser());
echo "hyu";