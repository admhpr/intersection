<?php

    namespace IntersectionCore {
        interface interface_Handler {

            public function prepare(array $requested_sections = [], $phpunit=false);

            public function render(array $partials, $exclude = []);
        }
    }