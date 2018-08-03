<?php

    namespace IntersectionHandler {
        interface interface_handler {

            public function prepare(array $requested_sections = [], $phpunit=false);

            public function render(array $partials, $exclude = []);
        }
    }