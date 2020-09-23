<?php

namespace Gendiff\data\dataForGendiff;

function getReference()
{
    return <<<DOCOPT
    Generate diff
    
    Usage:
        gendiff (-h|--help)
        gendiff (-v|--version)
        gendiff [--format <fmt>] <firstFile> <secondFile>
    
    Options:
        -h --help                     Show this screen
        -v --version                  Show version
        --format <fmt>                Report format [default: stylish]
    DOCOPT;
}
