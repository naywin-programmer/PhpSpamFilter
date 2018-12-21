<?php

function beautiful_output($val) {
    highlight_string("\n\n<?php \n\n" . var_export($val, true) . ";\n\n?>");
}
